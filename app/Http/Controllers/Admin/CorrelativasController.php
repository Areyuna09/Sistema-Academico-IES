<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReglaCorrelativa;
use App\Models\Materia;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CorrelativasController extends Controller
{
    /**
     * Mostrar listado de reglas de correlativas
     */
    public function index(Request $request)
    {
        $query = ReglaCorrelativa::with(['materia', 'correlativa', 'carrera'])
            ->whereHas('materia') // Solo reglas con materia válida
            ->whereHas('correlativa') // Solo reglas con correlativa válida
            ->orderBy('materia_id')
            ->orderBy('tipo');

        // Filtros
        if ($request->carrera_id) {
            $query->where('carrera_id', $request->carrera_id);
        }

        if ($request->materia_id) {
            $query->where('materia_id', $request->materia_id);
        }

        if ($request->tipo) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->has('activa')) {
            $query->where('es_activa', $request->activa);
        }

        $reglas = $query->paginate(20);

        return Inertia::render('Admin/Correlativas/Index', [
            'reglas' => $reglas,
            'materias' => Materia::select('id', 'nombre', 'anno', 'semestre')->orderBy('anno')->orderBy('nombre')->get(),
            'carreras' => Carrera::select('Id as id', 'Nombre as nombre')->get(),
            'filtros' => $request->only(['carrera_id', 'materia_id', 'tipo', 'activa']),
        ]);
    }

    /**
     * Mostrar formulario para crear nueva regla
     */
    public function create()
    {
        return Inertia::render('Admin/Correlativas/Create', [
            'materias' => Materia::select('id', 'nombre', 'anno', 'semestre', 'carrera')->orderBy('anno')->orderBy('nombre')->get(),
            'carreras' => Carrera::select('Id as id', 'Nombre as nombre')->get(),
        ]);
    }

    /**
     * Guardar nueva regla de correlativa
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'materia_id' => 'required|exists:tbl_materias,id',
            'carrera_id' => 'required|exists:tbl_carreras,Id',
            'tipo' => 'required|in:cursar,rendir',
            'correlativa_id' => 'required|exists:tbl_materias,id|different:materia_id',
            'estado_requerido' => 'required|in:regular,aprobada',
            'observaciones' => 'nullable|string',
        ]);

        // Verificar que no exista ya esta regla
        $existente = ReglaCorrelativa::where('materia_id', $validated['materia_id'])
            ->where('carrera_id', $validated['carrera_id'])
            ->where('tipo', $validated['tipo'])
            ->where('correlativa_id', $validated['correlativa_id'])
            ->first();

        if ($existente) {
            return back()->withErrors(['correlativa_id' => 'Esta regla de correlativa ya existe.']);
        }

        $regla = ReglaCorrelativa::create(array_merge($validated, [
            'es_activa' => true,
        ]));

        return redirect()->route('admin.correlativas.index')
            ->with('success', 'Regla de correlativa creada exitosamente.');
    }

    /**
     * Mostrar formulario para editar regla
     */
    public function edit(ReglaCorrelativa $correlativa)
    {
        $correlativa->load(['materia', 'correlativa', 'carrera']);

        return Inertia::render('Admin/Correlativas/Edit', [
            'regla' => $correlativa,
            'materias' => Materia::select('id', 'nombre', 'anno', 'semestre', 'carrera')->orderBy('anno')->orderBy('nombre')->get(),
            'carreras' => Carrera::select('Id as id', 'Nombre as nombre')->get(),
        ]);
    }

    /**
     * Actualizar regla existente
     */
    public function update(Request $request, ReglaCorrelativa $correlativa)
    {
        $validated = $request->validate([
            'materia_id' => 'required|exists:tbl_materias,id',
            'carrera_id' => 'required|exists:tbl_carreras,Id',
            'tipo' => 'required|in:cursar,rendir',
            'correlativa_id' => 'required|exists:tbl_materias,id|different:materia_id',
            'estado_requerido' => 'required|in:regular,aprobada',
            'es_activa' => 'boolean',
            'observaciones' => 'nullable|string',
        ]);

        // Verificar que no exista otra regla igual
        $existente = ReglaCorrelativa::where('materia_id', $validated['materia_id'])
            ->where('carrera_id', $validated['carrera_id'])
            ->where('tipo', $validated['tipo'])
            ->where('correlativa_id', $validated['correlativa_id'])
            ->where('id', '!=', $correlativa->id)
            ->first();

        if ($existente) {
            return back()->withErrors(['correlativa_id' => 'Esta regla de correlativa ya existe.']);
        }

        $correlativa->update($validated);

        return redirect()->route('admin.correlativas.index')
            ->with('success', 'Regla de correlativa actualizada exitosamente.');
    }

    /**
     * Activar/Desactivar regla
     */
    public function toggle(ReglaCorrelativa $correlativa)
    {
        $correlativa->update([
            'es_activa' => !$correlativa->es_activa
        ]);

        return back()->with('success', $correlativa->es_activa
            ? 'Regla activada exitosamente.'
            : 'Regla desactivada exitosamente.');
    }

    /**
     * Eliminar regla de correlativa
     */
    public function destroy(ReglaCorrelativa $correlativa)
    {
        try {
            $correlativa->delete();

            return redirect()->route('admin.correlativas.index')
                ->with('success', 'Regla de correlativa eliminada exitosamente.');
        } catch (\Exception $e) {
            \Log::error('Error al eliminar correlativa: ' . $e->getMessage());

            return redirect()->route('admin.correlativas.index')
                ->withErrors(['error' => 'Error al eliminar la regla de correlativa.']);
        }
    }

    /**
     * Obtener materias correlativas de una materia específica
     */
    public function obtenerCorrelativas(Request $request, $materiaId)
    {
        $tipo = $request->get('tipo', 'cursar');
        $carreraId = $request->get('carrera_id');

        $correlativas = ReglaCorrelativa::with('correlativa')
            ->where('materia_id', $materiaId)
            ->where('tipo', $tipo)
            ->where('carrera_id', $carreraId)
            ->activas()
            ->get();

        return response()->json($correlativas);
    }

    /**
     * Importación masiva de correlativas desde CSV/JSON
     */
    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:csv,json',
        ]);

        // TODO: Implementar lógica de importación masiva
        // Por ahora retornamos un mensaje de no implementado

        return back()->with('info', 'La importación masiva estará disponible próximamente.');
    }
}
