<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ProfesorMateria;
use App\Models\Inscripcion;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Dashboard para profesores
        if ($user->tipo == 3) {
            return $this->dashboardProfesor($user);
        }

        // Dashboard por defecto (alumnos, admin)
        return Inertia::render('Dashboard');
    }

    private function dashboardProfesor($user)
    {
        $profesorId = $user->profesor_id;

        if (!$profesorId) {
            return Inertia::render('Dashboard', [
                'tipoUsuario' => 'profesor',
                'metricas' => null,
                'mensaje' => 'No se encontró un perfil de profesor asociado'
            ]);
        }

        // Obtener materias del profesor
        $materias = ProfesorMateria::with(['materiaRelacion', 'carreraRelacion'])
            ->where('profesor', $profesorId)
            ->get();

        $totalMaterias = $materias->count();

        // Obtener total de alumnos únicos en todas sus materias
        $alumnosIds = [];
        foreach ($materias as $materia) {
            $ids = Inscripcion::where('materia_id', $materia->materia)
                ->where('carrera_id', $materia->carrera)
                ->where('estado', 'confirmada')
                ->pluck('alumno_id')
                ->toArray();
            $alumnosIds = array_merge($alumnosIds, $ids);
        }
        $totalAlumnos = count(array_unique($alumnosIds));

        // Obtener materias con sus horarios
        $materiasConHorarios = $materias->map(function($materia) {
            $cantidadAlumnos = Inscripcion::where('materia_id', $materia->materia)
                ->where('carrera_id', $materia->carrera)
                ->where('estado', 'confirmada')
                ->count();

            return [
                'id' => $materia->id,
                'nombre' => $materia->materiaRelacion->nombre ?? 'Sin nombre',
                'carrera' => $materia->carreraRelacion->nombre ?? 'Sin carrera',
                'cursado' => $materia->cursado ?? 'N/A',
                'division' => $materia->division ?? 'N/A',
                'horario' => $materia->horario ?? 'No definido',
                'aula' => $materia->aula ?? '-',
                'cantidad_alumnos' => $cantidadAlumnos,
            ];
        });

        return Inertia::render('Dashboard', [
            'tipoUsuario' => 'profesor',
            'metricas' => [
                'total_materias' => $totalMaterias,
                'total_alumnos' => $totalAlumnos,
                'asistencias_pendientes' => 0, // TODO: Implementar
                'notas_pendientes' => 0, // TODO: Implementar
            ],
            'materias' => $materiasConHorarios
        ]);
    }
}
