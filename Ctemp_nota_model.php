<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaTemporal extends Model
{
    protected $table = 'tbl_notas_temporales';
    public $timestamps = false;

    protected $fillable = [
        'alumno_id',
        'materia_id',
        'profesor_id',
        'nota',
        'observacion',
        'estado',
        'fecha_carga'
    ];
}
