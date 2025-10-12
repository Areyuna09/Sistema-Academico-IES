<?php

namespace App\Http\Middleware;

use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class JwtAuthenticate
{
    public function __construct(private JwtService $jwt)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $auth = $request->header('Authorization', '');
        if (!str_starts_with($auth, 'Bearer ')) {
            return response()->json(['message' => 'Token no provisto'], 401);
        }

        $token = substr($auth, 7);
        $payload = $this->jwt->verify($token);
        if (!$payload) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        $user = $this->jwt->userFromPayload($payload);
        if (!$user) {
            return response()->json(['message' => 'Usuario no autorizado'], 401);
        }

        // Resolver usuario en el request y autenticación para policies
        $request->setUserResolver(fn () => $user);
        Auth::setUser($user);

        // Adjuntar payload por si se requiere en el controlador
        $request->attributes->set('jwt_payload', $payload);

        return $next($request);
    }
}

