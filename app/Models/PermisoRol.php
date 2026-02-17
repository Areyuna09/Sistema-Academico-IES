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
     * Obtiene la matriz completa de permisos (para el frontend).
     */
    public static function obtenerMatriz(): array
    {
        $permisos = static::all();

        $nombres = [
            'puedeCrear'                  => 'Crear registros',
            'puedeModificar'              => 'Modificar registros',
            'puedeEliminar'               => 'Eliminar registros',
            'puedeGestionarUsuarios'      => 'Gestionar usuarios',
            'puedeGestionarInscripciones' => 'Gestionar inscripciones',
            'puedeGestionarMesas'         => 'Gestionar mesas de examen',
            'puedeTomarAsistencias'       => 'Tomar asistencias',
            'puedeCargarNotas'            => 'Cargar notas',
            'puedeAprobarNotas'           => 'Aprobar notas finales',
            'puedeRevisarLegajos'         => 'Revisar legajos',
            'puedeSupervisar'             => 'Supervisar',
        ];

        $tiposUsuario = TipoUsuario::getNombres();

        $matriz = [];
        foreach ($permisos as $p) {
            $matriz[$p->permiso][$p->tipo_usuario] = $p->activo;
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
