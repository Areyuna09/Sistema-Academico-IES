<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AsistenciasController extends Controller
{
    public function index(Request $request)
    {
        // Por ahora redirigimos al dashboard principal
        return redirect()->route('dashboard');
    }
}
