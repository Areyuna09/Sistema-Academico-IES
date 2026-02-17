<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use App\Models\Carrera;
use App\Models\PeriodoInscripcion;
use App\Traits\HandlesErrors;
use App\Traits\ChecksPermissions;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfesorController extends Controller
{
    use HandlesErrors, ChecksPermissions;

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Verificar permisos de creación
        $this->autorizarCrear($request);

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
        // Verificar permisos de creación
        $this->autorizarCrear($request);

        \Log::info('Datos recibidos para crear profesor', [
            'datos' => $request->all()
        ]);

        $validated = $request->validate([
            'dni' => 'required|string|max:20|unique:tbl_profesores,dni|regex:/^[0-9]+$/',
            'apellido' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'email' => 'nullable|email|max:255',
            'carrera' => 'required|exists:tbl_carreras,Id',
            'division' => 'required|in:1,2',
            'materias' => 'nullable|array',
            'materias.*' => 'exists:tbl_materias,id',
        ], [
            'dni.required' => 'El DNI es obligatorio.',
            'dni.unique' => 'Ya existe un profesor con este DNI.',
            'dni.regex' => 'El DNI debe contener solo números.',
            'dni.max' => 'El DNI no puede exceder 20 caracteres.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'apellido.max' => 'El apellido no puede exceder 100 caracteres.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'nombre.max' => 'El nombre no puede exceder 100 caracteres.',
            'email.email' => 'El email debe ser una dirección válida.',
            'carrera.required' => 'La carrera es obligatoria.',
            'carrera.exists' => 'La carrera seleccionada no existe.',
            'division.required' => 'La división es obligatoria.',
            'division.in' => 'La división debe ser 1 o 2.',
        ]);

        \Log::info('Validación exitosa', ['validated' => $validated]);

        try {
            \DB::beginTransaction();

            $profesor = Profesor::create([
                'dni' => $validated['dni'],
                'apellido' => $validated['apellido'],
                'nombre' => $validated['nombre'],
                'email' => $validated['email'] ?? null,
                'carrera' => $validated['carrera'],
                'division' => $validated['division'],
            ]);

            if (!empty($validated['materias'])) {
                $periodoActivo = PeriodoInscripcion::activo();
                $cursado = $periodoActivo
                    ? ($periodoActivo->cuatrimestre == '1' ? '1er Cuatrimestre' : '2do Cuatrimestre')
                    : '1er Cuatrimestre';

                foreach ($validated['materias'] as $materiaId) {
                    \DB::table('tbl_profesor_tiene_materias')->insert([
                        'profesor' => $profesor->id,
                        'carrera' => $validated['carrera'],
                        'materia' => $materiaId,
                        'division' => $validated['division'],
                        'cursado' => $cursado,
                        'periodo_id' => $periodoActivo?->id,
                        'nota_minima_promocion' => 7.00,
                        'nota_minima_regularidad' => 4.00,
                        'permite_promocion' => true,
                        'porcentaje_asistencia_minimo' => 75,
                        'configuracion_completa' => false,
                        'asignado_por' => auth()->id(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            \DB::commit();

            \Log::info('Profesor creado con materias', [
                'profesor_id' => $profesor->id,
                'dni' => $profesor->dni,
                'materias_asignadas' => count($validated['materias'] ?? []),
                'creado_por' => auth()->user()->nombre,
                'tipo_usuario' => auth()->user()->tipo,
            ]);

            return redirect()
                ->route('expediente.index')
                ->with('success', 'Profesor creado exitosamente con ' . count($validated['materias'] ?? []) . ' materia(s) asignada(s)');

        } catch (\Exception $e) {
            \DB::rollBack();

            $this->handleError($e, 'crear profesor', [
                'dni' => $validated['dni'] ?? null,
                'nombre' => $validated['nombre'] ?? null
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => $this->getFriendlyErrorMessage($e, 'Error al crear el profesor')]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Profesor $profesor)
    {
        // Verificar permisos de modificación
        $this->autorizarModificar($request);

        $carreras = Carrera::all();

        // Solo cargar materias del período activo para edición
        $periodoActivo = PeriodoInscripcion::activo();
        $materiasQuery = \DB::table('tbl_profesor_tiene_materias')
            ->where('profesor', $profesor->id);
        if ($periodoActivo) {
            $materiasQuery->where('periodo_id', $periodoActivo->id);
        }
        $materias = $materiasQuery->pluck('materia')->toArray();

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
        // Verificar permisos de modificación
        $this->autorizarModificar($request);

        \Log::info('=== UPDATE PROFESOR ===', [
            'profesor_id' => $profesor->id,
            'profesor_object' => $profesor->toArray(),
            'request_data' => $request->all(),
        ]);

        $validated = $request->validate([
            'dni' => 'required|string|max:20|unique:tbl_profesores,dni,' . $profesor->id . ',id|regex:/^[0-9]+$/',
            'apellido' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'email' => 'nullable|email|max:255',
            'carrera' => 'required|exists:tbl_carreras,Id',
            'division' => 'required|in:1,2',
            'materias' => 'nullable|array',
            'materias.*' => 'exists:tbl_materias,id',
        ], [
            'dni.required' => 'El DNI es obligatorio.',
            'dni.unique' => 'Ya existe otro profesor con este DNI.',
            'dni.regex' => 'El DNI debe contener solo números.',
            'dni.max' => 'El DNI no puede exceder 20 caracteres.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'apellido.max' => 'El apellido no puede exceder 100 caracteres.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'nombre.max' => 'El nombre no puede exceder 100 caracteres.',
            'email.email' => 'El email debe ser una dirección válida.',
            'carrera.required' => 'La carrera es obligatoria.',
            'carrera.exists' => 'La carrera seleccionada no existe.',
            'division.required' => 'La división es obligatoria.',
            'division.in' => 'La división debe ser 1 o 2.',
        ]);

        try {
            \DB::beginTransaction();

            $profesor->update([
                'dni' => $validated['dni'],
                'apellido' => $validated['apellido'],
                'nombre' => $validated['nombre'],
                'email' => $validated['email'] ?? null,
                'carrera' => $validated['carrera'],
                'division' => $validated['division'],
            ]);

            $periodoActivo = PeriodoInscripcion::activo();
            $cursado = $periodoActivo
                ? ($periodoActivo->cuatrimestre == '1' ? '1er Cuatrimestre' : '2do Cuatrimestre')
                : '1er Cuatrimestre';

            // Borrar asignaciones del período activo que NO tengan notas temporales asociadas
            $asignacionesABorrar = \DB::table('tbl_profesor_tiene_materias')
                ->where('profesor', $profesor->id);
            if ($periodoActivo) {
                $asignacionesABorrar->where('periodo_id', $periodoActivo->id);
            } else {
                $asignacionesABorrar->whereNull('periodo_id');
            }

            // Excluir asignaciones que tengan notas temporales pendientes
            $idsConNotas = \DB::table('tbl_notas_temporales')
                ->where('estado', 'pendiente')
                ->pluck('profesor_materia_id')
                ->toArray();

            if (!empty($idsConNotas)) {
                $asignacionesABorrar->whereNotIn('id', $idsConNotas);
            }

            $asignacionesABorrar->delete();

            if (!empty($validated['materias'])) {
                foreach ($validated['materias'] as $materiaId) {
                    \DB::table('tbl_profesor_tiene_materias')->insert([
                        'profesor' => $profesor->id,
                        'carrera' => $validated['carrera'],
                        'materia' => $materiaId,
                        'division' => $validated['division'],
                        'cursado' => $cursado,
                        'periodo_id' => $periodoActivo?->id,
                        'nota_minima_promocion' => 7.00,
                        'nota_minima_regularidad' => 4.00,
                        'permite_promocion' => true,
                        'porcentaje_asistencia_minimo' => 75,
                        'configuracion_completa' => false,
                        'asignado_por' => auth()->id(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            \DB::commit();

            \Log::info('Profesor actualizado', [
                'profesor_id' => $profesor->id,
                'dni' => $profesor->dni,
                'materias_asignadas' => count($validated['materias'] ?? []),
                'actualizado_por' => auth()->user()->nombre,
                'tipo_usuario' => auth()->user()->tipo,
            ]);

            return redirect()
                ->route('expediente.index')
                ->with('success', 'Profesor actualizado exitosamente con ' . count($validated['materias'] ?? []) . ' materia(s) asignada(s)');

        } catch (\Exception $e) {
            \DB::rollBack();

            $this->handleError($e, 'actualizar profesor', [
                'profesor_id' => $profesor->id,
                'dni' => $validated['dni'] ?? null
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => $this->getFriendlyErrorMessage($e, 'Error al actualizar el profesor')]);
        }
    }

    /**
     * Limpiar las materias asignadas a un profesor
     */
    public function limpiarMaterias(Request $request, Profesor $profesor)
    {
        // Verificar permisos de modificación
        $this->autorizarModificar($request);

        try {
            $materiasCount = $profesor->materias()->count();

            if ($materiasCount > 0) {
                $profesor->materias()->delete();

                \Log::info('Materias de profesor limpiadas', [
                    'profesor_id' => $profesor->id,
                    'dni' => $profesor->dni,
                    'materias_eliminadas' => $materiasCount,
                    'limpiado_por' => auth()->user()->nombre,
                    'tipo_usuario' => auth()->user()->tipo,
                ]);

                return back()->with('success', 'Se eliminaron ' . $materiasCount . ' asignación(es) de materias. Ahora puedes eliminar al profesor.');
            }

            return back()->with('info', 'El profesor no tiene materias asignadas.');

        } catch (\Exception $e) {
            $this->handleError($e, 'limpiar materias del profesor', [
                'profesor_id' => $profesor->id
            ]);

            return back()->withErrors([
                'error' => $this->getFriendlyErrorMessage($e, 'Error al limpiar las materias')
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Profesor $profesor)
    {
        // Solo Admin y Bedel pueden eliminar
        $this->autorizarEliminar($request);

        try {
            if ($profesor->user) {
                return back()->withErrors([
                    'error' => 'No se puede eliminar el profesor porque tiene un usuario asociado. Primero elimine el usuario.'
                ]);
            }

            $materiasCount = $profesor->materias()->count();
            $forceDelete = $request->boolean('force');

            if ($materiasCount > 0) {
                $materias = $profesor->materias()
                    ->with('materiaRelacion')
                    ->get()
                    ->map(fn($pm) => $pm->materiaRelacion?->nombre ?? 'Materia desconocida')
                    ->unique()
                    ->values()
                    ->toArray();

                $validator = \Validator::make([], []);
                $validator->errors()->add('requires_force', 'true');
                $validator->errors()->add('materias_count', $materiasCount);
                $validator->errors()->add('error', 'Este profesor tiene ' . $materiasCount . ' materia(s) asignada(s). Debes limpiar las asignaciones antes de eliminarlo.');

                foreach ($materias as $index => $materia) {
                    $validator->errors()->add('materias.' . $index, $materia);
                }

                throw new \Illuminate\Validation\ValidationException($validator);
            }

            $dni = $profesor->dni;
            $profesor->delete();

            \Log::info('Profesor eliminado', [
                'dni' => $dni,
                'eliminado_por' => auth()->user()->nombre,
                'tipo_usuario' => auth()->user()->tipo,
                'forzado' => $forceDelete,
            ]);

            return redirect()
                ->route('expediente.index')
                ->with('success', 'Profesor eliminado exitosamente');

        } catch (\Exception $e) {
            $this->handleError($e, 'eliminar profesor', [
                'profesor_id' => $profesor->id
            ]);

            return back()->withErrors([
                'error' => $this->getFriendlyErrorMessage($e, 'Error al eliminar el profesor')
            ]);
        }
    }
}