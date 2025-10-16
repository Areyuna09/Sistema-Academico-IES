<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $table = 'tbl_materias';
    public $timestamps = false;

    protected $fillable = ['nombre', 'carrera', 'cursado'];

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'materia_id');
    }
}
