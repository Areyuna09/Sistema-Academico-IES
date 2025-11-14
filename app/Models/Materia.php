<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    /**
     * Nombre de la tabla legacy
     */
    protected $table = 'tbl_materias';

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
        'nombre',
        'semestre',
        'carrera',
        'anno',
        'paracursar_regular',
        'paracursar_rendido',
        'pararendir',
        'resolucion',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'semestre' => 'integer',
        'anno' => 'integer',
        'carrera' => 'integer',
    ];

    /**
     * Relación: Carrera a la que pertenece
     */
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera', 'Id');
    }

    /**
     * Relación: Reglas de correlativas configuradas
     */
    public function reglasCorrelativas()
    {
        return $this->hasMany(ReglaCorrelativa::class, 'materia_id', 'id');
    }

    /**
     * Relación: Alumnos que cursaron/rindieron esta materia
     */
    public function alumnosMaterias()
    {
        return $this->hasMany(AlumnoMateria::class, 'materia', 'id');
    }

    /**
     * Relación: Profesores que dictan esta materia
     */
    public function profesores()
    {
        return $this->belongsToMany(Profesor::class, 'tbl_profesor_tiene_materias', 'materia', 'profesor');
    }

    /**
     * Obtener correlativas para cursar (parseadas)
     */
    public function getCorrelativasParaCursarRegularAttribute()
    {
        return $this->parsearCorrelativas($this->paracursar_regular);
    }

    /**
     * Obtener correlativas rendidas para cursar (parseadas)
     */
    public function getCorrelativasParaCursarRendidoAttribute()
    {
        return $this->parsearCorrelativas($this->paracursar_rendido);
    }

    /**
     * Obtener correlativas para rendir (parseadas)
     */
    public function getCorrelativasParaRendirAttribute()
    {
        return $this->parsearCorrelativas($this->pararendir);
    }

    /**
     * Parsear string de correlativas (formato: "1:2:3" o "1")
     */
    private function parsearCorrelativas($correlativasString)
    {
        if (empty($correlativasString)) {
            return [];
        }

        return array_map('intval', explode(':', $correlativasString));
    }

    /**
     * Scope: Por carrera
     */
    public function scopeDeCarrera($query, $carreraId)
    {
        return $query->where('carrera', $carreraId);
    }

    /**
     * Scope: Por semestre
     */
    public function scopeDeSemestre($query, $semestre)
    {
        return $query->where('semestre', $semestre);
    }

    /**
     * Scope: Por año
     */
    public function scopeDeAnno($query, $anno)
    {
        return $query->where('anno', $anno);
    }
}
