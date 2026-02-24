<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermisoDefinicion extends Model
{
    protected $table = 'permisos_definicion';

    protected $fillable = ['clave', 'nombre_legible', 'categoria'];

    public const CATEGORIA_ACCION = 'accion';
    public const CATEGORIA_ACCESO = 'acceso';

    /**
     * Obtiene los nombres legibles indexados por clave.
     */
    public static function obtenerNombres(?string $categoria = null): array
    {
        $query = static::query();

        if ($categoria) {
            $query->where('categoria', $categoria);
        }

        return $query->pluck('nombre_legible', 'clave')->toArray();
    }

    /**
     * Obtiene nombres legibles solo de permisos de acción.
     */
    public static function nombresAccion(): array
    {
        return static::obtenerNombres(self::CATEGORIA_ACCION);
    }

    /**
     * Obtiene nombres legibles solo de permisos de acceso a módulos.
     */
    public static function nombresAcceso(): array
    {
        return static::obtenerNombres(self::CATEGORIA_ACCESO);
    }
}
