<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PeriodoInscripcion extends Model
{
    protected $table = 'tbl_periodos_inscripcion';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'cuatrimestre',
        'anio',
        'fecha_inicio_inscripcion',
        'fecha_fin_inscripcion',
        'fecha_inicio_cursada',
        'fecha_fin_cursada',
        'activo',
    ];

    protected $casts = [
        'fecha_inicio_inscripcion' => 'date',
        'fecha_fin_inscripcion' => 'date',
        'fecha_inicio_cursada' => 'date',
        'fecha_fin_cursada' => 'date',
        'activo' => 'boolean',
        'anio' => 'integer',
    ];

    /**
     * Obtener el período activo actualmente
     */
    public static function activo()
    {
        return static::where('activo', true)->first();
    }

    /**
     * Verificar si las inscripciones están abiertas
     */
    public function inscripcionesAbiertas(): bool
    {
        $hoy = Carbon::today();
        return $this->activo &&
               $hoy->between($this->fecha_inicio_inscripcion, $this->fecha_fin_inscripcion);
    }

    /**
     * Días restantes para el cierre de inscripciones
     */
    public function diasRestantes(): int
    {
        if (!$this->inscripcionesAbiertas()) {
            return 0;
        }
        return Carbon::today()->diffInDays($this->fecha_fin_inscripcion);
    }

    /**
     * Scope: Períodos del año actual
     */
    public function scopeDelAnio($query, $anio = null)
    {
        $anio = $anio ?? date('Y');
        return $query->where('anio', $anio);
    }
}
