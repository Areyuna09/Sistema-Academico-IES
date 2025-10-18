<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Excepcion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'excepciones';

    protected $fillable = [
        'alumno_id',
        'tipo',
        'descripcion',
        'justificacion_administrativa',
        'estado',
        'materia_id',
        'mesa_examen_id',
        'solicitado_por',
        'aprobado_por',
        'fecha_solicitud',
        'fecha_resolucion',
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_resolucion' => 'datetime',
    ];

    protected $appends = ['estado_texto', 'tipo_texto'];

    /**
     * Relación con el alumno
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    /**
     * Relación con la materia (si aplica)
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    /**
     * Usuario que solicitó la excepción
     */
    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitado_por');
    }

    /**
     * Admin que aprobó/rechazó
     */
    public function aprobador()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    /**
     * Scopes
     */
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

    /**
     * Accessors
     */
    public function getEstadoTextoAttribute()
    {
        return match($this->estado) {
            'pendiente' => 'Pendiente',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
            default => $this->estado,
        };
    }

    public function getTipoTextoAttribute()
    {
        return match($this->tipo) {
            'correlativa' => 'Excepción de Correlativa',
            'reingreso' => 'Reingreso',
            'cambio_carrera' => 'Cambio de Carrera',
            'cambio_plan' => 'Cambio de Plan de Estudios',
            'justificacion_inasistencia' => 'Justificación de Inasistencia',
            'otra' => 'Otra',
            default => $this->tipo,
        };
    }
}
