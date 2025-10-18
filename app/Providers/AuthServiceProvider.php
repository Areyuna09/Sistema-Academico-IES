<?php

namespace App\Providers;

use App\Models\Alumno;
use App\Policies\AlumnoPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Alumno::class => AlumnoPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Gates adicionales si fueran necesarios en el futuro
        Gate::define('viewPlan', [AlumnoPolicy::class, 'viewPlan']);
        Gate::define('updatePlan', [AlumnoPolicy::class, 'updatePlan']);
    }
}

