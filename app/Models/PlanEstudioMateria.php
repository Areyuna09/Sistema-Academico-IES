<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanEstudioMateria extends Model
{
    protected $table = 'tbl_plan_estudio_materias';

    protected $fillable = [
        'plan_estudio_id',
        'materia_id',
        'orden',
    ];

    protected $casts = [
        'orden' => 'integer',
    ];

    /**
     * Relación: Plan de estudio al que pertenece
     */
    public function planEstudio()
    {
        return $this->belongsTo(PlanEstudio::class, 'plan_estudio_id');
    }

    /**
     * Relación: Materia asignada
     * Nota: No usa foreign key por incompatibilidad de tipos
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id', 'id');
    }
}
