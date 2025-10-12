<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use Illuminate\Http\Request;

class PlanAdminController extends Controller
{
    /**
     * Asignar/actualizar carrera (plan de estudio) a un alumno.
     * POST /admin/asignar-plan
     */
    public function asignarPlan(Request $request)
    {
        $validated = $request->validate([
            'alumno_id' => 'required|integer|exists:tbl_alumnos,id',
            'carrera_id' => 'required|integer|exists:tbl_carreras,Id',
        ]);

        $alumno = Alumno::findOrFail($validated['alumno_id']);
        $alumno->carrera = $validated['carrera_id'];
        $alumno->save();

        return back()->with('success', 'Plan de estudio asignado correctamente.');
    }
}

