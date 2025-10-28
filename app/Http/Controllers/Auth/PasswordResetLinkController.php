<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\SolicitudCambioEmail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
            'showEmailMismatchWarning' => session('showEmailMismatchWarning', false),
            'requestedEmail' => session('requestedEmail', ''),
            'requestedDni' => session('requestedDni', ''),
            'solicitudEnviada' => session('solicitudEnviada', false),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'dni' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ], [
            'dni.required' => 'El DNI es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
        ]);

        Log::info('Solicitud de recuperación de contraseña', [
            'dni' => $request->dni,
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Buscar usuario por DNI
        $user = User::where('dni', $request->dni)->first();

        if (!$user) {
            Log::warning('Intento de recuperación con DNI no registrado', [
                'dni' => $request->dni,
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);

            throw ValidationException::withMessages([
                'dni' => ['No encontramos ningún usuario con ese DNI.'],
            ]);
        }

        if (!$user->activo) {
            Log::warning('Intento de recuperación de usuario inactivo', [
                'user_id' => $user->id,
                'dni' => $request->dni,
                'ip' => $request->ip(),
            ]);

            throw ValidationException::withMessages([
                'dni' => ['Este usuario está inactivo. Contacta al administrador.'],
            ]);
        }

        // Verificar si el email coincide con el registrado
        if (strtolower(trim($user->email)) !== strtolower(trim($request->email))) {
            Log::warning('Intento de recuperación con email diferente al registrado', [
                'user_id' => $user->id,
                'dni' => $request->dni,
                'email_registrado' => $user->email,
                'email_solicitado' => $request->email,
                'ip' => $request->ip(),
            ]);

            return back()->with([
                'showEmailMismatchWarning' => true,
                'requestedEmail' => $request->email,
                'requestedDni' => $request->dni,
            ]);
        }

        // Si el email coincide, proceder con el envío del enlace de recuperación
        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status == Password::RESET_LINK_SENT) {
            Log::info('Enlace de recuperación enviado exitosamente', [
                'user_id' => $user->id,
                'dni' => $user->dni,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);

            return back()->with('status', '¡Enlace enviado! Revisa tu correo electrónico.');
        }

        Log::error('Error al enviar enlace de recuperación', [
            'status' => $status,
            'dni' => $request->dni,
            'email' => $request->email,
            'ip' => $request->ip(),
        ]);

        throw ValidationException::withMessages([
            'email' => ['Error al enviar el correo. Intenta nuevamente.'],
        ]);
    }
}