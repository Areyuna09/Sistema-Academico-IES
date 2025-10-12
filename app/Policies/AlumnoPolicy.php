<?php

namespace App\Policies;

use App\Models\Alumno;
use App\Models\User;

class AlumnoPolicy
{
    /**
     * Un alumno puede ver su propio plan, o un admin/profesor puede verlo.
     */
    public function viewPlan(User $user, Alumno $alumno): bool
    {
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }
        if (method_exists($user, 'isProfesor') && $user->isProfesor()) {
            return true;
        }
        return (int)($user->alumno_id ?? 0) === (int)$alumno->id;
    }

    /**
     * Un alumno puede actualizar su propio plan, o un admin/profesor puede hacerlo.
     */
    public function updatePlan(User $user, Alumno $alumno): bool
    {
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }
        if (method_exists($user, 'isProfesor') && $user->isProfesor()) {
            return true;
        }
        return (int)($user->alumno_id ?? 0) === (int)$alumno->id;
    }
}

