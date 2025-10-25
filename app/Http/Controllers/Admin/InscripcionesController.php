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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

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

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
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

        return Inertia::render('Admin/Inscripciones/Index', [
            'tipo' => 'cursado',
            'inscripciones' => $inscripciones,
            'periodos' => $periodos,
            'carreras' => $carreras,
            'filtros' => $request->only(['periodo_id', 'carrera_id', 'estado', 'buscar', 'tipo']),
        ]);
    }

    /**
     * Listado de inscripciones a MESAS DE EXAMEN
     */
    private function indexMesas(Request $request)
    {
        $query = InscripcionMesa::query()
            ->with(['alumno', 'mesa.materia.carrera', 'mesa.periodo']);

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

        return Inertia::render('Admin/Inscripciones/Index', [
            'tipo' => 'mesas',
            'inscripciones' => $inscripciones,
            'carreras' => $carreras,
            'filtros' => $request->only(['carrera_id', 'estado', 'buscar', 'tipo']),
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
}
