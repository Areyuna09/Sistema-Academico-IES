<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno_materia extends Model
{
    use HasFactory;

    protected $table = 'tbl_alumnos_materias';
    protected $fillable = ['alumno', 'materia', 'nota'];

    public function alumno()
    {
        return $this->belongsTo(Alumnos::class, 'alumno');
    }

    public function materia()
    {
        return $this->belongsTo(Materias::class, 'materia');
    }
}