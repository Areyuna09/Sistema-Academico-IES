<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ConfiguracionModulo extends Model
{
    protected $table = 'configuracion_modulos';

    protected $fillable = [
        'clave',
        'nombre',
        'descripcion',
        'categoria',
        'activo',
        'orden',
        'icono',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
    ];

    /**
     * Verificar si un módulo está activo
     */
    public static function estaActivo(string $clave): bool
    {
        return Cache::remember("modulo_{$clave}", 3600, function () use ($clave) {
            $modulo = self::where('clave', $clave)->first();
            return $modulo ? $modulo->activo : true; // Por defecto activo si no existe
        });
    }

    /**
     * Limpiar cache de módulos
     */
    public static function limpiarCache(?string $clave = null): void
    {
        if ($clave) {
            Cache::forget("modulo_{$clave}");
        } else {
            // Limpiar todo el cache de módulos
            $modulos = self::all();
            foreach ($modulos as $modulo) {
                Cache::forget("modulo_{$modulo->clave}");
            }
        }
    }

    /**
     * Al guardar, limpiar el cache
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($modulo) {
            self::limpiarCache($modulo->clave);
        });

        static::deleted(function ($modulo) {
            self::limpiarCache($modulo->clave);
        });
    }
}
