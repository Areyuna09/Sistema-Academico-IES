<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\JwtService;
use Carbon\CarbonImmutable;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login - Autenticación con DNI y contraseña
     *
     * Autentica al usuario usando su DNI como username y retorna un token de acceso.
     * El token debe incluirse en el header Authorization de las peticiones subsiguientes.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'dni' => 'required|string',
            'password' => 'required|string',
            'device_name' => 'sometimes|string'
        ]);

        $user = User::where('dni', $request->dni)->first();

        if (!$user || !Hash::check($request->password, $user->clave)) {
            throw ValidationException::withMessages([
                'dni' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        if (!$user->activo) {
            throw ValidationException::withMessages([
                'dni' => ['El usuario está inactivo.'],
            ]);
        }

        $jwt = app(JwtService::class);
        $issued = $jwt->issueToken($user, $request->device_name ?? 'web');

        return response()->json([
            'success' => true,
            'token' => $issued['token'],
            'expires_at' => $issued['expires_at'],
            'user' => [
                'id' => $user->id,
                'dni' => $user->dni,
                'nombre' => $user->nombre,
                'email' => $user->email,
                'tipo' => $user->tipo,
                'role' => $user->role,
                'alumno_id' => $user->alumno_id,
                'profesor_id' => $user->profesor_id,
            ],
        ]);
    }

    /**
     * Logout - Revocar token de acceso
     *
     * Revoca el token de acceso actual del usuario autenticado.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $payload = $request->attributes->get('jwt_payload');
        if (!$payload) {
            return response()->json(['success' => false, 'message' => 'Token inválido'], 401);
        }

        $jti = $payload['jti'] ?? null;
        $exp = $payload['exp'] ?? null;

        if ($jti && $exp) {
            app(JwtService::class)->blacklist($jti, (int) $exp);
        }

        return response()->json([
            'success' => true,
            'message' => 'Token revocado exitosamente',
        ]);
    }

    /**
     * Obtener información del usuario autenticado
     *
     * Retorna la información completa del usuario que está autenticado con el token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'dni' => $user->dni,
                'nombre' => $user->nombre,
                'email' => $user->email,
                'tipo' => $user->tipo,
                'role' => $user->role,
                'alumno_id' => $user->alumno_id,
                'profesor_id' => $user->profesor_id,
            ],
        ]);
    }

    /**
     * Cambiar contraseña del usuario
     *
     * Permite al usuario autenticado cambiar su contraseña. Requiere la contraseña actual
     * y la nueva contraseña con confirmación.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->clave)) {
            throw ValidationException::withMessages([
                'current_password' => ['La contraseña actual es incorrecta.'],
            ]);
        }

        $user->clave = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada exitosamente',
        ]);
    }
}
