<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrera;
use App\Models\Materia;
use App\Models\PlanEstudio;
use App\Models\PlanEstudioMateria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PlanesEstudioController extends Controller
{
    /**
     * Dashboard: lista de todas las carreras con sus planes
     */
    public function index()
    {
        $carreras = Carrera::query()
            ->with(['planesEstudio' => function ($query) {
                $query->withCount('materias')
                    ->orderBy('activo', 'desc')
                    ->orderBy('anio', 'desc');
            }])
            ->withCount(['materias', 'alumnos'])
            ->orderBy('nombre')
            ->get()
            ->map(function ($carrera) {
                return [
                    'id' => $carrera->Id,
                    'nombre' => $carrera->nombre,
                    'resolucion' => $carrera->resolucion,
                    'materias_count' => $carrera->materias_count,
                    'alumnos_count' => $carrera->alumnos_count,
                    'planes' => $carrera->planesEstudio->map(function ($plan) {
                        return [
                            'id' => $plan->id,
                            'nombre' => $plan->nombre,
                            'anio' => $plan->anio,
                            'resolucion' => $plan->resolucion,
                            'activo' => $plan->activo,
                            'vigente' => $plan->vigente,
                            'descripcion' => $plan->descripcion,
                            'materias_count' => $plan->materias_count,
                            'created_at' => $plan->created_at ? $plan->created_at->format('d/m/Y') : null,
                            'updated_at' => $plan->updated_at ? $plan->updated_at->format('d/m/Y') : null,
                        ];
                    }),
                ];
            });

        return Inertia::render('Admin/PlanesEstudio/Index', [
            'carreras' => $carreras,
        ]);
    }

    /**
     * Crear nuevo plan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'carrera_id' => 'required|exists:tbl_carreras,Id',
            'nombre' => 'required|string|max:100',
            'anio' => 'required|integer|min:1900|max:' . (date('Y') + 10),
            'resolucion' => 'nullable|string|max:255',
            'activo' => 'boolean',
            'vigente' => 'boolean',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $carrera = Carrera::findOrFail($validated['carrera_id']);

            // Si se marca como vigente, marcar otros como no vigentes
            if ($validated['vigente'] ?? false) {
                PlanEstudio::where('carrera_id', $carrera->Id)
                    ->update(['vigente' => false]);
            }

            $plan = $carrera->planesEstudio()->create([
                'nombre' => $validated['nombre'],
                'anio' => $validated['anio'],
                'resolucion' => $validated['resolucion'] ?? null,
                'activo' => $validated['activo'] ?? false,
                'vigente' => $validated['vigente'] ?? false,
                'descripcion' => $validated['descripcion'] ?? null,
            ]);

            Log::info('Plan de estudio creado', [
                'plan_id' => $plan->id,
                'carrera_id' => $carrera->Id,
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return back()->with('success', "Plan \"{$plan->nombre}\" creado exitosamente.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear plan', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al crear el plan.']);
        }
    }

    /**
     * Actualizar plan existente
     */
    public function update(Request $request, PlanEstudio $plan)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'anio' => 'required|integer|min:1900|max:' . (date('Y') + 10),
            'resolucion' => 'nullable|string|max:255',
            'activo' => 'boolean',
            'vigente' => 'boolean',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Si se marca como vigente, marcar otros como no vigentes
            if (($validated['vigente'] ?? false) && !$plan->vigente) {
                PlanEstudio::where('carrera_id', $plan->carrera_id)
                    ->where('id', '!=', $plan->id)
                    ->update(['vigente' => false]);
            }

            $plan->update($validated);

            Log::info('Plan de estudio actualizado', [
                'plan_id' => $plan->id,
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return back()->with('success', "Plan \"{$plan->nombre}\" actualizado exitosamente.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar plan', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al actualizar el plan.']);
        }
    }

    /**
     * Eliminar plan
     */
    public function destroy(PlanEstudio $plan)
    {
        // Validación: No tiene alumnos asignados (futuro)
        // if (method_exists($plan, 'alumnos') && $plan->alumnos()->count() > 0) {
        //     return back()->withErrors([
        //         'error' => "No se puede eliminar el plan porque tiene alumnos asignados."
        //     ]);
        // }

        DB::beginTransaction();
        try {
            $nombrePlan = $plan->nombre;
            $plan->delete();

            Log::info('Plan de estudio eliminado', [
                'plan_id' => $plan->id,
                'nombre' => $nombrePlan,
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return back()->with('success', "Plan \"$nombrePlan\" eliminado correctamente.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar plan', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al eliminar el plan.']);
        }
    }

    /**
     * Clonar plan (copiar materias)
     */
    public function clonar(Request $request, PlanEstudio $plan)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'anio' => 'required|integer|min:1900|max:' . (date('Y') + 10),
        ]);

        DB::beginTransaction();
        try {
            // Crear nuevo plan (archivado por defecto)
            $nuevoPlan = PlanEstudio::create([
                'carrera_id' => $plan->carrera_id,
                'nombre' => $validated['nombre'],
                'anio' => $validated['anio'],
                'resolucion' => $plan->resolucion,
                'activo' => false,
                'descripcion' => "Clonado de: {$plan->nombre}",
            ]);

            // Copiar materias con su orden
            $materias = $plan->materiasPivot()->get(['materia_id', 'orden']);
            foreach ($materias as $materia) {
                PlanEstudioMateria::create([
                    'plan_estudio_id' => $nuevoPlan->id,
                    'materia_id' => $materia->materia_id,
                    'orden' => $materia->orden,
                ]);
            }

            Log::info('Plan clonado', [
                'plan_original_id' => $plan->id,
                'nuevo_plan_id' => $nuevoPlan->id,
                'materias_copiadas' => $materias->count(),
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return back()->with('success', "Plan \"{$nuevoPlan->nombre}\" clonado exitosamente con {$materias->count()} materias.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al clonar plan', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al clonar el plan.']);
        }
    }

    /**
     * Toggle activo/archivado
     * Ahora permite múltiples planes activos simultáneamente
     */
    public function toggleActivo(PlanEstudio $plan)
    {
        DB::beginTransaction();
        try {
            $nuevoEstado = !$plan->activo;
            $plan->update(['activo' => $nuevoEstado]);

            $mensaje = $nuevoEstado
                ? "Plan \"{$plan->nombre}\" activado."
                : "Plan \"{$plan->nombre}\" archivado.";

            Log::info('Estado activo de plan cambiado', [
                'plan_id' => $plan->id,
                'nuevo_estado' => $nuevoEstado ? 'activo' : 'archivado',
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return back()->with('success', $mensaje);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al cambiar estado del plan', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al cambiar el estado del plan.']);
        }
    }

    /**
     * Toggle vigente (plan por defecto para nuevos inscriptos)
     * Solo puede haber un plan vigente por carrera
     */
    public function toggleVigente(PlanEstudio $plan)
    {
        DB::beginTransaction();
        try {
            if ($plan->vigente) {
                // Marcar como no vigente
                $plan->update(['vigente' => false]);
                $mensaje = "Plan \"{$plan->nombre}\" ya no es el vigente para nuevos inscriptos.";
            } else {
                // Marcar como vigente y quitar vigencia a otros
                PlanEstudio::where('carrera_id', $plan->carrera_id)
                    ->where('id', '!=', $plan->id)
                    ->update(['vigente' => false]);

                $plan->update(['vigente' => true]);
                $mensaje = "Plan \"{$plan->nombre}\" marcado como vigente para nuevos inscriptos.";
            }

            Log::info('Estado vigente de plan cambiado', [
                'plan_id' => $plan->id,
                'nuevo_estado' => $plan->vigente ? 'vigente' : 'no vigente',
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return back()->with('success', $mensaje);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al cambiar vigencia del plan', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al cambiar la vigencia del plan.']);
        }
    }

    /**
     * Obtener detalles de un plan (para editar)
     */
    public function show(PlanEstudio $plan)
    {
        return response()->json([
            'id' => $plan->id,
            'carrera_id' => $plan->carrera_id,
            'nombre' => $plan->nombre,
            'anio' => $plan->anio,
            'resolucion' => $plan->resolucion,
            'activo' => $plan->activo,
            'vigente' => $plan->vigente,
            'descripcion' => $plan->descripcion,
        ]);
    }

    /**
     * Obtener materias del plan con materias disponibles de la carrera
     */
    public function getMaterias(PlanEstudio $plan)
    {
        // Materias del plan con su orden
        $materiasDelPlan = $plan->materias()
            ->orderBy('tbl_materias.anno')
            ->orderBy('tbl_materias.semestre')
            ->orderBy('tbl_plan_estudio_materias.orden')
            ->get()
            ->map(function ($materia) {
                return [
                    'id' => $materia->id,
                    'nombre' => $materia->nombre,
                    'anio' => $materia->anno,
                    'cuatrimestre' => $materia->semestre,
                    'orden' => $materia->pivot->orden ?? 0,
                ];
            });

        // Materias disponibles de la carrera que NO están en el plan
        $materiasEnPlanIds = $materiasDelPlan->pluck('id')->toArray();
        $materiasDisponibles = Materia::where('carrera', $plan->carrera_id)
            ->whereNotIn('id', $materiasEnPlanIds)
            ->select(['id', 'nombre', 'anno', 'semestre'])
            ->orderBy('anno')
            ->orderBy('semestre')
            ->orderBy('nombre')
            ->get()
            ->map(function ($materia) {
                return [
                    'id' => $materia->id,
                    'nombre' => $materia->nombre,
                    'anio' => $materia->anno,
                    'cuatrimestre' => $materia->semestre,
                ];
            });

        Log::info('Materias del plan cargadas', [
            'plan_id' => $plan->id,
            'materias_count' => $materiasDelPlan->count(),
            'disponibles_count' => $materiasDisponibles->count(),
        ]);

        return response()->json([
            'plan' => [
                'id' => $plan->id,
                'nombre' => $plan->nombre,
                'carrera_id' => $plan->carrera_id,
            ],
            'materias' => $materiasDelPlan,
            'materiasDisponibles' => $materiasDisponibles,
        ]);
    }

    /**
     * Agregar materia al plan
     */
    public function agregarMateria(Request $request, PlanEstudio $plan)
    {
        $validated = $request->validate([
            'materia_id' => 'required|exists:tbl_materias,id',
        ]);

        DB::beginTransaction();
        try {
            // Verificar que la materia pertenece a la carrera del plan
            $materia = Materia::findOrFail($validated['materia_id']);
            if ((int)$materia->carrera !== (int)$plan->carrera_id) {
                return back()->withErrors(['error' => 'La materia no pertenece a la carrera del plan.']);
            }

            // Verificar que no está ya en el plan
            if ($plan->materias()->where('materia_id', $validated['materia_id'])->exists()) {
                return back()->withErrors(['error' => 'La materia ya está en el plan.']);
            }

            // Obtener el siguiente orden
            $maxOrden = $plan->materiasPivot()->max('orden') ?? 0;

            PlanEstudioMateria::create([
                'plan_estudio_id' => $plan->id,
                'materia_id' => $validated['materia_id'],
                'orden' => $maxOrden + 1,
            ]);

            Log::info('Materia agregada al plan', [
                'plan_id' => $plan->id,
                'materia_id' => $validated['materia_id'],
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return back()->with('success', 'Materia agregada al plan correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al agregar materia al plan', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al agregar la materia.']);
        }
    }

    /**
     * Eliminar materia del plan (solo la relación)
     */
    public function quitarMateria(PlanEstudio $plan, Materia $materia)
    {
        DB::beginTransaction();
        try {
            $plan->materias()->detach($materia->id);

            Log::info('Materia quitada del plan', [
                'plan_id' => $plan->id,
                'materia_id' => $materia->id,
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return back()->with('success', 'Materia quitada del plan correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al quitar materia del plan', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al quitar la materia.']);
        }
    }

    /**
     * Reemplazar una materia por otra en el plan
     */
    public function reemplazarMateria(Request $request, PlanEstudio $plan)
    {
        $validated = $request->validate([
            'materia_actual_id' => 'required|exists:tbl_materias,id',
            'materia_nueva_id' => 'required|exists:tbl_materias,id',
        ]);

        DB::beginTransaction();
        try {
            // Verificar que la materia nueva pertenece a la carrera
            $materiaNueva = Materia::findOrFail($validated['materia_nueva_id']);
            if ((int)$materiaNueva->carrera !== (int)$plan->carrera_id) {
                return back()->withErrors(['error' => 'La materia no pertenece a la carrera del plan.']);
            }

            // Obtener el orden de la materia actual
            $pivot = $plan->materiasPivot()
                ->where('materia_id', $validated['materia_actual_id'])
                ->first();

            if (!$pivot) {
                return back()->withErrors(['error' => 'La materia actual no está en el plan.']);
            }

            $orden = $pivot->orden;

            // Eliminar la materia actual
            $plan->materias()->detach($validated['materia_actual_id']);

            // Agregar la materia nueva con el mismo orden
            PlanEstudioMateria::create([
                'plan_estudio_id' => $plan->id,
                'materia_id' => $validated['materia_nueva_id'],
                'orden' => $orden,
            ]);

            Log::info('Materia reemplazada en el plan', [
                'plan_id' => $plan->id,
                'materia_actual_id' => $validated['materia_actual_id'],
                'materia_nueva_id' => $validated['materia_nueva_id'],
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return back()->with('success', 'Materia reemplazada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al reemplazar materia', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al reemplazar la materia.']);
        }
    }
}
