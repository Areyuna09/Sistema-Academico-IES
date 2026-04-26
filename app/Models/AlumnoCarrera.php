<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumnoCarrera extends Model
{
    protected $table = 'tbl_alumno_carreras';
    public $timestamps = false;

    protected $fillable = [
        'alumno_id',
        'carrera_id',
        'legajo',
        'anno',
        'curso',
        'division',
        'plan_estudio_id',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'Id');
    }

    public function planEstudio()
    {
        return $this->belongsTo(PlanEstudio::class, 'plan_estudio_id');
    }

    public function resolverPlan(): ?PlanEstudio
    {
        if ($this->plan_estudio_id) {
            return $this->planEstudio ?? PlanEstudio::find($this->plan_estudio_id);
        }

        $annoIngreso = (int) ($this->anno ?? now()->year);

        $plan = PlanEstudio::where('carrera_id', $this->carrera_id)
            ->where('anio', '<=', $annoIngreso)
            ->orderBy('anio', 'desc')
            ->first();

        return $plan ?? PlanEstudio::where('carrera_id', $this->carrera_id)
            ->orderBy('anio', 'asc')
            ->first();
    }
}
