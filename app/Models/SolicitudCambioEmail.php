<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudCambioEmail extends Model
{
    protected $table = 'solicitudes_cambio_email';

    protected $fillable = [
        'user_id',
        'dni',
        'email_actual',
        'email_nuevo',
        'motivo',
        'estado',
        'revisado_por',
        'fecha_revision',
        'comentario_admin',
        'ip_solicitud',
    ];

    protected $casts = [
        'fecha_revision' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que solicita
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con el admin que revisó
     */
    public function revisor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    /**
     * Scopes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'aprobada');
    }

    public function scopeRechazadas($query)
    {
        return $query->where('estado', 'rechazada');
    }

    public function scopeRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Accessor para estado en texto
     */
    public function getEstadoTextoAttribute()
    {
        return match($this->estado) {
            'pendiente' => 'Pendiente',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
            default => $this->estado,
        };
    }

    /**
     * Accessor para color de badge según estado
     */
    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'pendiente' => 'yellow',
            'aprobada' => 'green',
            'rechazada' => 'red',
            default => 'gray',
        };
    }
}
