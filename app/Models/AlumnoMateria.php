<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumnoMateria extends Model
{
    /**
     * Nombre de la tabla legacy
     */
    protected $table = 'tbl_alumnos_materias';

    /**
     * Clave primaria
     */
    protected $primaryKey = 'Id';

    /**
     * Indica si el modelo debe manejar timestamps
     */
    public $timestamps = false;

    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'alumno',
        'carrera',
        'materia',
        'nota',
        'cursada',
        'rendida',
        'equivalencia',
        'libre',
        'libro',
        'folio',
        'fecha',
        'calificacion-cursada',
        'calificacion_rendida',
        'libro_corte',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'fecha' => 'date',
        'equivalencia' => 'integer',
        'libre' => 'integer',
    ];

    /**
     * Relación: Alumno
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno', 'id');
    }

    /**
     * Relación: Materia
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia', 'id');
    }

    /**
     * Relación: Materia (alias para evitar conflicto con atributo)
     */
    public function materiaRelacion()
    {
        return $this->belongsTo(Materia::class, 'materia', 'id');
    }

    /**
     * Relación: Carrera
     */
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera', 'Id');
    }

    /**
     * Verificar si la materia está regularizada (cursada)
     */
    public function estaRegular()
    {
        return $this->cursada === '1';
    }

    /**
     * Verificar si la materia está aprobada (rendida)
     */
    public function estaAprobada()
    {
        return $this->rendida === '1';
    }

    /**
     * Scope: Materias cursadas (regulares)
     */
    public function scopeRegulares($query)
    {
        return $query->where('cursada', '1');
    }

    /**
     * Scope: Materias aprobadas (rendidas)
     */
    public function scopeAprobadas($query)
    {
        return $query->where('rendida', '1');
    }

    /**
     * Scope: Por alumno y carrera
     */
    public function scopeDeAlumno($query, $alumnoId, $carreraId = null)
    {
        $query->where('alumno', $alumnoId);

        if ($carreraId) {
            $query->where('carrera', $carreraId);
        }

        return $query;
    }

    /**
     * Scope: Por materia específica
     */
    public function scopeDeMateria($query, $materiaId)
    {
        return $query->where('materia', $materiaId);
    }
}