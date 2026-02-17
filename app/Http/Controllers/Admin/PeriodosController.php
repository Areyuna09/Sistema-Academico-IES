<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodoInscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            ->withCount(['asignacionesProfesores as asignaciones_count'])
            ->orderBy('anio', 'desc')
            ->orderBy('cuatrimestre', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Obtener años disponibles para el filtro
        $anios = PeriodoInscripcion::selectRaw('DISTINCT anio')
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        // Períodos que tienen asignaciones (para el dropdown de clonar)
        $periodosConAsignaciones = PeriodoInscripcion::whereHas('asignacionesProfesores')
            ->withCount('asignacionesProfesores as asignaciones_count')
            ->orderByDesc('anio')
            ->orderByDesc('cuatrimestre')
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'asignaciones_count' => $p->asignaciones_count,
            ]);

        return Inertia::render('Admin/Periodos/Index', [
            'periodos' => $periodos,
            'anios' => $anios,
            'filtros' => $request->only(['anio', 'cuatrimestre', 'activo']),
            'periodosConAsignaciones' => $periodosConAsignaciones,
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

        PeriodoInscripcion::create(array_merge($validated, [
            'creado_por' => auth()->id(),
        ]));

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

        $periodo->update(array_merge($validated, [
            'modificado_por' => auth()->id(),
        ]));

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

        // Si se activó, verificar si tiene asignaciones
        if ($periodo->activo) {
            $asignaciones = DB::table('tbl_profesor_tiene_materias')
                ->where('periodo_id', $periodo->id)
                ->count();

            if ($asignaciones === 0) {
                return back()->with([
                    'success' => "Período {$estado} exitosamente.",
                    'sin_asignaciones' => true,
                    'periodo_id' => $periodo->id,
                ]);
            }
        }

        return back()->with('success', "Período {$estado} exitosamente.");
    }

    /**
     * Clonar asignaciones profesor-materia de un período anterior al período destino
     */
    public function clonarAsignaciones(Request $request, PeriodoInscripcion $periodo)
    {
        $request->validate([
            'periodo_origen_id' => 'required|exists:tbl_periodos_inscripcion,id',
        ]);

        $periodoOrigen = PeriodoInscripcion::findOrFail($request->periodo_origen_id);

        if ($periodoOrigen->id === $periodo->id) {
            return back()->withErrors(['error' => 'No se puede clonar un período sobre sí mismo.']);
        }

        $asignacionesAnteriores = DB::table('tbl_profesor_tiene_materias')
            ->where('periodo_id', $periodoOrigen->id)
            ->get();

        if ($asignacionesAnteriores->isEmpty()) {
            return back()->withErrors(['error' => "El período \"{$periodoOrigen->nombre}\" no tiene asignaciones para clonar."]);
        }

        $cursado = $periodo->cuatrimestre == '1' ? '1er Cuatrimestre' : '2do Cuatrimestre';
        $clonadas = 0;

        foreach ($asignacionesAnteriores as $asignacion) {
            // Evitar duplicados
            $existe = DB::table('tbl_profesor_tiene_materias')
                ->where('profesor', $asignacion->profesor)
                ->where('materia', $asignacion->materia)
                ->where('carrera', $asignacion->carrera)
                ->where('periodo_id', $periodo->id)
                ->exists();

            if (!$existe) {
                DB::table('tbl_profesor_tiene_materias')->insert([
                    'profesor' => $asignacion->profesor,
                    'carrera' => $asignacion->carrera,
                    'materia' => $asignacion->materia,
                    'division' => $asignacion->division,
                    'cursado' => $cursado,
                    'periodo_id' => $periodo->id,
                    'nota_minima_promocion' => $asignacion->nota_minima_promocion,
                    'nota_minima_regularidad' => $asignacion->nota_minima_regularidad,
                    'permite_promocion' => $asignacion->permite_promocion,
                    'porcentaje_asistencia_minimo' => $asignacion->porcentaje_asistencia_minimo,
                    'criterios_evaluacion' => $asignacion->criterios_evaluacion,
                    'configuracion_completa' => false,
                    'asignado_por' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $clonadas++;
            }
        }

        return back()->with('success', "Se clonaron {$clonadas} asignaciones del período \"{$periodoOrigen->nombre}\" al período actual.");
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

        // Verificar datos dependientes
        $dependencias = [];

        $inscripciones = DB::table('tbl_inscripciones')->where('periodo_id', $periodo->id)->count();
        if ($inscripciones > 0) {
            $dependencias[] = "{$inscripciones} inscripción(es) a cursado";
        }

        $mesas = DB::table('tbl_mesas_examen')->where('periodo_id', $periodo->id)->count();
        if ($mesas > 0) {
            $dependencias[] = "{$mesas} mesa(s) de examen";
        }

        $asignaciones = DB::table('tbl_profesor_tiene_materias')->where('periodo_id', $periodo->id)->count();
        if ($asignaciones > 0) {
            $dependencias[] = "{$asignaciones} asignación(es) de profesores";
        }

        if (!empty($dependencias)) {
            return back()->withErrors(['error' => 'No se puede eliminar el período porque tiene: ' . implode(', ', $dependencias) . '.']);
        }

        $periodo->delete();

        return redirect()->route('admin.periodos.index')
            ->with('success', 'Período eliminado exitosamente.');
    }
}
