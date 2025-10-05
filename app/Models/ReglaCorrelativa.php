<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReglaCorrelativa extends Model
{
    /**
     * Nombre de la tabla en la base de datos
     */
    protected $table = 'reglas_correlativas';

    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'materia_id',
        'carrera_id',
        'tipo',
        'correlativa_id',
        'estado_requerido',
        'es_activa',
        'excepciones',
        'observaciones',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'es_activa' => 'boolean',
        'excepciones' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Materia principal
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id', 'id');
    }

    /**
     * Relación: Materia correlativa
     */
    public function correlativa()
    {
        return $this->belongsTo(Materia::class, 'correlativa_id', 'id');
    }

    /**
     * Relación: Carrera
     */
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'Id');
    }

    /**
     * Scope: Solo reglas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('es_activa', true);
    }

    /**
     * Scope: Por tipo de validación
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope: Por materia y carrera
     */
    public function scopeParaMateria($query, $materiaId, $carreraId)
    {
        return $query->where('materia_id', $materiaId)
                     ->where('carrera_id', $carreraId);
    }
}
