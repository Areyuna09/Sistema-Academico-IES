<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    protected $table = 'tbl_profesores';
    public $timestamps = false;

    protected $fillable = ['nombre', 'apellido', 'email', 'dni'];

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'tbl_profesor_tiene_materias', 'profesor', 'materia');
    }
}
