<?php

namespace App\Providers;

use App\Models\Alumno;
use App\Observers\AlumnoObserver;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Alumno::observe(AlumnoObserver::class);

        // Configurar reglas de contraseña por defecto: mínimo 6 caracteres
        Password::defaults(function () {
            return Password::min(6);
        });
    }
}
