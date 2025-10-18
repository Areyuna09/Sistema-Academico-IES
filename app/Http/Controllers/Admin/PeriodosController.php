<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodoInscripcion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PeriodosController extends Controller
{
    /**
     * Mostrar listado de períodos
     */
    public function index(Request $request)
    {
        $query = PeriodoInscripcion::query();

        // Filtrar por año
        if ($request->filled('anio')) {
            $query->where('anio', $request->anio);
        }

        // Filtrar por cuatrimestre
        if ($request->filled('cuatrimestre')) {
            $query->where('cuatrimestre', $request->cuatrimestre);
        }

        // Filtrar por estado
        if ($request->filled('activo')) {
            $query->where('activo', $request->activo === '1');
        }

        $periodos = $query
            ->orderBy('anio', 'desc')
            ->orderBy('cuatrimestre', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Obtener años disponibles para el filtro
        $anios = PeriodoInscripcion::selectRaw('DISTINCT anio')
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        return Inertia::render('Admin/Periodos/Index', [
            'periodos' => $periodos,
            'anios' => $anios,
            'filtros' => $request->only(['anio', 'cuatrimestre', 'activo']),
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return Inertia::render('Admin/Periodos/Create');
    }

    /**
     * Guardar nuevo período
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'cuatrimestre' => 'required|integer|min:1|max:2',
            'anio' => 'required|integer|min:2020|max:2050',
            'fecha_inicio_inscripcion' => 'required|date',
            'fecha_fin_inscripcion' => 'required|date|after:fecha_inicio_inscripcion',
            'fecha_inicio_cursada' => 'required|date',
            'fecha_fin_cursada' => 'required|date|after:fecha_inicio_cursada',
            'activo' => 'boolean',
        ]);

        // Validar que no exista ya un período para este cuatrimestre/año
        $existe = PeriodoInscripcion::where('cuatrimestre', $validated['cuatrimestre'])
            ->where('anio', $validated['anio'])
            ->exists();

        if ($existe) {
            return back()->withErrors(['cuatrimestre' => 'Ya existe un período para este cuatrimestre y año.']);
        }

        // Si se marca como activo, desactivar todos los demás
        if ($validated['activo'] ?? false) {
            PeriodoInscripcion::where('activo', true)->update(['activo' => false]);
        }

        PeriodoInscripcion::create($validated);

        return redirect()->route('admin.periodos.index')
            ->with('success', 'Período creado exitosamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(PeriodoInscripcion $periodo)
    {
        return Inertia::render('Admin/Periodos/Edit', [
            'periodo' => [
                'id' => $periodo->id,
                'nombre' => $periodo->nombre,
                'cuatrimestre' => $periodo->cuatrimestre,
                'anio' => $periodo->anio,
                'fecha_inicio_inscripcion' => $periodo->fecha_inicio_inscripcion->format('Y-m-d'),
                'fecha_fin_inscripcion' => $periodo->fecha_fin_inscripcion->format('Y-m-d'),
                'fecha_inicio_cursada' => $periodo->fecha_inicio_cursada->format('Y-m-d'),
                'fecha_fin_cursada' => $periodo->fecha_fin_cursada->format('Y-m-d'),
                'activo' => $periodo->activo,
            ],
        ]);
    }

    /**
     * Actualizar período
     */
    public function update(Request $request, PeriodoInscripcion $periodo)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'cuatrimestre' => 'required|integer|min:1|max:2',
            'anio' => 'required|integer|min:2020|max:2050',
            'fecha_inicio_inscripcion' => 'required|date',
            'fecha_fin_inscripcion' => 'required|date|after:fecha_inicio_inscripcion',
            'fecha_inicio_cursada' => 'required|date',
            'fecha_fin_cursada' => 'required|date|after:fecha_inicio_cursada',
            'activo' => 'boolean',
        ]);

        // Validar que no exista ya un período para este cuatrimestre/año (excepto el actual)
        $existe = PeriodoInscripcion::where('cuatrimestre', $validated['cuatrimestre'])
            ->where('anio', $validated['anio'])
            ->where('id', '!=', $periodo->id)
            ->exists();

        if ($existe) {
            return back()->withErrors(['cuatrimestre' => 'Ya existe un período para este cuatrimestre y año.']);
        }

        // Si se marca como activo, desactivar todos los demás
        if ($validated['activo'] ?? false) {
            PeriodoInscripcion::where('activo', true)
                ->where('id', '!=', $periodo->id)
                ->update(['activo' => false]);
        }

        $periodo->update($validated);

        return redirect()->route('admin.periodos.index')
            ->with('success', 'Período actualizado exitosamente.');
    }

    /**
     * Alternar estado activo/inactivo
     */
    public function toggle(PeriodoInscripcion $periodo)
    {
        // Si se va a activar, desactivar todos los demás
        if (!$periodo->activo) {
            PeriodoInscripcion::where('activo', true)->update(['activo' => false]);
        }

        $periodo->update(['activo' => !$periodo->activo]);

        $estado = $periodo->activo ? 'activado' : 'desactivado';

        return back()->with('success', "Período {$estado} exitosamente.");
    }

    /**
     * Eliminar período
     */
    public function destroy(PeriodoInscripcion $periodo)
    {
        // Validar que no sea el período activo
        if ($periodo->activo) {
            return back()->withErrors(['error' => 'No se puede eliminar el período activo.']);
        }

        $periodo->delete();

        return redirect()->route('admin.periodos.index')
            ->with('success', 'Período eliminado exitosamente.');
    }
}
