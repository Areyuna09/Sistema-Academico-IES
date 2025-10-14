<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CursarAgosto2Semestre extends Model
{
    use HasFactory;

    protected $table = 'cursar_agosto_2_semestre';

    protected $fillable = [
        'alumno',
        'materia',
        'fecha',
        'estado',
        'observacion',
        'fecha_cancelacion',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno');
    }
}
