<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\TipoUsuario;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Permite acceso solo a usuarios con tipo ADMIN (1) o PROFESOR (3)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si no está autenticado, redirigir al login
        if (!$user) {
            return redirect()->route('login');
        }

        // Permitir acceso solo a ADMIN (1) o PROFESOR (3)
        if (!in_array($user->tipo, [TipoUsuario::ADMIN, TipoUsuario::PROFESOR])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
