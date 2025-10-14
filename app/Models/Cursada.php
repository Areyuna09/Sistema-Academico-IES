<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cursada extends Model
{
    protected $table = 'cursar_agosto_2_semestre';

    protected $fillable = [
        'id',
        'alumno', // referencia a tbl_alumnos.id
        'materia',
        'estado' // Aprobada, Regular, Desaprobada
    ];

   public function alumno()
{
    return $this->belongsTo(Alumno::class, 'alumno', 'id');
}

}
