<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excepciones extends Model 
{
    use HasFactory;

    protected $table = 'excepciones';

    protected $fillable = [
        'id',
        'alumno',
        'tipo_excepcion',
        'fecha_envio' => 'date:Y-m-d',
        'estado',
        'justificacion',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno', 'id');
    }
}
