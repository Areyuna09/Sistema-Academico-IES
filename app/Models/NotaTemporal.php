<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaTemporal extends Model
{
    protected $table = 'tbl_notas_temporales';

    protected $fillable = [
        'profesor_materia_id',
        'alumno_id',
        'nota',
        'tipo_evaluacion',
        'estado',
        'fecha',
        'observaciones',
        'registrado_por',
        'aprobado_por',
        'fecha_aprobacion'
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_aprobacion' => 'datetime',
        'nota' => 'decimal:2'
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

    public function aprobadoPor()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    // Scopes útiles
    public function scopePorMateria($query, $profesorMateriaId)
    {
        return $query->where('profesor_materia_id', $profesorMateriaId);
    }

    public function scopePorAlumno($query, $alumnoId)
    {
        return $query->where('alumno_id', $alumnoId);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'aprobada');
    }

    public function scopeRechazadas($query)
    {
        return $query->where('estado', 'rechazada');
    }
}
