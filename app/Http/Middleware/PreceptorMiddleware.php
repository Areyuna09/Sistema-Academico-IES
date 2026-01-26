<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\TipoUsuario;

class PreceptorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Permite acceso solo a usuarios con tipo PRECEPTOR (8) o ADMIN (1)
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

        // Permitir acceso a ADMIN (1) o PRECEPTOR (8)
        if (!in_array($user->tipo, [TipoUsuario::ADMIN, TipoUsuario::PRECEPTOR])) {
            abort(403, 'No tienes permiso para acceder a esta sección. Acceso solo para Preceptores.');
        }

        return $next($request);
    }
}