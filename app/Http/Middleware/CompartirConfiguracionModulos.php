<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;
use App\Models\ConfiguracionModulo;

class CompartirConfiguracionModulos
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Compartir configuración de módulos con todas las vistas Inertia
        Inertia::share([
            'modulosConfig' => function () {
                return \Cache::remember('modulos_config', 3600, function () {
                    return ConfiguracionModulo::all()->pluck('activo', 'clave')->toArray();
                });
            },
        ]);

        return $next($request);
    }
}
