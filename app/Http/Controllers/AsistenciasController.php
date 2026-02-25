<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Asistencia;
use App\Models\ProfesorMateria;
use App\Models\Inscripcion;
use App\Models\PeriodoInscripcion;
use App\Models\Carrera;
use Carbon\Carbon;

class AsistenciasController extends Controller
{
    use \App\Traits\ChecksPermissions;

    /**
     * Listado de materias para tomar asistencia.
     * El preceptor ve TODAS las materias del período activo, filtrable por carrera.
     */
    public function index(Request $request)
    {
        $periodoActivo = PeriodoInscripcion::activo();

        if (!$periodoActivo) {
            return Inertia::render('Preceptor/Asistencias', [
                'materias' => [],
                'carreras' => [],
                'filtros' => [],
                'periodoActivo' => null,
            ]);
        }

        $query = ProfesorMateria::with(['materiaRelacion', 'carreraRelacion', 'profesorRelacion'])
            ->where('periodo_id', $periodoActivo->id);

        if ($request->filled('carrera_id')) {
            $query->where('carrera', $request->carrera_id);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->whereHas('materiaRelacion', function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%");
            });
        }

        $profesorMaterias = $query->orderBy('carrera')->orderBy('materia')->get();

        // Para cada asignación, verificar si se tomó asistencia hoy
        $hoy = Carbon::today()->format('Y-m-d');
        $materias = $profesorMaterias->map(function ($pm) use ($hoy) {
            $asistenciasHoy = Asistencia::where('profesor_materia_id', $pm->id)
                ->where('fecha', $hoy)
                ->where('tipo_carga', 'diaria')
                ->count();

            $inscriptos = Inscripcion::where('materia_id', $pm->materia)
                ->where('carrera_id', $pm->carrera)
                ->where('estado', 'confirmada')
                ->count();

            return [
                'id' => $pm->id,
                'materia_nombre' => $pm->materiaRelacion->nombre ?? 'Sin materia',
                'carrera_nombre' => $pm->carreraRelacion->nombre ?? 'Sin carrera',
                'carrera_id' => $pm->carrera,
                'profesor_nombre' => $pm->profesorRelacion
                    ? $pm->profesorRelacion->apellido . ', ' . $pm->profesorRelacion->nombre
                    : 'Sin profesor',
                'cursado' => $pm->cursado,
                'division' => $pm->division,
                'inscriptos' => $inscriptos,
                'asistencia_hoy' => $asistenciasHoy > 0,
                'asistencias_hoy_count' => $asistenciasHoy,
            ];
        });

        $carreras = Carrera::orderBy('nombre')->get()->map(fn($c) => [
            'id' => $c->Id,
            'nombre' => $c->nombre,
        ]);

        return Inertia::render('Preceptor/Asistencias', [
            'materias' => $materias,
            'carreras' => $carreras,
            'filtros' => $request->only(['carrera_id', 'buscar']),
            'periodoActivo' => [
                'id' => $periodoActivo->id,
                'nombre' => $periodoActivo->nombre,
            ],
        ]);
    }

    /**
     * Formulario para tomar asistencia de una materia.
     */
    public function tomar(Request $request, $profesorMateriaId)
    {
        $this->autorizarTomarAsistencias($request);

        $periodoActivo = PeriodoInscripcion::activo();
        if (!$periodoActivo) {
            return redirect()->route('preceptor.asistencias.index')
                ->with('error', 'No hay un período activo para tomar asistencia.');
        }

        $profesorMateria = ProfesorMateria::with(['materiaRelacion', 'carreraRelacion', 'profesorRelacion'])
            ->where('periodo_id', $periodoActivo->id)
            ->findOrFail($profesorMateriaId);

        $alumnos = Inscripcion::with(['alumno'])
            ->where('materia_id', $profesorMateria->materia)
            ->where('carrera_id', $profesorMateria->carrera)
            ->where('periodo_id', $periodoActivo->id)
            ->where('estado', 'confirmada')
            ->get()
            ->map(function ($inscripcion) use ($profesorMateriaId) {
                $alumno = $inscripcion->alumno;

                $asistenciaHoy = Asistencia::where('profesor_materia_id', $profesorMateriaId)
                    ->where('alumno_id', $alumno->id)
                    ->where('fecha', Carbon::today())
                    ->where('tipo_carga', 'diaria')
                    ->first();

                return [
                    'id' => $alumno->id,
                    'dni' => $alumno->dni,
                    'apellido' => $alumno->apellido,
                    'nombre' => $alumno->nombre,
                    'legajo' => $alumno->legajo,
                    'asistencia_hoy' => $asistenciaHoy ? [
                        'estado' => $asistenciaHoy->estado,
                        'observaciones' => $asistenciaHoy->observaciones,
                    ] : null,
                ];
            })
            ->sortBy('apellido')
            ->values();

        return Inertia::render('Preceptor/TomarAsistencia', [
            'profesorMateria' => [
                'id' => $profesorMateria->id,
                'materia_nombre' => $profesorMateria->materiaRelacion->nombre,
                'carrera_nombre' => $profesorMateria->carreraRelacion->nombre,
                'profesor_nombre' => $profesorMateria->profesorRelacion
                    ? $profesorMateria->profesorRelacion->apellido . ', ' . $profesorMateria->profesorRelacion->nombre
                    : 'Sin profesor',
                'cursado' => $profesorMateria->cursado,
                'division' => $profesorMateria->division,
            ],
            'alumnos' => $alumnos,
            'fechaHoy' => Carbon::today()->format('Y-m-d'),
            'periodoActivo' => [
                'id' => $periodoActivo->id,
                'nombre' => $periodoActivo->nombre,
            ],
        ]);
    }

    /**
     * Guardar asistencias para una materia.
     */
    public function guardar(Request $request, $profesorMateriaId)
    {
        $this->autorizarTomarAsistencias($request);

        $periodoActivo = PeriodoInscripcion::activo();
        if (!$periodoActivo) {
            return redirect()->route('preceptor.asistencias.index')
                ->with('error', 'No hay un período activo.');
        }

        $profesorMateria = ProfesorMateria::where('periodo_id', $periodoActivo->id)
            ->findOrFail($profesorMateriaId);

        $validated = $request->validate([
            'fecha' => 'required|date',
            'asistencias' => 'required|array',
            'asistencias.*.alumno_id' => 'required|exists:tbl_alumnos,id',
            'asistencias.*.estado' => 'required|in:presente,ausente,tarde,justificada',
            'asistencias.*.observaciones' => 'nullable|string|max:500',
        ]);

        $registrados = 0;
        $actualizados = 0;

        foreach ($validated['asistencias'] as $asistenciaData) {
            $asistencia = Asistencia::updateOrCreate(
                [
                    'profesor_materia_id' => $profesorMateriaId,
                    'alumno_id' => $asistenciaData['alumno_id'],
                    'fecha' => $validated['fecha'],
                ],
                [
                    'estado' => $asistenciaData['estado'],
                    'tipo_carga' => 'diaria',
                    'observaciones' => $asistenciaData['observaciones'] ?? null,
                    'registrado_por' => $request->user()->id,
                ]
            );

            if ($asistencia->wasRecentlyCreated) {
                $registrados++;
            } else {
                $actualizados++;
            }
        }

        return redirect()
            ->route('preceptor.asistencias.index')
            ->with('success', "Asistencias registradas: {$registrados} nuevas, {$actualizados} actualizadas.");
    }

    /**
     * Historial de asistencias de una materia.
     */
    public function historial(Request $request, $profesorMateriaId)
    {
        $periodoActivo = PeriodoInscripcion::activo();

        $query = ProfesorMateria::with(['materiaRelacion', 'carreraRelacion', 'profesorRelacion']);
        if ($periodoActivo) {
            $query->where('periodo_id', $periodoActivo->id);
        }
        $profesorMateria = $query->findOrFail($profesorMateriaId);

        $fechaDesde = $request->input('fecha_desde', Carbon::now()->subMonth()->format('Y-m-d'));
        $fechaHasta = $request->input('fecha_hasta', Carbon::now()->format('Y-m-d'));

        $asistencias = Asistencia::with(['alumno'])
            ->where('profesor_materia_id', $profesorMateriaId)
            ->where('tipo_carga', 'diaria')
            ->whereBetween('fecha', [$fechaDesde, $fechaHasta])
            ->orderBy('fecha', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->fecha->format('Y-m-d');
            })
            ->map(function ($asistenciasPorFecha, $fecha) {
                $totales = ['presente' => 0, 'ausente' => 0, 'tarde' => 0, 'justificada' => 0];

                $detalles = $asistenciasPorFecha->map(function ($asistencia) use (&$totales) {
                    $totales[$asistencia->estado]++;
                    return [
                        'id' => $asistencia->id,
                        'alumno_nombre' => $asistencia->alumno->apellido . ', ' . $asistencia->alumno->nombre,
                        'alumno_dni' => $asistencia->alumno->dni,
                        'estado' => $asistencia->estado,
                        'observaciones' => $asistencia->observaciones,
                    ];
                });

                return [
                    'fecha' => $fecha,
                    'fecha_formateada' => Carbon::parse($fecha)->format('d/m/Y'),
                    'totales' => $totales,
                    'total_alumnos' => $detalles->count(),
                    'detalles' => $detalles,
                ];
            });

        return Inertia::render('Preceptor/HistorialAsistencia', [
            'profesorMateria' => [
                'id' => $profesorMateria->id,
                'materia_nombre' => $profesorMateria->materiaRelacion->nombre,
                'carrera_nombre' => $profesorMateria->carreraRelacion->nombre,
                'profesor_nombre' => $profesorMateria->profesorRelacion
                    ? $profesorMateria->profesorRelacion->apellido . ', ' . $profesorMateria->profesorRelacion->nombre
                    : 'Sin profesor',
                'cursado' => $profesorMateria->cursado,
                'division' => $profesorMateria->division,
            ],
            'asistencias' => $asistencias,
            'filtros' => [
                'fecha_desde' => $fechaDesde,
                'fecha_hasta' => $fechaHasta,
            ],
            'periodoActivo' => $periodoActivo ? [
                'id' => $periodoActivo->id,
                'nombre' => $periodoActivo->nombre,
            ] : null,
        ]);
    }
}
