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

    public function create(Request $request)
    {
        $this->autorizarCrear($request);
        $carreras = Carrera::all();
        return Inertia::render('Admin/Profesor/Create', [
            'carreras' => $carreras
        ]);
    }

    public function store(Request $request)
    {
        $this->autorizarCrear($request);

        \Log::info('Datos recibidos para crear profesor', ['datos' => $request->all()]);

        $validated = $request->validate([
            'dni'      => 'required|string|max:20|unique:tbl_profesores,dni|regex:/^[0-9]+$/',
            'apellido' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'nombre'   => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'email'    => 'nullable|email|max:255',
            'carrera'  => 'required|exists:tbl_carreras,Id',
            // Nuevo formato
            'asignaciones'              => 'nullable|array',
            'asignaciones.*.materia_id' => 'required_with:asignaciones|exists:tbl_materias,id',
            'asignaciones.*.division'   => 'required_with:asignaciones|in:1,2',
            // Formato viejo (fallback)
            'materias'   => 'nullable|array',
            'materias.*' => 'exists:tbl_materias,id',
            'division'   => 'nullable|in:1,2',
        ], [
            'dni.required'   => 'El DNI es obligatorio.',
            'dni.unique'     => 'Ya existe un profesor con este DNI.',
            'dni.regex'      => 'El DNI debe contener solo números.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.regex'    => 'El apellido solo puede contener letras y espacios.',
            'nombre.required'   => 'El nombre es obligatorio.',
            'nombre.regex'      => 'El nombre solo puede contener letras y espacios.',
            'email.email'       => 'El email debe ser una dirección válida.',
            'carrera.required'  => 'La carrera es obligatoria.',
            'carrera.exists'    => 'La carrera seleccionada no existe.',
        ]);

        try {
            \DB::beginTransaction();

            // Normalizar asignaciones: nuevo formato o viejo
            $asignaciones = $this->normalizarAsignaciones($validated);

            $primeraDiv = collect($asignaciones)->first()['division'] ?? $validated['division'] ?? '1';

            $profesor = Profesor::create([
                'dni'      => $validated['dni'],
                'apellido' => $validated['apellido'],
                'nombre'   => $validated['nombre'],
                'email'    => $validated['email'] ?? null,
                'carrera'  => $validated['carrera'],
                'division' => $primeraDiv,
            ]);

            if (!empty($asignaciones)) {
                $this->insertarAsignaciones($profesor->id, $validated['carrera'], $asignaciones);
            }

            \DB::commit();

            \Log::info('Profesor creado', [
                'profesor_id'  => $profesor->id,
                'asignaciones' => count($asignaciones),
                'creado_por'   => auth()->user()->nombre,
            ]);

            return redirect()
                ->route('expediente.index')
                ->with('success', 'Profesor creado exitosamente con ' . count($asignaciones) . ' asignación(es)');

        } catch (\Exception $e) {
            \DB::rollBack();
            $this->handleError($e, 'crear profesor', ['dni' => $validated['dni'] ?? null]);
            return back()->withInput()
                ->withErrors(['error' => $this->getFriendlyErrorMessage($e, 'Error al crear el profesor')]);
        }
    }

    public function edit(Request $request, Profesor $profesor)
    {
        $this->autorizarModificar($request);

        $carreras = Carrera::all();
        $periodoActivo = PeriodoInscripcion::activo();

        $query = \DB::table('tbl_profesor_tiene_materias')
            ->where('profesor', $profesor->id);
        if ($periodoActivo) {
            $query->where('periodo_id', $periodoActivo->id);
        }

        // Devolver asignaciones en el nuevo formato para que el modal las entienda
        $asignaciones = $query->get(['materia', 'division'])
            ->map(fn($row) => [
                'materia_id' => (int) $row->materia,
                'division'   => (string) $row->division,
            ])
            ->values()
            ->toArray();

        // También construir el array viejo de IDs para compatibilidad
        $materias = array_unique(array_column($asignaciones, 'materia_id'));

        $profesorData = $profesor->toArray();
        $profesorData['materias']    = array_values($materias);
        $profesorData['asignaciones'] = $asignaciones;

        return Inertia::render('Admin/Profesor/Edit', [
            'profesor' => $profesorData,
            'carreras' => $carreras,
        ]);
    }

    public function update(Request $request, Profesor $profesor)
    {
        $this->autorizarModificar($request);

        \Log::info('=== UPDATE PROFESOR ===', [
            'profesor_id'  => $profesor->id,
            'request_data' => $request->all(),
        ]);

        $validated = $request->validate([
            'dni'      => 'required|string|max:20|unique:tbl_profesores,dni,' . $profesor->id . ',id|regex:/^[0-9]+$/',
            'apellido' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'nombre'   => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'email'    => 'nullable|email|max:255',
            'carrera'  => 'required|exists:tbl_carreras,Id',
            'asignaciones'              => 'nullable|array',
            'asignaciones.*.materia_id' => 'required_with:asignaciones|exists:tbl_materias,id',
            'asignaciones.*.division'   => 'required_with:asignaciones|in:1,2',
            'materias'   => 'nullable|array',
            'materias.*' => 'exists:tbl_materias,id',
            'division'   => 'nullable|in:1,2',
        ], [
            'dni.required'   => 'El DNI es obligatorio.',
            'dni.unique'     => 'Ya existe otro profesor con este DNI.',
            'dni.regex'      => 'El DNI debe contener solo números.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.regex'    => 'El apellido solo puede contener letras y espacios.',
            'nombre.required'   => 'El nombre es obligatorio.',
            'nombre.regex'      => 'El nombre solo puede contener letras y espacios.',
            'email.email'       => 'El email debe ser una dirección válida.',
            'carrera.required'  => 'La carrera es obligatoria.',
        ]);

        try {
            \DB::beginTransaction();

            $asignaciones = $this->normalizarAsignaciones($validated);
            $primeraDiv   = collect($asignaciones)->first()['division'] ?? $profesor->division;

            $profesor->update([
                'dni'      => $validated['dni'],
                'apellido' => $validated['apellido'],
                'nombre'   => $validated['nombre'],
                'email'    => $validated['email'] ?? null,
                'carrera'  => $validated['carrera'],
                'division' => $primeraDiv,
            ]);

            $periodoActivo = PeriodoInscripcion::activo();

            // IDs con notas pendientes — no tocar
            $idsConNotas = \DB::table('tbl_notas_temporales')
                ->where('estado', 'pendiente')
                ->pluck('profesor_materia_id')
                ->toArray();

            $borrar = \DB::table('tbl_profesor_tiene_materias')
                ->where('profesor', $profesor->id);
            if ($periodoActivo) {
                $borrar->where('periodo_id', $periodoActivo->id);
            } else {
                $borrar->whereNull('periodo_id');
            }
            if (!empty($idsConNotas)) {
                $borrar->whereNotIn('id', $idsConNotas);
            }
            $borrar->delete();

            if (!empty($asignaciones)) {
                $this->insertarAsignaciones($profesor->id, $validated['carrera'], $asignaciones, $idsConNotas);
            }

            \DB::commit();

            \Log::info('Profesor actualizado', [
                'profesor_id'  => $profesor->id,
                'asignaciones' => count($asignaciones),
                'actualizado_por' => auth()->user()->nombre,
            ]);

            return redirect()
                ->route('expediente.index')
                ->with('success', 'Profesor actualizado con ' . count($asignaciones) . ' asignación(es)');

        } catch (\Exception $e) {
            \DB::rollBack();
            $this->handleError($e, 'actualizar profesor', ['profesor_id' => $profesor->id]);
            return back()->withInput()
                ->withErrors(['error' => $this->getFriendlyErrorMessage($e, 'Error al actualizar el profesor')]);
        }
    }

    // ── Helpers privados ──────────────────────────────────────────────────────

    /**
     * Normaliza cualquier formato de entrada a array de { materia_id, division }.
     * Acepta el nuevo formato asignaciones[] o el viejo materias[] + division.
     */
    private function normalizarAsignaciones(array $validated): array
    {
        // Si viene el nuevo formato con asignaciones, usarlo directo (deduplicado)
        if (!empty($validated['asignaciones'])) {
            return collect($validated['asignaciones'])
                ->unique(fn($a) => $a['materia_id'] . '-' . $a['division'])
                ->values()
                ->toArray();
        }

        // Formato viejo: materias[] + division única
        if (!empty($validated['materias'])) {
            $div = $validated['division'] ?? '1';
            return collect($validated['materias'])
                ->map(fn($id) => ['materia_id' => $id, 'division' => $div])
                ->toArray();
        }

        return [];
    }

    /**
     * Inserta filas en tbl_profesor_tiene_materias, evitando duplicar
     * asignaciones que ya existen (ej: tenían notas pendientes y no fueron borradas).
     */
    private function insertarAsignaciones(int $profesorId, $carreraId, array $asignaciones, array $idsExcluir = []): void
    {
        $periodoActivo = PeriodoInscripcion::activo();
        $cursado = $periodoActivo
            ? ($periodoActivo->cuatrimestre == '1' ? '1er Cuatrimestre' : '2do Cuatrimestre')
            : '1er Cuatrimestre';

        foreach ($asignaciones as $asig) {
            $existe = \DB::table('tbl_profesor_tiene_materias')
                ->where('profesor', $profesorId)
                ->where('materia', $asig['materia_id'])
                ->where('division', $asig['division'])
                ->where('periodo_id', $periodoActivo?->id)
                ->exists();

            if (!$existe) {
                \DB::table('tbl_profesor_tiene_materias')->insert([
                    'profesor'                     => $profesorId,
                    'carrera'                      => $carreraId,
                    'materia'                      => $asig['materia_id'],
                    'division'                     => $asig['division'],
                    'cursado'                      => $cursado,
                    'periodo_id'                   => $periodoActivo?->id,
                    'nota_minima_promocion'        => 7.00,
                    'nota_minima_regularidad'      => 4.00,
                    'permite_promocion'            => true,
                    'porcentaje_asistencia_minimo' => 75,
                    'configuracion_completa'       => false,
                    'asignado_por'                 => auth()->id(),
                    'created_at'                   => now(),
                    'updated_at'                   => now(),
                ]);
            }
        }
    }

    // ── Resto sin cambios ─────────────────────────────────────────────────────

    public function limpiarMaterias(Request $request, Profesor $profesor)
    {
        $this->autorizarModificar($request);

        try {
            $materiasCount = $profesor->materias()->count();

            if ($materiasCount > 0) {
                $profesor->materias()->delete();

                \Log::info('Materias de profesor limpiadas', [
                    'profesor_id'        => $profesor->id,
                    'materias_eliminadas' => $materiasCount,
                    'limpiado_por'       => auth()->user()->nombre,
                ]);

                return back()->with('success', 'Se eliminaron ' . $materiasCount . ' asignación(es). Ahora podés eliminar al profesor.');
            }

            return back()->with('info', 'El profesor no tiene materias asignadas.');

        } catch (\Exception $e) {
            $this->handleError($e, 'limpiar materias del profesor', ['profesor_id' => $profesor->id]);
            return back()->withErrors(['error' => $this->getFriendlyErrorMessage($e, 'Error al limpiar las materias')]);
        }
    }

    public function destroy(Request $request, Profesor $profesor)
    {
        $this->autorizarEliminar($request);

        try {
            if ($profesor->user) {
                return back()->withErrors([
                    'error' => 'No se puede eliminar el profesor porque tiene un usuario asociado. Primero elimine el usuario.'
                ]);
            }

            $materiasCount = $profesor->materias()->count();

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
                $validator->errors()->add('error', 'Este profesor tiene ' . $materiasCount . ' asignación(es). Debes limpiarlas antes de eliminarlo.');

                foreach ($materias as $index => $materia) {
                    $validator->errors()->add('materias.' . $index, $materia);
                }

                throw new \Illuminate\Validation\ValidationException($validator);
            }

            $dni = $profesor->dni;
            $profesor->delete();

            \Log::info('Profesor eliminado', [
                'dni'          => $dni,
                'eliminado_por' => auth()->user()->nombre,
            ]);

            return redirect()
                ->route('expediente.index')
                ->with('success', 'Profesor eliminado exitosamente');

        } catch (\Exception $e) {
            $this->handleError($e, 'eliminar profesor', ['profesor_id' => $profesor->id]);
            return back()->withErrors(['error' => $this->getFriendlyErrorMessage($e, 'Error al eliminar el profesor')]);
        }
    }
}