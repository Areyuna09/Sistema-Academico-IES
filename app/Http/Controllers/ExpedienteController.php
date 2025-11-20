<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\ProfesorMateria;
use App\Models\Inscripcion;
use App\Models\Asistencia;
use App\Models\NotaTemporal;
use App\Models\AlumnoMateria;
use App\Models\Materia;
use App\Models\User;
use App\Models\Notificacion;
use App\Services\EstadoAcademicoService;
use App\Traits\HandlesErrors;

class ExpedienteController extends Controller
{
    use HandlesErrors;

    public function index(Request $request)
    {
        $user = $request->user();

        // Debug temporal
        \Log::info('Expediente Index', [
            'user_tipo' => $user->tipo,
            'user_id' => $user->id,
            'user_name' => $user->nombre
        ]);

        // Profesor: mostrar panel de opciones (sin métricas, están en Dashboard)
        if ($user->tipo == 3) {
            \Log::info('Renderizando ProfesorPanel');
            return Inertia::render('Expediente/ProfesorPanel');
        }

        // Admin: mostrar panel administrativo completo
        if ($user->tipo == 1) {
            \Log::info('Renderizando AdminPanel');
            return $this->mostrarPanelAdmin($request);
        }

        // Alumno: mostrar su expediente personal
        if ($user->tipo == 4) {
            return $this->mostrarExpedienteAlumno($user);
        }

        \Log::warning('Usuario sin acceso a expediente', ['tipo' => $user->tipo]);
        abort(403, 'No autorizado para acceder a esta sección');
    }

    private function mostrarPanelAdmin(Request $request)
    {
        // Cargar todas las carreras
        $carreras = Carrera::all();

        // Query para materias
        $queryMaterias = Materia::with('carrera');

        // Filtros para materias (solo si no estamos en otra tab)
        if ($request->filled('carrera_materias')) {
            $queryMaterias->where('carrera', $request->carrera_materias);
        }
        if ($request->filled('anno')) {
            $queryMaterias->where('anno', $request->anno);
        }
        if ($request->filled('semestre')) {
            $queryMaterias->where('semestre', $request->semestre);
        }
        if ($request->filled('buscar_materias')) {
            $queryMaterias->where('nombre', 'like', "%{$request->buscar_materias}%");
        }

        $materias = $queryMaterias->orderBy('nombre', 'asc')
                                  ->paginate(15)
                                  ->withQueryString();

        // Query para PROFESORES - traer todos desde tbl_profesores
        $queryProfesores = \App\Models\Profesor::with(['carrera', 'user']);

        // Filtros para profesores
        if ($request->filled('buscar_profesores')) {
            $search = $request->buscar_profesores;
            $queryProfesores->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $profesores = $queryProfesores->orderBy('apellido', 'asc')
                                     ->orderBy('nombre', 'asc')
                                     ->paginate(15)
                                     ->withQueryString();

        // Cargar las materias asignadas a cada profesor con sus nombres
        $profesores->getCollection()->transform(function($profesor) {
            $materias = \DB::table('tbl_profesor_tiene_materias')
                ->join('tbl_materias', 'tbl_profesor_tiene_materias.materia', '=', 'tbl_materias.id')
                ->where('tbl_profesor_tiene_materias.profesor', $profesor->id)
                ->select('tbl_materias.id', 'tbl_materias.nombre')
                ->get()
                ->map(function($materia) {
                    return [
                        'id' => $materia->id,
                        'nombre' => $materia->nombre
                    ];
                })
                ->toArray();

            $profesor->materias = $materias;
            return $profesor;
        });

        // Query para ALUMNOS - traer todos desde tbl_alumnos
        $queryAlumnos = Alumno::with(['carrera', 'user']);

        // Filtros para alumnos
        if ($request->filled('buscar_alumnos')) {
            $search = $request->buscar_alumnos;
            $queryAlumnos->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('legajo', 'like', "%{$search}%");
            });
        }
        // Filtrar por carrera
        if ($request->filled('carrera_alumnos')) {
            $queryAlumnos->where('carrera', $request->carrera_alumnos);
        }

        $alumnos = $queryAlumnos->orderBy('apellido', 'asc')
                                ->orderBy('nombre', 'asc')
                                ->paginate(15)
                                ->withQueryString();

        // Obtener SOLO notas FINALES pendientes (las notas parciales no requieren aprobación)
        try {
            $notasPendientesQuery = NotaTemporal::with([
                'alumno',
                'profesorMateria.materiaRelacion',
                'profesorMateria.carreraRelacion',
                'registradoPor'
            ])
            ->where('estado', 'pendiente')
            ->where(function($query) {
                $query->where('tipo_evaluacion', 'like', '%final%')
                      ->orWhere('tipo_evaluacion', 'like', '%Final%')
                      ->orWhere('tipo_evaluacion', 'like', '%FINAL%')
                      ->orWhere('tipo_evaluacion', 'like', '%mesa%')
                      ->orWhere('tipo_evaluacion', 'like', '%Mesa%')
                      ->orWhere('tipo_evaluacion', 'like', '%examen%')
                      ->orWhere('tipo_evaluacion', 'like', '%Examen%');
            });

            // Ordenar por fecha si la columna existe, sino solo por created_at
            if (\Schema::hasColumn('tbl_notas_temporales', 'fecha')) {
                $notasPendientesQuery->orderBy('fecha', 'desc');
            }

            $notasPendientes = $notasPendientesQuery
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($nota) {
            return [
                'id' => $nota->id,
                'alumno' => $nota->alumno ? $nota->alumno->apellido . ', ' . $nota->alumno->nombre : 'N/A',
                'alumno_id' => $nota->alumno_id,
                'dni' => $nota->alumno ? $nota->alumno->dni : 'N/A',
                'materia' => $nota->profesorMateria && $nota->profesorMateria->materiaRelacion
                    ? $nota->profesorMateria->materiaRelacion->nombre
                    : 'N/A',
                'carrera' => $nota->profesorMateria && $nota->profesorMateria->carreraRelacion
                    ? $nota->profesorMateria->carreraRelacion->nombre
                    : 'N/A',
                'nota' => $nota->nota,
                'tipo_evaluacion' => $nota->tipo_evaluacion,
                'fecha' => $nota->fecha ? \Carbon\Carbon::parse($nota->fecha)->format('d/m/Y') : 'N/A',
                'registrado_por' => $nota->registradoPor ? $nota->registradoPor->nombre : 'N/A',
                'estado' => $nota->estado,
                'observaciones' => $nota->observaciones,
                'profesor_materia_id' => $nota->profesor_materia_id,
            ];
        });
        } catch (\Exception $e) {
            \Log::error('Error al obtener notas pendientes: ' . $e->getMessage());
            $notasPendientes = collect([]); // Colección vacía si falla
        }

        // Obtener duración de cada carrera basado en las materias existentes
        $duracionCarreras = [];
        foreach ($carreras as $carrera) {
            $maxAnno = Materia::where('carrera', $carrera->Id)->max('anno');
            $duracionCarreras[$carrera->Id] = $maxAnno ? (int)$maxAnno : 4; // Default 4 si no tiene materias
        }

        // Debug temporal
        \Log::info('AdminPanel Data', [
            'materias_count' => $materias->total(),
            'profesores_count' => $profesores->total(),
            'alumnos_count' => $alumnos->total(),
            'carreras_count' => $carreras->count(),
            'notas_pendientes_count' => $notasPendientes->count()
        ]);

        return Inertia::render('Expediente/AdminPanel', [
            'materias' => $materias,
            'carreras' => $carreras,
            'profesores' => $profesores,
            'alumnos' => $alumnos,
            'notasPendientes' => $notasPendientes,
            'duracionCarreras' => $duracionCarreras,
            'filtrosMaterias' => $request->only(['carrera_materias', 'anno', 'semestre', 'buscar_materias']),
            'filtrosProfesores' => $request->only(['activo', 'buscar_profesores']),
            'filtrosAlumnos' => $request->only(['activo', 'buscar_alumnos', 'carrera_alumnos']),
        ]);
    }

    private function listarAlumnos(Request $request)
    {
        $query = Alumno::with('carrera');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        if ($request->filled('carrera')) {
            $query->where('carrera', $request->carrera);
        }

        $alumnos = $query->orderBy('apellido', 'asc')
                        ->orderBy('nombre', 'asc')
                        ->paginate(15)
                        ->withQueryString();

        $carreras = Carrera::all();

        return Inertia::render('Expediente/Index', [
            'alumnos' => $alumnos,
            'carreras' => $carreras,
            'filters' => $request->only(['search', 'carrera'])
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();

        // Bloquear acceso a alumnos
        if ($user->tipo == 4) {
            \Log::warning('Alumno intentó acceder a expediente/show - acceso denegado', [
                'user_id' => $user->id,
                'alumno_id' => $id
            ]);
            abort(403, 'Los alumnos no tienen acceso al expediente. Consulta con Secretaría Académica.');
        }

        $alumno = Alumno::with([
            'carreraRelacion',
            'materiasCursadas.materiaRelacion'
        ])->findOrFail($id);

        // Obtener el ID de carrera del alumno
        $carreraId = $alumno->getAttributes()['carrera'] ?? null;

        // Obtener materias cursadas
        $materiasCursadas = $alumno->materiasCursadas()
            ->with('materiaRelacion')
            ->when($carreraId, function($query) use ($carreraId) {
                return $query->where('carrera', $carreraId);
            })
            ->get();

        // Agrupar por año
        $historialPorAnio = $materiasCursadas->groupBy(function($item) {
            return $item->materiaRelacion && $item->materiaRelacion->anno
                ? $item->materiaRelacion->anno
                : 0;
        })->map(function($materias) {
            return $materias->map(function($materiaCursada) {
                return [
                    'id' => $materiaCursada->Id,
                    'materia_nombre' => $materiaCursada->materiaRelacion ? $materiaCursada->materiaRelacion->nombre : 'Sin nombre',
                    'rendida' => $materiaCursada->nota ? true : false,
                    'cursada' => $materiaCursada->cursada === '1' ? true : false,
                    'libre' => $materiaCursada->libre ? true : false,
                    'equivalencia' => $materiaCursada->equivalencia ? true : false,
                    'calificacion_cursada' => $materiaCursada->cursada === '1' ? 'Regular' : null,
                    'calificacion_rendida' => $materiaCursada->nota ? $materiaCursada->nota : null,
                    'nota_final' => $materiaCursada->nota,
                    'fecha' => $materiaCursada->fecha ? \Carbon\Carbon::parse($materiaCursada->fecha)->format('d/m/Y') : null,
                ];
            })->values();
        });

        // Calcular estadísticas
        $totalMaterias = $materiasCursadas->count();
        $aprobadas = $materiasCursadas->where('nota', '>=', 4)->count();
        $regulares = $materiasCursadas->where('cursada', '1')->where('nota', null)->count();

        // Calcular promedio de materias aprobadas
        $notasAprobadas = $materiasCursadas->where('nota', '>=', 4)->pluck('nota')->filter();
        $promedio = $notasAprobadas->count() > 0 ? round($notasAprobadas->avg(), 2) : 0;

        // Calcular porcentaje de avance (basado en aprobadas)
        $porcentajeAvance = $totalMaterias > 0 ? round(($aprobadas / $totalMaterias) * 100, 1) : 0;

        $estadisticas = [
            'total_materias' => $totalMaterias,
            'aprobadas' => $aprobadas,
            'regulares' => $regulares,
            'promedio' => $promedio,
            'porcentaje_avance' => $porcentajeAvance
        ];

        return Inertia::render('Expediente/Show', [
            'alumno' => [
                'id' => $alumno->id,
                'dni' => $alumno->dni,
                'nombre_completo' => $alumno->apellido . ', ' . $alumno->nombre,
                'carrera' => $alumno->carreraRelacion ? $alumno->carreraRelacion->nombre : 'Sin carrera',
            ],
            'historialPorAnio' => $historialPorAnio,
            'estadisticas' => $estadisticas
        ]);
    }

    public function buscarPorDni(Request $request)
    {
        $request->validate([
            'dni' => ['required', 'string', 'max:10', 'regex:/^[0-9]+$/']
        ], [
            'dni.regex' => 'El DNI debe contener solo números.',
        ]);

        // Usar carreraRelacion para evitar conflicto con el atributo 'carrera'
        $alumno = Alumno::with(['carreraRelacion'])->where('dni', $request->dni)->first();

        if (!$alumno) {
            return response()->json([
                'error' => 'No se encontró ningún alumno con ese DNI'
            ], 404);
        }

        // Obtener el ID de carrera del alumno
        $carreraId = $alumno->getAttributes()['carrera'] ?? null;

        // Obtener el historial académico agrupado por año
        $historialAcademico = $alumno->materiasCursadas()
            ->with('materiaRelacion')
            ->when($carreraId, function($query) use ($carreraId) {
                return $query->where('carrera', $carreraId);
            })
            ->get()
            ->groupBy(function($item) {
                // Verificar que materiaRelacion sea un objeto y tenga la propiedad anno
                if ($item->materiaRelacion && is_object($item->materiaRelacion) && isset($item->materiaRelacion->anno)) {
                    return $item->materiaRelacion->anno . '° Año';
                }
                return 'Sin año';
            })
            ->map(function($materias) {
                return $materias->map(function($materiaCursada) {
                    // Determinar si promocionó (cursada y rendida al mismo tiempo)
                    $esPromocional = ($materiaCursada->cursada === '1' && $materiaCursada->rendida === '1');

                    return [
                        'id' => $materiaCursada->Id,  // ID para editar
                        'materia_id' => $materiaCursada->materia,  // ID de la materia
                        'materia' => $materiaCursada->materiaRelacion ? $materiaCursada->materiaRelacion->nombre : 'Sin nombre',
                        'regular' => ($materiaCursada->cursada === '1' && !$esPromocional) ? '✓' : '',
                        'promocional' => $esPromocional ? '✓' : '',
                        'equivalencia' => $materiaCursada->equivalencia ? '✓' : '',
                        'libre' => $materiaCursada->libre ? '✓' : '',
                        // Valores booleanos para los checkboxes
                        'cursada_value' => $materiaCursada->cursada === '1',
                        'rendida_value' => $materiaCursada->rendida === '1',
                        'libre_value' => $materiaCursada->libre == 1,
                        'equivalencia_value' => $materiaCursada->equivalencia == 1,
                        'nota' => $materiaCursada->nota ?? '',
                        'fecha' => $materiaCursada->fecha ? \Carbon\Carbon::parse($materiaCursada->fecha)->format('Y-m-d') : '',
                        'libro' => $materiaCursada->libro ?? '',
                        'folio' => $materiaCursada->folio ?? ''
                    ];
                });
            });

        return response()->json([
            'alumno' => [
                'id' => $alumno->id,
                'dni' => $alumno->dni,
                'apellido' => $alumno->apellido,
                'nombre' => $alumno->nombre,
                'anno' => $alumno->anno ?? 'N/A',
                'curso' => $alumno->curso ?? 0,
                'carrera' => $alumno->carreraRelacion ? $alumno->carreraRelacion->nombre : 'Sin carrera',
                'id_carrera' => $carreraId
            ],
            'historial' => $historialAcademico
        ]);
    }

    public function obtenerAlumnosProfesor(Request $request)
    {
        $user = $request->user();

        if (!$user->profesor_id) {
            return response()->json([
                'error' => 'No se encontró un perfil de profesor asociado'
            ], 404);
        }

        // Obtener materias del profesor
        $materias = ProfesorMateria::with(['materiaRelacion', 'carreraRelacion'])
            ->where('profesor', $user->profesor_id)
            ->get();

        // Agrupar alumnos por materia
        $alumnosPorMateria = [];

        foreach ($materias as $materia) {
            $inscripciones = Inscripcion::with(['alumno'])
                ->where('materia_id', $materia->materia)
                ->where('carrera_id', $materia->carrera)
                ->where('estado', 'confirmada')
                ->get();

            $alumnos = $inscripciones->map(function($inscripcion) {
                return [
                    'id' => $inscripcion->alumno->id,
                    'dni' => $inscripcion->alumno->dni,
                    'apellido' => $inscripcion->alumno->apellido,
                    'nombre' => $inscripcion->alumno->nombre,
                    'email' => $inscripcion->alumno->email ?? 'N/A',
                ];
            })->sortBy('apellido')->values();

            $alumnosPorMateria[] = [
                'materia_id' => $materia->id,
                'materia' => $materia->materiaRelacion->nombre ?? 'Sin nombre',
                'carrera' => $materia->carreraRelacion->nombre ?? 'Sin carrera',
                'cursado' => $materia->materiaRelacion->semestre ?? $materia->cursado ?? 'N/A',
                'division' => $materia->division ?? 'N/A',
                'alumnos' => $alumnos,
                'total_alumnos' => $alumnos->count(),
                // Configuración académica
                'configuracion' => [
                    'nota_minima_promocion' => $materia->nota_minima_promocion ?? 7.00,
                    'nota_minima_regularidad' => $materia->nota_minima_regularidad ?? 4.00,
                    'permite_promocion' => $materia->permite_promocion ?? true,
                    'porcentaje_asistencia_minimo' => $materia->porcentaje_asistencia_minimo ?? 75,
                    'criterios_evaluacion' => $materia->criterios_evaluacion,
                    'configuracion_completa' => $materia->configuracion_completa ?? false
                ]
            ];
        }

        return response()->json([
            'materias' => $alumnosPorMateria
        ]);
    }

    public function guardarAsistencia(Request $request)
    {
        $request->validate([
            'profesor_materia_id' => 'required|exists:tbl_profesor_tiene_materias,id',
            'fecha' => 'required|date',
            'asistencias' => 'required|array',
            'asistencias.*.alumno_id' => 'required|exists:tbl_alumnos,id',
            'asistencias.*.estado' => 'required|in:presente,ausente,tarde',
            'asistencias.*.observaciones' => 'nullable|string|max:500'
        ]);

        $user = $request->user();
        $registros = [];

        foreach ($request->asistencias as $asistencia) {
            // Verificar si ya existe asistencia para este alumno en esta fecha
            $existe = Asistencia::where('profesor_materia_id', $request->profesor_materia_id)
                ->where('alumno_id', $asistencia['alumno_id'])
                ->where('fecha', $request->fecha)
                ->first();

            if ($existe) {
                // Actualizar registro existente
                $existe->update([
                    'estado' => $asistencia['estado'],
                    'observaciones' => $asistencia['observaciones'] ?? null,
                ]);
                $registros[] = $existe;
            } else {
                // Crear nuevo registro
                $registros[] = Asistencia::create([
                    'profesor_materia_id' => $request->profesor_materia_id,
                    'alumno_id' => $asistencia['alumno_id'],
                    'fecha' => $request->fecha,
                    'estado' => $asistencia['estado'],
                    'observaciones' => $asistencia['observaciones'] ?? null,
                    'registrado_por' => $user->id
                ]);
            }
        }

        return response()->json([
            'message' => 'Asistencia guardada correctamente',
            'registros' => count($registros)
        ], 200);
    }

    public function guardarNotas(Request $request)
    {
        $request->validate([
            'profesor_materia_id' => 'required|exists:tbl_profesor_tiene_materias,id',
            'tipo_evaluacion' => 'required|string|max:100',
            'fecha' => 'required|date',
            'notas' => 'required|array',
            'notas.*.alumno_id' => 'required|exists:tbl_alumnos,id',
            'notas.*.nota' => 'required|numeric|min:1|max:10',
            'notas.*.observaciones' => 'nullable|string|max:500'
        ]);

        $user = $request->user();
        $registros = [];

        // Determinar si es nota final (requiere aprobación) o nota parcial (no requiere aprobación)
        $tipoEvaluacion = strtolower($request->tipo_evaluacion);
        $esNotaFinal = (
            str_contains($tipoEvaluacion, 'final') ||
            str_contains($tipoEvaluacion, 'mesa') ||
            str_contains($tipoEvaluacion, 'examen final')
        );

        try {
            if (!$esNotaFinal) {
                // Notas de parciales/prácticos: se guardan como 'aprobada' SIN transferir al legajo
                // Solo quedan registradas como notas temporales para consulta
                foreach ($request->notas as $nota) {
                    $registros[] = NotaTemporal::create([
                        'profesor_materia_id' => $request->profesor_materia_id,
                        'alumno_id' => $nota['alumno_id'],
                        'nota' => $nota['nota'],
                        'tipo_evaluacion' => $request->tipo_evaluacion,
                        'estado' => 'aprobada', // Auto-aprobada, no requiere revisión
                        'fecha' => $request->fecha,
                        'observaciones' => $nota['observaciones'] ?? null,
                        'registrado_por' => $user->id,
                        'aprobado_por' => $user->id,
                        'fecha_aprobacion' => now()
                    ]);
                }

                \Log::info('Notas parciales guardadas (no requieren aprobación)', [
                    'cantidad' => count($registros),
                    'tipo_evaluacion' => $request->tipo_evaluacion,
                    'registrado_por' => $user->id
                ]);

                return response()->json([
                    'message' => 'Notas guardadas correctamente.',
                    'registros' => count($registros),
                    'tipo' => 'notas_parciales'
                ], 200);

            } else {
                // Notas finales: requieren aprobación de admin/bedel para transferir al legajo
                foreach ($request->notas as $nota) {
                    $registros[] = NotaTemporal::create([
                        'profesor_materia_id' => $request->profesor_materia_id,
                        'alumno_id' => $nota['alumno_id'],
                        'nota' => $nota['nota'],
                        'tipo_evaluacion' => $request->tipo_evaluacion,
                        'estado' => 'pendiente',
                        'fecha' => $request->fecha,
                        'observaciones' => $nota['observaciones'] ?? null,
                        'registrado_por' => $user->id
                    ]);
                }

                \Log::info('Notas finales guardadas (requieren aprobación)', [
                    'cantidad' => count($registros),
                    'tipo_evaluacion' => $request->tipo_evaluacion,
                    'registrado_por' => $user->id
                ]);

                return response()->json([
                    'message' => 'Notas finales guardadas correctamente. Pendientes de aprobación por Secretaría.',
                    'registros' => count($registros),
                    'tipo' => 'pendiente_aprobacion'
                ], 200);
            }

        } catch (\Exception $e) {
            $this->handleError($e, 'guardar notas', [
                'user_id' => $user->id,
                'tipo_evaluacion' => $request->tipo_evaluacion
            ]);

            return response()->json([
                'error' => $this->getFriendlyErrorMessage($e, 'Error al guardar las notas')
            ], 500);
        }
    }

    public function guardarAsistenciaFinal(Request $request)
    {
        $request->validate([
            'profesor_materia_id' => 'required|exists:tbl_profesor_tiene_materias,id',
            'total_clases' => 'required|integer|min:1',
            'asistencias' => 'required|array',
            'asistencias.*.alumno_id' => 'required|exists:tbl_alumnos,id',
            'asistencias.*.presentes' => 'required|integer|min:0',
            'asistencias.*.ausentes' => 'required|integer|min:0',
            'asistencias.*.tardes' => 'required|integer|min:0',
            'asistencias.*.observaciones' => 'nullable|string|max:500'
        ]);

        $user = $request->user();
        $registros = [];
        $fecha = now()->format('Y-m-d'); // Usar fecha actual para el registro

        foreach ($request->asistencias as $asistencia) {
            // Verificar si ya existe asistencia final para este alumno
            $existe = Asistencia::where('profesor_materia_id', $request->profesor_materia_id)
                ->where('alumno_id', $asistencia['alumno_id'])
                ->where('tipo_carga', 'final')
                ->first();

            if ($existe) {
                // Actualizar registro existente
                $existe->update([
                    'presentes' => $asistencia['presentes'],
                    'ausentes' => $asistencia['ausentes'],
                    'tardes' => $asistencia['tardes'],
                    'total_clases' => $request->total_clases,
                    'observaciones' => $asistencia['observaciones'] ?? null,
                ]);
                $registros[] = $existe;
            } else {
                // Crear nuevo registro de asistencia final
                $registros[] = Asistencia::create([
                    'profesor_materia_id' => $request->profesor_materia_id,
                    'alumno_id' => $asistencia['alumno_id'],
                    'fecha' => $fecha,
                    'estado' => 'presente', // No se usa para tipo final
                    'tipo_carga' => 'final',
                    'presentes' => $asistencia['presentes'],
                    'ausentes' => $asistencia['ausentes'],
                    'tardes' => $asistencia['tardes'],
                    'total_clases' => $request->total_clases,
                    'observaciones' => $asistencia['observaciones'] ?? null,
                    'registrado_por' => $user->id
                ]);
            }
        }

        return response()->json([
            'message' => 'Asistencia final guardada correctamente',
            'registros' => count($registros)
        ], 200);
    }

    public function guardarNotasFinales(Request $request)
    {
        $request->validate([
            'profesor_materia_id' => 'required|exists:tbl_profesor_tiene_materias,id',
            'fecha' => 'required|date',
            'notas' => 'required|array',
            'notas.*.alumno_id' => 'required|exists:tbl_alumnos,id',
            'notas.*.nota' => 'required|numeric|min:1|max:10',
            'notas.*.observaciones' => 'nullable|string|max:500'
        ]);

        $user = $request->user();
        $registros = [];

        foreach ($request->notas as $nota) {
            // Verificar si ya existe nota final para este alumno
            $existe = NotaTemporal::where('profesor_materia_id', $request->profesor_materia_id)
                ->where('alumno_id', $nota['alumno_id'])
                ->where('tipo_evaluacion', 'Final')
                ->first();

            if ($existe) {
                // Actualizar registro existente
                $existe->update([
                    'nota' => $nota['nota'],
                    'fecha' => $request->fecha,
                    'observaciones' => $nota['observaciones'] ?? null,
                    'estado' => 'pendiente', // Volver a pendiente si se modifica
                ]);
                $registros[] = $existe;
            } else {
                // Crear nuevo registro
                $registros[] = NotaTemporal::create([
                    'profesor_materia_id' => $request->profesor_materia_id,
                    'alumno_id' => $nota['alumno_id'],
                    'nota' => $nota['nota'],
                    'tipo_evaluacion' => 'Final',
                    'estado' => 'pendiente',
                    'fecha' => $request->fecha,
                    'observaciones' => $nota['observaciones'] ?? null,
                    'registrado_por' => $user->id
                ]);
            }
        }

        return response()->json([
            'message' => 'Notas finales guardadas correctamente. Pendientes de aprobación.',
            'registros' => count($registros)
        ], 200);
    }

    public function aprobarNota(Request $request, $id)
    {
        $user = $request->user();

        // Verificar que el usuario sea admin o bedel
        if (!in_array($user->tipo, [1, 2])) {
            return response()->json([
                'error' => 'No tiene permisos para aprobar notas'
            ], 403);
        }

        $nota = NotaTemporal::with(['alumno', 'profesorMateria.materiaRelacion', 'profesorMateria.profesorRelacion.user'])->findOrFail($id);

        // Verificar que la nota esté pendiente
        if ($nota->estado !== 'pendiente') {
            return response()->json([
                'error' => 'La nota no está en estado pendiente'
            ], 400);
        }

        // Verificar que sea una nota FINAL (solo las finales deben aprobarse y transferirse al legajo)
        $tipoEvaluacion = strtolower($nota->tipo_evaluacion);
        $esNotaFinal = (
            str_contains($tipoEvaluacion, 'final') ||
            str_contains($tipoEvaluacion, 'mesa') ||
            str_contains($tipoEvaluacion, 'examen')
        );

        if (!$esNotaFinal) {
            return response()->json([
                'error' => 'Solo las notas finales requieren aprobación. Las notas parciales/prácticas se guardan automáticamente.'
            ], 400);
        }

        try {
            \DB::beginTransaction();

            // 1. Actualizar estado de la nota temporal con trazabilidad
            $nota->update([
                'estado' => 'aprobada',
                'aprobado_por' => $user->id,
                'fecha_aprobacion' => now()
            ]);

            // 2. Transferir nota al legajo oficial (tbl_alumnos_materias)
            $profesorMateria = $nota->profesorMateria;

            if (!$profesorMateria) {
                throw new \Exception('No se encontró la relación profesor-materia');
            }

            // Verificar si ya existe un registro en el legajo para este alumno y materia
            $alumnoMateria = AlumnoMateria::where('alumno', $nota->alumno_id)
                ->where('materia', $profesorMateria->materia)
                ->where('carrera', $profesorMateria->carrera)
                ->first();

            // Determinar si es nota de cursada o final
            $esFinal = strtolower($nota->tipo_evaluacion) === 'final';

            // Obtener notas mínimas configuradas
            $notaMinimaPromocion = $profesorMateria->nota_minima_promocion ?? 4.00;
            $notaMinimaRegularidad = $profesorMateria->nota_minima_regularidad ?? 4.00;

            if ($alumnoMateria) {
                // Actualizar registro existente
                if ($esFinal) {
                    // Nota final: verificar si aprueba antes de marcar rendida
                    $aprobado = $nota->nota >= $notaMinimaPromocion;
                    $alumnoMateria->update([
                        'nota' => $nota->nota,
                        'calificacion_rendida' => $nota->nota,
                        'rendida' => $aprobado ? '1' : '0',
                        'fecha' => $nota->fecha,
                        // NO tocar cursada ni libre - se mantiene el estado anterior
                    ]);
                } else {
                    // Nota de cursada: verificar si regulariza antes de marcar cursada
                    $regularizado = $nota->nota >= $notaMinimaRegularidad;
                    $alumnoMateria->update([
                        'calificacion-cursada' => $nota->nota,
                        'cursada' => $regularizado ? '1' : '0',
                        // NO marcar rendida - solo es regular
                    ]);
                }
            } else {
                // Crear nuevo registro en el legajo
                if ($esFinal) {
                    // Si es final y no existe registro previo, asumimos que rindió LIBRE
                    $aprobado = $nota->nota >= $notaMinimaPromocion;
                    AlumnoMateria::create([
                        'alumno' => $nota->alumno_id,
                        'carrera' => $profesorMateria->carrera,
                        'materia' => $profesorMateria->materia,
                        'nota' => $nota->nota,
                        'cursada' => null, // No cursó
                        'rendida' => $aprobado ? '1' : '0',  // Solo aprobado si cumple mínimo
                        'calificacion-cursada' => null,
                        'calificacion_rendida' => $nota->nota,
                        'fecha' => $nota->fecha,
                        'equivalencia' => 0,
                        'libre' => 1, // Por defecto LIBRE si no cursó antes
                    ]);
                } else {
                    // Nota de cursada: crear como regular si cumple mínimo
                    $regularizado = $nota->nota >= $notaMinimaRegularidad;
                    AlumnoMateria::create([
                        'alumno' => $nota->alumno_id,
                        'carrera' => $profesorMateria->carrera,
                        'materia' => $profesorMateria->materia,
                        'nota' => null, // No rindió final aún
                        'cursada' => $regularizado ? '1' : '0', // Solo si cumple mínimo
                        'rendida' => null,
                        'calificacion-cursada' => $nota->nota,
                        'calificacion_rendida' => null,
                        'fecha' => $nota->fecha,
                        'equivalencia' => 0,
                        'libre' => $regularizado ? 0 : 1, // Libre si no cumple mínimo
                    ]);
                }
            }

            // 3. Log de auditoría
            \Log::info('Nota aprobada y transferida', [
                'nota_temporal_id' => $nota->id,
                'alumno_id' => $nota->alumno_id,
                'materia_id' => $profesorMateria->materia,
                'nota' => $nota->nota,
                'tipo_evaluacion' => $nota->tipo_evaluacion,
                'aprobado_por' => $user->id,
                'aprobado_por_nombre' => $user->nombre,
                'fecha_aprobacion' => now()
            ]);

            \DB::commit();

            // Enviar correo al profesor para notificar la aprobación
            try {
                $profesor = $nota->profesorMateria->profesorRelacion ?? null;
                $profesorEmail = $profesor?->user?->email ?? null;

                if ($profesorEmail) {
                    \Mail::to($profesorEmail)->queue(new \App\Mail\NotaAprobada($nota));
                    \Log::info('Correo de nota aprobada encolado', [
                        'nota_id' => $nota->id,
                        'profesor_email' => $profesorEmail
                    ]);
                } else {
                    \Log::warning('No se pudo enviar correo: profesor sin email', [
                        'nota_id' => $nota->id,
                        'profesor_id' => $nota->profesorMateria->profesor ?? null
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Error al encolar correo de nota aprobada', [
                    'nota_id' => $nota->id,
                    'error' => $e->getMessage()
                ]);
                // No fallar la aprobación por error en email
            }

            // Crear notificación para el profesor
            try {
                $profesor = $nota->profesorMateria->profesorRelacion ?? null;
                $profesorUser = $profesor?->user ?? null;

                if ($profesorUser) {
                    $alumnoNombre = $nota->alumno->apellido . ', ' . $nota->alumno->nombre;
                    $materiaNombre = $nota->profesorMateria->materiaRelacion->nombre ?? 'N/A';

                    Notificacion::crear(
                        $profesorUser->id,
                        'nota_aprobada',
                        'Nota Aprobada',
                        "La nota de {$alumnoNombre} en {$materiaNombre} ({$nota->tipo_evaluacion}: {$nota->nota}) ha sido aprobada por {$user->nombre}",
                        [
                            'icono' => 'bx-check-circle',
                            'color' => 'green',
                            'url' => route('expediente.index'),
                            'datos' => [
                                'nota_id' => $nota->id,
                                'alumno_id' => $nota->alumno_id,
                                'nota_valor' => $nota->nota,
                                'tipo_evaluacion' => $nota->tipo_evaluacion,
                            ],
                        ]
                    );
                }
            } catch (\Exception $e) {
                \Log::error('Error al crear notificación de nota aprobada: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Nota aprobada y transferida al legajo exitosamente. Se notificó al profesor.',
                'nota' => $nota
            ], 200);

        } catch (\Exception $e) {
            \DB::rollBack();

            $this->handleError($e, 'aprobar nota', [
                'nota_id' => $id,
                'aprobado_por' => $user->id
            ]);

            return response()->json([
                'error' => $this->getFriendlyErrorMessage($e, 'Error al aprobar la nota')
            ], 500);
        }
    }

    public function rechazarNota(Request $request, $id)
    {
        $user = $request->user();

        // Verificar que el usuario sea admin o bedel
        if (!in_array($user->tipo, [1, 2])) {
            return response()->json([
                'error' => 'No tiene permisos para rechazar notas'
            ], 403);
        }

        $request->validate([
            'observaciones' => 'required|string|max:500'
        ]);

        $nota = NotaTemporal::with(['alumno', 'profesorMateria.materiaRelacion', 'profesorMateria.profesorRelacion.user'])->findOrFail($id);

        // Verificar que la nota esté pendiente
        if ($nota->estado !== 'pendiente') {
            return response()->json([
                'error' => 'La nota no está en estado pendiente'
            ], 400);
        }

        // Verificar que sea una nota FINAL (solo las finales requieren aprobación)
        $tipoEvaluacion = strtolower($nota->tipo_evaluacion);
        $esNotaFinal = (
            str_contains($tipoEvaluacion, 'final') ||
            str_contains($tipoEvaluacion, 'mesa') ||
            str_contains($tipoEvaluacion, 'examen')
        );

        if (!$esNotaFinal) {
            return response()->json([
                'error' => 'Solo las notas finales requieren aprobación/rechazo. Las notas parciales/prácticas se guardan automáticamente.'
            ], 400);
        }

        try {
            // Actualizar estado con motivo de rechazo y trazabilidad
            $nota->update([
                'estado' => 'rechazada',
                'aprobado_por' => $user->id,
                'fecha_aprobacion' => now(),
                'observaciones' => $request->observaciones
            ]);

            // Log de auditoría
            \Log::info('Nota rechazada', [
                'nota_temporal_id' => $nota->id,
                'alumno_id' => $nota->alumno_id,
                'nota' => $nota->nota,
                'tipo_evaluacion' => $nota->tipo_evaluacion,
                'rechazado_por' => $user->id,
                'rechazado_por_nombre' => $user->nombre,
                'motivo' => $request->observaciones,
                'fecha_rechazo' => now()
            ]);

            // Enviar correo al profesor para que corrija la nota
            try {
                $profesor = $nota->profesorMateria->profesorRelacion ?? null;
                $profesorEmail = $profesor?->user?->email ?? null;

                if ($profesorEmail) {
                    \Mail::to($profesorEmail)->queue(new \App\Mail\NotaRechazada($nota));
                    \Log::info('Correo de nota rechazada encolado', [
                        'nota_id' => $nota->id,
                        'profesor_email' => $profesorEmail
                    ]);
                } else {
                    \Log::warning('No se pudo enviar correo: profesor sin email', [
                        'nota_id' => $nota->id,
                        'profesor_id' => $nota->profesorMateria->profesor ?? null
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Error al encolar correo de nota rechazada', [
                    'nota_id' => $nota->id,
                    'error' => $e->getMessage()
                ]);
                // No fallar el rechazo por error en email
            }

            // Crear notificación para el profesor
            try {
                $profesor = $nota->profesorMateria->profesorRelacion ?? null;
                $profesorUser = $profesor?->user ?? null;

                if ($profesorUser) {
                    $alumnoNombre = $nota->alumno->apellido . ', ' . $nota->alumno->nombre;
                    $materiaNombre = $nota->profesorMateria->materiaRelacion->nombre ?? 'N/A';

                    Notificacion::crear(
                        $profesorUser->id,
                        'nota_rechazada',
                        'Nota Rechazada',
                        "La nota de {$alumnoNombre} en {$materiaNombre} ({$nota->tipo_evaluacion}: {$nota->nota}) ha sido rechazada por {$user->nombre}. Motivo: {$request->observaciones}",
                        [
                            'icono' => 'bx-x-circle',
                            'color' => 'red',
                            'url' => route('expediente.index'),
                            'datos' => [
                                'nota_id' => $nota->id,
                                'alumno_id' => $nota->alumno_id,
                                'nota_valor' => $nota->nota,
                                'tipo_evaluacion' => $nota->tipo_evaluacion,
                                'motivo_rechazo' => $request->observaciones,
                            ],
                        ]
                    );
                }
            } catch (\Exception $e) {
                \Log::error('Error al crear notificación de nota rechazada: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Nota rechazada exitosamente. Se notificó al profesor.',
                'nota' => $nota
            ], 200);

        } catch (\Exception $e) {
            $this->handleError($e, 'rechazar nota', [
                'nota_id' => $id,
                'rechazado_por' => $user->id
            ]);

            return response()->json([
                'error' => $this->getFriendlyErrorMessage($e, 'Error al rechazar la nota')
            ], 500);
        }
    }

    public function configurarParametrosAcademicos(Request $request, $profesorMateriaId)
    {
        $request->validate([
            'nota_minima_promocion' => 'required|numeric|min:1|max:10',
            'nota_minima_regularidad' => 'required|numeric|min:1|max:10',
            'permite_promocion' => 'required|boolean',
            'porcentaje_asistencia_minimo' => 'required|integer|min:0|max:100',
            'criterios_evaluacion' => 'nullable|string|max:1000'
        ]);

        $user = $request->user();
        $profesorMateria = ProfesorMateria::findOrFail($profesorMateriaId);

        // Verificar que el usuario sea el profesor de la materia o admin
        if ($user->tipo != 1 && $user->profesor_id != $profesorMateria->profesor) {
            return response()->json([
                'error' => 'No tiene permisos para configurar esta materia'
            ], 403);
        }

        try {
            $profesorMateria->update([
                'nota_minima_promocion' => $request->nota_minima_promocion,
                'nota_minima_regularidad' => $request->nota_minima_regularidad,
                'permite_promocion' => $request->permite_promocion,
                'porcentaje_asistencia_minimo' => $request->porcentaje_asistencia_minimo,
                'criterios_evaluacion' => $request->criterios_evaluacion,
                'configuracion_completa' => true
            ]);

            \Log::info('Configuración académica actualizada', [
                'profesor_materia_id' => $profesorMateriaId,
                'configurado_por' => $user->id,
                'nota_minima_promocion' => $request->nota_minima_promocion,
                'nota_minima_regularidad' => $request->nota_minima_regularidad
            ]);

            return response()->json([
                'message' => 'Configuración académica actualizada exitosamente',
                'configuracion' => $profesorMateria
            ], 200);

        } catch (\Exception $e) {
            $this->handleError($e, 'actualizar configuración académica', [
                'profesor_materia_id' => $profesorMateriaId,
                'configurado_por' => $user->id
            ]);

            return response()->json([
                'error' => $this->getFriendlyErrorMessage($e, 'Error al actualizar la configuración')
            ], 500);
        }
    }

    public function actualizarEstadoMateria(Request $request, $alumnoMateriaId)
    {
        $user = $request->user();

        // Verificar que el usuario sea admin o bedel
        if (!in_array($user->tipo, [1, 2])) {
            return response()->json([
                'error' => 'No tiene permisos para editar legajos'
            ], 403);
        }

        $request->validate([
            'cursada' => 'nullable|in:0,1',
            'rendida' => 'nullable|in:0,1',
            'libre' => 'nullable|in:0,1',
            'equivalencia' => 'nullable|in:0,1',
            'nota' => 'nullable|numeric|min:1|max:10',
            'fecha' => 'nullable|date',
            'libro' => 'nullable|string|max:255',
            'folio' => 'nullable|string|max:255',
        ]);

        try {
            $alumnoMateria = AlumnoMateria::findOrFail($alumnoMateriaId);

            // Validar que solo se marque un estado a la vez
            $cursada = $request->cursada ?? $alumnoMateria->cursada;
            $rendida = $request->rendida ?? $alumnoMateria->rendida;
            $libre = $request->libre ?? $alumnoMateria->libre;
            $equivalencia = $request->equivalencia ?? $alumnoMateria->equivalencia;

            // Contar estados marcados (excluyendo la combinación válida de Regular y Promocional)
            $estadosMarcados = 0;

            // Regular (cursada sin rendida)
            if ($cursada == '1' && $rendida == '0') {
                $estadosMarcados++;
            }
            // Promocional (cursada con rendida)
            if ($cursada == '1' && $rendida == '1') {
                $estadosMarcados++;
            }
            // Libre
            if ($libre == 1) {
                $estadosMarcados++;
            }
            // Equivalencia
            if ($equivalencia == 1) {
                $estadosMarcados++;
            }

            // Validar que solo haya un estado marcado
            if ($estadosMarcados > 1) {
                return response()->json([
                    'error' => 'Solo se puede marcar un estado por materia (Regular, Promocional, Libre o Equivalencia)'
                ], 400);
            }

            // Actualizar los campos
            $alumnoMateria->update([
                'cursada' => $cursada,
                'rendida' => $rendida,
                'libre' => $libre,
                'equivalencia' => $equivalencia,
                'nota' => $request->nota ?? $alumnoMateria->nota,
                'fecha' => $request->fecha ?? $alumnoMateria->fecha,
                'libro' => $request->libro ?? $alumnoMateria->libro,
                'folio' => $request->folio ?? $alumnoMateria->folio,
            ]);

            \Log::info('Estado de materia actualizado manualmente en legajo', [
                'alumno_materia_id' => $alumnoMateriaId,
                'alumno_id' => $alumnoMateria->alumno,
                'materia_id' => $alumnoMateria->materia,
                'modificado_por' => $user->id,
                'modificado_por_nombre' => $user->nombre,
                'cursada' => $alumnoMateria->cursada,
                'rendida' => $alumnoMateria->rendida,
                'libre' => $alumnoMateria->libre,
                'equivalencia' => $alumnoMateria->equivalencia,
            ]);

            return response()->json([
                'message' => 'Estado de materia actualizado correctamente',
                'alumnoMateria' => $alumnoMateria
            ], 200);

        } catch (\Exception $e) {
            $this->handleError($e, 'actualizar estado de materia', [
                'alumno_materia_id' => $alumnoMateriaId,
                'modificado_por' => $user->id
            ]);

            return response()->json([
                'error' => $this->getFriendlyErrorMessage($e, 'Error al actualizar el estado de la materia')
            ], 500);
        }
    }

    public function materiasDisponibles(Request $request)
    {
        $user = $request->user();

        // Verificar que el usuario sea admin o bedel
        if (!in_array($user->tipo, [1, 2])) {
            return response()->json([
                'error' => 'No tiene permisos para acceder a esta funcionalidad'
            ], 403);
        }

        $request->validate([
            'carrera_id' => 'required|exists:tbl_carreras,Id'
        ]);

        try {
            // Obtener todas las materias de la carrera con sus planes
            $materias = Materia::where('carrera', $request->carrera_id)
                ->orderBy('anno', 'asc')
                ->orderBy('semestre', 'asc')
                ->orderBy('nombre', 'asc')
                ->get()
                ->map(function($materia) {
                    // Obtener los planes a los que pertenece esta materia
                    $planes = \App\Models\PlanEstudio::whereHas('materias', function($query) use ($materia) {
                        $query->where('materia_id', $materia->id);
                    })
                    ->where('carrera_id', $materia->carrera)
                    ->where('activo', true) // Solo planes activos
                    ->orderBy('anio', 'desc')
                    ->get(['id', 'nombre', 'anio', 'vigente'])
                    ->map(function($plan) {
                        return [
                            'id' => $plan->id,
                            'nombre' => $plan->nombre,
                            'anio' => $plan->anio,
                            'vigente' => $plan->vigente
                        ];
                    });

                    return [
                        'id' => $materia->id,
                        'nombre' => $materia->nombre,
                        'anno' => $materia->anno,
                        'semestre' => $materia->semestre,
                        'planes' => $planes
                    ];
                });

            return response()->json($materias, 200);

        } catch (\Exception $e) {
            $this->handleError($e, 'obtener materias disponibles', [
                'carrera_id' => $request->carrera_id
            ]);

            return response()->json([
                'error' => $this->getFriendlyErrorMessage($e, 'Error al obtener las materias disponibles')
            ], 500);
        }
    }

    public function agregarMateria(Request $request)
    {
        $user = $request->user();

        // Verificar que el usuario sea admin o bedel
        if (!in_array($user->tipo, [1, 2])) {
            return response()->json([
                'error' => 'No tiene permisos para agregar materias al legajo'
            ], 403);
        }

        $request->validate([
            'alumno_id' => 'required|exists:tbl_alumnos,id',
            'carrera_id' => 'required|exists:tbl_carreras,Id',
            'materia_id' => 'required|exists:tbl_materias,id',
            'nota' => 'nullable|numeric|min:1|max:10',
            'fecha' => 'nullable|date',
            'libro' => 'nullable|string|max:255',
            'folio' => 'nullable|string|max:255',
            'cursada' => 'nullable|boolean',
            'rendida' => 'nullable|boolean',
            'libre' => 'nullable|boolean',
            'equivalencia' => 'nullable|boolean',
        ]);

        try {
            // Verificar si ya existe esta materia en el legajo del alumno
            $existe = AlumnoMateria::where('alumno', $request->alumno_id)
                ->where('materia', $request->materia_id)
                ->where('carrera', $request->carrera_id)
                ->first();

            if ($existe) {
                return response()->json([
                    'error' => 'Esta materia ya existe en el legajo del alumno'
                ], 400);
            }

            // Validar que solo se marque un estado a la vez
            $cursada = $request->cursada ? '1' : '0';
            $rendida = $request->rendida ? '1' : '0';
            $libre = $request->libre ? 1 : 0;
            $equivalencia = $request->equivalencia ? 1 : 0;

            // Contar estados marcados
            $estadosMarcados = 0;

            // Regular (cursada sin rendida)
            if ($cursada == '1' && $rendida == '0') {
                $estadosMarcados++;
            }
            // Promocional (cursada con rendida)
            if ($cursada == '1' && $rendida == '1') {
                $estadosMarcados++;
            }
            // Libre
            if ($libre == 1) {
                $estadosMarcados++;
            }
            // Equivalencia
            if ($equivalencia == 1) {
                $estadosMarcados++;
            }

            // Validar que solo haya un estado marcado
            if ($estadosMarcados > 1) {
                return response()->json([
                    'error' => 'Solo se puede marcar un estado por materia (Regular, Promocional, Libre o Equivalencia)'
                ], 400);
            }

            // Crear el registro en el legajo
            $alumnoMateria = AlumnoMateria::create([
                'alumno' => $request->alumno_id,
                'carrera' => $request->carrera_id,
                'materia' => $request->materia_id,
                'nota' => $request->nota ?? null,
                'fecha' => $request->fecha ?? null,
                'libro' => $request->libro ?? null,
                'folio' => $request->folio ?? null,
                'cursada' => $cursada,
                'rendida' => $rendida,
                'libre' => $libre,
                'equivalencia' => $equivalencia,
            ]);

            \Log::info('Materia agregada manualmente al legajo', [
                'alumno_materia_id' => $alumnoMateria->Id,
                'alumno_id' => $request->alumno_id,
                'materia_id' => $request->materia_id,
                'agregado_por' => $user->id,
                'agregado_por_nombre' => $user->nombre,
            ]);

            return response()->json([
                'message' => 'Materia agregada al legajo correctamente',
                'alumnoMateria' => $alumnoMateria
            ], 201);

        } catch (\Exception $e) {
            $this->handleError($e, 'agregar materia al legajo', [
                'alumno_id' => $request->alumno_id,
                'materia_id' => $request->materia_id,
                'agregado_por' => $user->id
            ]);

            return response()->json([
                'error' => $this->getFriendlyErrorMessage($e, 'Error al agregar materia al legajo')
            ], 500);
        }
    }

    /**
     * Mostrar expediente completo del alumno
     */
    private function mostrarExpedienteAlumno($user)
    {
        // Obtener alumno relacionado
        $alumno = Alumno::where('dni', $user->dni)->first();

        if (!$alumno) {
            abort(404, 'No se encontró información del alumno');
        }

        // Obtener carrera del alumno
        $carrera = Carrera::find($alumno->carrera);

        // Obtener todas las materias del historial académico
        $materias = AlumnoMateria::with(['materiaRelacion', 'carrera'])
            ->where('alumno', $alumno->id)
            ->orderBy('fecha', 'desc')
            ->get()
            ->filter(function($am) {
                // Filtrar solo las que tienen relación con materia
                return $am->materiaRelacion !== null;
            })
            ->map(function($am) {
                return [
                    'id' => $am->Id,
                    'materia' => [
                        'id' => $am->materiaRelacion->id,
                        'nombre' => $am->materiaRelacion->nombre,
                        'codigo' => $am->materiaRelacion->codigo ?? null,
                        'anno' => $am->materiaRelacion->anno,
                        'semestre' => $am->materiaRelacion->semestre,
                    ],
                    'nota_final' => $am->nota,
                    'calificacion_cursada' => $am->{'calificacion-cursada'} ?? null,
                    'calificacion_rendida' => $am->calificacion_rendida ?? null,
                    'estado' => $this->obtenerEstadoMateria($am),
                    'cursada' => $am->cursada === '1',
                    'rendida' => $am->rendida === '1',
                    'fecha' => $am->fecha ? $am->fecha->format('d/m/Y') : null,
                    'libro' => $am->libro ?? null,
                    'folio' => $am->folio ?? null,
                ];
            })
            ->values(); // Reindexar después del filter

        // Calcular estadísticas
        $materiasAprobadas = $materias->where('rendida', true)->count();
        $materiasRegulares = $materias->where('cursada', true)->where('rendida', false)->count();
        $totalMaterias = $materias->count();

        // Calcular promedio (solo materias aprobadas con nota)
        $materiasConNota = $materias->where('rendida', true)->filter(function($m) {
            return !empty($m['nota_final']) && is_numeric($m['nota_final']);
        });

        $promedio = $materiasConNota->count() > 0
            ? round($materiasConNota->avg('nota_final'), 2)
            : null;

        // Obtener inscripciones actuales con asistencias
        $inscripcionesActuales = Inscripcion::with(['materia'])
            ->where('alumno_id', $alumno->id)
            ->where('estado', 'confirmada')
            ->get()
            ->map(function($inscripcion) use ($alumno) {
                // Obtener IDs de profesor_materia para esta materia
                $profesorMateriaIds = \DB::table('tbl_profesor_tiene_materias')
                    ->where('materia', $inscripcion->materia_id)
                    ->pluck('id');

                // Obtener asistencias del alumno para esta materia
                $asistencias = Asistencia::where('alumno_id', $alumno->id)
                    ->whereIn('profesor_materia_id', $profesorMateriaIds)
                    ->where('tipo_carga', 'diaria')
                    ->get();

                $totalClases = $asistencias->count();
                $asistenciasPresentes = $asistencias->where('estado', 'presente')->count();
                $porcentajeAsistencia = $totalClases > 0
                    ? round(($asistenciasPresentes / $totalClases) * 100, 1)
                    : null;

                return [
                    'materia' => [
                        'id' => $inscripcion->materia->id,
                        'nombre' => $inscripcion->materia->nombre,
                        'anno' => $inscripcion->materia->anno,
                        'semestre' => $inscripcion->materia->semestre,
                    ],
                    'asistencia' => [
                        'total_clases' => $totalClases,
                        'presentes' => $asistenciasPresentes,
                        'porcentaje' => $porcentajeAsistencia,
                    ],
                ];
            });

        return Inertia::render('Expediente/AlumnoPanel', [
            'alumno' => [
                'id' => $alumno->id,
                'dni' => $alumno->dni,
                'nombre_completo' => trim($alumno->apellido . ', ' . $alumno->nombre),
                'legajo' => $alumno->legajo,
                'email' => $alumno->email,
                'telefono' => $alumno->telefono,
                'celular' => $alumno->celular,
                'curso' => $alumno->curso,
                'division' => $alumno->division,
            ],
            'carrera' => $carrera ? [
                'id' => $carrera->Id,
                'nombre' => $carrera->nombre,
                'duracion' => $carrera->duracion,
            ] : null,
            'estadisticas' => [
                'total_materias' => $totalMaterias,
                'aprobadas' => $materiasAprobadas,
                'regulares' => $materiasRegulares,
                'promedio' => $promedio,
                'porcentaje_progreso' => $totalMaterias > 0
                    ? round(($materiasAprobadas / $totalMaterias) * 100, 1)
                    : 0,
            ],
            'historial' => $materias,
            'materias_actuales' => $inscripcionesActuales,
        ]);
    }

    /**
     * Obtener estado legible de una materia
     */
    private function obtenerEstadoMateria($alumnoMateria)
    {
        if ($alumnoMateria->rendida === '1') {
            return 'aprobada';
        }
        if ($alumnoMateria->cursada === '1') {
            return 'regular';
        }
        return 'cursando';
    }
}