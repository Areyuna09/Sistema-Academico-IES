<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'tbl_configuracion';

    protected $fillable = [
        'nombre_institucion',
        'logo_path',
        'logo_dark_path',
        'direccion',
        'telefono',
        'email',
        'sitio_web',
        'footer_documentos',
        'firma_digital_path',
        'cargo_firma',
        'horarios_atencion',
    ];

    /**
     * Obtener la configuración (Singleton)
     * Siempre retorna el primer (y único) registro
     */
    public static function get()
    {
        return static::first() ?? static::create([
            'nombre_institucion' => 'Instituto de Educación Superior',
        ]);
    }

    /**
     * Prevenir la creación de múltiples registros
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Si ya existe un registro, no permitir crear otro
            if (static::count() > 0) {
                throw new \Exception('Solo puede existir una configuración general.');
            }
        });
    }
}
