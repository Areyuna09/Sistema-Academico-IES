<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use App\Models\InscripcionMesa;
use App\Models\Alumno;
use App\Models\Materia;
use App\Models\MesaExamen;
use App\Models\PeriodoInscripcion;
use App\Models\Carrera;
use App\Models\Notificacion;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InscripcionesController extends Controller
{
    /**
     * Mostrar listado de inscripciones con filtros
     * Maneja TANTO inscripciones a cursado COMO a mesas
     */
    public function index(Request $request)
    {
        // Tipo de inscripción: 'cursado' o 'mesas'
        $tipo = $request->get('tipo', 'cursado');

        if ($tipo === 'mesas') {
            return $this->indexMesas($request);
        }

        // INSCRIPCIONES A CURSADO (por defecto)
        $query = Inscripcion::query()
            ->with(['alumno', 'materia.carrera', 'periodo']);

        // Filtrar por período
        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }

        // Filtrar por carrera
        if ($request->filled('carrera_id')) {
            $query->whereHas('materia', function($q) use ($request) {
                $q->where('carrera', $request->carrera_id);
            });
        }

        // Filtrar por materia
        if ($request->filled('materia_id')) {
            $query->where('materia_id', $request->materia_id);
        }

        // Filtrar por año de cursado
        if ($request->filled('anio_cursado')) {
            $query->whereHas('materia', function($q) use ($request) {
                $q->where('anno', $request->anio_cursado);
            });
        }

        // Buscar por DNI o nombre de alumno
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->whereHas('alumno', function($q) use ($buscar) {
                $q->where('dni', 'like', "%{$buscar}%")
                  ->orWhere('apellido', 'like', "%{$buscar}%")
                  ->orWhere('nombre', 'like', "%{$buscar}%");
            });
        }

        $inscripciones = $query
            ->orderBy('fecha_inscripcion', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Obtener períodos para el filtro
        $periodos = PeriodoInscripcion::orderBy('anio', 'desc')
            ->orderBy('cuatrimestre', 'desc')
            ->get(['id', 'nombre', 'anio', 'cuatrimestre', 'activo']);

        // Obtener carreras para el filtro
        $carreras = Carrera::orderBy('nombre')
            ->get(['Id', 'nombre']);

        // Obtener materias para el filtro
        $materias = Materia::orderBy('nombre')
            ->get()
            ->map(function ($materia) {
                $carreraObj = Carrera::find($materia->carrera);
                return [
                    'id' => $materia->id,
                    'nombre' => $materia->nombre,
                    'carrera_id' => $materia->carrera,
                    'carrera_nombre' => $carreraObj->nombre ?? '',
                ];
            });

        return Inertia::render('Admin/Inscripciones/Index', [
            'tipo' => 'cursado',
            'inscripciones' => $inscripciones,
            'periodos' => $periodos,
            'carreras' => $carreras,
            'materias' => $materias,
            'filtros' => $request->only(['periodo_id', 'carrera_id', 'materia_id', 'anio_cursado', 'buscar', 'tipo']),
        ]);
    }

    /**
     * Listado de inscripciones a MESAS DE EXAMEN
     */
    private function indexMesas(Request $request)
    {
        $query = InscripcionMesa::query()
            ->with(['alumno', 'mesa.materia.carrera', 'mesa.periodo']);

        // Filtrar por mesa específica
        if ($request->filled('mesa_id')) {
            $query->where('mesa_id', $request->mesa_id);
        }

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtrar por carrera
        if ($request->filled('carrera_id')) {
            $query->whereHas('mesa.materia', function($q) use ($request) {
                $q->where('carrera', $request->carrera_id);
            });
        }

        // Buscar por DNI o nombre de alumno
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->whereHas('alumno', function($q) use ($buscar) {
                $q->where('dni', 'like', "%{$buscar}%")
                  ->orWhere('apellido', 'like', "%{$buscar}%")
                  ->orWhere('nombre', 'like', "%{$buscar}%");
            });
        }

        $inscripciones = $query
            ->orderBy('fecha_inscripcion', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Obtener carreras para el filtro
        $carreras = Carrera::orderBy('nombre')
            ->get(['Id', 'nombre']);

        // Obtener mesas disponibles para el filtro (últimos 6 meses)
        $mesas = MesaExamen::with(['materia'])
            ->where('fecha_examen', '>=', now()->subMonths(6))
            ->orderBy('fecha_examen', 'desc')
            ->get()
            ->map(function ($mesa) {
                $carreraId = $mesa->materia->carrera;
                $carreraObj = Carrera::find($carreraId);
                return [
                    'id' => $mesa->id,
                    'nombre' => $mesa->materia->nombre . ' - ' . \Carbon\Carbon::parse($mesa->fecha_examen)->format('d/m/Y'),
                    'carrera_id' => $carreraId,
                    'carrera_nombre' => $carreraObj->nombre ?? 'Sin carrera',
                    'materia_anio' => $mesa->materia->anno ?? null,
                    'inscriptos_count' => $mesa->inscripciones()->count(),
                ];
            });

        // Obtener períodos para el filtro
        $periodos = PeriodoInscripcion::orderBy('anio', 'desc')
            ->orderBy('cuatrimestre', 'desc')
            ->get(['id', 'nombre', 'anio', 'cuatrimestre', 'activo']);

        return Inertia::render('Admin/Inscripciones/Index', [
            'tipo' => 'mesas',
            'inscripciones' => $inscripciones,
            'carreras' => $carreras,
            'mesas' => $mesas,
            'periodos' => $periodos,
            'filtros' => $request->only(['carrera_id', 'buscar', 'tipo', 'mesa_id']),
        ]);
    }

    /**
     * Eliminar una inscripción (cursado o mesa)
     */
    public function destroy(Request $request, $id)
    {
        $tipo = $request->get('tipo', 'cursado');

        try {
            if ($tipo === 'mesas') {
                return $this->destroyMesa($id);
            }

            // ELIMINAR INSCRIPCIÓN A CURSADO
            $inscripcion = Inscripcion::findOrFail($id);

            // Guardar datos para la notificación
            $alumnoId = $inscripcion->alumno_id;
            $materiaNombre = $inscripcion->materia->nombre;
            $periodoNombre = $inscripcion->periodo->nombre;

            // Eliminar la inscripción
            $inscripcion->delete();

            // También eliminar de la tabla legacy si existe
            DB::table('tbl_alumnos_cursa')
                ->where('alumno', $inscripcion->alumno_id)
                ->where('materia', $inscripcion->materia_id)
                ->where('carrera', $inscripcion->carrera_id)
                ->delete();

            // Crear notificación para el alumno
            $user = \App\Models\User::where('alumno_id', $alumnoId)->first();
            if ($user) {
                Notificacion::create([
                    'user_id' => $user->id,
                    'tipo' => 'inscripcion_cancelada',
                    'titulo' => 'Inscripción Cancelada',
                    'mensaje' => "Tu inscripción a {$materiaNombre} ({$periodoNombre}) ha sido cancelada por la administración.",
                    'icono' => 'bx-x-circle',
                    'color' => 'red',
                    'leida' => false,
                ]);
            }

            return redirect()->back()->with('success', 'Inscripción eliminada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar la inscripción: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar inscripción a MESA DE EXAMEN
     */
    private function destroyMesa($id)
    {
        try {
            $inscripcion = InscripcionMesa::findOrFail($id);

            // Guardar datos para la notificación
            $alumnoId = $inscripcion->alumno_id;
            $materiaNombre = $inscripcion->mesa->materia->nombre;
            $mesaFecha = $inscripcion->mesa->fecha;

            // Eliminar la inscripción
            $inscripcion->delete();

            // Crear notificación para el alumno
            $user = \App\Models\User::where('alumno_id', $alumnoId)->first();
            if ($user) {
                Notificacion::create([
                    'user_id' => $user->id,
                    'tipo' => 'mesa_cancelada',
                    'titulo' => 'Inscripción a Mesa Cancelada',
                    'mensaje' => "Tu inscripción a la mesa de {$materiaNombre} ({$mesaFecha}) ha sido cancelada por la administración.",
                    'icono' => 'bx-x-circle',
                    'color' => 'red',
                    'leida' => false,
                ]);
            }

            return redirect()->back()->with('success', 'Inscripción a mesa eliminada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar la inscripción: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar el estado de una inscripción (cursado o mesa)
     */
    public function update(Request $request, $id)
    {
        $tipo = $request->get('tipo', 'cursado');

        if ($tipo === 'mesas') {
            return $this->updateMesa($request, $id);
        }

        // ACTUALIZAR INSCRIPCIÓN A CURSADO
        $request->validate([
            'estado' => 'required|in:confirmada,cancelada,pendiente',
        ]);

        try {
            $inscripcion = Inscripcion::findOrFail($id);
            $estadoAnterior = $inscripcion->estado;

            $inscripcion->update([
                'estado' => $request->estado,
            ]);

            // Si se cancela, también actualizar legacy
            if ($request->estado === 'cancelada') {
                DB::table('tbl_alumnos_cursa')
                    ->where('alumno', $inscripcion->alumno_id)
                    ->where('materia', $inscripcion->materia_id)
                    ->where('carrera', $inscripcion->carrera_id)
                    ->delete();
            }

            // Notificar al alumno si cambió el estado
            if ($estadoAnterior !== $request->estado) {
                $user = \App\Models\User::where('alumno_id', $inscripcion->alumno_id)->first();
                if ($user) {
                    $mensaje = match($request->estado) {
                        'confirmada' => "Tu inscripción a {$inscripcion->materia->nombre} ha sido confirmada.",
                        'cancelada' => "Tu inscripción a {$inscripcion->materia->nombre} ha sido cancelada.",
                        'pendiente' => "Tu inscripción a {$inscripcion->materia->nombre} está pendiente de revisión.",
                        default => "El estado de tu inscripción a {$inscripcion->materia->nombre} ha cambiado.",
                    };

                    Notificacion::create([
                        'user_id' => $user->id,
                        'tipo' => 'inscripcion_actualizada',
                        'titulo' => 'Estado de Inscripción Actualizado',
                        'mensaje' => $mensaje,
                        'icono' => 'bx-info-circle',
                        'color' => 'blue',
                        'leida' => false,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Estado de inscripción actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar la inscripción: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar inscripción a MESA DE EXAMEN
     */
    private function updateMesa(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:inscripto,presente,ausente,aprobado,desaprobado',
            'nota' => 'nullable|numeric|min:0|max:10',
        ]);

        try {
            $inscripcion = InscripcionMesa::findOrFail($id);
            $estadoAnterior = $inscripcion->estado;

            $inscripcion->update([
                'estado' => $request->estado,
                'nota' => $request->nota,
            ]);

            // Notificar al alumno si cambió el estado
            if ($estadoAnterior !== $request->estado) {
                $user = \App\Models\User::where('alumno_id', $inscripcion->alumno_id)->first();
                if ($user) {
                    $materiaNombre = $inscripcion->mesa->materia->nombre;
                    $mensaje = match($request->estado) {
                        'inscripto' => "Tu inscripción a la mesa de {$materiaNombre} ha sido confirmada.",
                        'presente' => "Tu asistencia a la mesa de {$materiaNombre} ha sido registrada.",
                        'ausente' => "Has sido marcado como ausente en la mesa de {$materiaNombre}.",
                        'aprobado' => "¡Felicitaciones! Has aprobado la mesa de {$materiaNombre}" . ($request->nota ? " con nota {$request->nota}" : "") . ".",
                        'desaprobado' => "Lamentablemente no has aprobado la mesa de {$materiaNombre}" . ($request->nota ? " (nota: {$request->nota})" : "") . ".",
                        default => "El estado de tu inscripción a la mesa de {$materiaNombre} ha cambiado.",
                    };

                    Notificacion::create([
                        'user_id' => $user->id,
                        'tipo' => 'mesa_actualizada',
                        'titulo' => 'Estado de Mesa Actualizado',
                        'mensaje' => $mensaje,
                        'icono' => $request->estado === 'aprobado' ? 'bx-check-circle' : 'bx-info-circle',
                        'color' => $request->estado === 'aprobado' ? 'green' : 'blue',
                        'leida' => false,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Estado de inscripción a mesa actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar la inscripción: ' . $e->getMessage());
        }
    }

    /**
     * Inscribir manualmente a un alumno
     */
    public function store(Request $request)
    {
        $request->validate([
            'alumno_id' => 'required|exists:tbl_alumnos,id',
            'materia_id' => 'required|exists:tbl_materias,id',
            'periodo_id' => 'required|exists:tbl_periodos_inscripcion,id',
            'carrera_id' => 'required|exists:tbl_carreras,Id',
        ]);

        try {
            // Verificar que no exista ya
            $existe = Inscripcion::where('alumno_id', $request->alumno_id)
                ->where('materia_id', $request->materia_id)
                ->where('periodo_id', $request->periodo_id)
                ->where('estado', '!=', 'cancelada')
                ->exists();

            if ($existe) {
                return redirect()->back()->with('error', 'El alumno ya está inscrito en esta materia');
            }

            // Crear inscripción
            $inscripcion = Inscripcion::create([
                'alumno_id' => $request->alumno_id,
                'materia_id' => $request->materia_id,
                'periodo_id' => $request->periodo_id,
                'carrera_id' => $request->carrera_id,
                'estado' => 'confirmada',
                'fecha_inscripcion' => now(),
            ]);

            // También crear en tabla legacy
            DB::table('tbl_alumnos_cursa')->insert([
                'alumno' => $request->alumno_id,
                'materia' => $request->materia_id,
                'carrera' => $request->carrera_id,
            ]);

            // Notificar al alumno
            $user = \App\Models\User::where('alumno_id', $request->alumno_id)->first();
            if ($user) {
                $materia = Materia::find($request->materia_id);
                Notificacion::create([
                    'user_id' => $user->id,
                    'tipo' => 'inscripcion_confirmada',
                    'titulo' => 'Inscripción Realizada',
                    'mensaje' => "Has sido inscrito a {$materia->nombre} por la administración.",
                    'icono' => 'bx-check-circle',
                    'color' => 'green',
                    'leida' => false,
                ]);
            }

            return redirect()->back()->with('success', 'Alumno inscrito correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear la inscripción: ' . $e->getMessage());
        }
    }

    /**
     * Exportar listado de inscriptos a mesa en PDF
     */
    public function exportarMesaPdf(Request $request, $mesaId)
    {
        $mesa = MesaExamen::with(['materia', 'periodo', 'presidente', 'vocal1', 'vocal2'])
            ->findOrFail($mesaId);

        // Obtener la carrera de la materia
        $carrera = Carrera::find($mesa->materia->carrera);

        $inscripciones = InscripcionMesa::with('alumno')
            ->where('mesa_id', $mesaId)
            ->orderBy('fecha_inscripcion')
            ->get();

        // Obtener configuración institucional
        $configuracion = Configuracion::first();

        // Verificar si el módulo de aula está activo
        $mostrarAula = \App\Models\ConfiguracionModulo::estaActivo('mesas_aula');

        $pdf = PDF::loadView('pdfs.listado-inscriptos-mesa', [
            'mesa' => $mesa,
            'carrera' => $carrera,
            'inscripciones' => $inscripciones,
            'configuracion' => $configuracion,
            'mostrarAula' => $mostrarAula,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'listado_inscriptos_' . str_replace(' ', '_', $mesa->materia->nombre) . '_' . $mesa->fecha_examen . '.pdf';

        // stream() abre en navegador para previsualizar
        return $pdf->stream($filename);
    }

    /**
     * Exportar listado de inscriptos a mesa en CSV
     */
    public function exportarMesaCsv(Request $request, $mesaId)
    {
        $mesa = MesaExamen::with('materia.carrera')
            ->findOrFail($mesaId);

        $inscripciones = InscripcionMesa::with('alumno')
            ->where('mesa_id', $mesaId)
            ->orderBy('fecha_inscripcion')
            ->get();

        $filename = 'listado_inscriptos_' . str_replace(' ', '_', $mesa->materia->nombre) . '_' . $mesa->fecha_examen . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($mesa, $inscripciones) {
            $file = fopen('php://output', 'w');

            // BOM para Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezado con información de la mesa
            fputcsv($file, ['LISTADO DE INSCRIPTOS A MESA DE EXAMEN'], ';');
            fputcsv($file, ['Materia:', $mesa->materia->nombre], ';');
            fputcsv($file, ['Carrera:', $mesa->materia->carrera->nombre], ';');
            fputcsv($file, ['Fecha:', \Carbon\Carbon::parse($mesa->fecha_examen)->format('d/m/Y')], ';');
            fputcsv($file, ['Hora:', $mesa->hora_examen], ';');
            fputcsv($file, [''], ';');

            // Encabezados de columnas
            fputcsv($file, ['N°', 'DNI', 'Apellido', 'Nombre', 'Estado', 'Nota', 'Fecha Inscripción'], ';');

            // Datos
            $numero = 1;
            foreach ($inscripciones as $inscripcion) {
                fputcsv($file, [
                    $numero++,
                    $inscripcion->alumno->dni,
                    $inscripcion->alumno->apellido,
                    $inscripcion->alumno->nombre,
                    ucfirst($inscripcion->estado),
                    $inscripcion->nota ?? '-',
                    \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y H:i'),
                ], ';');
            }

            fputcsv($file, [''], ';');
            fputcsv($file, ['Total inscriptos:', count($inscripciones)], ';');

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Exportar listado de inscriptos a cursado en PDF
     */
    public function exportarCursadoPdf(Request $request)
    {
        $query = Inscripcion::query()
            ->with(['alumno', 'materia.carrera', 'periodo']);

        // Aplicar filtros
        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }
        if ($request->filled('carrera_id')) {
            $query->whereHas('materia', function($q) use ($request) {
                $q->where('carrera', $request->carrera_id);
            });
        }
        if ($request->filled('materia_id')) {
            $query->where('materia_id', $request->materia_id);
        }
        if ($request->filled('anio_cursado')) {
            $query->whereHas('materia', function($q) use ($request) {
                $q->where('anno', $request->anio_cursado);
            });
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $inscripciones = $query->orderBy('fecha_inscripcion', 'desc')->get();

        // Obtener información del período si está filtrado
        $periodo = null;
        if ($request->filled('periodo_id')) {
            $periodo = PeriodoInscripcion::find($request->periodo_id);
        }

        // Obtener configuración institucional
        $configuracion = Configuracion::first();

        $pdf = PDF::loadView('pdfs.listado-inscriptos-cursado', [
            'inscripciones' => $inscripciones,
            'periodo' => $periodo,
            'configuracion' => $configuracion,
            'filtros' => $request->only(['periodo_id', 'carrera_id', 'estado']),
        ]);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'listado_inscriptos_cursado_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Exportar listado de inscriptos a cursado en CSV
     */
    public function exportarCursadoCsv(Request $request)
    {
        $query = Inscripcion::query()
            ->with(['alumno', 'materia.carrera', 'periodo']);

        // Aplicar filtros
        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }
        if ($request->filled('carrera_id')) {
            $query->whereHas('materia', function($q) use ($request) {
                $q->where('carrera', $request->carrera_id);
            });
        }
        if ($request->filled('materia_id')) {
            $query->where('materia_id', $request->materia_id);
        }
        if ($request->filled('anio_cursado')) {
            $query->whereHas('materia', function($q) use ($request) {
                $q->where('anno', $request->anio_cursado);
            });
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $inscripciones = $query->orderBy('fecha_inscripcion', 'desc')->get();

        $filename = 'listado_inscriptos_cursado_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($inscripciones) {
            $file = fopen('php://output', 'w');

            // BOM para Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezado
            fputcsv($file, ['LISTADO DE INSCRIPTOS A CURSADO'], ';');
            fputcsv($file, ['Fecha de exportación:', now()->format('d/m/Y H:i')], ';');
            fputcsv($file, [''], ';');

            // Encabezados de columnas
            fputcsv($file, ['N°', 'DNI', 'Apellido', 'Nombre', 'Materia', 'Carrera', 'Período', 'Estado', 'Fecha Inscripción'], ';');

            // Datos
            $numero = 1;
            foreach ($inscripciones as $inscripcion) {
                fputcsv($file, [
                    $numero++,
                    $inscripcion->alumno->dni,
                    $inscripcion->alumno->apellido,
                    $inscripcion->alumno->nombre,
                    $inscripcion->materia->nombre,
                    $inscripcion->materia->carrera->nombre,
                    $inscripcion->periodo->nombre,
                    ucfirst($inscripcion->estado),
                    \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y H:i'),
                ], ';');
            }

            fputcsv($file, [''], ';');
            fputcsv($file, ['Total inscriptos:', count($inscripciones)], ';');

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
