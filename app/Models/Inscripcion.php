<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    /**
     * Nombre de la tabla
     */
    protected $table = 'inscripciones';

    /**
     * Campos asignables en masa
     */
    protected $fillable = [
        'alumno_id',
        'materia_id',
        'carrera_id',
        'periodo_id',
        'estado',
        'observaciones',
        'fecha_inscripcion',
        'fecha_cancelacion',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'fecha_inscripcion' => 'datetime',
        'fecha_cancelacion' => 'datetime',
    ];

    /**
     * Relación: Alumno inscrito
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id', 'id');
    }

    /**
     * Relación: Materia inscrita
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id', 'id');
    }

    /**
     * Relación: Carrera
     */
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'Id');
    }

    /**
     * Relación: Período de inscripción
     */
    public function periodo()
    {
        return $this->belongsTo(PeriodoInscripcion::class, 'periodo_id', 'id');
    }

    /**
     * Scope: Inscripciones confirmadas
     */
    public function scopeConfirmadas($query)
    {
        return $query->where('estado', 'confirmada');
    }

    /**
     * Scope: Inscripciones de un alumno
     */
    public function scopeDeAlumno($query, $alumnoId)
    {
        return $query->where('alumno_id', $alumnoId);
    }

    /**
     * Scope: Inscripciones de un período
     */
    public function scopeDePeriodo($query, $periodoId)
    {
        return $query->where('periodo_id', $periodoId);
    }

    /**
     * Scope: Inscripciones de una materia
     */
    public function scopeDeMateria($query, $materiaId)
    {
        return $query->where('materia_id', $materiaId);
    }
}
