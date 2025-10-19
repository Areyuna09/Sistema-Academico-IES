<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $table = 'tbl_carreras';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function alumnos()
    {
        return $this->hasMany(Alumnos::class, 'carrera', 'id');
    }

    public function materias()
    {
        return $this->hasMany(Materias::class, 'carrera', 'id');
    }
}
