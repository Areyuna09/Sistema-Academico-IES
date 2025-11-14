<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use App\Models\Carrera;
use App\Traits\HandlesErrors;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MateriasController extends Controller
{
    use HandlesErrors;
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

        // Filtrar por cuatrimestre
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

        // Obtener duración de cada carrera basado en las materias existentes
        $duracionCarreras = [];
        foreach ($carreras as $carrera) {
            $maxAnno = Materia::where('carrera', $carrera->Id)->max('anno');
            $duracionCarreras[$carrera->Id] = $maxAnno ? (int)$maxAnno : 4; // Default 4 si no tiene materias
        }

        return Inertia::render('Admin/Materias/Index', [
            'materias' => $materias,
            'carreras' => $carreras,
            'filtros' => $request->only(['carrera', 'semestre', 'anno', 'buscar']),
            'duracionCarreras' => $duracionCarreras,
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $carreras = Carrera::orderBy('nombre')->get(['Id', 'nombre']);

        // Obtener duración de cada carrera basado en las materias existentes
        $duracionCarreras = [];
        foreach ($carreras as $carrera) {
            $maxAnno = Materia::where('carrera', $carrera->Id)->max('anno');
            $duracionCarreras[$carrera->Id] = $maxAnno ? (int)$maxAnno : 4; // Default 4 si no tiene materias
        }

        return Inertia::render('Admin/Materias/Create', [
            'carreras' => $carreras,
            'duracionCarreras' => $duracionCarreras,
        ]);
    }

    /**
     * Guardar nueva materia
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\d\-\(\)\.]+$/',
            'carrera' => 'required|exists:tbl_carreras,Id',
            'semestre' => 'required|integer|min:1|max:2',
            'anno' => 'required|integer|min:1|max:4',
            'resolucion' => 'nullable|string|max:55|regex:/^[a-zA-Z0-9\s\-\/]+$/',
        ], [
            'nombre.regex' => 'El nombre de la materia contiene caracteres no válidos.',
            'nombre.max' => 'El nombre de la materia no puede exceder 100 caracteres.',
            'semestre.required' => 'El cuatrimestre es obligatorio.',
            'semestre.min' => 'El cuatrimestre debe ser 1 o 2.',
            'semestre.max' => 'El cuatrimestre debe ser 1 o 2.',
            'anno.max' => 'El año no puede ser mayor a 4.',
            'resolucion.regex' => 'La resolución solo puede contener letras, números, espacios y guiones.',
        ]);

        try {
            // Validar que no exista ya una materia con el mismo nombre en la misma carrera
            $existe = Materia::where('nombre', $validated['nombre'])
                ->where('carrera', $validated['carrera'])
                ->exists();

            if ($existe) {
                return back()
                    ->withInput()
                    ->withErrors(['nombre' => 'Ya existe una materia con este nombre en esta carrera.']);
            }

            Materia::create($validated);

            \Log::info('Materia creada', [
                'materia' => $validated['nombre'],
                'carrera' => $validated['carrera'],
                'creado_por' => auth()->id()
            ]);

            return back()->with('success', 'Materia creada exitosamente.');

        } catch (\Exception $e) {
            $this->handleError($e, 'crear materia', [
                'nombre' => $validated['nombre'] ?? null,
                'carrera' => $validated['carrera'] ?? null
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => $this->getFriendlyErrorMessage($e, 'Error al crear la materia')]);
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Materia $materia)
    {
        $materia->load('carrera');

        $carreras = Carrera::orderBy('nombre')->get(['Id', 'nombre']);

        // Obtener duración de cada carrera basado en las materias existentes
        $duracionCarreras = [];
        foreach ($carreras as $carrera) {
            $maxAnno = Materia::where('carrera', $carrera->Id)->max('anno');
            $duracionCarreras[$carrera->Id] = $maxAnno ? (int)$maxAnno : 4; // Default 4 si no tiene materias
        }

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
            'duracionCarreras' => $duracionCarreras,
        ]);
    }

    /**
     * Actualizar materia
     */
    public function update(Request $request, Materia $materia)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\d\-\(\)\.]+$/',
            'carrera' => 'required|exists:tbl_carreras,Id',
            'semestre' => 'required|integer|min:1|max:2',
            'anno' => 'required|integer|min:1|max:4',
            'resolucion' => 'nullable|string|max:55|regex:/^[a-zA-Z0-9\s\-\/]+$/',
        ], [
            'nombre.regex' => 'El nombre de la materia contiene caracteres no válidos.',
            'nombre.max' => 'El nombre de la materia no puede exceder 100 caracteres.',
            'semestre.required' => 'El cuatrimestre es obligatorio.',
            'semestre.min' => 'El cuatrimestre debe ser 1 o 2.',
            'semestre.max' => 'El cuatrimestre debe ser 1 o 2.',
            'anno.max' => 'El año no puede ser mayor a 4.',
            'resolucion.regex' => 'La resolución solo puede contener letras, números, espacios y guiones.',
        ]);

        try {
            // Validar que no exista ya una materia con el mismo nombre en la misma carrera (excepto la actual)
            $existe = Materia::where('nombre', $validated['nombre'])
                ->where('carrera', $validated['carrera'])
                ->where('id', '!=', $materia->id)
                ->exists();

            if ($existe) {
                return back()
                    ->withInput()
                    ->withErrors(['nombre' => 'Ya existe una materia con este nombre en esta carrera.']);
            }

            $materia->update($validated);

            \Log::info('Materia actualizada', [
                'materia_id' => $materia->id,
                'nombre' => $validated['nombre'],
                'actualizado_por' => auth()->id()
            ]);

            return back()->with('success', 'Materia actualizada exitosamente.');

        } catch (\Exception $e) {
            $this->handleError($e, 'actualizar materia', [
                'materia_id' => $materia->id,
                'nombre' => $validated['nombre'] ?? null
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => $this->getFriendlyErrorMessage($e, 'Error al actualizar la materia')]);
        }
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

        // Redirigir de vuelta sin especificar ruta (para que Inertia maneje el refresh)
        return back()->with('success', 'Materia eliminada exitosamente.');
    }
}
