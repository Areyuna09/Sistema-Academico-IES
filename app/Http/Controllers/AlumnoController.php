<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Traits\HandlesErrors;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlumnoController extends Controller
{
    use HandlesErrors;
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
            'dni' => 'required|string|min:7|max:20|unique:tbl_alumnos,dni|regex:/^[0-9]+$/',
            'apellido' => 'required|string|min:2|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\'\-]+$/',
            'nombre' => 'required|string|min:2|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\'\-]+$/',
            'email' => 'nullable|email|max:255|unique:tbl_alumnos,email',
            'telefono' => 'nullable|string|max:50|regex:/^[0-9\s\-\+\(\)]*$/',
            'celular' => 'nullable|string|min:8|max:50|regex:/^[0-9\s\-\+\(\)]*$/',
            'legajo' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\-\/]+$/',
            'anno' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'carrera' => 'required|exists:tbl_carreras,Id',
            'curso' => 'nullable|integer|min:1|max:6',
            'division' => 'nullable|string|max:10|regex:/^[a-zA-Z0-9]+$/',
        ], [
            'dni.required' => 'El DNI es obligatorio.',
            'dni.unique' => 'Ya existe un alumno con este DNI.',
            'dni.regex' => 'El DNI debe contener solo números.',
            'dni.min' => 'El DNI debe tener al menos 7 dígitos.',
            'dni.max' => 'El DNI no puede exceder 20 caracteres.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.regex' => 'El apellido solo puede contener letras, espacios, apóstrofes y guiones.',
            'apellido.min' => 'El apellido debe tener al menos 2 caracteres.',
            'apellido.max' => 'El apellido no puede exceder 100 caracteres.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras, espacios, apóstrofes y guiones.',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre no puede exceder 100 caracteres.',
            'email.email' => 'El email debe ser una dirección válida.',
            'email.unique' => 'Ya existe un alumno con este email.',
            'telefono.regex' => 'El teléfono solo puede contener números, espacios, guiones y paréntesis.',
            'celular.regex' => 'El celular solo puede contener números, espacios, guiones y paréntesis.',
            'celular.min' => 'El celular debe tener al menos 8 dígitos.',
            'legajo.regex' => 'El legajo solo puede contener letras, números, guiones y barras.',
            'carrera.required' => 'La carrera es obligatoria.',
            'carrera.exists' => 'La carrera seleccionada no existe.',
            'curso.min' => 'El curso debe ser al menos 1.',
            'curso.max' => 'El curso no puede ser mayor a 6.',
            'division.regex' => 'La división solo puede contener letras y números.',
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
            $this->handleError($e, 'crear alumno', [
                'dni' => $validated['dni'] ?? null,
                'nombre' => $validated['nombre'] ?? null
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => $this->getFriendlyErrorMessage($e, 'Error al crear el alumno')]);
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
            'dni' => 'required|string|max:20|unique:tbl_alumnos,dni,' . $alumno->id . '|regex:/^[0-9]+$/',
            'apellido' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50|regex:/^[0-9\s\-\+\(\)]*$/',
            'celular' => 'nullable|string|max:50|regex:/^[0-9\s\-\+\(\)]*$/',
            'legajo' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\-\/]+$/',
            'anno' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'carrera' => 'required|exists:tbl_carreras,Id',
            'curso' => 'nullable|integer|min:1|max:6',
            'division' => 'nullable|string|max:10|regex:/^[a-zA-Z0-9]+$/',
        ], [
            'dni.required' => 'El DNI es obligatorio.',
            'dni.unique' => 'Ya existe otro alumno con este DNI.',
            'dni.regex' => 'El DNI debe contener solo números.',
            'dni.max' => 'El DNI no puede exceder 20 caracteres.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'apellido.max' => 'El apellido no puede exceder 100 caracteres.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'nombre.max' => 'El nombre no puede exceder 100 caracteres.',
            'email.email' => 'El email debe ser una dirección válida.',
            'telefono.regex' => 'El teléfono solo puede contener números, espacios, guiones y paréntesis.',
            'celular.regex' => 'El celular solo puede contener números, espacios, guiones y paréntesis.',
            'legajo.regex' => 'El legajo solo puede contener letras, números, guiones y barras.',
            'carrera.required' => 'La carrera es obligatoria.',
            'carrera.exists' => 'La carrera seleccionada no existe.',
            'division.regex' => 'La división solo puede contener letras y números.',
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
            $this->handleError($e, 'actualizar alumno', [
                'alumno_id' => $alumno->id,
                'dni' => $validated['dni'] ?? null
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => $this->getFriendlyErrorMessage($e, 'Error al actualizar el alumno')]);
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
            $this->handleError($e, 'eliminar alumno', [
                'alumno_id' => $alumno->id
            ]);

            return back()->withErrors([
                'error' => $this->getFriendlyErrorMessage($e, 'Error al eliminar el alumno')
            ]);
        }
    }
}
