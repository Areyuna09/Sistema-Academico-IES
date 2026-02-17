<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfesorMateria extends Model
{
    protected $table = 'tbl_profesor_tiene_materias';
    public $timestamps = true;

    protected $fillable = [
        'profesor',
        'carrera',
        'materia',
        'cursado',
        'division',
        'periodo_id',
        'nota_minima_promocion',
        'nota_minima_regularidad',
        'permite_promocion',
        'porcentaje_asistencia_minimo',
        'criterios_evaluacion',
        'configuracion_completa',
        'asignado_por',
    ];

    protected $casts = [
        'nota_minima_promocion' => 'decimal:2',
        'nota_minima_regularidad' => 'decimal:2',
        'permite_promocion' => 'boolean',
        'configuracion_completa' => 'boolean',
    ];

    // AGREGAR ESTE MÉTODO
    public function profesorRelacion()
    {
        return $this->belongsTo(Profesor::class, 'profesor', 'id');
    }

    public function materiaRelacion()
    {
        return $this->belongsTo(Materia::class, 'materia', 'id');
    }

    public function carreraRelacion()
    {
        return $this->belongsTo(Carrera::class, 'carrera', 'Id');
    }

    public function periodoRelacion()
    {
        return $this->belongsTo(PeriodoInscripcion::class, 'periodo_id');
    }

    public function scopeDelPeriodoActivo($query)
    {
        $periodoActivo = PeriodoInscripcion::activo();
        if ($periodoActivo) {
            return $query->where('periodo_id', $periodoActivo->id);
        }
        return $query;
    }

    public function scopeDelPeriodo($query, $periodoId)
    {
        return $query->where('periodo_id', $periodoId);
    }

    public function scopeDelCuatrimestre($query, $cuatrimestre)
    {
        return $query->whereHas('materiaRelacion', function ($q) use ($cuatrimestre) {
            $q->where('semestre', $cuatrimestre);
        });
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'profesor_materia_id');
    }
}