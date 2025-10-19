<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materias extends Model
{
    use HasFactory;

    protected $table = 'tbl_materias';
    protected $fillable = ['nombre', 'carrera'];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera');
    }
}
