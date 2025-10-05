<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();

        // Si es alumno, cargar información adicional
        if ($user->alumno_id) {
            $alumno = $user->alumno()->with('carreraRelacion')->first();

            return Inertia::render('Profile/Edit', [
                'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
                'status' => session('status'),
                'alumno' => $alumno ? [
                    'id' => $alumno->id,
                    'dni' => $alumno->dni,
                    'nombre_completo' => $alumno->nombre_completo,
                    'email' => $alumno->email,
                    'telefono' => $alumno->telefono,
                    'celular' => $alumno->celular,
                    'legajo' => $alumno->legajo,
                    'curso' => $alumno->curso,
                    'division' => $alumno->division,
                    'descripcion_personalizada' => $alumno->descripcion_personalizada ?? null,
                    'carrera' => $alumno->carreraRelacion ? [
                        'id' => $alumno->carreraRelacion->Id,
                        'nombre' => $alumno->carreraRelacion->nombre,
                    ] : null,
                ] : null,
            ]);
        }

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Actualizar el usuario
        $user->fill($request->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Si es alumno, actualizar también la tabla de alumnos
        if ($user->alumno_id && $user->alumno) {
            $alumno = $user->alumno;

            // Actualizar campos del alumno
            if ($request->has('telefono')) {
                $alumno->telefono = $request->telefono;
            }
            if ($request->has('celular')) {
                $alumno->celular = $request->celular;
            }
            if ($request->has('descripcion_personalizada')) {
                $alumno->descripcion_personalizada = $request->descripcion_personalizada;
            }
            if ($request->has('email')) {
                $alumno->email = $request->email;
            }

            $alumno->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
