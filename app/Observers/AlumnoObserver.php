<?php

namespace App\Observers;

use App\Models\Alumno;
use App\Models\AlumnoCarrera;

class AlumnoObserver
{
    public function saving(Alumno $alumno): void
    {
        // Si el admin no asignó un plan explícito, resolverlo automáticamente
        if (!$alumno->plan_estudio_id) {
            $plan = $alumno->resolverPlan();
            if ($plan) {
                $alumno->plan_estudio_id = $plan->id;
            }
        }
    }
}
