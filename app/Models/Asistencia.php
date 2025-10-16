<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';

    protected $fillable = [
        'alumno_id',
        'materia_id',
        'profesor_id',
        'fecha',
        'estado',
        'observacion'
    ];

    public function alumno()  { return $this->belongsTo(Alumno::class, 'alumno_id'); }
    public function materia() { return $this->belongsTo(Materia::class, 'materia_id'); }
    public function profesor(){ return $this->belongsTo(Profesor::class, 'profesor_id'); }
}
