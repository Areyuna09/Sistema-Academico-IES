<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExcepcionRequest;
use App\Http\Requests\Admin\UpdateExcepcionRequest;
use App\Models\Excepcion;
use App\Models\Alumno;
use App\Models\Materia;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExcepcionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Excepcion::with(['alumno', 'materia', 'solicitante', 'aprobador']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('alumno', function ($sq) use ($search) {
                    $sq->where('nombre', 'like', "%{$search}%")
                       ->orWhere('apellido', 'like', "%{$search}%")
                       ->orWhere('dni', 'like', "%{$search}%");
                })
                ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Ordenar por más recientes primero
        $excepciones = $query->orderBy('fecha_solicitud', 'desc')
                             ->paginate(15)
                             ->withQueryString();

        // Cargar alumnos para el selector
        $alumnos = Alumno::select('id', 'nombre', 'apellido', 'dni')
                        ->orderBy('apellido')
                        ->orderBy('nombre')
                        ->get();

        // Cargar materias para el selector
        $materias = Materia::select('id', 'nombre', 'carrera')
                          ->orderBy('nombre')
                          ->get();

        return Inertia::render('Admin/Excepciones/Index', [
            'excepciones' => $excepciones,
            'alumnos' => $alumnos,
            'materias' => $materias,
            'filtros' => $request->only(['search', 'estado', 'tipo']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExcepcionRequest $request)
    {
        $excepcion = Excepcion::create($request->validated());

        // Notificar al alumno
        if ($excepcion->alumno && $excepcion->alumno->user_id) {
            Notificacion::crear(
                $excepcion->alumno->user_id,
                'sistema',
                'Excepción Solicitada',
                "Se ha registrado una solicitud de excepción: {$excepcion->tipo_texto}",
                [
                    'icono' => 'bx-info-circle',
                    'color' => 'blue',
                    'url' => route('perfil.index'), // o donde corresponda
                ]
            );
        }

        return redirect()->back()->with('success', 'Excepción creada correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExcepcionRequest $request, Excepcion $excepcion)
    {
        $excepcion->update($request->validated());

        // Notificar al alumno sobre la resolución
        if ($excepcion->alumno && $excepcion->alumno->user_id) {
            $esAprobada = $request->estado === 'aprobada';

            Notificacion::crear(
                $excepcion->alumno->user_id,
                $esAprobada ? 'excepcion_aprobada' : 'excepcion_rechazada',
                $esAprobada ? 'Excepción Aprobada' : 'Excepción Rechazada',
                $esAprobada
                    ? "Tu solicitud de {$excepcion->tipo_texto} ha sido aprobada."
                    : "Tu solicitud de {$excepcion->tipo_texto} ha sido rechazada.",
                [
                    'icono' => $esAprobada ? 'bx-check-circle' : 'bx-x-circle',
                    'color' => $esAprobada ? 'green' : 'red',
                    'datos' => [
                        'excepcion_id' => $excepcion->id,
                        'justificacion' => $request->justificacion_administrativa,
                    ],
                ]
            );
        }

        return redirect()->back()->with('success', 'Excepción actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Excepcion $excepcion)
    {
        $excepcion->delete();

        return redirect()->back()->with('success', 'Excepción eliminada correctamente.');
    }
}
