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
        // Los admins y profesores siempre tienen acceso
        // Tipos: 1=Admin, 2=Puesto, 3=Profesor, 4=Alumno
        $tipoUsuario = $request->user()?->tipo;
        if (in_array($tipoUsuario, [1, 2, 3])) {
            return $next($request);
        }

        // Para alumnos (tipo 4), verificar si el módulo está activo
        if (!ConfiguracionModulo::estaActivo($modulo)) {
            abort(403, 'Este módulo está actualmente desactivado.');
        }

        return $next($request);
    }
}
