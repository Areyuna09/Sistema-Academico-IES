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
     *
     * Devuelve el plan de estudio del alumno.
     * Si el alumno tiene múltiples registros en tbl_alumnos (una por carrera),
     * devuelve todas las carreras en el campo `carreras[]` y mantiene
     * compatibilidad con el campo `materias` (primera carrera) para no
     * romper nada que ya funcione.
     */
    public function showAlumnoPlan(Alumno $alumno)
    {
        Log::info('Mostrar plan de estudio de alumno', [
            'alumno_id' => $alumno->id,
            'carrera'   => $alumno->carrera,
            'user_id'   => optional(request()->user())->id,
        ]);

        Gate::authorize('viewPlan', $alumno);

        // Buscar todos los registros del alumno por DNI (puede tener más de una carrera)
        $todosLosRegistros = Alumno::where('dni', $alumno->dni)
            ->with('carreraRelacion')
            ->get();

        // Construir el payload de cada carrera
        $carreras = $todosLosRegistros->map(function ($registro) {
            return $this->buildCarreraPayload($registro);
        })->values();

        // El registro principal (el que vino en la ruta)
        $alumno->load('carreraRelacion');
        $planPrincipal = $this->buildCarreraPayload($alumno);

        // Resumen del registro principal (compatibilidad)
        $lista   = collect($planPrincipal['materias']);
        $total   = $lista->count();
        $aprobadas     = $lista->where('estado', 'aprobada')->count();
        $promocionadas = $lista->where('estado', 'promocionada')->count();
        $regularizadas = $lista->where('estado', 'regularizada')->count();
        $pendientes    = $lista->where('estado', 'pendiente')->count();
        $avance = $total > 0 ? round((($aprobadas + $promocionadas) / $total) * 100, 1) : 0.0;

        $payload = [
            // Datos del alumno (registro principal)
            'alumno' => [
                'id'     => $alumno->id,
                'nombre' => $alumno->nombre_completo ?? trim(($alumno->apellido ?? '') . ' ' . ($alumno->nombre ?? '')),
                'carrera' => [
                    'id'     => $alumno->carreraRelacion ? $alumno->carreraRelacion->Id : null,
                    'nombre' => $alumno->carreraRelacion ? $alumno->carreraRelacion->nombre : null,
                ],
            ],
            // Resumen de la carrera principal (compatibilidad con PlanDeEstudio.vue)
            'resumen' => [
                'totalMaterias'    => $total,
                'pendientes'       => $pendientes,
                'regularizadas'    => $regularizadas,
                'promocionadas'    => $promocionadas,
                'aprobadas'        => $aprobadas,
                'avancePorcentaje' => $avance,
            ],
            // Materias de la carrera principal (compatibilidad)
            'materias' => $planPrincipal['materias'],
            // Plan resuelto de la carrera principal
            'plan' => $planPrincipal['plan'],
            // NUEVO: todas las carreras del alumno para el selector en AlumnoPanel
            'carreras' => $carreras,
        ];

        Log::info('Plan de estudio calculado', [
            'alumno_id'       => $alumno->id,
            'total_carreras'  => $carreras->count(),
            'resumen'         => $payload['resumen'],
        ]);

        return response()->json($payload);
    }

    /**
     * Construye el payload de materias para un registro de alumno (una carrera).
     * Incluye el plan de estudio resuelto si existe.
     */
    private function buildCarreraPayload(Alumno $registro): array
    {
        $registro->loadMissing('carreraRelacion');

        // Resolver plan de estudio del alumno para esta carrera
        $planResuelto = $registro->resolverPlan();

        // Si hay plan, filtrar las materias que pertenecen a él
        if ($planResuelto) {
            $materiasDelPlan = PlanEstudioMateria::where('plan_estudio_id', $planResuelto->id)
                ->pluck('materia_id')
                ->toArray();

            $materiasQuery = Materia::query()
                ->whereIn('tbl_materias.id', $materiasDelPlan)
                ->leftJoin('tbl_alumnos_materias as am', function ($join) use ($registro) {
                    $join->on('am.materia', '=', 'tbl_materias.id')
                        ->where('am.alumno', '=', $registro->id)
                        ->where('am.carrera', '=', $registro->carrera);
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
                ])
                ->orderBy('tbl_materias.anno', 'asc')
                ->orderBy('tbl_materias.semestre', 'asc')
                ->orderBy('tbl_materias.nombre', 'asc')
                ->get();
        } else {
            // Sin plan: todas las materias de la carrera (comportamiento legacy)
            $materiasQuery = Materia::query()
                ->where('tbl_materias.carrera', $registro->carrera)
                ->leftJoin('tbl_alumnos_materias as am', function ($join) use ($registro) {
                    $join->on('am.materia', '=', 'tbl_materias.id')
                        ->where('am.alumno', '=', $registro->id)
                        ->where('am.carrera', '=', $registro->carrera);
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
                ])
                ->orderBy('tbl_materias.anno', 'asc')
                ->orderBy('tbl_materias.semestre', 'asc')
                ->orderBy('tbl_materias.nombre', 'asc')
                ->get();
        }

        $lista = $materiasQuery->map(function ($m) {
            $estado = 'pendiente';
            if ($m->nota !== null && is_numeric($m->nota) && (float) $m->nota >= 4) {
                $estado = 'aprobada';
            }

            return [
                'materia' => [
                    'id'          => (int) $m->id,
                    'nombre'      => $m->nombre,
                    'anio'        => $m->anno,
                    'cuatrimestre' => $m->semestre,
                    'creditos'    => null,
                ],
                'estado'      => $estado,
                'nota'        => is_null($m->nota) ? null : (is_numeric($m->nota) ? (float) $m->nota : $m->nota),
                'fecha_estado' => $m->fecha
                    ? ($m->fecha instanceof \DateTimeInterface ? $m->fecha->format('Y-m-d H:i:s') : (string) $m->fecha)
                    : null,
            ];
        });

        $total         = $lista->count();
        $aprobadas     = $lista->where('estado', 'aprobada')->count();
        $promocionadas = $lista->where('estado', 'promocionada')->count();
        $regularizadas = $lista->where('estado', 'regularizada')->count();
        $pendientes    = $lista->where('estado', 'pendiente')->count();
        $avance = $total > 0 ? round((($aprobadas + $promocionadas) / $total) * 100, 1) : 0.0;

        return [
            // Nombre de la carrera para el selector en AlumnoPanel
            'nombre' => $registro->carreraRelacion ? $registro->carreraRelacion->nombre : "Carrera #{$registro->carrera}",
            'carrera_id' => $registro->carrera,
            'alumno_id'  => $registro->id,
            'plan' => $planResuelto ? [
                'id'         => $planResuelto->id,
                'nombre'     => $planResuelto->nombre,
                'anio'       => $planResuelto->anio,
                'resolucion' => $planResuelto->resolucion ?? null,
            ] : null,
            'estadisticas' => [
                'totalMaterias'    => $total,
                'pendientes'       => $pendientes,
                'regularizadas'    => $regularizadas,
                'promocionadas'    => $promocionadas,
                'aprobadas'        => $aprobadas,
                'avancePorcentaje' => $avance,
            ],
            'materias'         => $lista->values(),
            // Estos campos los usa AlumnoPanel.vue
            'historial'        => $lista->values(),
            'materias_actuales' => [], // se podría poblar si se necesita por carrera
        ];
    }

    /**
     * PATCH /api/alumnos/{alumno}/materias/{materia}
     * Crea/actualiza el estado de una materia para el alumno en la tabla legacy.
     */
    public function updateAlumnoMateria(Request $request, Alumno $alumno, Materia $materia)
    {
        Log::info('Actualizar estado de materia', [
            'alumno_id'  => $alumno->id,
            'materia_id' => $materia->id,
            'user_id'    => optional($request->user())->id,
        ]);
        Gate::authorize('updatePlan', $alumno);

        $data = $request->validate([
            'estado'      => 'required|string|in:pendiente,regularizada,promocionada,aprobada',
            'nota'        => 'nullable|numeric',
            'fecha_estado' => 'nullable|date',
        ]);

        if ((int) $materia->carrera !== (int) $alumno->carrera) {
            Log::warning('Materia no pertenece a carrera del alumno', [
                'materia_carrera' => $materia->carrera,
                'alumno_carrera'  => $alumno->carrera,
            ]);
            return response()->json(['message' => 'La materia no pertenece a la carrera del alumno.'], 422);
        }

        $registro = AlumnoMateria::query()
            ->where('alumno', $alumno->id)
            ->where('carrera', $alumno->carrera)
            ->where('materia', $materia->id)
            ->first();

        if (!$registro) {
            $registro          = new AlumnoMateria();
            $registro->alumno  = $alumno->id;
            $registro->carrera = $alumno->carrera;
            $registro->materia = $materia->id;
        }

        $estado = $data['estado'];
        if ($estado === 'pendiente') {
            $registro->cursada = '0';
            $registro->rendida = '0';
        } elseif ($estado === 'regularizada') {
            $registro->cursada = '1';
            $registro->rendida = '0';
        } elseif ($estado === 'promocionada') {
            $registro->cursada = '1';
            $registro->rendida = '0';
            if (array_key_exists('nota', $data) && !is_null($data['nota'])) {
                $registro->{'calificacion-cursada'} = (string) $data['nota'];
            }
        } elseif ($estado === 'aprobada') {
            $registro->rendida = '1';
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
            'alumno'  => $registro->alumno,
            'materia' => $registro->materia,
            'nota'    => $registro->nota,
        ]);

        return response()->json(['message' => 'Estado actualizado correctamente.']);
    }

    /**
     * GET /api/carreras/{carrera}/plan
     */
    public function showCarreraPlan(Carrera $carrera)
    {
        $user = request()->user();
        Log::info('Mostrar plan base de carrera', [
            'carrera_id' => $carrera->Id,
            'user_id'    => optional($user)->id,
        ]);

        $materiasProfesorIds = [];
        if ($user && $user->profesor_id) {
            $profesor = $user->profesor;
            if ($profesor) {
                $materiasProfesorIds = $profesor->materias()
                    ->where('tbl_materias.carrera', $carrera->Id)
                    ->pluck('tbl_materias.id')
                    ->toArray();
            }
        }

        $planActivo = $carrera->planActivo()->first();

        if ($planActivo) {
            $materias = $planActivo->materias()
                ->select(['tbl_materias.id', 'tbl_materias.nombre', 'tbl_materias.anno', 'tbl_materias.semestre'])
                ->get()
                ->map(function ($m) use ($materiasProfesorIds) {
                    return [
                        'id'           => (int) $m->id,
                        'nombre'       => $m->nombre,
                        'anio'         => $m->anno,
                        'cuatrimestre' => $m->semestre,
                        'creditos'     => null,
                        'orden'        => $m->pivot->orden ?? 0,
                        'es_mi_materia' => in_array($m->id, $materiasProfesorIds),
                    ];
                });

            $payload = [
                'carrera' => ['id' => $carrera->Id, 'nombre' => $carrera->nombre],
                'plan'    => ['id' => $planActivo->id, 'nombre' => $planActivo->nombre, 'anio' => $planActivo->anio, 'activo' => $planActivo->activo],
                'materias' => $materias,
            ];
        } else {
            $materias = $carrera->materias()
                ->select(['id', 'nombre', 'anno', 'semestre'])
                ->orderBy('anno')->orderBy('semestre')->orderBy('nombre')
                ->get()
                ->map(function ($m) use ($materiasProfesorIds) {
                    return [
                        'id'           => (int) $m->id,
                        'nombre'       => $m->nombre,
                        'anio'         => $m->anno,
                        'cuatrimestre' => $m->semestre,
                        'creditos'     => null,
                        'es_mi_materia' => in_array($m->id, $materiasProfesorIds),
                    ];
                });

            $payload = [
                'carrera'  => ['id' => $carrera->Id, 'nombre' => $carrera->nombre],
                'plan'     => null,
                'materias' => $materias,
            ];
        }

        return response()->json($payload);
    }

    /**
     * POST /api/carreras/{carrera}/planes
     */
    public function crearPlan(Request $request, Carrera $carrera)
    {
        $data = $request->validate([
            'nombre'     => 'required|string|max:255',
            'anio'       => 'required|integer',
            'resolucion' => 'nullable|string|max:255',
            'activo'     => 'boolean',
        ]);

        if ($data['activo'] ?? false) {
            $carrera->planesEstudio()->update(['activo' => false]);
        }

        $plan = $carrera->planesEstudio()->create([
            'nombre'     => $data['nombre'],
            'anio'       => $data['anio'],
            'resolucion' => $data['resolucion'] ?? null,
            'activo'     => $data['activo'] ?? false,
        ]);

        Log::info('Plan de estudio creado', ['plan_id' => $plan->id, 'carrera_id' => $carrera->Id]);

        return response()->json([
            'message' => 'Plan de estudio creado exitosamente',
            'plan'    => ['id' => $plan->id, 'nombre' => $plan->nombre, 'anio' => $plan->anio, 'resolucion' => $plan->resolucion, 'activo' => $plan->activo],
        ], 201);
    }

    /**
     * GET /api/carreras/{carrera}/planes
     */
    public function listPlanesCarrera(Carrera $carrera)
    {
        Log::info('Listar planes de carrera', ['carrera_id' => $carrera->Id]);

        $planes = $carrera->planesEstudio()
            ->orderBy('activo', 'desc')
            ->orderBy('anio', 'desc')
            ->get()
            ->map(function ($plan) {
                return [
                    'id'         => $plan->id,
                    'nombre'     => $plan->nombre,
                    'anio'       => $plan->anio,
                    'resolucion' => $plan->resolucion,
                    'activo'     => $plan->activo,
                    'vigente'    => $plan->vigente,
                ];
            });

        return response()->json([
            'carrera' => ['id' => $carrera->Id, 'nombre' => $carrera->nombre],
            'planes'  => $planes,
        ]);
    }

    /**
     * GET /api/carreras
     */
    public function listCarreras()
    {
        $user = request()->user();
        Log::info('Listado de carreras solicitado', ['user_id' => optional($user)->id]);

        if ($user && $user->profesor_id) {
            $profesor = $user->profesor;
            if ($profesor) {
                $carreraIds = $profesor->materias()
                    ->pluck('tbl_materias.carrera')
                    ->unique()->filter()->toArray();

                return response()->json(
                    Carrera::whereIn('Id', $carreraIds)
                        ->select(['Id as id', 'nombre'])
                        ->orderBy('nombre')->get()
                );
            }
        }

        return response()->json(
            Carrera::select(['Id as id', 'nombre'])->orderBy('nombre')->get()
        );
    }

    /**
     * POST /api/carreras/{carrera}/planes/{plan}/materias
     */
    public function asignarMateria(Request $request, Carrera $carrera, PlanEstudio $plan)
    {
        Log::info('Asignar materia a plan', ['carrera_id' => $carrera->Id, 'plan_id' => $plan->id]);

        if ((int) $plan->carrera_id !== (int) $carrera->Id) {
            return response()->json(['message' => 'El plan no pertenece a esta carrera.'], 422);
        }

        $data = $request->validate([
            'materia_id' => 'required|integer|exists:tbl_materias,id',
            'orden'      => 'nullable|integer',
        ]);

        $materia = Materia::find($data['materia_id']);
        if ((int) $materia->carrera !== (int) $carrera->Id) {
            return response()->json(['message' => 'La materia no pertenece a esta carrera.'], 422);
        }

        $existe = PlanEstudioMateria::where('plan_estudio_id', $plan->id)
            ->where('materia_id', $data['materia_id'])->exists();

        if ($existe) {
            return response()->json(['message' => 'La materia ya está asignada a este plan.'], 422);
        }

        $orden = $data['orden'] ?? PlanEstudioMateria::where('plan_estudio_id', $plan->id)->max('orden') + 1;

        PlanEstudioMateria::create([
            'plan_estudio_id' => $plan->id,
            'materia_id'      => $data['materia_id'],
            'orden'           => $orden,
        ]);

        return response()->json(['message' => 'Materia asignada correctamente al plan.']);
    }

    /**
     * DELETE /api/carreras/{carrera}/planes/{plan}/materias/{materia}
     */
    public function quitarMateria(Request $request, Carrera $carrera, PlanEstudio $plan, Materia $materia)
    {
        if ((int) $plan->carrera_id !== (int) $carrera->Id) {
            return response()->json(['message' => 'El plan no pertenece a esta carrera.'], 422);
        }

        $eliminado = PlanEstudioMateria::where('plan_estudio_id', $plan->id)
            ->where('materia_id', $materia->id)->delete();

        if (!$eliminado) {
            return response()->json(['message' => 'La materia no está asignada a este plan.'], 404);
        }

        Log::info('Materia quitada del plan', ['plan_id' => $plan->id, 'materia_id' => $materia->id]);

        return response()->json(['message' => 'Materia quitada del plan correctamente.']);
    }

    /**
     * GET /api/carreras/{carrera}/planes/{plan}
     */
    public function showPlan(Carrera $carrera, PlanEstudio $plan)
    {
        if ((int) $plan->carrera_id !== (int) $carrera->Id) {
            return response()->json(['message' => 'El plan no pertenece a esta carrera.'], 404);
        }

        $materias = $plan->materias()
            ->select(['tbl_materias.id', 'tbl_materias.nombre', 'tbl_materias.anno', 'tbl_materias.semestre'])
            ->get()
            ->map(function ($m) {
                return [
                    'id'           => (int) $m->id,
                    'nombre'       => $m->nombre,
                    'anio'         => $m->anno,
                    'cuatrimestre' => $m->semestre,
                    'creditos'     => null,
                    'orden'        => $m->pivot->orden ?? 0,
                ];
            });

        return response()->json([
            'plan'     => ['id' => $plan->id, 'nombre' => $plan->nombre, 'anio' => $plan->anio, 'resolucion' => $plan->resolucion, 'activo' => $plan->activo],
            'carrera'  => ['id' => $carrera->Id, 'nombre' => $carrera->nombre],
            'materias' => $materias,
        ]);
    }
}