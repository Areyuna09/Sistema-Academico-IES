<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialNota extends Model
{
    protected $table = 'tbl_historial_notas';
    public $timestamps = false;

    protected $fillable = [
        'nota_id',
        'accion',
        'usuario',
        'fecha'
    ];
}
