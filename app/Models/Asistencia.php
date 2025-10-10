<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'tbl_asistencias';

    protected $fillable = [
        'profesor_materia_id',
        'alumno_id',
        'fecha',
        'estado',
        'tipo_carga',
        'presentes',
        'ausentes',
        'tardes',
        'total_clases',
        'observaciones',
        'registrado_por'
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relaciones
    public function profesorMateria()
    {
        return $this->belongsTo(ProfesorMateria::class, 'profesor_materia_id');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    // Scopes útiles
    public function scopePorFecha($query, $fecha)
    {
        return $query->where('fecha', $fecha);
    }

    public function scopePorMateria($query, $profesorMateriaId)
    {
        return $query->where('profesor_materia_id', $profesorMateriaId);
    }

    public function scopePorAlumno($query, $alumnoId)
    {
        return $query->where('alumno_id', $alumnoId);
    }

    public function scopeTipoDiaria($query)
    {
        return $query->where('tipo_carga', 'diaria');
    }

    public function scopeTipoFinal($query)
    {
        return $query->where('tipo_carga', 'final');
    }
}