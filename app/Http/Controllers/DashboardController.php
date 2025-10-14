<?php
namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $alumno = $user->alumno;

        // Materias cursadas
        $cursadas = $alumno->cursadas()->get();

        // Progreso académico (ejemplo simple)
        $aprobadas = $cursadas->where('estado', 'Aprobada')->count();
        $regulares = $cursadas->where('estado', 'Regular')->count();
        $totalMaterias = 30; // lo podés traer de otra tabla
        $totalRegulares = 6;

        return Inertia::render('Dashboard/Index', [
            'alumno' => $alumno,
            'cursadas' => $cursadas,
            'stats' => [
                'aprobadas' => $aprobadas,
                'totalMaterias' => $totalMaterias,
                'regulares' => $regulares,
                'totalRegulares' => $totalRegulares
            ]
        ]);
    }
}
