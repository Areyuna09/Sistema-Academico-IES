<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class MateriasController extends Controller
{
    /**
     * Mostrar listado de materias
     */
    public function index(Request $request)
    {
        $query = Materia::with('carrera');

        // Filtrar por carrera
        if ($request->filled('carrera')) {
            $query->where('carrera', $request->carrera);
        }

        // Filtrar por semestre
        if ($request->filled('semestre')) {
            $query->where('semestre', $request->semestre);
        }

        // Filtrar por año
        if ($request->filled('anno')) {
            $query->where('anno', $request->anno);
        }

        // Buscar por nombre
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', "%{$request->buscar}%");
        }

        $materias = $query
            ->orderBy('carrera')
            ->orderBy('anno')
            ->orderBy('semestre')
            ->orderBy('nombre')
            ->paginate(20)
            ->withQueryString();

        // Obtener carreras para el filtro
        $carreras = Carrera::orderBy('nombre')->get(['Id', 'nombre']);

        return Inertia::render('Admin/Materias/Index', [
            'materias' => $materias,
            'carreras' => $carreras,
            'filtros' => $request->only(['carrera', 'semestre', 'anno', 'buscar']),
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $carreras = Carrera::orderBy('nombre')->get(['Id', 'nombre']);

        return Inertia::render('Admin/Materias/Create', [
            'carreras' => $carreras,
        ]);
    }

    /**
     * Guardar nueva materia
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'carrera' => 'required|exists:tbl_carreras,Id',
            'semestre' => 'required|integer|min:1|max:2',
            'anno' => 'required|integer|min:1|max:6',
            'resolucion' => 'nullable|string|max:55',
        ]);

        // Validar que no exista ya una materia con el mismo nombre en la misma carrera
        $existe = Materia::where('nombre', $validated['nombre'])
            ->where('carrera', $validated['carrera'])
            ->exists();

        if ($existe) {
            return back()->withErrors(['nombre' => 'Ya existe una materia con este nombre en esta carrera.']);
        }

        Materia::create($validated);

        return redirect()->route('admin.materias.index')
            ->with('success', 'Materia creada exitosamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Materia $materia)
    {
        $materia->load('carrera');

        $carreras = Carrera::orderBy('nombre')->get(['Id', 'nombre']);

        return Inertia::render('Admin/Materias/Edit', [
            'materia' => [
                'id' => $materia->id,
                'nombre' => $materia->nombre,
                'carrera' => $materia->carrera,
                'semestre' => $materia->semestre,
                'anno' => $materia->anno,
                'resolucion' => $materia->resolucion,
                'carrera_nombre' => $materia->carrera->nombre ?? null,
            ],
            'carreras' => $carreras,
        ]);
    }

    /**
     * Actualizar materia
     */
    public function update(Request $request, Materia $materia)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'carrera' => 'required|exists:tbl_carreras,Id',
            'semestre' => 'required|integer|min:1|max:2',
            'anno' => 'required|integer|min:1|max:6',
            'resolucion' => 'nullable|string|max:55',
        ]);

        // Validar que no exista ya una materia con el mismo nombre en la misma carrera (excepto la actual)
        $existe = Materia::where('nombre', $validated['nombre'])
            ->where('carrera', $validated['carrera'])
            ->where('id', '!=', $materia->id)
            ->exists();

        if ($existe) {
            return back()->withErrors(['nombre' => 'Ya existe una materia con este nombre en esta carrera.']);
        }

        $materia->update($validated);

        return redirect()->route('admin.materias.index')
            ->with('success', 'Materia actualizada exitosamente.');
    }

    /**
     * Eliminar materia
     */
    public function destroy(Materia $materia)
    {
        // Verificar que no tenga correlativas configuradas
        if ($materia->reglasCorrelativas()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar una materia con reglas de correlativas configuradas.']);
        }

        // Verificar que no tenga alumnos que la hayan cursado
        if ($materia->alumnosMaterias()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar una materia con alumnos que la hayan cursado.']);
        }

        $materia->delete();

        return redirect()->route('admin.materias.index')
            ->with('success', 'Materia eliminada exitosamente.');
    }
}
