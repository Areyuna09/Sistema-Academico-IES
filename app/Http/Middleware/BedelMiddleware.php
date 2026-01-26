<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\TipoUsuario;

class BedelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Permite acceso solo a usuarios con tipo BEDEL (7), USUARIO (2 - legacy) o ADMIN (1)
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

        // Permitir acceso a ADMIN (1), USUARIO (2 - legacy), o BEDEL (7)
        if (!in_array($user->tipo, [TipoUsuario::ADMIN, TipoUsuario::USUARIO, TipoUsuario::BEDEL])) {
            abort(403, 'No tienes permiso para acceder a esta sección. Acceso solo para Bedeles.');
        }

        return $next($request);
    }
}