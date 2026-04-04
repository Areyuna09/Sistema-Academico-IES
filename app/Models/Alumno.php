<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    /**
     * Nombre de la tabla legacy
     */
    protected $table = 'tbl_alumnos';

    /**
     * Clave primaria
     */
    protected $primaryKey = 'id';

    /**
     * Indica si el modelo debe manejar timestamps
     */
    public $timestamps = false;

    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'dni',
        'apellido',
        'nombre',
        'curso',
        'division',
        'email',
        'telefono',
        'celular',
        'legajo',
        'descripcion_personalizada',
        'anno',
        'carrera',
        'materia',
        'fecha',
        'plan_estudio_id', // FK al plan de estudio asignado (nullable)
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'fecha' => 'datetime',
        'plan_estudio_id' => 'integer',
    ];

    // =========================================================================
    // RELACIONES
    // =========================================================================

    /**
     * Relación: Usuario asociado a este alumno
     */
    public function user()
    {
        return $this->hasOne(User::class, 'alumno_id');
    }

    /**
     * Relación: Carrera del alumno
     */
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera', 'Id');
    }

    /**
     * Relación: Carrera del alumno (alias para evitar conflicto con el atributo)
     */
    public function carreraRelacion()
    {
        return $this->belongsTo(Carrera::class, 'carrera', 'Id');
    }

    /**
     * Relación: Plan de estudio asignado explícitamente al alumno.
     * Puede ser null si el alumno fue creado antes de implementar planes.
     */
    public function planEstudio()
    {
        return $this->belongsTo(PlanEstudio::class, 'plan_estudio_id');
    }

    /**
     * Relación: Historial académico del alumno
     */
    public function materiasCursadas()
    {
        return $this->hasMany(AlumnoMateria::class, 'alumno', 'id');
    }

    // =========================================================================
    // LÓGICA DE PLAN DE ESTUDIO
    // =========================================================================

    /**
     * Resolver el plan de estudio efectivo del alumno.
     *
     * Prioridad:
     *   1. Plan asignado explícitamente (plan_estudio_id en tbl_alumnos).
     *   2. Plan cuyo año (anio) es el más cercano y menor o igual al año de
     *      ingreso del alumno — cubre alumnos históricos sin asignación.
     *   3. Plan marcado como vigente en la carrera — fallback final.
     *
     * Devuelve null si la carrera no tiene ningún plan cargado.
     */
    public function resolverPlan(): ?PlanEstudio
    {
        // 1. Plan asignado explícitamente
        if ($this->plan_estudio_id) {
            return $this->planEstudio ?? PlanEstudio::find($this->plan_estudio_id);
        }

        $carreraId = $this->carrera;

        if (!$carreraId) {
            return null;
        }

        $annoIngreso = (int) ($this->anno ?? now()->year);

        // 2. Plan más reciente cuyo año no supera el año de ingreso
        $plan = PlanEstudio::where('carrera_id', $carreraId)
            ->where('anio', '<=', $annoIngreso)
            ->orderBy('anio', 'desc')
            ->first();

        if ($plan) {
            return $plan;
        }

        // 3. Fallback: plan vigente de la carrera (el admin lo marcó como
        //    vigente para nuevos inscriptos)
        return PlanEstudio::where('carrera_id', $carreraId)
            ->where('vigente', true)
            ->first();
    }

    // =========================================================================
    // ATRIBUTOS CALCULADOS
    // =========================================================================

    /**
     * Nombre completo formateado: "Apellido, Nombre"
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->apellido}, {$this->nombre}";
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope: Buscar por DNI
     */
    public function scopePorDni($query, $dni)
    {
        return $query->where('dni', $dni);
    }

    /**
     * Scope: Filtrar por carrera
     */
    public function scopeDeCarrera($query, $carreraId)
    {
        return $query->where('carrera', $carreraId);
    }
}