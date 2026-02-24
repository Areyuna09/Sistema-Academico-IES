<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Asistencia;
use App\Models\PeriodoInscripcion;

class AsistenciasController extends Controller
{
    public function index(Request $request)
    {
        $periodoActivo = PeriodoInscripcion::activo();

        $query = Asistencia::with(['alumno', 'profesorMateria.materiaRelacion'])
            ->where('tipo_carga', 'diaria')
            ->orderBy('fecha', 'desc');

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        } else {
            $query->whereDate('fecha', today());
        }

        $asistencias = $query->paginate(50)->withQueryString();

        return Inertia::render('Preceptor/Asistencias', [
            'asistencias' => $asistencias,
            'filtros' => $request->only(['fecha']),
            'periodoActivo' => $periodoActivo,
        ]);
    }
}
