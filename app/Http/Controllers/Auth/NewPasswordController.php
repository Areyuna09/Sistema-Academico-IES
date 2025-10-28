<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class NewPasswordController extends Controller
{
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|max:255',
            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->uncompromised()
            ],
        ], [
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters' => 'La contraseña debe contener al menos una letra.',
            'password.mixed' => 'La contraseña debe contener mayúsculas y minúsculas.',
            'password.numbers' => 'La contraseña debe contener al menos un número.',
            'password.uncompromised' => 'Esta contraseña ha sido filtrada en brechas de seguridad. Por favor, elige otra.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        Log::info('Intento de restablecimiento de contraseña', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->updateQuietly([
                    'clave' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ]);

                event(new PasswordReset($user));

                // Logging de seguridad
                Log::info('Contraseña restablecida exitosamente', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip(),
                    'timestamp' => now(),
                ]);

                // Crear notificación interna
                try {
                    Notificacion::crear(
                        $user->id,
                        'password_changed',
                        'Contraseña Actualizada',
                        'Tu contraseña ha sido restablecida exitosamente el ' . now()->format('d/m/Y H:i') . '.',
                        [
                            'icono' => 'bx-check-shield',
                            'color' => 'green',
                        ]
                    );
                } catch (\Exception $e) {
                    Log::error('Error al crear notificación de cambio de contraseña: ' . $e->getMessage());
                }

                // Enviar email de confirmación
                try {
                    Mail::to($user->email)->send(
                        new \App\Mail\PasswordChangedConfirmation($user, $request->ip())
                    );
                } catch (\Exception $e) {
                    Log::error('Error al enviar email de confirmación de cambio de contraseña: ' . $e->getMessage());
                }
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', '¡Contraseña restablecida! Ya puedes iniciar sesión.');
        }

        // Logging de error
        Log::warning('Fallo al restablecer contraseña', [
            'status' => $status,
            'email' => $request->email,
            'ip' => $request->ip(),
        ]);

        // Mensajes de error específicos según el status
        $errorMessages = [
            Password::INVALID_TOKEN => 'El enlace de recuperación es inválido o ha expirado. Solicita uno nuevo.',
            Password::INVALID_USER => 'No encontramos ningún usuario con ese correo electrónico.',
            Password::THROTTLED => 'Has realizado demasiados intentos. Por favor, espera un momento.',
        ];

        throw ValidationException::withMessages([
            'email' => [$errorMessages[$status] ?? 'Error al restablecer la contraseña. Intenta nuevamente.'],
        ]);
    }
}