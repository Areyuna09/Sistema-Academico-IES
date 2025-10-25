<?php

namespace App\Http\Controllers;

use App\Models\AlumnoMateria;
use App\Models\HistorialRevision;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SupervisorController extends Controller
{
    /**
     * Panel principal del Supervisor
     * Muestra legajos aprobados por Directivo pendientes de supervisión
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Query base para legajos pendientes de supervisión (aprobados por Directivo)
        $queryPendientes = AlumnoMateria::with([
            'alumno',
            'materiaRelacion',
            'carrera',
            'directivoRevisor'
        ])->pendientesSupervisor();

        // Query base para legajos con observaciones del Directivo (después de corrección)
        $queryCorregidos = AlumnoMateria::with([
            'alumno',
            'materiaRelacion',
            'carrera',
            'directivoRevisor'
        ])
            ->conObservacionesDirectivo()
            ->whereNotNull('revisado_por_supervisor'); // Ya los revisó antes

        // Aplicar filtros
        if ($request->filled('carrera')) {
            $queryPendientes->where('carrera', $request->carrera);
            $queryCorregidos->where('carrera', $request->carrera);
        }

        if ($request->filled('dni')) {
            $dni = $request->dni;
            $queryPendientes->whereHas('alumno', function($q) use ($dni) {
                $q->where('dni', 'like', "%{$dni}%");
            });
            $queryCorregidos->whereHas('alumno', function($q) use ($dni) {
                $q->where('dni', 'like', "%{$dni}%");
            });
        }

        // Obtener resultados paginados
        $legajosPendientes = $queryPendientes
            ->orderBy('fecha_revision_directivo', 'desc')
            ->paginate(20)
            ->withQueryString();

        $legajosCorregidos = $queryCorregidos
            ->orderBy('fecha_revision_directivo', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Estadísticas (sin filtros para mostrar totales reales)
        $estadisticas = [
            'pendientes_supervision' => AlumnoMateria::pendientesSupervisor()->count(),
            'legajos_corregidos' => AlumnoMateria::conObservacionesDirectivo()
                ->whereNotNull('revisado_por_supervisor')
                ->count(),
            'aprobados_hoy' => AlumnoMateria::where('estado_revision', 'aprobado_supervisor')
                ->whereDate('fecha_revision_supervisor', today())
                ->count(),
            'total_supervisados' => AlumnoMateria::where('revisado_por_supervisor', $user->id)->count(),
            'aprobados_final' => AlumnoMateria::aprobadosFinal()->count(),
        ];

        // Obtener carreras para filtros
        $carreras = Carrera::all();

        return Inertia::render('Supervisor/Index', [
            'legajosPendientes' => $legajosPendientes,
            'legajosCorregidos' => $legajosCorregidos,
            'estadisticas' => $estadisticas,
            'carreras' => $carreras,
            'filtros' => $request->only(['carrera', 'dni']),
        ]);
    }

    /**
     * Ver detalle de un legajo para supervisión
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

        // Obtener estadísticas del alumno
        $estadisticasAlumno = [
            'materias_aprobadas' => AlumnoMateria::where('alumno', $legajo->alumno)
                ->where('rendida', '1')
                ->count(),
            'materias_regulares' => AlumnoMateria::where('alumno', $legajo->alumno)
                ->where('cursada', '1')
                ->where('rendida', '!=', '1')
                ->count(),
            'promedio' => AlumnoMateria::where('alumno', $legajo->alumno)
                ->where('rendida', '1')
                ->avg('nota'),
        ];

        return Inertia::render('Supervisor/Show', [
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
            'estadisticasAlumno' => $estadisticasAlumno,
        ]);
    }

    /**
     * Aprobar un legajo (aprobación final)
     */
    public function aprobar(Request $request, $id)
    {
        $user = $request->user();
        $legajo = AlumnoMateria::with(['alumno', 'materiaRelacion'])->findOrFail($id);

        // Validar que esté en estado correcto
        if (!in_array($legajo->estado_revision, ['aprobado_directivo', 'observaciones_directivo'])) {
            return back()->withErrors([
                'error' => 'Este legajo no está en un estado que permita aprobación por Supervisor.'
            ]);
        }

        try {
            DB::beginTransaction();

            $estadoAnterior = $legajo->estado_revision;
            $estadoNuevo = 'aprobado_final';

            // Actualizar legajo
            $legajo->update([
                'estado_revision' => $estadoNuevo,
                'revisado_por_supervisor' => $user->id,
                'fecha_revision_supervisor' => now(),
                'observaciones_supervisor' => null, // Limpiar observaciones al aprobar
            ]);

            // Registrar en historial
            HistorialRevision::registrar(
                $legajo->Id,
                $user->id,
                'supervisor',
                'aprobar',
                $estadoAnterior,
                $estadoNuevo,
                'Legajo aprobado finalmente por Supervisor - Listo para Oficina de Títulos'
            );

            // Notificar a Directivos y Admins
            $this->notificarAprobacionFinal($legajo, $user);

            DB::commit();

            \Log::info('Legajo aprobado FINALMENTE por Supervisor', [
                'legajo_id' => $legajo->Id,
                'alumno_id' => $legajo->alumno,
                'supervisor_id' => $user->id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $estadoNuevo,
            ]);

            return redirect()->route('supervisor.index')
                ->with('success', 'Legajo aprobado finalmente. Está listo para la Oficina de Títulos.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al aprobar legajo (Supervisor)', [
                'legajo_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'error' => 'Error al aprobar el legajo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Rechazar un legajo con observaciones (vuelve a Directivo)
     */
    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'observaciones' => 'required|string|min:10|max:1000'
        ], [
            'observaciones.required' => 'Debes especificar las observaciones para el Directivo.',
            'observaciones.min' => 'Las observaciones deben tener al menos 10 caracteres.',
        ]);

        $user = $request->user();
        $legajo = AlumnoMateria::with(['alumno', 'materiaRelacion', 'directivoRevisor'])->findOrFail($id);

        // Validar que esté en estado correcto
        if (!in_array($legajo->estado_revision, ['aprobado_directivo', 'observaciones_directivo'])) {
            return back()->withErrors([
                'error' => 'Este legajo no está en un estado que permita rechazo por Supervisor.'
            ]);
        }

        try {
            DB::beginTransaction();

            $estadoAnterior = $legajo->estado_revision;
            $estadoNuevo = 'observaciones_supervisor';

            // Actualizar legajo
            $legajo->update([
                'estado_revision' => $estadoNuevo,
                'revisado_por_supervisor' => $user->id,
                'fecha_revision_supervisor' => now(),
                'observaciones_supervisor' => $request->observaciones,
            ]);

            // Registrar en historial
            HistorialRevision::registrar(
                $legajo->Id,
                $user->id,
                'supervisor',
                'rechazar',
                $estadoAnterior,
                $estadoNuevo,
                $request->observaciones
            );

            // Notificar a Directivos
            $this->notificarDirectivoObservaciones($legajo, $request->observaciones);

            DB::commit();

            \Log::info('Legajo rechazado por Supervisor con observaciones', [
                'legajo_id' => $legajo->Id,
                'alumno_id' => $legajo->alumno,
                'supervisor_id' => $user->id,
                'observaciones' => $request->observaciones,
            ]);

            return redirect()->route('supervisor.index')
                ->with('success', 'Legajo devuelto al Directivo con observaciones para corrección.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al rechazar legajo (Supervisor)', [
                'legajo_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'error' => 'Error al rechazar el legajo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Ver historial completo de revisiones de un alumno
     */
    public function historialAlumno($alumnoId)
    {
        $alumno = Alumno::with('carreraRelacion')->findOrFail($alumnoId);

        $legajos = AlumnoMateria::with([
            'materiaRelacion',
            'directivoRevisor',
            'supervisorRevisor',
            'historialRevisiones'
        ])
            ->where('alumno', $alumnoId)
            ->orderBy('fecha', 'desc')
            ->get();

        return Inertia::render('Supervisor/HistorialAlumno', [
            'alumno' => [
                'id' => $alumno->id,
                'dni' => $alumno->dni,
                'nombre_completo' => $alumno->nombre_completo,
                'email' => $alumno->email,
                'carrera' => $alumno->carreraRelacion->nombre ?? 'Sin carrera',
            ],
            'legajos' => $legajos->map(function ($legajo) {
                return [
                    'id' => $legajo->Id,
                    'materia' => $legajo->materiaRelacion->nombre ?? 'Sin nombre',
                    'nota' => $legajo->nota,
                    'cursada' => $legajo->cursada === '1',
                    'rendida' => $legajo->rendida === '1',
                    'fecha' => $legajo->fecha?->format('d/m/Y'),
                    'estado_revision' => $legajo->estado_revision,
                    'directivo_revisor' => $legajo->directivoRevisor?->nombre,
                    'supervisor_revisor' => $legajo->supervisorRevisor?->nombre,
                    'fecha_revision_directivo' => $legajo->fecha_revision_directivo?->format('d/m/Y'),
                    'fecha_revision_supervisor' => $legajo->fecha_revision_supervisor?->format('d/m/Y'),
                    'cantidad_revisiones' => $legajo->historialRevisiones->count(),
                ];
            }),
        ]);
    }

    /**
     * Notificar aprobación final a Directivos y Administradores
     */
    private function notificarAprobacionFinal($legajo, $supervisor)
    {
        // Notificar a Directivos
        $directivos = \App\Models\User::where('tipo', \App\Models\TipoUsuario::DIRECTIVO)
            ->where('activo', true)
            ->get();

        foreach ($directivos as $directivo) {
            try {
                $materiaNombre = $legajo->materiaRelacion->nombre ?? 'Materia';
                Notificacion::crear(
                    $directivo->id,
                    'legajo_aprobado_final',
                    'Legajo Aprobado Finalmente',
                    "Legajo de {$legajo->alumno->nombre_completo} - {$materiaNombre} aprobado finalmente por Supervisor",
                    [
                        'icono' => 'bx-check-double',
                        'color' => 'green',
                        'url' => route('directivo.show', ['id' => $legajo->Id]),
                        'datos' => [
                            'legajo_id' => $legajo->Id,
                            'alumno_id' => $legajo->alumno,
                            'supervisor' => $supervisor->nombre,
                        ],
                    ]
                );
            } catch (\Exception $e) {
                \Log::error('Error al notificar a directivo: ' . $e->getMessage());
            }
        }

        // Notificar a Administradores
        $admins = \App\Models\User::where('tipo', \App\Models\TipoUsuario::ADMIN)
            ->where('activo', true)
            ->get();

        foreach ($admins as $admin) {
            try {
                Notificacion::crear(
                    $admin->id,
                    'legajo_aprobado_final',
                    'Legajo Aprobado Finalmente',
                    "Legajo de {$legajo->alumno->nombre_completo} listo para Oficina de Títulos",
                    [
                        'icono' => 'bx-check-double',
                        'color' => 'green',
                        'url' => route('expediente.show', ['alumno' => $legajo->alumno]),
                        'datos' => [
                            'legajo_id' => $legajo->Id,
                            'alumno_id' => $legajo->alumno,
                            'supervisor' => $supervisor->nombre,
                        ],
                    ]
                );
            } catch (\Exception $e) {
                \Log::error('Error al notificar a admin: ' . $e->getMessage());
            }
        }
    }

    /**
     * Notificar a Directivos sobre observaciones del Supervisor
     */
    private function notificarDirectivoObservaciones($legajo, $observaciones)
    {
        $directivos = \App\Models\User::where('tipo', \App\Models\TipoUsuario::DIRECTIVO)
            ->where('activo', true)
            ->get();

        foreach ($directivos as $directivo) {
            try {
                $materiaNombre = $legajo->materiaRelacion->nombre ?? 'Materia';
                Notificacion::crear(
                    $directivo->id,
                    'legajo_observaciones_supervisor',
                    'Observaciones del Supervisor',
                    "El legajo de {$legajo->alumno->nombre_completo} - {$materiaNombre} requiere correcciones",
                    [
                        'icono' => 'bx-error-circle',
                        'color' => 'orange',
                        'url' => route('directivo.show', ['id' => $legajo->Id]),
                        'datos' => [
                            'legajo_id' => $legajo->Id,
                            'alumno_id' => $legajo->alumno,
                            'observaciones' => $observaciones,
                        ],
                    ]
                );
            } catch (\Exception $e) {
                \Log::error('Error al notificar a directivo: ' . $e->getMessage());
            }
        }
    }
}
