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
    const USUARIO = 2; // Bedel legacy
    const PROFESOR = 3;
    const ALUMNO = 4;
    const DIRECTIVO = 5;
    const SUPERVISOR = 6;
    const BEDEL = 7; // Nuevo rol específico
    const PRECEPTOR = 8; // Nuevo rol específico

    /**
     * Mapa de nombres de roles
     */
    public static function getNombres(): array
    {
        return [
            self::ADMIN => 'Administrador',
            self::USUARIO => 'Usuario (Legacy)',
            self::PROFESOR => 'Profesor',
            self::ALUMNO => 'Alumno',
            self::DIRECTIVO => 'Directivo',
            self::SUPERVISOR => 'Supervisor',
            self::BEDEL => 'Bedel',
            self::PRECEPTOR => 'Preceptor',
        ];
    }

    /**
     * Obtener nombre del rol
     */
    public static function getNombre(int $tipo): string
    {
        return self::getNombres()[$tipo] ?? 'Desconocido';
    }

    /**
     * Roles con permisos de modificación
     */
    public static function rolesConPermisoModificacion(): array
    {
        return [
            self::ADMIN,
            self::BEDEL,
            self::PRECEPTOR,
        ];
    }

    /**
     * Roles con permisos de solo lectura
     */
    public static function rolesSoloLectura(): array
    {
        return [
            self::DIRECTIVO,
            self::SUPERVISOR,
        ];
    }

    /**
     * Roles administrativos (acceso a panel admin)
     */
    public static function rolesAdministrativos(): array
    {
        return [
            self::ADMIN,
            self::BEDEL,
            self::PRECEPTOR,
        ];
    }

    /**
     * Roles de revisión (flujo de aprobación)
     */
    public static function rolesRevision(): array
    {
        return [
            self::DIRECTIVO,
            self::SUPERVISOR,
        ];
    }
}