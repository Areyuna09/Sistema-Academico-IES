<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    /**
     * Nombre de la tabla legacy
     */
    protected $table = 'tbl_tipos_usuarios';

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
    ];

    /**
     * Constantes para tipos de usuario
     */
    const ADMIN = 1;
    const USUARIO = 2;
    const PROFESOR = 3;
    const ALUMNO = 4;
    const DIRECTIVO = 5;
    const SUPERVISOR = 6;
}
