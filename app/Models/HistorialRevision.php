<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialRevision extends Model
{
    /**
     * Nombre de la tabla
     */
    protected $table = 'tbl_historial_revisiones';

    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'alumno_materia_id',
        'revisor_id',
        'tipo_revisor',
        'accion',
        'observaciones',
        'estado_anterior',
        'estado_nuevo',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: AlumnoMateria revisado
     */
    public function alumnoMateria()
    {
        return $this->belongsTo(AlumnoMateria::class, 'alumno_materia_id', 'Id');
    }

    /**
     * Relación: Usuario que realizó la revisión
     */
    public function revisor()
    {
        return $this->belongsTo(User::class, 'revisor_id', 'id');
    }

    /**
     * Scope: Por tipo de revisor
     */
    public function scopePorTipoRevisor($query, string $tipo)
    {
        return $query->where('tipo_revisor', $tipo);
    }

    /**
     * Scope: Por alumno-materia
     */
    public function scopePorAlumnoMateria($query, int $alumnoMateriaId)
    {
        return $query->where('alumno_materia_id', $alumnoMateriaId);
    }

    /**
     * Scope: Revisiones de un revisor específico
     */
    public function scopePorRevisor($query, int $revisorId)
    {
        return $query->where('revisor_id', $revisorId);
    }

    /**
     * Registrar una nueva revisión en el historial
     */
    public static function registrar(
        int $alumnoMateriaId,
        int $revisorId,
        string $tipoRevisor,
        string $accion,
        string $estadoAnterior,
        string $estadoNuevo,
        ?string $observaciones = null
    ): self {
        return self::create([
            'alumno_materia_id' => $alumnoMateriaId,
            'revisor_id' => $revisorId,
            'tipo_revisor' => $tipoRevisor,
            'accion' => $accion,
            'observaciones' => $observaciones,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoNuevo,
        ]);
    }
}
