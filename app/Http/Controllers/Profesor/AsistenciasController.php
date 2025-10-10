<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\ProfesorMateria;
use App\Models\Asistencia;
use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AsistenciasController extends Controller
{
    public function create(Request $request, $profesorMateriaId)
    {
        if ($request->user()->tipo != 3) {
            abort(403, 'No autorizado');
        }

        $profesorId = $request->user()->profesor_id;

        // CAMBIO: usar materiaRelacion y carreraRelacion
        $profesorMateria = ProfesorMateria::with(['materiaRelacion', 'carreraRelacion'])
            ->where('id', $profesorMateriaId)
            ->where('profesor', $profesorId)
            ->firstOrFail();

        $alumnos = Inscripcion::with(['alumno'])
            ->where('materia_id', $profesorMateria->materia)
            ->where('carrera_id', $profesorMateria->carrera)
            ->where('estado', 'confirmada')
            ->get()
            ->map(function ($inscripcion) use ($profesorMateriaId) {
                $alumno = $inscripcion->alumno;
                
                $asistenciaHoy = Asistencia::where('profesor_materia_id', $profesorMateriaId)
                    ->where('alumno_id', $alumno->id)
                    ->where('fecha', Carbon::today())
                    ->first();

                return [
                    'id' => $alumno->id,
                    'dni' => $alumno->dni,
                    'apellido' => $alumno->apellido,
                    'nombre' => $alumno->nombre,
                    'legajo' => $alumno->legajo,
                    'asistencia_hoy' => $asistenciaHoy ? [
                        'estado' => $asistenciaHoy->estado,
                        'observaciones' => $asistenciaHoy->observaciones
                    ] : null
                ];
            });

        return Inertia::render('Profesor/Asistencias/Create', [
            'profesorMateria' => [
                'id' => $profesorMateria->id,
                'materia_nombre' => $profesorMateria->materiaRelacion->nombre,
                'carrera_nombre' => $profesorMateria->carreraRelacion->nombre,
                'cursado' => $profesorMateria->cursado,
                'division' => $profesorMateria->division,
            ],
            'alumnos' => $alumnos,
            'fechaHoy' => Carbon::today()->format('Y-m-d')
        ]);
    }

    public function store(Request $request, $profesorMateriaId)
    {
        if ($request->user()->tipo != 3) {
            abort(403, 'No autorizado');
        }

        $profesorId = $request->user()->profesor_id;

        $profesorMateria = ProfesorMateria::where('id', $profesorMateriaId)
            ->where('profesor', $profesorId)
            ->firstOrFail();

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
                    'fecha' => $validated['fecha']
                ],
                [
                    'estado' => $asistenciaData['estado'],
                    'observaciones' => $asistenciaData['observaciones'] ?? null,
                    'registrado_por' => $request->user()->id
                ]
            );

            if ($asistencia->wasRecentlyCreated) {
                $registrados++;
            } else {
                $actualizados++;
            }
        }

        return redirect()
            ->route('profesor.asistencias.index', $profesorMateriaId)
            ->with('success', "Asistencias registradas: {$registrados} nuevas, {$actualizados} actualizadas");
    }

    public function index(Request $request, $profesorMateriaId)
    {
        if ($request->user()->tipo != 3) {
            abort(403, 'No autorizado');
        }

        $profesorId = $request->user()->profesor_id;

        // CAMBIO: usar materiaRelacion y carreraRelacion
        $profesorMateria = ProfesorMateria::with(['materiaRelacion', 'carreraRelacion'])
            ->where('id', $profesorMateriaId)
            ->where('profesor', $profesorId)
            ->firstOrFail();

        $fechaDesde = $request->input('fecha_desde', Carbon::now()->subMonth()->format('Y-m-d'));
        $fechaHasta = $request->input('fecha_hasta', Carbon::now()->format('Y-m-d'));

        $asistencias = Asistencia::with(['alumno'])
            ->where('profesor_materia_id', $profesorMateriaId)
            ->whereBetween('fecha', [$fechaDesde, $fechaHasta])
            ->orderBy('fecha', 'desc')
            ->get()
            ->groupBy(function($item) {
                return $item->fecha->format('Y-m-d');
            })
            ->map(function($asistenciasPorFecha, $fecha) {
                $totales = [
                    'presente' => 0,
                    'ausente' => 0,
                    'tarde' => 0,
                    'justificada' => 0
                ];

                $detalles = $asistenciasPorFecha->map(function($asistencia) use (&$totales) {
                    $totales[$asistencia->estado]++;
                    
                    return [
                        'id' => $asistencia->id,
                        'alumno_nombre' => $asistencia->alumno->apellido . ', ' . $asistencia->alumno->nombre,
                        'alumno_dni' => $asistencia->alumno->dni,
                        'estado' => $asistencia->estado,
                        'observaciones' => $asistencia->observaciones
                    ];
                });

                return [
                    'fecha' => $fecha,
                    'fecha_formateada' => Carbon::parse($fecha)->format('d/m/Y'),
                    'totales' => $totales,
                    'total_alumnos' => $detalles->count(),
                    'detalles' => $detalles
                ];
            });

        return Inertia::render('Profesor/Asistencias/Index', [
            'profesorMateria' => [
                'id' => $profesorMateria->id,
                'materia_nombre' => $profesorMateria->materiaRelacion->nombre,
                'carrera_nombre' => $profesorMateria->carreraRelacion->nombre,
                'cursado' => $profesorMateria->cursado,
                'division' => $profesorMateria->division,
            ],
            'asistencias' => $asistencias,
            'filtros' => [
                'fecha_desde' => $fechaDesde,
                'fecha_hasta' => $fechaHasta
            ]
        ]);
    }
}