<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\TipoUsuario;

class SupervisorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Permite acceso solo a usuarios con tipo SUPERVISOR (6) o ADMIN (1)
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

        // Permitir acceso solo a SUPERVISOR (6) o ADMIN (1)
        if (!in_array($user->tipo, [TipoUsuario::SUPERVISOR, TipoUsuario::ADMIN])) {
            abort(403, 'No tienes permiso para acceder a esta sección. Acceso solo para Supervisores.');
        }

        return $next($request);
    }
}
