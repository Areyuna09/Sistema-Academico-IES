<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\TipoUsuario;
use App\Models\PermisoRol;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Permite acceso solo a roles administrativos: Admin (1), Bedel (7), Preceptor (8)
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

        // Permitir acceso a roles administrativos o a cualquier rol con al menos un acceso admin asignado
        if (!in_array($user->tipo, TipoUsuario::rolesAdministrativos()) &&
            !PermisoRol::tieneAlgunAccesoAdmin($user->tipo)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
