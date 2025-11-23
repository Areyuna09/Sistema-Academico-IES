<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumnoMateria extends Model
{
    /**
     * Nombre de la tabla legacy
     */
    protected $table = 'tbl_alumnos_materias';

    /**
     * Clave primaria
     */
    protected $primaryKey = 'Id';

    /**
     * Indica si el modelo debe manejar timestamps
     */
    public $timestamps = false;

    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'alumno',
        'carrera',
        'materia',
        'nota',
        'cursada',
        'rendida',
        'equivalencia',
        'libre',
        'libro',
        'folio',
        'fecha',
        'calificacion-cursada',
        'calificacion_rendida',
        'libro_corte',
        // Campos de revisión
        'estado_revision',
        'revisado_por_directivo',
        'fecha_revision_directivo',
        'observaciones_directivo',
        'revisado_por_supervisor',
        'fecha_revision_supervisor',
        'observaciones_supervisor',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'fecha' => 'date',
        'equivalencia' => 'integer',
        'libre' => 'integer',
        'fecha_revision_directivo' => 'datetime',
        'fecha_revision_supervisor' => 'datetime',
    ];

    /**
     * Relación: Alumno
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno', 'id');
    }

    /**
     * Relación: Materia
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia', 'id');
    }

    /**
     * Relación: Materia (alias para evitar conflicto con atributo)
     */
    public function materiaRelacion()
    {
        return $this->belongsTo(Materia::class, 'materia', 'id');
    }

    /**
     * Relación: Carrera
     */
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera', 'Id');
    }

    /**
     * Verificar si la materia está regularizada (cursada)
     * NOTA: Con datos legacy, la fuente de verdad es la NOTA, no los flags
     */
    public function estaRegular()
    {
        // Una materia está regular si tiene nota >= 4 (aprobó cursada/final)
        // Los flags cursada/rendida no son confiables en datos migrados
        return $this->nota !== null && is_numeric($this->nota) && (float) $this->nota >= 4;
    }

    /**
     * Verificar si la materia está aprobada (rendida)
     * NOTA: Con datos legacy, la fuente de verdad es la NOTA, no los flags
     */
    public function estaAprobada()
    {
        // Una materia está aprobada si tiene nota >= 4 en el legajo
        // Los flags cursada/rendida no son confiables en datos migrados
        return $this->nota !== null && is_numeric($this->nota) && (float) $this->nota >= 4;
    }

    /**
     * Scope: Materias cursadas (regulares)
     * NOTA: Con datos legacy, la fuente de verdad es la NOTA, no los flags
     */
    public function scopeRegulares($query)
    {
        return $query->whereNotNull('nota')->where('nota', '>=', 4);
    }

    /**
     * Scope: Materias aprobadas (rendidas)
     * NOTA: Con datos legacy, la fuente de verdad es la NOTA, no los flags
     */
    public function scopeAprobadas($query)
    {
        return $query->whereNotNull('nota')->where('nota', '>=', 4);
    }

    /**
     * Scope: Por alumno y carrera
     */
    public function scopeDeAlumno($query, $alumnoId, $carreraId = null)
    {
        $query->where('alumno', $alumnoId);

        if ($carreraId) {
            $query->where('carrera', $carreraId);
        }

        return $query;
    }

    /**
     * Scope: Por materia específica
     */
    public function scopeDeMateria($query, $materiaId)
    {
        return $query->where('materia', $materiaId);
    }

    /**
     * Relación: Usuario Directivo que revisó
     */
    public function directivoRevisor()
    {
        return $this->belongsTo(User::class, 'revisado_por_directivo', 'id');
    }

    /**
     * Relación: Usuario Supervisor que revisó
     */
    public function supervisorRevisor()
    {
        return $this->belongsTo(User::class, 'revisado_por_supervisor', 'id');
    }

    /**
     * Relación: Historial de revisiones
     */
    public function historialRevisiones()
    {
        return $this->hasMany(HistorialRevision::class, 'alumno_materia_id', 'Id');
    }

    /**
     * Scope: Legajos pendientes de revisión por Directivo
     */
    public function scopePendientesDirectivo($query)
    {
        return $query->where('estado_revision', 'pendiente');
    }

    /**
     * Scope: Legajos con observaciones de Directivo
     */
    public function scopeConObservacionesDirectivo($query)
    {
        return $query->where('estado_revision', 'observaciones_directivo');
    }

    /**
     * Scope: Legajos pendientes de revisión por Supervisor
     */
    public function scopePendientesSupervisor($query)
    {
        return $query->where('estado_revision', 'aprobado_directivo');
    }

    /**
     * Scope: Legajos con observaciones de Supervisor
     */
    public function scopeConObservacionesSupervisor($query)
    {
        return $query->where('estado_revision', 'observaciones_supervisor');
    }

    /**
     * Scope: Legajos aprobados finalmente
     */
    public function scopeAprobadosFinal($query)
    {
        return $query->where('estado_revision', 'aprobado_final');
    }

    /**
     * Verificar si requiere revisión de Directivo
     */
    public function requiereRevisionDirectivo(): bool
    {
        return in_array($this->estado_revision, ['pendiente', 'observaciones_supervisor']);
    }

    /**
     * Verificar si requiere revisión de Supervisor
     */
    public function requiereRevisionSupervisor(): bool
    {
        return in_array($this->estado_revision, ['aprobado_directivo', 'observaciones_directivo']);
    }

    /**
     * Verificar si fue aprobado finalmente
     */
    public function estaAprobadoFinal(): bool
    {
        return $this->estado_revision === 'aprobado_final';
    }
}