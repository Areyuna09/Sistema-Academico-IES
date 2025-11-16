<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ConfiguracionModulo;

class VerificarModuloActivo
{
    /**
     * Handle an incoming request.
     *
     * @param  string  $modulo  Clave del módulo a verificar
     */
    public function handle(Request $request, Closure $next, string $modulo): Response
    {
        // Los admins siempre tienen acceso
        if (in_array($request->user()?->tipo, [1, 2])) {
            return $next($request);
        }

        // Verificar si el módulo está activo
        if (!ConfiguracionModulo::estaActivo($modulo)) {
            abort(403, 'Este módulo está actualmente desactivado.');
        }

        return $next($request);
    }
}
