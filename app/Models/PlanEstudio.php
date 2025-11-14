<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanEstudio extends Model
{
    protected $table = 'tbl_planes_estudio';

    protected $fillable = [
        'carrera_id',
        'nombre',
        'anio',
        'resolucion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'anio' => 'integer',
    ];

    /**
     * Relación: Carrera a la que pertenece este plan
     * Nota: No usa foreign key por incompatibilidad de tipos
     */
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'Id');
    }

    /**
     * Relación: Materias asignadas a este plan (pivot)
     */
    public function materiasPivot()
    {
        return $this->hasMany(PlanEstudioMateria::class, 'plan_estudio_id');
    }

    /**
     * Relación: Materias asignadas a este plan (directo)
     */
    public function materias()
    {
        return $this->belongsToMany(
            Materia::class,
            'tbl_plan_estudio_materias',
            'plan_estudio_id',
            'materia_id'
        )
        ->withPivot('orden')
        ->withTimestamps()
        ->orderBy('tbl_plan_estudio_materias.orden');
    }
}
