<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MesaExamen extends Model
{
    protected $table = 'tbl_mesas_examen';

    protected $fillable = [
        'materia_id',
        'fecha_examen',
        'hora_examen',
        'llamado',
        'periodo_id',
        'presidente_id',
        'vocal1_id',
        'vocal2_id',
        'estado',
        'observaciones',
        'fecha_inicio_inscripcion',
        'fecha_fin_inscripcion',
    ];

    protected $casts = [
        'fecha_examen' => 'date',
        'fecha_inicio_inscripcion' => 'date',
        'fecha_fin_inscripcion' => 'date',
        'llamado' => 'integer',
    ];

    /**
     * Relación: Materia
     */
    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    /**
     * Relación: Período
     */
    public function periodo(): BelongsTo
    {
        return $this->belongsTo(PeriodoInscripcion::class, 'periodo_id');
    }

    /**
     * Relación: Presidente del tribunal
     */
    public function presidente(): BelongsTo
    {
        return $this->belongsTo(Profesor::class, 'presidente_id');
    }

    /**
     * Relación: Vocal 1
     */
    public function vocal1(): BelongsTo
    {
        return $this->belongsTo(Profesor::class, 'vocal1_id');
    }

    /**
     * Relación: Vocal 2
     */
    public function vocal2(): BelongsTo
    {
        return $this->belongsTo(Profesor::class, 'vocal2_id');
    }

    /**
     * Relación: Inscripciones a esta mesa
     */
    public function inscripciones(): HasMany
    {
        return $this->hasMany(InscripcionMesa::class, 'mesa_id');
    }

    /**
     * Scope: Mesas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    /**
     * Scope: Mesas disponibles para inscripción (dentro del período)
     */
    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'activa')
                    ->whereDate('fecha_examen', '>=', now())
                    ->where(function($q) {
                        $q->whereNull('fecha_inicio_inscripcion')
                          ->orWhere(function($q2) {
                              $q2->whereDate('fecha_inicio_inscripcion', '<=', now())
                                 ->whereDate('fecha_fin_inscripcion', '>=', now());
                          });
                    });
    }

    /**
     * Scope: Por materia
     */
    public function scopeDeMateria($query, $materiaId)
    {
        return $query->where('materia_id', $materiaId);
    }

    /**
     * Scope: Por período
     */
    public function scopeDePeriodo($query, $periodoId)
    {
        return $query->where('periodo_id', $periodoId);
    }

    /**
     * Obtener cantidad de inscriptos
     */
    public function getCantidadInscriptosAttribute()
    {
        return $this->inscripciones()->whereIn('estado', ['inscripto', 'presente', 'aprobado', 'desaprobado'])->count();
    }

    /**
     * Obtener nombre completo de la mesa
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->materia->nombre} - {$this->llamado}° Llamado - {$this->fecha_examen->format('d/m/Y')}";
    }
}
