<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlumnoController extends Controller
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

        return Inertia::render('Admin/Alumno/Create', [
            'carreras' => $carreras
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni' => 'required|string|max:20|unique:tbl_alumnos,dni',
            'apellido' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'celular' => 'nullable|string|max:50',
            'legajo' => 'nullable|string|max:50',
            'anno' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'carrera' => 'required|exists:tbl_carreras,Id',
            'curso' => 'nullable|integer|min:1|max:6',
            'division' => 'nullable|string|max:10',
        ], [
            'dni.required' => 'El DNI es obligatorio',
            'dni.unique' => 'Ya existe un alumno con este DNI',
            'apellido.required' => 'El apellido es obligatorio',
            'nombre.required' => 'El nombre es obligatorio',
            'carrera.required' => 'La carrera es obligatoria',
            'carrera.exists' => 'La carrera seleccionada no existe',
        ]);

        // Si no se proporciona año, usar el año actual
        if (empty($validated['anno'])) {
            $validated['anno'] = date('Y');
        }

        // Agregar fecha actual si no existe
        $validated['fecha'] = now();

        try {
            $alumno = Alumno::create($validated);

            \Log::info('Alumno creado', [
                'alumno_id' => $alumno->id,
                'dni' => $alumno->dni,
                'creado_por' => auth()->id(),
            ]);

            return redirect()
                ->route('expediente.index')
                ->with('success', 'Alumno creado exitosamente');

        } catch (\Exception $e) {
            \Log::error('Error al crear alumno', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear el alumno: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Alumno $alumno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alumno $alumno)
    {
        $carreras = Carrera::all();

        return Inertia::render('Admin/Alumno/Edit', [
            'alumno' => $alumno,
            'carreras' => $carreras
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alumno $alumno)
    {
        $validated = $request->validate([
            'dni' => 'required|string|max:20|unique:tbl_alumnos,dni,' . $alumno->id,
            'apellido' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'celular' => 'nullable|string|max:50',
            'legajo' => 'nullable|string|max:50',
            'anno' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'carrera' => 'required|exists:tbl_carreras,Id',
            'curso' => 'nullable|integer|min:1|max:6',
            'division' => 'nullable|string|max:10',
        ], [
            'dni.required' => 'El DNI es obligatorio',
            'dni.unique' => 'Ya existe otro alumno con este DNI',
            'apellido.required' => 'El apellido es obligatorio',
            'nombre.required' => 'El nombre es obligatorio',
            'carrera.required' => 'La carrera es obligatoria',
            'carrera.exists' => 'La carrera seleccionada no existe',
        ]);

        // Si no se proporciona año, usar el año actual
        if (empty($validated['anno'])) {
            $validated['anno'] = date('Y');
        }

        // Mantener la fecha si existe, si no usar la actual
        if (!$alumno->fecha) {
            $validated['fecha'] = now();
        }

        try {
            $alumno->update($validated);

            \Log::info('Alumno actualizado', [
                'alumno_id' => $alumno->id,
                'dni' => $alumno->dni,
                'actualizado_por' => auth()->id(),
            ]);

            return redirect()
                ->route('expediente.index')
                ->with('success', 'Alumno actualizado exitosamente');

        } catch (\Exception $e) {
            \Log::error('Error al actualizar alumno', [
                'alumno_id' => $alumno->id,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al actualizar el alumno: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumno $alumno)
    {
        try {
            // Verificar si tiene usuario asociado
            if ($alumno->user) {
                return back()->withErrors([
                    'error' => 'No se puede eliminar el alumno porque tiene un usuario asociado. Primero elimine el usuario.'
                ]);
            }

            // Verificar si tiene materias cursadas
            if ($alumno->materiasCursadas()->count() > 0) {
                return back()->withErrors([
                    'error' => 'No se puede eliminar el alumno porque tiene materias cursadas en su legajo.'
                ]);
            }

            $dni = $alumno->dni;
            $alumno->delete();

            \Log::info('Alumno eliminado', [
                'dni' => $dni,
                'eliminado_por' => auth()->id(),
            ]);

            return redirect()
                ->route('expediente.index')
                ->with('success', 'Alumno eliminado exitosamente');

        } catch (\Exception $e) {
            \Log::error('Error al eliminar alumno', [
                'alumno_id' => $alumno->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'error' => 'Error al eliminar el alumno: ' . $e->getMessage()
            ]);
        }
    }
}
