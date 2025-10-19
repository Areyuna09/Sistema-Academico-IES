<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumnos extends Model
{
    use HasFactory;

    protected $table = 'tbl_alumnos';
    protected $fillable = ['nombre', 'apellido', 'dni', 'carrera'];
    public $timestamps = false;

    public function carreraInfo()
    {
        return $this->belongsTo(Carrera::class, 'carrera', 'id');
    }

    public function materias()
    {
        return $this->hasMany(Alumno_materia::class, 'alumno');
    }
}
