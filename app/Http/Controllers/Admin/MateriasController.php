<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use App\Models\Carrera;
use App\Models\PlanEstudio;
use App\Models\PlanEstudioMateria;
use App\Traits\HandlesErrors;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MateriasController extends Controller
{
    use HandlesErrors, \App\Traits\ChecksPermissions;

    /**
     * Mostrar listado de materias
     */
    public function index(Request $request)
    {
        $query = Materia::with('carrera')
            ->withCount('alumnosMaterias as alumnos_count');

        if ($request->filled('carrera')) {
            $query->where('carrera', $request->carrera);
        }
        if ($request->filled('semestre')) {
            $query->where('semestre', $request->semestre);
        }
        if ($request->filled('anno')) {
            $query->where('anno', $request->anno);
        }
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

        $carreras = Carrera::orderBy('nombre')->get(['Id', 'nombre']);

        $duracionCarreras = [];
        foreach ($carreras as $carrera) {
            $maxAnno = Materia::where('carrera', $carrera->Id)->max('anno');
            $duracionCarreras[$carrera->Id] = $maxAnno ? (int)$maxAnno : 4;
        }

        return Inertia::render('Admin/Materias/Index', [
            'materias'        => $materias,
            'carreras'        => $carreras,
            'filtros'         => $request->only(['carrera', 'semestre', 'anno', 'buscar']),
            'duracionCarreras' => $duracionCarreras,
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $carreras = Carrera::orderBy('nombre')->get(['Id', 'nombre']);

        $duracionCarreras = [];
        foreach ($carreras as $carrera) {
            $maxAnno = Materia::where('carrera', $carrera->Id)->max('anno');
            $duracionCarreras[$carrera->Id] = $maxAnno ? (int)$maxAnno : 4;
        }

        return Inertia::render('Admin/Materias/Create', [
            'carreras'        => $carreras,
            'duracionCarreras' => $duracionCarreras,
        ]);
    }

    /**
     * Guardar nueva materia.
     *
     * NO se agrega automáticamente a ningún plan de estudio.
     * El administrador asigna las materias a los planes manualmente
     * desde Parámetros → Planes de Estudio → Ver Materias.
     */
    public function store(Request $request)
    {
        $this->autorizarCrear($request);

        $validated = $request->validate([
            'nombre'     => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\d\-\(\)\.]+$/',
            'carrera'    => 'required|exists:tbl_carreras,Id',
            'semestre'   => 'required|integer|min:1|max:2',
            'anno'       => 'required|integer|min:1|max:4',
            'resolucion' => 'nullable|string|max:55|regex:/^[a-zA-Z0-9\s\-\/]+$/',
        ], [
            'nombre.regex'    => 'El nombre de la materia contiene caracteres no válidos.',
            'nombre.max'      => 'El nombre de la materia no puede exceder 100 caracteres.',
            'semestre.required' => 'El cuatrimestre es obligatorio.',
            'semestre.min'    => 'El cuatrimestre debe ser 1 o 2.',
            'semestre.max'    => 'El cuatrimestre debe ser 1 o 2.',
            'anno.max'        => 'El año no puede ser mayor a 4.',
            'resolucion.regex' => 'La resolución solo puede contener letras, números, espacios y guiones.',
        ]);

        try {
            $existe = Materia::where('nombre', $validated['nombre'])
                ->where('carrera', $validated['carrera'])
                ->exists();

            if ($existe) {
                return back()
                    ->withInput()
                    ->withErrors(['nombre' => 'Ya existe una materia con este nombre en esta carrera.']);
            }

            $materia = Materia::create($validated);

            \Log::info('Materia creada', [
                'materia_id'  => $materia->id,
                'materia'     => $validated['nombre'],
                'carrera'     => $validated['carrera'],
                'creado_por'  => auth()->id(),
            ]);

            return back()->with('success', 'Materia creada exitosamente. Recordá asignarla al plan de estudio correspondiente desde Parámetros → Planes de Estudio.');

        } catch (\Exception $e) {
            $this->handleError($e, 'crear materia', [
                'nombre'  => $validated['nombre'] ?? null,
                'carrera' => $validated['carrera'] ?? null,
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

        $duracionCarreras = [];
        foreach ($carreras as $carrera) {
            $maxAnno = Materia::where('carrera', $carrera->Id)->max('anno');
            $duracionCarreras[$carrera->Id] = $maxAnno ? (int)$maxAnno : 4;
        }

        return Inertia::render('Admin/Materias/Edit', [
            'materia' => [
                'id'             => $materia->id,
                'nombre'         => $materia->nombre,
                'carrera'        => $materia->carrera,
                'semestre'       => $materia->semestre,
                'anno'           => $materia->anno,
                'resolucion'     => $materia->resolucion,
                'carrera_nombre' => $materia->carrera->nombre ?? null,
            ],
            'carreras'        => $carreras,
            'duracionCarreras' => $duracionCarreras,
        ]);
    }

    /**
     * Actualizar materia.
     *
     * Si cambia la carrera, se eliminan las asignaciones a planes de la
     * carrera anterior. NO se agrega automáticamente a ningún plan nuevo —
     * el administrador lo hace manualmente desde Planes de Estudio.
     */
    public function update(Request $request, Materia $materia)
    {
        $this->autorizarModificar($request);

        $validated = $request->validate([
            'nombre'     => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\d\-\(\)\.]+$/',
            'carrera'    => 'required|exists:tbl_carreras,Id',
            'semestre'   => 'required|integer|min:1|max:2',
            'anno'       => 'required|integer|min:1|max:4',
            'resolucion' => 'nullable|string|max:55|regex:/^[a-zA-Z0-9\s\-\/]+$/',
        ], [
            'nombre.regex'    => 'El nombre de la materia contiene caracteres no válidos.',
            'nombre.max'      => 'El nombre de la materia no puede exceder 100 caracteres.',
            'semestre.required' => 'El cuatrimestre es obligatorio.',
            'semestre.min'    => 'El cuatrimestre debe ser 1 o 2.',
            'semestre.max'    => 'El cuatrimestre debe ser 1 o 2.',
            'anno.max'        => 'El año no puede ser mayor a 4.',
            'resolucion.regex' => 'La resolución solo puede contener letras, números, espacios y guiones.',
        ]);

        try {
            $existe = Materia::where('nombre', $validated['nombre'])
                ->where('carrera', $validated['carrera'])
                ->where('id', '!=', $materia->id)
                ->exists();

            if ($existe) {
                return back()
                    ->withInput()
                    ->withErrors(['nombre' => 'Ya existe una materia con este nombre en esta carrera.']);
            }

            $carreraCambio  = $materia->carrera != $validated['carrera'];
            $carreraAnterior = $materia->carrera;

            $materia->update($validated);

            \Log::info('Materia actualizada', [
                'materia_id'    => $materia->id,
                'nombre'        => $validated['nombre'],
                'carrera_cambio' => $carreraCambio,
                'actualizado_por' => auth()->id(),
            ]);

            // Si cambió la carrera, limpiar asignaciones a planes de la carrera anterior
            if ($carreraCambio) {
                $eliminadas = PlanEstudioMateria::whereHas('planEstudio', function($query) use ($carreraAnterior) {
                    $query->where('carrera_id', $carreraAnterior);
                })->where('materia_id', $materia->id)->delete();

                \Log::info('Materia eliminada de planes de carrera anterior', [
                    'materia_id'      => $materia->id,
                    'carrera_anterior' => $carreraAnterior,
                    'planes_afectados' => $eliminadas,
                ]);

                return back()->with('success', 'Materia actualizada. Como cambió la carrera, fue removida de los planes anteriores. Asignala manualmente al plan correspondiente de la nueva carrera desde Parámetros → Planes de Estudio.');
            }

            return back()->with('success', 'Materia actualizada exitosamente.');

        } catch (\Exception $e) {
            $this->handleError($e, 'actualizar materia', [
                'materia_id' => $materia->id,
                'nombre'     => $validated['nombre'] ?? null,
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => $this->getFriendlyErrorMessage($e, 'Error al actualizar la materia')]);
        }
    }

    /**
     * Eliminar materia
     */
    public function destroy(Request $request, Materia $materia)
    {
        $this->autorizarEliminar($request);

        if ($materia->reglasCorrelativas()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar una materia con reglas de correlativas configuradas.']);
        }

        $alumnosConNotas = $materia->alumnosMaterias()->whereNotNull('nota')->count();
        if ($alumnosConNotas > 0) {
            return back()->withErrors([
                'eliminar' => "No se puede eliminar esta materia porque hay {$alumnosConNotas} alumno(s) con notas registradas en el legajo.",
            ]);
        }

        $alumnosEnLegajo = $materia->alumnosMaterias()->count();
        if ($alumnosEnLegajo > 0) {
            return back()->withErrors([
                'eliminar' => "No se puede eliminar esta materia porque hay {$alumnosEnLegajo} alumno(s) con registros en el legajo.",
            ]);
        }

        try {
            \DB::beginTransaction();

            // Eliminar de todos los planes donde esté asignada
            $relacionesEliminadas = PlanEstudioMateria::where('materia_id', $materia->id)->delete();

            if ($relacionesEliminadas > 0) {
                \Log::info('Materia eliminada de planes de estudio', [
                    'materia_id'      => $materia->id,
                    'planes_afectados' => $relacionesEliminadas,
                ]);
            }

            $materia->delete();

            \DB::commit();

            \Log::info('Materia eliminada exitosamente', [
                'materia_id'   => $materia->id,
                'eliminado_por' => auth()->id(),
            ]);

            return back()->with('success', 'Materia eliminada exitosamente.');

        } catch (\Exception $e) {
            \DB::rollBack();

            $this->handleError($e, 'eliminar materia', ['materia_id' => $materia->id]);

            return back()->withErrors(['error' => $this->getFriendlyErrorMessage($e, 'Error al eliminar la materia')]);
        }
    }
}