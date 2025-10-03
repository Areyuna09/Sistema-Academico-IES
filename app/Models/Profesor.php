<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    /**
     * Nombre de la tabla legacy
     */
    protected $table = 'tbl_profesores';

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
        'apellido',
        'nombre',
        'email',
        'dni',
        'carrera',
        'division',
    ];

    /**
     * Relación: Usuario asociado a este profesor
     */
    public function user()
    {
        return $this->hasOne(User::class, 'profesor_id');
    }

    /**
     * Relación: Carrera del profesor
     */
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera', 'Id');
    }

    /**
     * Relación: Materias que dicta el profesor
     */
    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'tbl_profesor_tiene_materias', 'profesor', 'materia');
    }

    /**
     * Obtener nombre completo del profesor
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
}
