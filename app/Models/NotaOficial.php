<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaOficial extends Model
{
    protected $table = 'tbl_notas_oficiales';
    public $timestamps = false;

    protected $fillable = [
        'alumno_id',
        'materia_id',
        'nota',
        'fecha_registro',
        'origen_id'
    ];
}
