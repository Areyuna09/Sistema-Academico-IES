<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    /**
     * Nombre de la tabla legacy
     */
    protected $table = 'tbl_carreras';

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
        'nombre',
        'resolucion',
    ];

    /**
     * Relación: Materias de esta carrera
     */
    public function materias()
    {
        return $this->hasMany(Materia::class, 'carrera', 'Id');
    }

    /**
     * Relación: Alumnos de esta carrera
     */
    public function alumnos()
    {
        return $this->hasMany(Alumno::class, 'carrera', 'Id');
    }

    /**
     * Relación: Reglas de correlativas de esta carrera
     */
    public function reglasCorrelativas()
    {
        return $this->hasMany(ReglaCorrelativa::class, 'carrera_id', 'Id');
    }
}