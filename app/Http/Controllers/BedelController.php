<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class BedelController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Bedel/Index', [
            'user' => [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'email' => $user->email,
                'tipo' => $user->tipo,
            ],
        ]);
    }
}
