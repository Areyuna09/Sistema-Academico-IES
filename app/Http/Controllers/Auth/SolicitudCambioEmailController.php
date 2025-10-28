<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\SolicitudCambioEmail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SolicitudCambioEmailController extends Controller
{
    /**
     * Crear una solicitud de cambio de email desde la pantalla de recuperación
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'dni' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        // Buscar usuario por DNI
        $user = User::where('dni', $request->dni)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'dni' => ['No encontramos ningún usuario con ese DNI.'],
            ]);
        }

        // Verificar que el email sea diferente al actual (no debería pasar, pero por seguridad)
        if (strtolower(trim($user->email)) === strtolower(trim($request->email))) {
            return back()->with('error', 'Este email ya está registrado en tu cuenta.');
        }

        // Verificar si ya existe una solicitud pendiente para este usuario con el mismo email
        $solicitudExistente = SolicitudCambioEmail::where('user_id', $user->id)
            ->where('email_nuevo', $request->email)
            ->where('estado', 'pendiente')
            ->first();

        if ($solicitudExistente) {
            return back()->with([
                'showEmailMismatchWarning' => true,
                'requestedEmail' => $request->email,
                'requestedDni' => $request->dni,
                'solicitudEnviada' => true,
            ]);
        }

        try {
            // Crear solicitud de cambio de email
            $solicitud = SolicitudCambioEmail::create([
                'user_id' => $user->id,
                'dni' => $request->dni,
                'email_actual' => $user->email,
                'email_nuevo' => $request->email,
                'motivo' => 'Solicitud desde recuperación de contraseña',
                'estado' => 'pendiente',
                'ip_solicitud' => $request->ip(),
            ]);

            // Notificar a administradores
            $admins = User::where('tipo', 1)->where('activo', true)->get();
            foreach ($admins as $admin) {
                try {
                    Notificacion::crear(
                        $admin->id,
                        'solicitud_cambio_email',
                        'Solicitud de Cambio de Email',
                        "Usuario {$user->nombre} (DNI: {$user->dni}) solicita cambiar su email de {$user->email} a {$request->email}",
                        [
                            'icono' => 'bx-envelope-open',
                            'color' => 'blue',
                            'url' => route('admin.solicitudes-email.index'),
                            'datos' => [
                                'solicitud_id' => $solicitud->id,
                            ],
                        ]
                    );
                } catch (\Exception $e) {
                    Log::error('Error al notificar admin sobre solicitud de cambio de email: ' . $e->getMessage());
                }
            }

            Log::info('Solicitud de cambio de email creada', [
                'solicitud_id' => $solicitud->id,
                'user_id' => $user->id,
                'dni' => $request->dni,
                'email_anterior' => $user->email,
                'email_nuevo' => $request->email,
                'ip' => $request->ip(),
            ]);

            return back()->with([
                'showEmailMismatchWarning' => true,
                'requestedEmail' => $request->email,
                'requestedDni' => $request->dni,
                'solicitudEnviada' => true,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al crear solicitud de cambio de email: ' . $e->getMessage());
            return back()->with('error', 'Error al enviar la solicitud. Por favor, intenta nuevamente.');
        }
    }
}
