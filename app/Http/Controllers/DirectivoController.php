<?php

namespace App\Http\Controllers;

use App\Models\AlumnoMateria;
use App\Models\HistorialRevision;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Notificacion;
use App\Traits\HandlesErrors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DirectivoController extends Controller
{
    use HandlesErrors;
    /**
     * Panel principal del Directivo
     * Muestra legajos pendientes de revisión y estadísticas
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Query base para legajos pendientes de revisión
        $queryPendientes = AlumnoMateria::with([
            'alumno',
            'materiaRelacion',
            'carrera'
        ])->pendientesDirectivo();

        // Query base para legajos con observaciones del Supervisor
        $queryObservaciones = AlumnoMateria::with([
            'alumno',
            'materiaRelacion',
            'carrera',
            'supervisorRevisor'
        ])->conObservacionesSupervisor();

        // Aplicar filtros
        if ($request->filled('carrera')) {
            $queryPendientes->where('carrera', $request->carrera);
            $queryObservaciones->where('carrera', $request->carrera);
        }

        if ($request->filled('dni')) {
            $dni = $request->dni;
            $queryPendientes->whereHas('alumno', function($q) use ($dni) {
                $q->where('dni', 'like', "%{$dni}%");
            });
            $queryObservaciones->whereHas('alumno', function($q) use ($dni) {
                $q->where('dni', 'like', "%{$dni}%");
            });
        }

        // Obtener resultados paginados
        $legajosPendientes = $queryPendientes
            ->orderBy('fecha', 'desc')
            ->paginate(20)
            ->withQueryString();

        $legajosConObservaciones = $queryObservaciones
            ->orderBy('fecha_revision_supervisor', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Estadísticas (sin filtros para mostrar totales reales)
        $estadisticas = [
            'pendientes_revision' => AlumnoMateria::pendientesDirectivo()->count(),
            'con_observaciones_supervisor' => AlumnoMateria::conObservacionesSupervisor()->count(),
            'aprobados_hoy' => AlumnoMateria::where('estado_revision', 'aprobado_directivo')
                ->whereDate('fecha_revision_directivo', today())
                ->count(),
            'total_revisados' => AlumnoMateria::where('revisado_por_directivo', $user->id)->count(),
        ];

        // Obtener carreras para filtros
        $carreras = Carrera::all();

        return Inertia::render('Directivo/Index', [
            'legajosPendientes' => $legajosPendientes,
            'legajosConObservaciones' => $legajosConObservaciones,
            'estadisticas' => $estadisticas,
            'carreras' => $carreras,
            'filtros' => $request->only(['carrera', 'dni']),
        ]);
    }

    /**
     * Ver detalle de un legajo para revisión
     */
    public function show($id)
    {
        $legajo = AlumnoMateria::with([
            'alumno.carreraRelacion',
            'materiaRelacion',
            'carrera',
            'directivoRevisor',
            'supervisorRevisor',
            'historialRevisiones.revisor'
        ])->findOrFail($id);

        // Obtener todo el historial académico del alumno para contexto
        $historialCompleto = AlumnoMateria::with('materiaRelacion')
            ->where('alumno', $legajo->alumno)
            ->where('carrera', $legajo->carrera)
            ->orderBy('fecha', 'desc')
            ->get();

        return Inertia::render('Directivo/Show', [
            'legajo' => [
                'id' => $legajo->Id,
                'alumno' => [
                    'id' => $legajo->alumno->id,
                    'dni' => $legajo->alumno->dni,
                    'nombre_completo' => $legajo->alumno->nombre_completo,
                    'email' => $legajo->alumno->email,
                    'carrera' => $legajo->alumno->carreraRelacion->nombre ?? 'Sin carrera',
                ],
                'materia' => [
                    'id' => $legajo->materiaRelacion->id ?? null,
                    'nombre' => $legajo->materiaRelacion->nombre ?? 'Sin nombre',
                    'anno' => $legajo->materiaRelacion->anno ?? null,
                ],
                'nota' => $legajo->nota,
                'cursada' => $legajo->cursada === '1',
                'rendida' => $legajo->rendida === '1',
                'libre' => $legajo->libre == 1,
                'equivalencia' => $legajo->equivalencia == 1,
                'fecha' => $legajo->fecha ? $legajo->fecha->format('d/m/Y') : null,
                'libro' => $legajo->libro,
                'folio' => $legajo->folio,
                'calificacion_cursada' => $legajo->getAttribute('calificacion-cursada'),
                'calificacion_rendida' => $legajo->calificacion_rendida,
                'estado_revision' => $legajo->estado_revision,
                'observaciones_directivo' => $legajo->observaciones_directivo,
                'observaciones_supervisor' => $legajo->observaciones_supervisor,
                'fecha_revision_directivo' => $legajo->fecha_revision_directivo?->format('d/m/Y H:i'),
                'fecha_revision_supervisor' => $legajo->fecha_revision_supervisor?->format('d/m/Y H:i'),
                'revisor_directivo' => $legajo->directivoRevisor?->nombre,
                'revisor_supervisor' => $legajo->supervisorRevisor?->nombre,
            ],
            'historialCompleto' => $historialCompleto->map(function ($item) {
                return [
                    'id' => $item->Id,
                    'materia' => $item->materiaRelacion->nombre ?? 'Sin nombre',
                    'nota' => $item->nota,
                    'cursada' => $item->cursada === '1',
                    'rendida' => $item->rendida === '1',
                    'fecha' => $item->fecha?->format('d/m/Y'),
                    'estado_revision' => $item->estado_revision,
                ];
            }),
            'historialRevisiones' => $legajo->historialRevisiones->map(function ($revision) {
                return [
                    'id' => $revision->id,
                    'revisor' => $revision->revisor->nombre ?? 'N/A',
                    'tipo_revisor' => $revision->tipo_revisor,
                    'accion' => $revision->accion,
                    'observaciones' => $revision->observaciones,
                    'estado_anterior' => $revision->estado_anterior,
                    'estado_nuevo' => $revision->estado_nuevo,
                    'fecha' => $revision->created_at->format('d/m/Y H:i'),
                ];
            }),
        ]);
    }

    /**
     * Aprobar un legajo
     */
    public function aprobar(Request $request, $id)
    {
        $user = $request->user();
        $legajo = AlumnoMateria::with(['alumno'])->findOrFail($id);

        // Validar que esté en estado correcto
        if (!in_array($legajo->estado_revision, ['pendiente', 'observaciones_supervisor'])) {
            return back()->withErrors([
                'error' => 'Este legajo no está en un estado que permita aprobación.'
            ]);
        }

        try {
            DB::beginTransaction();

            $estadoAnterior = $legajo->estado_revision;
            $estadoNuevo = 'aprobado_directivo';

            // Actualizar legajo
            $legajo->update([
                'estado_revision' => $estadoNuevo,
                'revisado_por_directivo' => $user->id,
                'fecha_revision_directivo' => now(),
                'observaciones_directivo' => null, // Limpiar observaciones al aprobar
            ]);

            // Registrar en historial
            HistorialRevision::registrar(
                $legajo->Id,
                $user->id,
                'directivo',
                'aprobar',
                $estadoAnterior,
                $estadoNuevo,
                'Legajo aprobado por Directivo'
            );

            // Crear notificación para Supervisores
            $this->notificarSupervisores($legajo, $user);

            DB::commit();

            \Log::info('Legajo aprobado por Directivo', [
                'legajo_id' => $legajo->Id,
                'alumno_id' => $legajo->alumno,
                'directivo_id' => $user->id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $estadoNuevo,
            ]);

            return redirect()->route('directivo.index')
                ->with('success', 'Legajo aprobado exitosamente. Ahora pasa a revisión del Supervisor.');

        } catch (\Exception $e) {
            DB::rollBack();

            $this->handleError($e, 'aprobar legajo', [
                'legajo_id' => $id,
                'aprobado_por' => $user->id
            ]);

            return back()->withErrors([
                'error' => $this->getFriendlyErrorMessage($e, 'Error al aprobar el legajo')
            ]);
        }
    }

    /**
     * Rechazar un legajo con observaciones
     */
    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'observaciones' => 'required|string|min:10|max:1000'
        ], [
            'observaciones.required' => 'Debes especificar las observaciones y correcciones necesarias.',
            'observaciones.min' => 'Las observaciones deben tener al menos 10 caracteres.',
        ]);

        $user = $request->user();
        $legajo = AlumnoMateria::with(['alumno.user'])->findOrFail($id);

        // Validar que esté en estado correcto
        if (!in_array($legajo->estado_revision, ['pendiente', 'observaciones_supervisor'])) {
            return back()->withErrors([
                'error' => 'Este legajo no está en un estado que permita rechazo.'
            ]);
        }

        try {
            DB::beginTransaction();

            $estadoAnterior = $legajo->estado_revision;
            $estadoNuevo = 'observaciones_directivo';

            // Actualizar legajo
            $legajo->update([
                'estado_revision' => $estadoNuevo,
                'revisado_por_directivo' => $user->id,
                'fecha_revision_directivo' => now(),
                'observaciones_directivo' => $request->observaciones,
            ]);

            // Registrar en historial
            HistorialRevision::registrar(
                $legajo->Id,
                $user->id,
                'directivo',
                'rechazar',
                $estadoAnterior,
                $estadoNuevo,
                $request->observaciones
            );

            // Crear notificación para Bedel/Admin
            $this->notificarBedelObservaciones($legajo, $request->observaciones);

            DB::commit();

            \Log::info('Legajo rechazado por Directivo con observaciones', [
                'legajo_id' => $legajo->Id,
                'alumno_id' => $legajo->alumno,
                'directivo_id' => $user->id,
                'observaciones' => $request->observaciones,
            ]);

            return redirect()->route('directivo.index')
                ->with('success', 'Legajo rechazado. Se notificó al Bedel para realizar correcciones.');

        } catch (\Exception $e) {
            DB::rollBack();

            $this->handleError($e, 'rechazar legajo', [
                'legajo_id' => $id,
                'rechazado_por' => $user->id
            ]);

            return back()->withErrors([
                'error' => $this->getFriendlyErrorMessage($e, 'Error al rechazar el legajo')
            ]);
        }
    }

    /**
     * Editar datos de un legajo (corrección de errores)
     */
    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'nota' => 'nullable|numeric|min:1|max:10',
            'cursada' => 'nullable|in:0,1',
            'rendida' => 'nullable|in:0,1',
            'libre' => 'nullable|in:0,1',
            'equivalencia' => 'nullable|in:0,1',
            'libro' => 'nullable|string|max:50',
            'folio' => 'nullable|string|max:50',
            'fecha' => 'nullable|date',
            'motivo_correccion' => 'required|string|min:10|max:500'
        ], [
            'motivo_correccion.required' => 'Debes especificar el motivo de la corrección.',
        ]);

        $user = $request->user();
        $legajo = AlumnoMateria::findOrFail($id);

        try {
            DB::beginTransaction();

            $cambios = [];
            $datosActualizados = [];

            // Detectar cambios
            if ($request->filled('nota') && $request->nota != $legajo->nota) {
                $cambios[] = "Nota: {$legajo->nota} → {$request->nota}";
                $datosActualizados['nota'] = $request->nota;
            }

            if ($request->filled('cursada') && $request->cursada != $legajo->cursada) {
                $cambios[] = "Cursada: {$legajo->cursada} → {$request->cursada}";
                $datosActualizados['cursada'] = $request->cursada;
            }

            if ($request->filled('rendida') && $request->rendida != $legajo->rendida) {
                $cambios[] = "Rendida: {$legajo->rendida} → {$request->rendida}";
                $datosActualizados['rendida'] = $request->rendida;
            }

            if ($request->filled('libre') && $request->libre != $legajo->libre) {
                $cambios[] = "Libre: {$legajo->libre} → {$request->libre}";
                $datosActualizados['libre'] = $request->libre;
            }

            if ($request->filled('equivalencia') && $request->equivalencia != $legajo->equivalencia) {
                $cambios[] = "Equivalencia: {$legajo->equivalencia} → {$request->equivalencia}";
                $datosActualizados['equivalencia'] = $request->equivalencia;
            }

            if ($request->filled('libro') && $request->libro != $legajo->libro) {
                $cambios[] = "Libro: {$legajo->libro} → {$request->libro}";
                $datosActualizados['libro'] = $request->libro;
            }

            if ($request->filled('folio') && $request->folio != $legajo->folio) {
                $cambios[] = "Folio: {$legajo->folio} → {$request->folio}";
                $datosActualizados['folio'] = $request->folio;
            }

            if ($request->filled('fecha') && $request->fecha != $legajo->fecha?->format('Y-m-d')) {
                $cambios[] = "Fecha: {$legajo->fecha?->format('Y-m-d')} → {$request->fecha}";
                $datosActualizados['fecha'] = $request->fecha;
            }

            if (empty($cambios)) {
                return back()->withErrors(['error' => 'No se detectaron cambios para actualizar.']);
            }

            // Actualizar legajo
            $legajo->update($datosActualizados);

            // Registrar corrección en historial
            $observaciones = "CORRECCIÓN: {$request->motivo_correccion}\nCambios: " . implode(', ', $cambios);

            HistorialRevision::registrar(
                $legajo->Id,
                $user->id,
                'directivo',
                'observar',
                $legajo->estado_revision,
                $legajo->estado_revision, // Mantiene el mismo estado
                $observaciones
            );

            DB::commit();

            \Log::info('Legajo corregido por Directivo', [
                'legajo_id' => $legajo->Id,
                'directivo_id' => $user->id,
                'cambios' => $cambios,
                'motivo' => $request->motivo_correccion,
            ]);

            return back()->with('success', 'Legajo actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            $this->handleError($e, 'actualizar legajo', [
                'legajo_id' => $id,
                'actualizado_por' => $user->id
            ]);

            return back()->withErrors([
                'error' => $this->getFriendlyErrorMessage($e, 'Error al actualizar el legajo')
            ]);
        }
    }

    /**
     * Notificar a todos los Supervisores sobre legajo aprobado
     */
    private function notificarSupervisores($legajo, $directivo)
    {
        $supervisores = \App\Models\User::where('tipo', \App\Models\TipoUsuario::SUPERVISOR)
            ->where('activo', true)
            ->get();

        foreach ($supervisores as $supervisor) {
            try {
                $materiaNombre = $legajo->materiaRelacion->nombre ?? 'Materia';
                Notificacion::crear(
                    $supervisor->id,
                    'legajo_aprobado_directivo',
                    'Legajo Aprobado por Directivo',
                    "Nuevo legajo pendiente de supervisión: {$legajo->alumno->nombre_completo} - {$materiaNombre}",
                    [
                        'icono' => 'bx-check-circle',
                        'color' => 'blue',
                        'url' => route('supervisor.show', ['id' => $legajo->Id]),
                        'datos' => [
                            'legajo_id' => $legajo->Id,
                            'alumno_id' => $legajo->alumno,
                            'directivo' => $directivo->nombre,
                        ],
                    ]
                );
            } catch (\Exception $e) {
                \Log::error('Error al notificar a supervisor: ' . $e->getMessage());
            }
        }
    }

    /**
     * Notificar a Bedeles sobre observaciones
     */
    private function notificarBedelObservaciones($legajo, $observaciones)
    {
        $bedeles = \App\Models\User::where('tipo', \App\Models\TipoUsuario::USUARIO)
            ->where('activo', true)
            ->get();

        foreach ($bedeles as $bedel) {
            try {
                Notificacion::crear(
                    $bedel->id,
                    'legajo_observaciones_directivo',
                    'Observaciones de Directivo',
                    "El legajo de {$legajo->alumno->nombre_completo} requiere correcciones",
                    [
                        'icono' => 'bx-error-circle',
                        'color' => 'orange',
                        'url' => route('expediente.show', ['alumno' => $legajo->alumno]),
                        'datos' => [
                            'legajo_id' => $legajo->Id,
                            'alumno_id' => $legajo->alumno,
                            'observaciones' => $observaciones,
                        ],
                    ]
                );
            } catch (\Exception $e) {
                \Log::error('Error al notificar a bedel: ' . $e->getMessage());
            }
        }
    }
}
