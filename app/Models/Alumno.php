<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'tbl_alumnos';
    public $timestamps = false;

    protected $fillable = [
        'dni',
        'apellido',
        'nombre',
        'curso',
        'division',
        'email'
    ];

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'alumno_id');
    }
}
