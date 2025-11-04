<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfesorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $carreras = Carrera::all();

        return Inertia::render('Admin/Profesor/Create', [
            'carreras' => $carreras
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Datos recibidos para crear profesor', [
            'datos' => $request->all()
        ]);

        $validated = $request->validate([
            'dni' => 'required|string|max:20|unique:tbl_profesores,dni',
            'apellido' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'carrera' => 'required|exists:tbl_carreras,Id',
            'division' => 'required|in:1,2',
            'materias' => 'nullable|array',
            'materias.*' => 'exists:tbl_materias,id',
        ], [
            'dni.required' => 'El DNI es obligatorio',
            'dni.unique' => 'Ya existe un profesor con este DNI',
            'apellido.required' => 'El apellido es obligatorio',
            'nombre.required' => 'El nombre es obligatorio',
            'carrera.required' => 'La carrera es obligatoria',
            'carrera.exists' => 'La carrera seleccionada no existe',
            'division.required' => 'La división es obligatoria',
            'division.in' => 'La división debe ser 1 o 2',
        ]);

        \Log::info('Validación exitosa', ['validated' => $validated]);

        try {
            \DB::beginTransaction();

            // Crear el profesor
            $profesor = Profesor::create([
                'dni' => $validated['dni'],
                'apellido' => $validated['apellido'],
                'nombre' => $validated['nombre'],
                'email' => $validated['email'] ?? null,
                'carrera' => $validated['carrera'],
                'division' => $validated['division'],
            ]);

            // Asignar materias si se seleccionaron
            if (!empty($validated['materias'])) {
                foreach ($validated['materias'] as $materiaId) {
                    \DB::table('tbl_profesor_tiene_materias')->insert([
                        'profesor' => $profesor->id,
                        'carrera' => $validated['carrera'],
                        'materia' => $materiaId,
                        'division' => $validated['division'],
                        'cursado' => '1er Cuatrimestre', // Valor por defecto
                        'nota_minima_promocion' => 7.00,
                        'nota_minima_regularidad' => 4.00,
                        'permite_promocion' => true,
                        'porcentaje_asistencia_minimo' => 75,
                        'configuracion_completa' => false,
                    ]);
                }
            }

            \DB::commit();

            \Log::info('Profesor creado con materias', [
                'profesor_id' => $profesor->id,
                'dni' => $profesor->dni,
                'materias_asignadas' => count($validated['materias'] ?? []),
                'creado_por' => auth()->id(),
            ]);

            return redirect()
                ->route('expediente.index')
                ->with('success', 'Profesor creado exitosamente con ' . count($validated['materias'] ?? []) . ' materia(s) asignada(s)');

        } catch (\Exception $e) {
            \DB::rollBack();

            \Log::error('Error al crear profesor', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear el profesor: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Profesor $profesor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profesor $profesor)
    {
        $carreras = Carrera::all();

        // Cargar las materias asignadas al profesor
        $materias = \DB::table('tbl_profesor_tiene_materias')
            ->where('profesor', $profesor->id)
            ->pluck('materia')
            ->toArray();

        $profesorData = $profesor->toArray();
        $profesorData['materias'] = $materias;

        return Inertia::render('Admin/Profesor/Edit', [
            'profesor' => $profesorData,
            'carreras' => $carreras
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profesor $profesor)
    {
        \Log::info('=== UPDATE PROFESOR ===', [
            'profesor_id' => $profesor->id,
            'profesor_object' => $profesor->toArray(),
            'request_data' => $request->all(),
        ]);

        $validated = $request->validate([
            'dni' => 'required|string|max:20|unique:tbl_profesores,dni,' . $profesor->id . ',id',
            'apellido' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'carrera' => 'required|exists:tbl_carreras,Id',
            'division' => 'required|in:1,2',
            'materias' => 'nullable|array',
            'materias.*' => 'exists:tbl_materias,id',
        ], [
            'dni.required' => 'El DNI es obligatorio',
            'dni.unique' => 'Ya existe otro profesor con este DNI',
            'apellido.required' => 'El apellido es obligatorio',
            'nombre.required' => 'El nombre es obligatorio',
            'carrera.required' => 'La carrera es obligatoria',
            'carrera.exists' => 'La carrera seleccionada no existe',
            'division.required' => 'La división es obligatoria',
            'division.in' => 'La división debe ser 1 o 2',
        ]);

        try {
            \DB::beginTransaction();

            // Actualizar datos del profesor
            $profesor->update([
                'dni' => $validated['dni'],
                'apellido' => $validated['apellido'],
                'nombre' => $validated['nombre'],
                'email' => $validated['email'] ?? null,
                'carrera' => $validated['carrera'],
                'division' => $validated['division'],
            ]);

            // Actualizar materias asignadas
            // Eliminar las materias actuales
            \DB::table('tbl_profesor_tiene_materias')
                ->where('profesor', $profesor->id)
                ->delete();

            // Asignar nuevas materias si se seleccionaron
            if (!empty($validated['materias'])) {
                foreach ($validated['materias'] as $materiaId) {
                    \DB::table('tbl_profesor_tiene_materias')->insert([
                        'profesor' => $profesor->id,
                        'carrera' => $validated['carrera'],
                        'materia' => $materiaId,
                        'division' => $validated['division'],
                        'cursado' => '1er Cuatrimestre',
                        'nota_minima_promocion' => 7.00,
                        'nota_minima_regularidad' => 4.00,
                        'permite_promocion' => true,
                        'porcentaje_asistencia_minimo' => 75,
                        'configuracion_completa' => false,
                    ]);
                }
            }

            \DB::commit();

            \Log::info('Profesor actualizado', [
                'profesor_id' => $profesor->id,
                'dni' => $profesor->dni,
                'materias_asignadas' => count($validated['materias'] ?? []),
                'actualizado_por' => auth()->id(),
            ]);

            return redirect()
                ->route('expediente.index')
                ->with('success', 'Profesor actualizado exitosamente con ' . count($validated['materias'] ?? []) . ' materia(s) asignada(s)');

        } catch (\Exception $e) {
            \DB::rollBack();

            \Log::error('Error al actualizar profesor', [
                'profesor_id' => $profesor->id,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al actualizar el profesor: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profesor $profesor)
    {
        try {
            // Verificar si tiene usuario asociado
            if ($profesor->user) {
                return back()->withErrors([
                    'error' => 'No se puede eliminar el profesor porque tiene un usuario asociado. Primero elimine el usuario.'
                ]);
            }

            // Verificar si tiene materias asignadas
            if ($profesor->materias()->count() > 0) {
                return back()->withErrors([
                    'error' => 'No se puede eliminar el profesor porque tiene materias asignadas.'
                ]);
            }

            $dni = $profesor->dni;
            $profesor->delete();

            \Log::info('Profesor eliminado', [
                'dni' => $dni,
                'eliminado_por' => auth()->id(),
            ]);

            return redirect()
                ->route('expediente.index')
                ->with('success', 'Profesor eliminado exitosamente');

        } catch (\Exception $e) {
            \Log::error('Error al eliminar profesor', [
                'profesor_id' => $profesor->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'error' => 'Error al eliminar el profesor: ' . $e->getMessage()
            ]);
        }
    }
}
