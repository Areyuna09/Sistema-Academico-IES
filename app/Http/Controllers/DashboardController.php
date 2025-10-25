<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ProfesorMateria;
use App\Models\Inscripcion;
use App\Models\Alumno;
use App\Models\AlumnoMateria;
use App\Models\Asistencia;
use App\Models\NotaTemporal;
use App\Models\User;
use App\Models\Materia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Redirigir Directivos a su panel
        if ($user->tipo == 5) {
            return redirect()->route('directivo.index');
        }

        // Redirigir Supervisores a su panel
        if ($user->tipo == 6) {
            return redirect()->route('supervisor.index');
        }

        // Dashboard para profesores
        if ($user->tipo == 3) {
            return $this->dashboardProfesor($user);
        }

        // Dashboard para alumnos
        if ($user->tipo == 4) {
            return $this->dashboardAlumno($user);
        }

        // Dashboard para admin/bedel
        if (in_array($user->tipo, [1, 2])) {
            return $this->dashboardAdmin($user);
        }

        // Dashboard por defecto
        return Inertia::render('Dashboard', [
            'tipoUsuario' => 'default'
        ]);
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
        $materias = ProfesorMateria::where('profesor', $profesorId)->get();
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

        // Contar notas pendientes de aprobación (solo finales)
        $profesorMateriaIds = $materias->pluck('id');
        $notasPendientes = NotaTemporal::whereIn('profesor_materia_id', $profesorMateriaIds)
            ->where('estado', 'pendiente')
            ->where(function($query) {
                $query->where('tipo_evaluacion', 'like', '%final%')
                      ->orWhere('tipo_evaluacion', 'like', '%Final%')
                      ->orWhere('tipo_evaluacion', 'like', '%mesa%')
                      ->orWhere('tipo_evaluacion', 'like', '%examen%');
            })
            ->count();

        return Inertia::render('Dashboard', [
            'tipoUsuario' => 'profesor',
            'metricas' => [
                'total_materias' => $totalMaterias,
                'total_alumnos' => $totalAlumnos,
                'asistencias_pendientes' => 0, // TODO: Implementar lógica de asistencias pendientes
                'notas_pendientes' => $notasPendientes,
            ]
        ]);
    }

    private function dashboardAlumno($user)
    {
        // Obtener alumno relacionado
        $alumno = Alumno::where('dni', $user->dni)->first();

        if (!$alumno) {
            return Inertia::render('Dashboard', [
                'tipoUsuario' => 'alumno',
                'metricas' => null,
                'mensaje' => 'No se encontró información del alumno'
            ]);
        }

        // Inscripciones actuales
        $inscripcionesActuales = Inscripcion::where('alumno_id', $alumno->id)
            ->where('estado', 'confirmada')
            ->count();

        // Materias aprobadas
        $materiasAprobadas = AlumnoMateria::where('alumno', $alumno->id)
            ->where('rendida', '1')
            ->count();

        // Calcular promedio general
        $notasAprobadas = AlumnoMateria::where('alumno', $alumno->id)
            ->where('rendida', '1')
            ->whereNotNull('nota')
            ->where('nota', '>=', 4)
            ->pluck('nota');

        $promedioGeneral = $notasAprobadas->count() > 0
            ? round($notasAprobadas->avg(), 2)
            : 0;

        // Calcular porcentaje de asistencia general (aproximado)
        $inscripciones = Inscripcion::where('alumno_id', $alumno->id)
            ->where('estado', 'confirmada')
            ->get();

        $totalClases = 0;
        $totalPresentes = 0;

        foreach ($inscripciones as $inscripcion) {
            $profesorMateriaIds = \DB::table('tbl_profesor_tiene_materias')
                ->where('materia', $inscripcion->materia_id)
                ->pluck('id');

            $asistencias = Asistencia::where('alumno_id', $alumno->id)
                ->whereIn('profesor_materia_id', $profesorMateriaIds)
                ->where('tipo_carga', 'diaria')
                ->get();

            $totalClases += $asistencias->count();
            $totalPresentes += $asistencias->where('estado', 'presente')->count();
        }

        $porcentajeAsistencia = $totalClases > 0
            ? round(($totalPresentes / $totalClases) * 100, 1)
            : 0;

        return Inertia::render('Dashboard', [
            'tipoUsuario' => 'alumno',
            'metricas' => [
                'materias_cursando' => $inscripcionesActuales,
                'materias_aprobadas' => $materiasAprobadas,
                'promedio_general' => $promedioGeneral,
                'asistencia' => $porcentajeAsistencia,
            ]
        ]);
    }

    private function dashboardAdmin($user)
    {
        // Métricas para admin/bedel
        $totalUsuarios = User::where('activo', true)->count();
        $totalMaterias = Materia::count();
        $inscripcionesActivas = Inscripcion::where('estado', 'confirmada')->count();

        // Notas finales pendientes de aprobación
        $notasPendientes = NotaTemporal::where('estado', 'pendiente')
            ->where(function($query) {
                $query->where('tipo_evaluacion', 'like', '%final%')
                      ->orWhere('tipo_evaluacion', 'like', '%Final%')
                      ->orWhere('tipo_evaluacion', 'like', '%mesa%')
                      ->orWhere('tipo_evaluacion', 'like', '%examen%');
            })
            ->count();

        return Inertia::render('Dashboard', [
            'tipoUsuario' => 'admin',
            'metricas' => [
                'total_usuarios' => $totalUsuarios,
                'total_materias' => $totalMaterias,
                'inscripciones_activas' => $inscripcionesActivas,
                'notas_pendientes' => $notasPendientes,
            ]
        ]);
    }
}
