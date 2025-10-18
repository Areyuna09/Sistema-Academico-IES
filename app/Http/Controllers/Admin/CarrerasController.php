<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CarrerasController extends Controller
{
    /**
     * Mostrar listado de carreras
     */
    public function index(Request $request)
    {
        $query = Carrera::query();

        // Buscar por nombre o resolución
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('resolucion', 'like', "%{$buscar}%");
            });
        }

        $carreras = $query
            ->withCount('materias')
            ->withCount('alumnos')
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Carreras/Index', [
            'carreras' => $carreras,
            'filtros' => $request->only(['buscar']),
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return Inertia::render('Admin/Carreras/Create');
    }

    /**
     * Guardar nueva carrera
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:55|unique:tbl_carreras,nombre',
            'resolucion' => 'nullable|string|max:55',
        ]);

        Carrera::create($validated);

        return redirect()->route('admin.carreras.index')
            ->with('success', 'Carrera creada exitosamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Carrera $carrera)
    {
        $carrera->loadCount('materias', 'alumnos');

        return Inertia::render('Admin/Carreras/Edit', [
            'carrera' => [
                'Id' => $carrera->Id,
                'nombre' => $carrera->nombre,
                'resolucion' => $carrera->resolucion,
                'materias_count' => $carrera->materias_count,
                'alumnos_count' => $carrera->alumnos_count,
            ],
        ]);
    }

    /**
     * Actualizar carrera
     */
    public function update(Request $request, Carrera $carrera)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:55', Rule::unique('tbl_carreras')->ignore($carrera->Id, 'Id')],
            'resolucion' => 'nullable|string|max:55',
        ]);

        $carrera->update($validated);

        return redirect()->route('admin.carreras.index')
            ->with('success', 'Carrera actualizada exitosamente.');
    }

    /**
     * Eliminar carrera
     */
    public function destroy(Carrera $carrera)
    {
        // Verificar que no tenga materias o alumnos asociados
        if ($carrera->materias()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar una carrera con materias asociadas.']);
        }

        if ($carrera->alumnos()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar una carrera con alumnos asociados.']);
        }

        $carrera->delete();

        return redirect()->route('admin.carreras.index')
            ->with('success', 'Carrera eliminada exitosamente.');
    }
}
