<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InscripcionMesa extends Model
{
    protected $table = 'tbl_inscripciones_mesa';

    protected $fillable = [
        'mesa_id',
        'alumno_id',
        'fecha_inscripcion',
        'estado',
        'nota',
        'observaciones',
        'nota_transferida',
    ];

    protected $casts = [
        'fecha_inscripcion' => 'datetime',
        'nota' => 'decimal:2',
        'nota_transferida' => 'boolean',
    ];

    /**
     * Relación: Mesa de examen
     */
    public function mesa(): BelongsTo
    {
        return $this->belongsTo(MesaExamen::class, 'mesa_id');
    }

    /**
     * Relación: Alumno
     */
    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    /**
     * Scope: Inscripciones de un alumno
     */
    public function scopeDeAlumno($query, $alumnoId)
    {
        return $query->where('alumno_id', $alumnoId);
    }

    /**
     * Scope: Inscripciones a una mesa
     */
    public function scopeDeMesa($query, $mesaId)
    {
        return $query->where('mesa_id', $mesaId);
    }

    /**
     * Scope: Por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope: Aprobados
     */
    public function scopeAprobados($query)
    {
        return $query->where('estado', 'aprobado');
    }

    /**
     * Scope: Desaprobados
     */
    public function scopeDesaprobados($query)
    {
        return $query->where('estado', 'desaprobado');
    }

    /**
     * Scope: Pendientes de transferencia
     */
    public function scopePendientesTransferencia($query)
    {
        return $query->where('nota_transferida', false)
                    ->whereIn('estado', ['aprobado', 'desaprobado'])
                    ->whereNotNull('nota');
    }

    /**
     * Verificar si está aprobado
     */
    public function estaAprobado()
    {
        return $this->estado === 'aprobado' && $this->nota >= 4;
    }

    /**
     * Verificar si está desaprobado
     */
    public function estaDesaprobado()
    {
        return $this->estado === 'desaprobado' || ($this->nota !== null && $this->nota < 4);
    }
}
