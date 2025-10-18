<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Materia;
use App\Models\AlumnoMateria;
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
     * Devuelve el plan base (materias) sin estados de alumnos.
     */
    public function showCarreraPlan(Carrera $carrera)
    {
        Log::info('Mostrar plan base de carrera', ['carrera_id' => $carrera->Id]);
        $materias = $carrera->materias()
            ->select(['id', 'nombre', 'anno', 'semestre'])
            ->orderBy('anno', 'asc')
            ->orderBy('semestre', 'asc')
            ->orderBy('nombre', 'asc')
            ->get()
            ->map(function ($m) {
                return [
                    'id' => (int) $m->id,
                    'nombre' => $m->nombre,
                    'anio' => $m->anno,
                    'cuatrimestre' => $m->semestre,
                    'creditos' => null,
                ];
            });

        $payload = [
            'carrera' => [
                'id' => $carrera->Id,
                'nombre' => $carrera->nombre,
            ],
            'materias' => $materias,
        ];
        Log::debug('Cantidad de materias en plan de carrera', ['count' => count($materias)]);
        return response()->json($payload);
    }

    /**
     * GET /api/carreras
     * Lista de carreras básicas para selección en UI.
     */
    public function listCarreras()
    {
        Log::info('Listado de carreras solicitado', ['user_id' => optional(request()->user())->id]);
        $carreras = Carrera::query()
            ->select(['Id as id', 'nombre'])
            ->orderBy('nombre', 'asc')
            ->get();

        Log::debug('Cantidad de carreras devueltas', ['count' => $carreras->count()]);
        return response()->json($carreras);
    }
}
