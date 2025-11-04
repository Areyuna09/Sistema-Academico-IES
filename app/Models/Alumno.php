<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    /**
     * Nombre de la tabla legacy
     */
    protected $table = 'tbl_alumnos';

    /**
     * Clave primaria
     */
    protected $primaryKey = 'id';

    /**
     * Indica si el modelo debe manejar timestamps
     */
    public $timestamps = false;

    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'dni',
        'apellido',
        'nombre',
        'curso',
        'division',
        'email',
        'telefono',
        'celular',
        'legajo',
        'descripcion_personalizada',
        'anno',
        'carrera',
        'materia',
        'fecha',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'fecha' => 'datetime',
    ];

    /**
     * Relación: Usuario asociado a este alumno
     */
    public function user()
    {
        return $this->hasOne(User::class, 'alumno_id');
    }

    /**
     * Relación: Carrera del alumno
     */
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera', 'Id');
    }

    /**
     * Relación: Carrera del alumno (alias para evitar conflicto con atributo)
     */
    public function carreraRelacion()
    {
        return $this->belongsTo(Carrera::class, 'carrera', 'Id');
    }

    /**
     * Relación: Historial académico del alumno
     */
    public function materiasCursadas()
    {
        return $this->hasMany(AlumnoMateria::class, 'alumno', 'id');
    }

    /**
     * Obtener nombre completo del alumno
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->apellido}, {$this->nombre}";
    }

    /**
     * Scope: Buscar por DNI
     */
    public function scopePorDni($query, $dni)
    {
        return $query->where('dni', $dni);
    }

    /**
     * Scope: Por carrera
     */
    public function scopeDeCarrera($query, $carreraId)
    {
        return $query->where('carrera', $carreraId);
    }
}