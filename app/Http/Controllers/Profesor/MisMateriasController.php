<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\ProfesorMateria;
use App\Models\Inscripcion;
use App\Models\Asistencia;
use App\Models\NotaTemporal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MisMateriasController extends Controller
{
    public function index(Request $request)
    {
        // Verificar que sea profesor
        if ($request->user()->tipo != 3) {
            abort(403, 'No autorizado');
        }

        $profesorId = $request->user()->profesor_id;

        if (!$profesorId) {
            return Inertia::render('Profesor/MisMaterias/Index', [
                'materias' => [],
                'mensaje' => 'No se encontró el perfil de profesor asociado'
            ]);
        }

        // Obtener materias asignadas al profesor
        $materias = ProfesorMateria::with(['materiaRelacion', 'carreraRelacion'])
            ->where('profesor', $profesorId)
            ->get()
            ->map(function ($profesorMateria) {
                // Contar alumnos inscritos en esta materia
                $cantidadAlumnos = Inscripcion::where('materia_id', $profesorMateria->materia)
                    ->where('carrera_id', $profesorMateria->carrera)
                    ->where('estado', 'confirmada')
                    ->count();

                return [
                    'id' => $profesorMateria->id,
                    'materia_nombre' => $profesorMateria->materiaRelacion->nombre,
                    'carrera_nombre' => $profesorMateria->carreraRelacion->nombre,
                    'cursado' => $profesorMateria->cursado,
                    'division' => $profesorMateria->division,
                    'cantidad_alumnos' => $cantidadAlumnos,
                ];
            });

        return Inertia::render('Profesor/MisMaterias/Index', [
            'materias' => $materias
        ]);
    }

    public function show(Request $request, $profesorMateriaId)
    {
        // Verificar que sea profesor
        if ($request->user()->tipo != 3) {
            abort(403, 'No autorizado');
        }

        $profesorId = $request->user()->profesor_id;

        $profesorMateria = ProfesorMateria::with(['materiaRelacion', 'carreraRelacion'])
            ->where('id', $profesorMateriaId)
            ->where('profesor', $profesorId)
            ->firstOrFail();

        // Obtener alumnos inscritos
        $alumnos = Inscripcion::with(['alumno'])
            ->where('materia_id', $profesorMateria->materia)
            ->where('carrera_id', $profesorMateria->carrera)
            ->where('estado', 'confirmada')
            ->get()
            ->map(function ($inscripcion) {
                $alumno = $inscripcion->alumno;
                return [
                    'id' => $alumno->id,
                    'dni' => $alumno->dni,
                    'apellido' => $alumno->apellido,
                    'nombre' => $alumno->nombre,
                    'legajo' => $alumno->legajo,
                    'email' => $alumno->email,
                ];
            });

        // Obtener asistencias con porcentajes por alumno
        $asistencias = $alumnos->map(function ($alumno) use ($profesorMateriaId) {
            // Primero verificar si existe asistencia final
            $asistenciaFinal = Asistencia::where('profesor_materia_id', $profesorMateriaId)
                ->where('alumno_id', $alumno['id'])
                ->where('tipo_carga', 'final')
                ->first();

            if ($asistenciaFinal) {
                // Usar datos de asistencia final
                $presentes = $asistenciaFinal->presentes;
                $tardes = $asistenciaFinal->tardes;
                $ausentes = $asistenciaFinal->ausentes;
                $totalClases = $asistenciaFinal->total_clases;
            } else {
                // Usar asistencias diarias
                $asistenciasDiarias = Asistencia::where('profesor_materia_id', $profesorMateriaId)
                    ->where('alumno_id', $alumno['id'])
                    ->where('tipo_carga', 'diaria')
                    ->get();

                $presentes = $asistenciasDiarias->where('estado', 'presente')->count();
                $tardes = $asistenciasDiarias->where('estado', 'tarde')->count();
                $ausentes = $asistenciasDiarias->where('estado', 'ausente')->count();

                // Total de clases = días únicos con asistencia diaria
                $totalClases = Asistencia::where('profesor_materia_id', $profesorMateriaId)
                    ->where('tipo_carga', 'diaria')
                    ->distinct('fecha')
                    ->count('fecha');
            }

            // Considerar tardes como 0.5 asistencia
            $asistenciasEfectivas = $presentes + ($tardes * 0.5);
            $porcentaje = $totalClases > 0 ? round(($asistenciasEfectivas / $totalClases) * 100, 2) : 0;

            return [
                'alumno_id' => $alumno['id'],
                'alumno' => $alumno['apellido'] . ', ' . $alumno['nombre'],
                'dni' => $alumno['dni'],
                'presentes' => $presentes,
                'tardes' => $tardes,
                'ausentes' => $ausentes,
                'total_clases' => $totalClases,
                'porcentaje' => $porcentaje,
                'estado_promocion' => $porcentaje >= 80 ? 'Apto' : ($porcentaje >= 70 ? 'Regular' : 'Insuficiente'),
                'tiene_asistencia_final' => $asistenciaFinal ? true : false
            ];
        });

        // Calcular total de clases para estadísticas (usar el máximo)
        $totalClases = $asistencias->max('total_clases') ?? 0;

        // Obtener notas temporales de la materia
        $notasTemporales = NotaTemporal::with(['alumno', 'registradoPor', 'aprobadoPor'])
            ->where('profesor_materia_id', $profesorMateriaId)
            ->orderBy('fecha', 'desc')
            ->get()
            ->map(function ($nota) {
                return [
                    'id' => $nota->id,
                    'alumno_id' => $nota->alumno_id,
                    'alumno' => $nota->alumno->apellido . ', ' . $nota->alumno->nombre,
                    'dni' => $nota->alumno->dni,
                    'nota' => $nota->nota,
                    'tipo_evaluacion' => $nota->tipo_evaluacion,
                    'estado' => $nota->estado,
                    'fecha' => $nota->fecha->format('d/m/Y'),
                    'observaciones' => $nota->observaciones,
                    'registrado_por' => $nota->registradoPor->name ?? 'N/A',
                    'aprobado_por' => $nota->aprobadoPor->name ?? null,
                    'fecha_aprobacion' => $nota->fecha_aprobacion ? $nota->fecha_aprobacion->format('d/m/Y H:i') : null
                ];
            });

        // Calcular estadísticas
        $promedioAsistencia = $totalClases > 0 ? round($asistencias->avg('porcentaje'), 2) : 0;
        $totalNotasCargadas = $notasTemporales->count();

        return Inertia::render('Profesor/MisMaterias/Show', [
            'profesorMateria' => [
                'id' => $profesorMateria->id,
                'materia_nombre' => $profesorMateria->materiaRelacion->nombre,
                'carrera_nombre' => $profesorMateria->carreraRelacion->nombre,
                'cursado' => $profesorMateria->cursado,
                'division' => $profesorMateria->division,
                'horario' => $profesorMateria->horario ?? null,
                'aula' => $profesorMateria->aula ?? null,
            ],
            'alumnos' => $alumnos,
            'asistencias' => $asistencias,
            'notasTemporales' => $notasTemporales,
            'estadisticas' => [
                'total_alumnos' => $alumnos->count(),
                'promedio_asistencia' => $promedioAsistencia,
                'total_clases' => $totalClases,
                'notas_cargadas' => $totalNotasCargadas,
                'notas_pendientes' => $notasTemporales->where('estado', 'pendiente')->count(),
                'notas_aprobadas' => $notasTemporales->where('estado', 'aprobada')->count(),
            ]
        ]);
    }
}
