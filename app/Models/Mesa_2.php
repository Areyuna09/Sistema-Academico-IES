<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa_2 extends Model
{
    protected $table = 'mesa_agosoto_2_llamado';
    public $timestamps = false;
    
    protected $fillable = [
        'alumno', 
        'materia', 
        'fecha', 
        'estado', 
        'observacion', 
        'fecha_cancelacion'
    ];

    public function alumnoInfo()
    {
        return $this->belongsTo(Alumnos::class, 'alumno');
    }

    public function materiaInfo()
    {
        return $this->belongsTo(Materias::class, 'materia');
    }
}