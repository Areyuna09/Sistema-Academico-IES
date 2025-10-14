<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MesaAgosto2Llamado extends Model
{
    use HasFactory;

    protected $table = 'mesa_agosoto_2_llamado';

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
