<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfesorMateria extends Model
{
    protected $table = 'tbl_profesor_tiene_materias';
    public $timestamps = false;
    
    protected $fillable = [
        'profesor',
        'carrera',
        'materia',
        'cursado',
        'division'
    ];

    // AGREGAR ESTE MÉTODO
    public function profesorRelacion()
    {
        return $this->belongsTo(Profesor::class, 'profesor', 'id');
    }

    public function materiaRelacion()
    {
        return $this->belongsTo(Materia::class, 'materia', 'id');
    }

    public function carreraRelacion()
    {
        return $this->belongsTo(Carrera::class, 'carrera', 'Id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'profesor_materia_id');
    }
}