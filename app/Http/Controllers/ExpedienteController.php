<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Alumno;
use App\Models\Carrera;

class ExpedienteController extends Controller
{
    /**
     * Muestra la página principal de expedientes académicos
     * Si es un alumno, redirige a su propio expediente
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Si es alumno, mostrar solo su expediente
        if ($user->alumno_id) {
            return $this->show($user->alumno_id);
        }

        // Si es admin/docente, mostrar lista de alumnos
        $query = Alumno::with('carrera');

        // Filtro por búsqueda (nombre, apellido o DNI)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        // Filtro por carrera
        if ($request->filled('carrera')) {
            $query->where('carrera', $request->carrera);
        }

        // Filtro por división/año
        if ($request->filled('division')) {
            $query->where('curso', $request->division);
        }

        // Obtener alumnos paginados
        $alumnos = $query->orderBy('apellido', 'asc')
                        ->orderBy('nombre', 'asc')
                        ->paginate(15)
                        ->withQueryString();

        // Obtener todas las carreras para los filtros
        $carreras = Carrera::all();

        return Inertia::render('Expediente/Index', [
            'alumnos' => $alumnos,
            'carreras' => $carreras,
            'filters' => $request->only(['search', 'carrera', 'division'])
        ]);
    }

    /**
     * Muestra el detalle del expediente de un alumno
     */
    public function show($id)
    {
        $alumno = Alumno::with([
            'carreraRelacion',
        ])->findOrFail($id);

        // Obtener historial académico con todas las materias
        $materiasAlumno = $alumno->materiasCursadas()
            ->with('materia')
            ->where('carrera', $alumno->carrera)
            ->get();

        // Agrupar por año y preparar datos
        $historialPorAnio = $materiasAlumno->groupBy(function($item) {
            $materiaObj = $item->getRelation('materia');
            return $materiaObj ? $materiaObj->anno : 0;
        })->map(function($materias, $anno) {
            return $materias->map(function($alumnoMateria) {
                $materiaObj = $alumnoMateria->getRelation('materia');

                return [
                    'id' => $alumnoMateria->Id,
                    'materia_id' => $alumnoMateria->materia,
                    'materia_nombre' => $materiaObj ? $materiaObj->nombre : 'Materia no encontrada',
                    'anno' => $materiaObj ? $materiaObj->anno : 0,
                    // Estado
                    'cursada' => $alumnoMateria->cursada === '1',
                    'rendida' => $alumnoMateria->rendida === '1',
                    'libre' => $alumnoMateria->libre == 1,
                    'equivalencia' => $alumnoMateria->equivalencia == 1,
                    // Notas
                    'nota_final' => $alumnoMateria->nota,
                    'calificacion_cursada' => $alumnoMateria->{'calificacion-cursada'},
                    'calificacion_rendida' => $alumnoMateria->calificacion_rendida,
                    // Datos adicionales
                    'libro' => $alumnoMateria->libro,
                    'folio' => $alumnoMateria->folio,
                    'fecha' => $alumnoMateria->fecha ? $alumnoMateria->fecha->format('d/m/Y') : null,
                ];
            })->sortBy('materia_nombre')->values();
        })->sortKeys();

        // Calcular estadísticas
        $totalMaterias = $materiasAlumno->count();
        $materiasAprobadas = $materiasAlumno->filter(fn($m) => $m->rendida === '1')->count();
        $materiasRegulares = $materiasAlumno->filter(fn($m) => $m->cursada === '1' && $m->rendida !== '1')->count();
        $materiasPendientes = $totalMaterias - $materiasAprobadas - $materiasRegulares;

        // Promedio de notas aprobadas
        $notasAprobadas = $materiasAlumno->filter(fn($m) => $m->rendida === '1' && $m->nota !== null);
        $promedio = $notasAprobadas->count() > 0 ? round($notasAprobadas->avg('nota'), 2) : 0;

        return Inertia::render('Expediente/Show', [
            'alumno' => [
                'id' => $alumno->id,
                'nombre_completo' => $alumno->nombre_completo,
                'dni' => $alumno->dni,
                'legajo' => $alumno->legajo,
                'email' => $alumno->email,
                'carrera' => $alumno->carreraRelacion ? $alumno->carreraRelacion->nombre : 'Sin carrera',
                'curso' => $alumno->curso,
            ],
            'historialPorAnio' => $historialPorAnio,
            'estadisticas' => [
                'total_materias' => $totalMaterias,
                'aprobadas' => $materiasAprobadas,
                'regulares' => $materiasRegulares,
                'pendientes' => $materiasPendientes,
                'promedio' => $promedio,
                'porcentaje_avance' => $totalMaterias > 0 ? round(($materiasAprobadas / $totalMaterias) * 100, 1) : 0,
            ],
        ]);
    }
}
