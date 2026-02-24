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
        // Admins, profesores y roles administrativos siempre tienen acceso
        // Solo alumnos (tipo 4) dependen de que el módulo esté activo
        $tipoUsuario = $request->user()?->tipo;
        if (in_array($tipoUsuario, [1, 2, 3, 5, 6, 7, 8])) {
            return $next($request);
        }

        // Para alumnos (tipo 4), verificar si el módulo está activo
        if (!ConfiguracionModulo::estaActivo($modulo)) {
            abort(403, 'Este módulo está actualmente desactivado.');
        }

        return $next($request);
    }
}
