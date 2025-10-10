<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProfesorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->tipo != 3) {
            abort(403, 'Acceso denegado. Solo profesores pueden acceder.');
        }

        return $next($request);
    }
}



?>