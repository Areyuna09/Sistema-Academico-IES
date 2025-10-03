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
     */
    public function index(Request $request)
    {
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
            'carrera',
            'materiasCursadas.materia'
        ])->findOrFail($id);

        // Obtener historial académico organizado
        $historialAcademico = $alumno->materiasCursadas()
            ->with('materia')
            ->where('carrera', $alumno->carrera)
            ->get()
            ->groupBy(function($item) {
                return $item->materia->anno ?? 'Sin año';
            });

        return Inertia::render('Expediente/Show', [
            'alumno' => $alumno,
            'historialAcademico' => $historialAcademico
        ]);
    }
}
