<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'tbl_alumnos'; // nombre real de la tabla

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
        'anno',
        'carrera',
        'materia',
        'turno',
        'fecha',
    ];

    // Relación con inscripciones
    public function inscripciones()
    {
        return $this->hasMany(CursarAgosto2Semestre::class, 'alumno');
    }

    public function user()
{
    return $this->belongsTo(User::class, 'email', 'email');
}


    public function cursadas()
{
    return $this->hasMany(Cursada::class, 'alumno', 'id');
}

}
