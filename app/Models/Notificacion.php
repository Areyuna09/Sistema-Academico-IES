<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notificacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id',
        'tipo',
        'titulo',
        'mensaje',
        'icono',
        'color',
        'leida',
        'fecha_lectura',
        'url',
        'datos',
    ];

    protected $casts = [
        'leida' => 'boolean',
        'fecha_lectura' => 'datetime',
        'datos' => 'array',
    ];

    protected $appends = ['tiempo_transcurrido'];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes
     */
    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    public function scopeLeidas($query)
    {
        return $query->where('leida', true);
    }

    public function scopeRecientes($query, $dias = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($dias));
    }

    /**
     * Marcar como leída
     */
    public function marcarComoLeida()
    {
        if (!$this->leida) {
            $this->update([
                'leida' => true,
                'fecha_lectura' => now(),
            ]);
        }
    }

    /**
     * Accessor para tiempo transcurrido (ej: "hace 2 horas")
     */
    public function getTiempoTranscurridoAttribute()
    {
        $diff = $this->created_at->diff(now());

        if ($diff->d >= 7) {
            return $this->created_at->format('d/m/Y');
        } elseif ($diff->d > 0) {
            return $diff->d . ' día' . ($diff->d > 1 ? 's' : '');
        } elseif ($diff->h > 0) {
            return $diff->h . ' hora' . ($diff->h > 1 ? 's' : '');
        } elseif ($diff->i > 0) {
            return $diff->i . ' minuto' . ($diff->i > 1 ? 's' : '');
        } else {
            return 'Ahora';
        }
    }

    /**
     * Helper estático para crear notificaciones
     */
    public static function crear($userId, $tipo, $titulo, $mensaje, $opciones = [])
    {
        return self::create(array_merge([
            'user_id' => $userId,
            'tipo' => $tipo,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
        ], $opciones));
    }
}
