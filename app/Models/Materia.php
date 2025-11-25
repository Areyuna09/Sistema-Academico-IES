<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    /**
     * Longitud máxima permitida para el nombre de la materia
     */
    const MAX_NOMBRE_LENGTH = 50; // Ajusta este valor según tus necesidades

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
     * Reglas de validación
     */
    public static function rules($id = null)
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:' . self::MAX_NOMBRE_LENGTH,
                'unique:tbl_materias,nombre,' . $id . ',id,carrera,' . request('carrera')
            ],
            'semestre' => 'required|integer|min:1|max:2',
            'carrera' => 'required|exists:tbl_carreras,Id',
            'anno' => 'required|integer|min:1|max:5',
            'paracursar_regular' => 'nullable|string',
            'paracursar_rendido' => 'nullable|string',
            'pararendir' => 'nullable|string',
            'resolucion' => 'nullable|string|max:255',
        ];
    }

    /**
     * Mensajes de validación personalizados
     */
    public static function messages()
    {
        return [
            'nombre.required' => 'El nombre de la materia es obligatorio',
            'nombre.max' => 'El nombre de la materia no puede superar los ' . self::MAX_NOMBRE_LENGTH . ' caracteres',
            'nombre.unique' => 'Ya existe una materia con este nombre en la carrera seleccionada',
            'semestre.required' => 'El semestre es obligatorio',
            'semestre.min' => 'El semestre debe ser 1 o 2',
            'semestre.max' => 'El semestre debe ser 1 o 2',
            'carrera.required' => 'La carrera es obligatoria',
            'carrera.exists' => 'La carrera seleccionada no existe',
            'anno.required' => 'El año es obligatorio',
            'anno.min' => 'El año debe estar entre 1 y 5',
            'anno.max' => 'El año debe estar entre 1 y 5',
            'resolucion.max' => 'La resolución no puede superar los 255 caracteres',
        ];
    }

    /**
     * Boot del modelo para validaciones automáticas
     */
    protected static function boot()
    {
        parent::boot();

        // Validar antes de guardar
        static::saving(function ($materia) {
            if (strlen($materia->nombre) > self::MAX_NOMBRE_LENGTH) {
                throw new \Exception('El nombre de la materia no puede superar los ' . self::MAX_NOMBRE_LENGTH . ' caracteres');
            }
        });
    }

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

    /**
     * Accessor: Nombre truncado para visualización
     */
    public function getNombreCortoAttribute()
    {
        if (strlen($this->nombre) > 50) {
            return substr($this->nombre, 0, 47) . '...';
        }
        return $this->nombre;
    }
}