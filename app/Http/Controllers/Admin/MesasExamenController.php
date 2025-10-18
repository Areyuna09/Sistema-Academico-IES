<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MesaExamen;
use App\Models\Materia;
use App\Models\Profesor;
use App\Models\PeriodoInscripcion;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MesasExamenController extends Controller
{
    /**
     * Mostrar listado de mesas de examen
     */
    public function index(Request $request)
    {
        $query = MesaExamen::with(['materia.carrera', 'periodo', 'presidente', 'vocal1', 'vocal2']);

        // Filtros
        if ($request->filled('carrera_id')) {
            $query->whereHas('materia', function ($q) use ($request) {
                $q->where('carrera', $request->carrera_id);
            });
        }

        if ($request->filled('materia_id')) {
            $query->where('materia_id', $request->materia_id);
        }

        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->whereHas('materia', function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%");
            });
        }

        $mesas = $query->orderBy('fecha_examen', 'desc')
                      ->orderBy('hora_examen', 'desc')
                      ->paginate(15)
                      ->withQueryString();

        // Cargar cantidad de inscriptos para cada mesa
        $mesas->getCollection()->transform(function ($mesa) {
            $mesa->cantidad_inscriptos = $mesa->inscripciones()
                ->whereIn('estado', ['inscripto', 'presente', 'aprobado', 'desaprobado'])
                ->count();
            return $mesa;
        });

        return Inertia::render('Admin/Mesas/Index', [
            'mesas' => $mesas,
            'carreras' => Carrera::orderBy('nombre')->get(),
            'periodos' => PeriodoInscripcion::orderBy('anio', 'desc')->orderBy('cuatrimestre', 'desc')->get(),
            'filtros' => $request->only(['carrera_id', 'materia_id', 'periodo_id', 'estado', 'buscar']),
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return Inertia::render('Admin/Mesas/Create', [
            'carreras' => Carrera::with('materias')->orderBy('nombre')->get(),
            'materias' => Materia::with('carrera')->orderBy('nombre')->get(),
            'profesores' => Profesor::select('id', 'dni', 'apellido', 'nombre')->orderBy('apellido')->get()->map(function($prof) {
                return [
                    'id' => $prof->id,
                    'nombre_completo' => $prof->nombre_completo,
                ];
            }),
            'periodos' => PeriodoInscripcion::orderBy('anio', 'desc')->orderBy('cuatrimestre', 'desc')->get(),
        ]);
    }

    /**
     * Guardar nueva mesa
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'materia_id' => 'required|exists:tbl_materias,id',
            'fecha_examen' => 'required|date|after_or_equal:today',
            'hora_examen' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'llamado' => 'required|integer|min:1|max:3',
            'periodo_id' => 'nullable|exists:tbl_periodos_inscripcion,id',
            'aula' => 'nullable|string|max:100',
            'presidente_id' => 'nullable|exists:tbl_profesores,id',
            'vocal1_id' => 'nullable|exists:tbl_profesores,id',
            'vocal2_id' => 'nullable|exists:tbl_profesores,id',
            'fecha_inicio_inscripcion' => 'nullable|date',
            'fecha_fin_inscripcion' => 'nullable|date|after_or_equal:fecha_inicio_inscripcion',
            'observaciones' => 'nullable|string',
        ], [
            'fecha_examen.after_or_equal' => 'La fecha del examen debe ser igual o posterior a hoy.',
            'hora_examen.regex' => 'El formato de la hora no es válido. Use formato 24 horas (ej: 17:30).',
        ]);

        // Verificar que no exista mesa duplicada (misma materia, llamado y fecha)
        $mesaExistente = MesaExamen::where('materia_id', $validated['materia_id'])
            ->where('llamado', $validated['llamado'])
            ->whereDate('fecha_examen', $validated['fecha_examen'])
            ->exists();

        if ($mesaExistente) {
            return back()->withErrors([
                'materia_id' => 'Ya existe una mesa para esta materia en este llamado y fecha.'
            ]);
        }

        MesaExamen::create($validated);

        return redirect()->route('admin.mesas.index')
            ->with('success', 'Mesa de examen creada exitosamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(MesaExamen $mesa)
    {
        $mesa->load(['materia.carrera', 'periodo', 'presidente', 'vocal1', 'vocal2']);

        return Inertia::render('Admin/Mesas/Edit', [
            'mesa' => $mesa,
            'carreras' => Carrera::with('materias')->orderBy('nombre')->get(),
            'materias' => Materia::with('carrera')->orderBy('nombre')->get(),
            'profesores' => Profesor::select('id', 'dni', 'apellido', 'nombre')->orderBy('apellido')->get()->map(function($prof) {
                return [
                    'id' => $prof->id,
                    'nombre_completo' => $prof->nombre_completo,
                ];
            }),
            'periodos' => PeriodoInscripcion::orderBy('anio', 'desc')->orderBy('cuatrimestre', 'desc')->get(),
        ]);
    }

    /**
     * Actualizar mesa
     */
    public function update(Request $request, MesaExamen $mesa)
    {
        $validated = $request->validate([
            'materia_id' => 'required|exists:tbl_materias,id',
            'fecha_examen' => 'required|date',
            'hora_examen' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'llamado' => 'required|integer|min:1|max:3',
            'periodo_id' => 'nullable|exists:tbl_periodos_inscripcion,id',
            'aula' => 'nullable|string|max:100',
            'presidente_id' => 'nullable|exists:tbl_profesores,id',
            'vocal1_id' => 'nullable|exists:tbl_profesores,id',
            'vocal2_id' => 'nullable|exists:tbl_profesores,id',
            'estado' => 'required|in:activa,cerrada,suspendida',
            'observaciones' => 'nullable|string',
        ], [
            'hora_examen.regex' => 'El formato de la hora no es válido. Use formato 24 horas (ej: 17:30).',
        ]);

        // Verificar duplicados excluyendo la mesa actual
        $mesaExistente = MesaExamen::where('materia_id', $validated['materia_id'])
            ->where('llamado', $validated['llamado'])
            ->whereDate('fecha_examen', $validated['fecha_examen'])
            ->where('id', '!=', $mesa->id)
            ->exists();

        if ($mesaExistente) {
            return back()->withErrors([
                'materia_id' => 'Ya existe una mesa para esta materia en este llamado y fecha.'
            ]);
        }

        $mesa->update($validated);

        return redirect()->route('admin.mesas.index')
            ->with('success', 'Mesa de examen actualizada exitosamente.');
    }

    /**
     * Eliminar mesa
     */
    public function destroy(MesaExamen $mesa)
    {
        // Verificar si hay inscripciones
        if ($mesa->inscripciones()->count() > 0) {
            return back()->withErrors([
                'error' => 'No se puede eliminar una mesa con inscripciones. Primero debe suspenderla o cerrarla.'
            ]);
        }

        $mesa->delete();

        return redirect()->route('admin.mesas.index')
            ->with('success', 'Mesa de examen eliminada exitosamente.');
    }

    /**
     * Ver detalles de una mesa con inscripciones
     */
    public function show(MesaExamen $mesa)
    {
        $mesa->load([
            'materia.carrera',
            'periodo',
            'presidente',
            'vocal1',
            'vocal2',
            'inscripciones.alumno'
        ]);

        return Inertia::render('Admin/Mesas/Show', [
            'mesa' => $mesa,
        ]);
    }
}
