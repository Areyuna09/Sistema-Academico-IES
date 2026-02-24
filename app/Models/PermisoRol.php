<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PermisoRol extends Model
{
    protected $table = 'permisos_rol';

    protected $fillable = ['permiso', 'tipo_usuario', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
        'tipo_usuario' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            static::limpiarCache();
        });
    }

    /**
     * Verifica si un tipo de usuario tiene un permiso activo.
     * Admin (tipo 1) siempre retorna true.
     */
    public static function tienePermiso(string $permiso, int $tipoUsuario): bool
    {
        if ($tipoUsuario === TipoUsuario::ADMIN) {
            return true;
        }

        $matriz = static::obtenerMatrizCache();

        return $matriz[$permiso][$tipoUsuario] ?? false;
    }

    /**
     * Verifica si un tipo de usuario tiene acceso a un módulo admin.
     */
    public static function tieneAccesoModulo(string $moduloClave, int $tipoUsuario): bool
    {
        return static::tienePermiso('acceso:' . $moduloClave, $tipoUsuario);
    }

    /**
     * Obtiene todos los accesos a módulos para un tipo de usuario.
     * Retorna array ['admin_usuarios' => true, 'admin_carreras' => false, ...]
     */
    public static function obtenerAccesosModulo(int $tipoUsuario): array
    {
        $matriz = static::obtenerMatrizCache();
        $accesos = [];

        foreach ($matriz as $permiso => $tipos) {
            if (str_starts_with($permiso, 'acceso:')) {
                $clave = substr($permiso, 7); // quitar 'acceso:'
                if ($tipoUsuario === TipoUsuario::ADMIN) {
                    $accesos[$clave] = true;
                } else {
                    $accesos[$clave] = !empty($tipos[$tipoUsuario]);
                }
            }
        }

        return $accesos;
    }

    /**
     * Verifica si un tipo de usuario tiene al menos un acceso admin activo.
     */
    public static function tieneAlgunAccesoAdmin(int $tipoUsuario): bool
    {
        if ($tipoUsuario === TipoUsuario::ADMIN) {
            return true;
        }

        $accesos = static::obtenerAccesosModulo($tipoUsuario);

        foreach ($accesos as $activo) {
            if ($activo) return true;
        }

        return false;
    }

    /**
     * Obtiene la matriz completa de permisos (para el frontend).
     * Se puede filtrar por categoría: 'accion', 'acceso', o null para todas.
     */
    public static function obtenerMatriz(?string $categoria = null): array
    {
        $permisos = static::all();
        $tiposUsuario = TipoUsuario::getNombres();

        // Obtener nombres legibles desde permisos_definicion
        $nombres = PermisoDefinicion::obtenerNombres($categoria);

        // Filtrar permisos según las claves que existan en definiciones
        $matriz = [];
        foreach ($permisos as $p) {
            if ($categoria === null || array_key_exists($p->permiso, $nombres)) {
                $matriz[$p->permiso][$p->tipo_usuario] = $p->activo;
            }
        }

        return [
            'matriz'        => $matriz,
            'nombres'       => $nombres,
            'tiposUsuario'  => $tiposUsuario,
        ];
    }

    /**
     * Cache interno de la matriz para consultas en la misma request.
     */
    private static function obtenerMatrizCache(): array
    {
        return Cache::remember('permisos_rol_matriz', 3600, function () {
            $permisos = static::all();
            $matriz = [];
            foreach ($permisos as $p) {
                $matriz[$p->permiso][$p->tipo_usuario] = $p->activo;
            }
            return $matriz;
        });
    }

    public static function limpiarCache(): void
    {
        Cache::forget('permisos_rol_matriz');
    }
}
