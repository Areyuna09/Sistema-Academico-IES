<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa_1 extends Model
{
    protected $table = 'mesa_agosto_1_llamado';
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