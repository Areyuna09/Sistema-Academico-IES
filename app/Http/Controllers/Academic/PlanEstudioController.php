<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Materia;
use App\Models\AlumnoMateria;
use App\Models\PlanEstudio;
use App\Models\PlanEstudioMateria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

/**
 * Controlador de Plan de Estudio
 *
 * Middleware aplicado: auth:sanctum
 * Autorización: AlumnoPolicy (viewPlan, updatePlan)
 */
class PlanEstudioController extends Controller
{
    /**
     * GET /api/alumnos/{alumno}/plan-estudio
     * Muestra el plan de estudio del alumno: materias de su carrera y estado por materia.
     */
    public function showAlumnoPlan(Alumno $alumno)
    {
        Log::info('Mostrar plan de estudio de alumno', [
            'alumno_id' => $alumno->id,
            'carrera' => $alumno->carrera,
            'user_id' => optional(request()->user())->id,
        ]);
        Gate::authorize('viewPlan', $alumno);

        $alumno->load('carreraRelacion');

        // Construir consulta: materias de la carrera con left join al estado del alumno
        $materiasQuery = Materia::query()
            ->where('tbl_materias.carrera', $alumno->carrera)
            ->leftJoin('tbl_alumnos_materias as am', function ($join) use ($alumno) {
                $join->on('am.materia', '=', 'tbl_materias.id')
                    ->where('am.alumno', '=', $alumno->id)
                    ->where('am.carrera', '=', $alumno->carrera);
            })
            ->select([
                'tbl_materias.id as id',
                'tbl_materias.nombre as nombre',
                DB::raw('tbl_materias.anno as anno'),
                DB::raw('tbl_materias.semestre as semestre'),
                DB::raw('am.nota as nota'),
                DB::raw('am.cursada as cursada'),
                DB::raw('am.rendida as rendida'),
                DB::raw('am.`calificacion-cursada` as calificacion_cursada'),
                DB::raw('am.calificacion_rendida as calificacion_rendida'),
                DB::raw('am.fecha as fecha'),
            ]);

        // Ordenar si existen columnas anno/semestre, sino por nombre
        $materiasQuery->orderBy('tbl_materias.anno', 'asc')
            ->orderBy('tbl_materias.semestre', 'asc')
            ->orderBy('tbl_materias.nombre', 'asc');

        $materias = $materiasQuery->get();
        Log::debug('Materias obtenidas para plan', ['count' => $materias->count()]);

        // Mapear estado por materia desde columnas legacy
        $lista = $materias->map(function ($m) {
            $estado = 'pendiente';
            if ($m->rendida === '1') {
                $estado = 'aprobada';
            } elseif ($m->cursada === '1') {
                // Si hubiera lógica de promoción explícita, podría diferenciarse aquí
                $estado = 'regularizada';
            }

            return [
                'materia' => [
                    'id' => (int) $m->id,
                    'nombre' => $m->nombre,
                    'anio' => $m->anno, // puede ser null en algunos planes
                    'cuatrimestre' => $m->semestre, // mapea de "semestre" legacy
                    'creditos' => null, // la tabla legacy no tiene créditos
                ],
                'estado' => $estado,
                'nota' => is_null($m->nota) ? null : (is_numeric($m->nota) ? (float) $m->nota : $m->nota),
                'fecha_estado' => $m->fecha ? ($m->fecha instanceof \DateTimeInterface ? $m->fecha->format('Y-m-d H:i:s') : (string) $m->fecha) : null,
            ];
        });

        // Resumen
        $total = $lista->count();
        $pendientes = $lista->where('estado', 'pendiente')->count();
        $regularizadas = $lista->where('estado', 'regularizada')->count();
        $promocionadas = $lista->where('estado', 'promocionada')->count();
        $aprobadas = $lista->where('estado', 'aprobada')->count();
        $avance = $total > 0 ? round((($aprobadas + $promocionadas) / $total) * 100, 1) : 0.0;

        $payload = [
            'alumno' => [
                'id' => $alumno->id,
                'nombre' => $alumno->nombre_completo ?? trim(($alumno->apellido ?? '') . ' ' . ($alumno->nombre ?? '')),
                'carrera' => [
                    'id' => $alumno->carreraRelacion ? $alumno->carreraRelacion->Id : null,
                    'nombre' => $alumno->carreraRelacion ? $alumno->carreraRelacion->nombre : null,
                ],
            ],
            'resumen' => [
                'totalMaterias' => $total,
                'pendientes' => $pendientes,
                'regularizadas' => $regularizadas,
                'promocionadas' => $promocionadas,
                'aprobadas' => $aprobadas,
                'avancePorcentaje' => $avance,
            ],
            'materias' => $lista,
        ];
        Log::info('Resumen de plan calculado', $payload['resumen']);
        return response()->json($payload);
    }

    /**
     * PATCH /api/alumnos/{alumno}/materias/{materia}
     * Crea/actualiza el estado de una materia para el alumno en la tabla legacy.
     */
    public function updateAlumnoMateria(Request $request, Alumno $alumno, Materia $materia)
    {
        Log::info('Actualizar estado de materia', [
            'alumno_id' => $alumno->id,
            'materia_id' => $materia->id,
            'user_id' => optional($request->user())->id,
        ]);
        Gate::authorize('updatePlan', $alumno);

        // Validación de entrada
        $data = $request->validate([
            'estado' => 'required|string|in:pendiente,regularizada,promocionada,aprobada',
            'nota' => 'nullable|numeric',
            'fecha_estado' => 'nullable|date',
        ]);

        // Verificar que la materia pertenece a la carrera del alumno
        if ((int) $materia->carrera !== (int) $alumno->carrera) {
            Log::warning('Materia no pertenece a carrera del alumno', [
                'materia_carrera' => $materia->carrera,
                'alumno_carrera' => $alumno->carrera,
            ]);
            return response()->json(['message' => 'La materia no pertenece a la carrera del alumno.'], 422);
        }

        // Buscar registro existente legacy
        $registro = AlumnoMateria::query()
            ->where('alumno', $alumno->id)
            ->where('carrera', $alumno->carrera)
            ->where('materia', $materia->id)
            ->first();

        if (!$registro) {
            $registro = new AlumnoMateria();
            $registro->alumno = $alumno->id;
            $registro->carrera = $alumno->carrera;
            $registro->materia = $materia->id;
        }

        // Mapear estado -> columnas legacy
        $estado = $data['estado'];
        if ($estado === 'pendiente') {
            $registro->cursada = '0';
            $registro->rendida = '0';
        } elseif ($estado === 'regularizada') {
            $registro->cursada = '1';
            $registro->rendida = '0';
        } elseif ($estado === 'promocionada') {
            // En esquema legacy no hay columna específica; se usa cursada=1 sin rendir
            $registro->cursada = '1';
            $registro->rendida = '0';
            // Si viene nota, guardar como calificación de cursada si existe
            if (array_key_exists('nota', $data) && !is_null($data['nota'])) {
                // Campo legacy con guión
                $registro->{'calificacion-cursada'} = (string) $data['nota'];
            }
        } elseif ($estado === 'aprobada') {
            $registro->rendida = '1';
            // Opcionalmente, mantener cursada en 1
            $registro->cursada = $registro->cursada === '1' ? '1' : '0';
        }

        if (array_key_exists('nota', $data)) {
            $registro->nota = is_null($data['nota']) ? null : (string) $data['nota'];
        }

        if (array_key_exists('fecha_estado', $data)) {
            $registro->fecha = $data['fecha_estado'] ? date('Y-m-d', strtotime($data['fecha_estado'])) : null;
        }

        $registro->save();
        Log::info('Estado de materia actualizado', [
            'alumno' => $registro->alumno,
            'materia' => $registro->materia,
            'cursada' => $registro->cursada,
            'rendida' => $registro->rendida,
            'nota' => $registro->nota,
            'fecha' => $registro->fecha,
        ]);

        return response()->json(['message' => 'Estado actualizado correctamente.']);
    }

    /**
     * GET /api/carreras/{carrera}/plan
     * Devuelve el plan activo de la carrera con sus materias asignadas.
     * Si no hay plan activo, devuelve todas las materias de la carrera (comportamiento legacy).
     * Si el usuario es profesor, marca las materias que dicta con el campo 'es_mi_materia'.
     */
    public function showCarreraPlan(Carrera $carrera)
    {
        $user = request()->user();
        Log::info('Mostrar plan base de carrera', [
            'carrera_id' => $carrera->Id,
            'user_id' => optional($user)->id,
            'tipo' => optional($user)->tipo,
            'profesor_id' => optional($user)->profesor_id,
        ]);

        // Obtener IDs de materias que el profesor dicta (si es profesor)
        $materiasProfesorIds = [];
        if ($user && $user->profesor_id) {
            $profesor = $user->profesor;
            if ($profesor) {
                $materiasProfesorIds = $profesor->materias()
                    ->where('tbl_materias.carrera', $carrera->Id)
                    ->pluck('tbl_materias.id')
                    ->toArray();

                Log::debug('Materias del profesor en esta carrera', [
                    'count' => count($materiasProfesorIds),
                    'materia_ids' => $materiasProfesorIds,
                ]);
            }
        }

        // Buscar el plan activo de la carrera
        $planActivo = $carrera->planActivo()->first();

        if ($planActivo) {
            // Si existe un plan activo, devolver todas las materias asignadas a ese plan
            $materias = $planActivo->materias()
                ->select(['tbl_materias.id', 'tbl_materias.nombre', 'tbl_materias.anno', 'tbl_materias.semestre'])
                ->get()
                ->map(function ($m) use ($materiasProfesorIds) {
                    return [
                        'id' => (int) $m->id,
                        'nombre' => $m->nombre,
                        'anio' => $m->anno,
                        'cuatrimestre' => $m->semestre,
                        'creditos' => null,
                        'orden' => $m->pivot->orden ?? 0,
                        'es_mi_materia' => in_array($m->id, $materiasProfesorIds), // Marca si el profesor la dicta
                    ];
                });

            $payload = [
                'carrera' => [
                    'id' => $carrera->Id,
                    'nombre' => $carrera->nombre,
                ],
                'plan' => [
                    'id' => $planActivo->id,
                    'nombre' => $planActivo->nombre,
                    'anio' => $planActivo->anio,
                    'activo' => $planActivo->activo,
                ],
                'materias' => $materias,
            ];
            Log::debug('Materias del plan activo', ['plan_id' => $planActivo->id, 'count' => count($materias)]);
        } else {
            // Comportamiento legacy: si no hay plan, devolver todas las materias de la carrera
            $materias = $carrera->materias()
                ->select(['id', 'nombre', 'anno', 'semestre'])
                ->orderBy('anno', 'asc')
                ->orderBy('semestre', 'asc')
                ->orderBy('nombre', 'asc')
                ->get()
                ->map(function ($m) use ($materiasProfesorIds) {
                    return [
                        'id' => (int) $m->id,
                        'nombre' => $m->nombre,
                        'anio' => $m->anno,
                        'cuatrimestre' => $m->semestre,
                        'creditos' => null,
                        'es_mi_materia' => in_array($m->id, $materiasProfesorIds), // Marca si el profesor la dicta
                    ];
                });

            $payload = [
                'carrera' => [
                    'id' => $carrera->Id,
                    'nombre' => $carrera->nombre,
                ],
                'plan' => null,
                'materias' => $materias,
            ];
            Log::debug('Materias sin plan (legacy)', ['count' => count($materias)]);
        }

        return response()->json($payload);
    }

    /**
     * POST /api/carreras/{carrera}/planes
     * Crear un nuevo plan de estudio para una carrera
     */
    public function crearPlan(Request $request, Carrera $carrera)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'anio' => 'required|integer',
            'resolucion' => 'nullable|string|max:255',
            'activo' => 'boolean',
        ]);

        // Si se marca como activo, desactivar otros planes
        if ($data['activo'] ?? false) {
            $carrera->planesEstudio()->update(['activo' => false]);
        }

        $plan = $carrera->planesEstudio()->create([
            'nombre' => $data['nombre'],
            'anio' => $data['anio'],
            'resolucion' => $data['resolucion'] ?? null,
            'activo' => $data['activo'] ?? false,
        ]);

        Log::info('Plan de estudio creado', ['plan_id' => $plan->id, 'carrera_id' => $carrera->Id]);

        return response()->json([
            'message' => 'Plan de estudio creado exitosamente',
            'plan' => [
                'id' => $plan->id,
                'nombre' => $plan->nombre,
                'anio' => $plan->anio,
                'resolucion' => $plan->resolucion,
                'activo' => $plan->activo,
            ],
        ], 201);
    }

    /**
     * GET /api/carreras/{carrera}/planes
     * Lista todos los planes de estudio de una carrera
     */
    public function listPlanesCarrera(Carrera $carrera)
    {
        Log::info('Listar planes de carrera', ['carrera_id' => $carrera->Id]);

        $planes = $carrera->planesEstudio()
            ->orderBy('activo', 'desc') // Activos primero
            ->orderBy('anio', 'desc') // Más recientes primero
            ->get()
            ->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'nombre' => $plan->nombre,
                    'anio' => $plan->anio,
                    'resolucion' => $plan->resolucion,
                    'activo' => $plan->activo,
                ];
            });

        return response()->json([
            'carrera' => [
                'id' => $carrera->Id,
                'nombre' => $carrera->nombre,
            ],
            'planes' => $planes,
        ]);
    }

    /**
     * GET /api/carreras
     * Lista de carreras básicas para selección en UI.
     * Si el usuario es profesor, solo devuelve las carreras en las que dicta materias.
     */
    public function listCarreras()
    {
        $user = request()->user();
        Log::info('Listado de carreras solicitado', [
            'user_id' => optional($user)->id,
            'tipo' => optional($user)->tipo,
            'profesor_id' => optional($user)->profesor_id,
        ]);

        // Si es profesor, solo mostrar las carreras en las que dicta materias
        if ($user && $user->profesor_id) {
            $profesor = $user->profesor;

            if ($profesor) {
                // Obtener IDs únicos de carreras de las materias que dicta el profesor
                $carreraIds = $profesor->materias()
                    ->pluck('tbl_materias.carrera')
                    ->unique()
                    ->filter()
                    ->toArray();

                $carreras = Carrera::query()
                    ->whereIn('Id', $carreraIds)
                    ->select(['Id as id', 'nombre'])
                    ->orderBy('nombre', 'asc')
                    ->get();

                Log::debug('Carreras del profesor devueltas', [
                    'count' => $carreras->count(),
                    'carrera_ids' => $carreraIds,
                ]);

                return response()->json($carreras);
            }
        }

        // Para admin o usuarios sin restricciones, devolver todas las carreras
        $carreras = Carrera::query()
            ->select(['Id as id', 'nombre'])
            ->orderBy('nombre', 'asc')
            ->get();

        Log::debug('Cantidad de carreras devueltas', ['count' => $carreras->count()]);
        return response()->json($carreras);
    }

    /**
     * POST /api/carreras/{carrera}/planes/{plan}/materias
     * Asignar una materia a un plan de estudio específico
     */
    public function asignarMateria(Request $request, Carrera $carrera, PlanEstudio $plan)
    {
        Log::info('Asignar materia a plan', [
            'carrera_id' => $carrera->Id,
            'plan_id' => $plan->id,
            'user_id' => optional($request->user())->id,
        ]);

        // Validar que el plan pertenece a la carrera
        if ((int) $plan->carrera_id !== (int) $carrera->Id) {
            return response()->json(['message' => 'El plan no pertenece a esta carrera.'], 422);
        }

        $data = $request->validate([
            'materia_id' => 'required|integer|exists:tbl_materias,id',
            'orden' => 'nullable|integer',
        ]);

        // Verificar que la materia pertenece a la carrera
        $materia = Materia::find($data['materia_id']);
        if ((int) $materia->carrera !== (int) $carrera->Id) {
            return response()->json(['message' => 'La materia no pertenece a esta carrera.'], 422);
        }

        // Verificar que no esté ya asignada
        $existe = PlanEstudioMateria::where('plan_estudio_id', $plan->id)
            ->where('materia_id', $data['materia_id'])
            ->exists();

        if ($existe) {
            return response()->json(['message' => 'La materia ya está asignada a este plan.'], 422);
        }

        // Asignar materia al plan
        $orden = $data['orden'] ?? PlanEstudioMateria::where('plan_estudio_id', $plan->id)->max('orden') + 1;

        PlanEstudioMateria::create([
            'plan_estudio_id' => $plan->id,
            'materia_id' => $data['materia_id'],
            'orden' => $orden,
        ]);

        Log::info('Materia asignada a plan correctamente', [
            'plan_id' => $plan->id,
            'materia_id' => $data['materia_id'],
        ]);

        return response()->json(['message' => 'Materia asignada correctamente al plan.']);
    }

    /**
     * DELETE /api/carreras/{carrera}/planes/{plan}/materias/{materia}
     * Quitar una materia de un plan de estudio (sin eliminarla del sistema)
     */
    public function quitarMateria(Request $request, Carrera $carrera, PlanEstudio $plan, Materia $materia)
    {
        Log::info('Quitar materia de plan', [
            'carrera_id' => $carrera->Id,
            'plan_id' => $plan->id,
            'materia_id' => $materia->id,
            'user_id' => optional($request->user())->id,
        ]);

        // Validar que el plan pertenece a la carrera
        if ((int) $plan->carrera_id !== (int) $carrera->Id) {
            return response()->json(['message' => 'El plan no pertenece a esta carrera.'], 422);
        }

        // Buscar y eliminar la relación
        $eliminado = PlanEstudioMateria::where('plan_estudio_id', $plan->id)
            ->where('materia_id', $materia->id)
            ->delete();

        if (!$eliminado) {
            return response()->json(['message' => 'La materia no está asignada a este plan.'], 404);
        }

        Log::info('Materia quitada del plan correctamente', [
            'plan_id' => $plan->id,
            'materia_id' => $materia->id,
        ]);

        return response()->json(['message' => 'Materia quitada del plan correctamente.']);
    }

    /**
     * GET /api/carreras/{carrera}/planes/{plan}
     * Obtener un plan de estudio específico con sus materias
     */
    public function showPlan(Carrera $carrera, PlanEstudio $plan)
    {
        Log::info('Mostrar plan de estudio específico', [
            'carrera_id' => $carrera->Id,
            'plan_id' => $plan->id,
        ]);

        // Validar que el plan pertenece a la carrera
        if ((int) $plan->carrera_id !== (int) $carrera->Id) {
            return response()->json(['message' => 'El plan no pertenece a esta carrera.'], 404);
        }

        // Cargar materias del plan
        $materias = $plan->materias()
            ->select(['tbl_materias.id', 'tbl_materias.nombre', 'tbl_materias.anno', 'tbl_materias.semestre'])
            ->get()
            ->map(function ($m) {
                return [
                    'id' => (int) $m->id,
                    'nombre' => $m->nombre,
                    'anio' => $m->anno,
                    'cuatrimestre' => $m->semestre,
                    'creditos' => null,
                    'orden' => $m->pivot->orden ?? 0,
                ];
            });

        $payload = [
            'plan' => [
                'id' => $plan->id,
                'nombre' => $plan->nombre,
                'anio' => $plan->anio,
                'resolucion' => $plan->resolucion,
                'activo' => $plan->activo,
            ],
            'carrera' => [
                'id' => $carrera->Id,
                'nombre' => $carrera->nombre,
            ],
            'materias' => $materias,
        ];

        return response()->json($payload);
    }
}
