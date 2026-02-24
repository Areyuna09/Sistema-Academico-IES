<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Inscripcion;
use App\Models\PeriodoInscripcion;
use App\Models\Asistencia;

class PreceptorController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $periodoActivo = PeriodoInscripcion::activo();

        $queryInsc = Inscripcion::where('estado', 'confirmada');
        if ($periodoActivo) {
            $queryInsc->where('periodo_id', $periodoActivo->id);
        }
        $inscripcionesActivas = $queryInsc->count();

        $asistenciasHoy = Asistencia::whereDate('fecha', today())
            ->where('tipo_carga', 'diaria')
            ->count();

        return Inertia::render('Preceptor/Index', [
            'user' => [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'email' => $user->email,
                'tipo' => $user->tipo,
            ],
            'metricas' => [
                'inscripciones_activas' => $inscripcionesActivas,
                'asistencias_hoy' => $asistenciasHoy,
                'periodo_activo' => $periodoActivo ? $periodoActivo->nombre : 'Sin período activo',
            ],
        ]);
    }
}
