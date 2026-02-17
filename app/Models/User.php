<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable, HasApiTokens, CanResetPasswordTrait;

    protected $table = 'tbl_usuarios';

    protected $fillable = [
        'dni',
        'nombre',
        'usuario',
        'clave',
        'email',
        'telefono',
        'tipo',
        'activo',
        'pais',
        'provincia',
        'sexo',
        'avatar',
        'alumno_id',
        'profesor_id',
    ];

    protected $hidden = [
        'clave',
        'remember_token',
    ];

    protected $appends = [
        'name',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'tipo' => 'integer',
            'activo' => 'boolean',
        ];
    }

    public function getAuthPassword()
    {
        return $this->clave;
    }

    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    public function getNameAttribute()
    {
        return $this->attributes['nombre'] ?? $this->attributes['usuario'] ?? $this->email ?? 'Usuario';
    }

    public function setNameAttribute($value)
    {
        $this->attributes['nombre'] = $value;
    }

    public function getPasswordAttribute()
    {
        return $this->attributes['clave'] ?? '';
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['clave'] = bcrypt($value);
    }

    public function getRoleAttribute()
    {
        $roleMap = [
            TipoUsuario::ADMIN => 'admin',
            TipoUsuario::USUARIO => 'bedel',
            TipoUsuario::PROFESOR => 'profesor',
            TipoUsuario::ALUMNO => 'alumno',
            TipoUsuario::DIRECTIVO => 'directivo',
            TipoUsuario::SUPERVISOR => 'supervisor',
            TipoUsuario::BEDEL => 'bedel',
            TipoUsuario::PRECEPTOR => 'preceptor',
        ];

        return $roleMap[$this->attributes['tipo'] ?? 4] ?? 'alumno';
    }

    public function setRoleAttribute($value)
    {
        $typeMap = [
            'admin' => TipoUsuario::ADMIN,
            'profesor' => TipoUsuario::PROFESOR,
            'alumno' => TipoUsuario::ALUMNO,
            'directivo' => TipoUsuario::DIRECTIVO,
            'supervisor' => TipoUsuario::SUPERVISOR,
            'bedel' => TipoUsuario::BEDEL,
            'preceptor' => TipoUsuario::PRECEPTOR,
        ];

        $this->attributes['tipo'] = $typeMap[$value] ?? TipoUsuario::ALUMNO;
    }

    // ========================================
    // MÉTODOS DE VERIFICACIÓN DE ROL
    // ========================================

    public function isAdmin(): bool
    {
        return $this->tipo === TipoUsuario::ADMIN;
    }

    public function isProfesor(): bool
    {
        return $this->tipo === TipoUsuario::PROFESOR;
    }

    public function isAlumno(): bool
    {
        return $this->tipo === TipoUsuario::ALUMNO;
    }

    public function isDirectivo(): bool
    {
        return $this->tipo === TipoUsuario::DIRECTIVO;
    }

    public function isSupervisor(): bool
    {
        return $this->tipo === TipoUsuario::SUPERVISOR;
    }

    public function isBedel(): bool
    {
        return $this->tipo === TipoUsuario::BEDEL || $this->tipo === TipoUsuario::USUARIO;
    }

    public function isPreceptor(): bool
    {
        return $this->tipo === TipoUsuario::PRECEPTOR;
    }

    // ========================================
    // MÉTODOS DE PERMISOS GRANULARES
    // ========================================

    /**
     * Verifica si el usuario puede crear registros
     */
    public function puedeCrear(): bool
    {
        return in_array($this->tipo, [
            TipoUsuario::ADMIN,
            TipoUsuario::BEDEL,
            TipoUsuario::PRECEPTOR,
        ]);
    }

    /**
     * Verifica si el usuario puede modificar registros
     */
    public function puedeModificar(): bool
    {
        return in_array($this->tipo, [
            TipoUsuario::ADMIN,
            TipoUsuario::BEDEL,
            TipoUsuario::PRECEPTOR,
        ]);
    }

    /**
     * Verifica si el usuario puede eliminar registros
     */
    public function puedeEliminar(): bool
    {
        return in_array($this->tipo, [
            TipoUsuario::ADMIN,
            TipoUsuario::BEDEL,
        ]);
    }

    /**
     * Verifica si el usuario tiene permisos de administrador
     */
    public function esAdministrativo(): bool
    {
        return in_array($this->tipo, TipoUsuario::rolesAdministrativos());
    }

    /**
     * Verifica si el usuario tiene rol de revisión
     */
    public function esRevisor(): bool
    {
        return in_array($this->tipo, TipoUsuario::rolesRevision());
    }

    /**
     * Verifica si el usuario solo tiene permisos de lectura
     */
    public function esSoloLectura(): bool
    {
        return in_array($this->tipo, TipoUsuario::rolesSoloLectura());
    }

    /**
     * Verifica si puede acceder al panel administrativo
     */
    public function puedeAccederAdmin(): bool
    {
        return $this->esAdministrativo() || $this->tipo === TipoUsuario::PROFESOR;
    }

    /**
     * Verifica si puede gestionar usuarios
     */
    public function puedeGestionarUsuarios(): bool
    {
        return in_array($this->tipo, [
            TipoUsuario::ADMIN,
            TipoUsuario::BEDEL,
        ]);
    }

    /**
     * Verifica si puede gestionar inscripciones
     */
    public function puedeGestionarInscripciones(): bool
    {
        return in_array($this->tipo, [
            TipoUsuario::ADMIN,
            TipoUsuario::BEDEL,
            TipoUsuario::PRECEPTOR,
        ]);
    }

    /**
     * Verifica si puede revisar legajos (primer nivel)
     */
    public function puedeRevisarLegajos(): bool
    {
        return $this->tipo === TipoUsuario::DIRECTIVO || $this->isAdmin();
    }

    /**
     * Verifica si puede supervisar (segundo nivel)
     */
    public function puedeSupervisar(): bool
    {
        return $this->tipo === TipoUsuario::SUPERVISOR || $this->isAdmin();
    }

    /**
     * Verifica si puede gestionar mesas de examen
     */
    public function puedeGestionarMesas(): bool
    {
        return in_array($this->tipo, [
            TipoUsuario::ADMIN,
            TipoUsuario::BEDEL,
            TipoUsuario::PRECEPTOR,
        ]);
    }

    /**
     * Verifica si puede tomar asistencias
     */
    public function puedeTomarAsistencias(): bool
    {
        return in_array($this->tipo, [
            TipoUsuario::ADMIN,
            TipoUsuario::PROFESOR,
            TipoUsuario::PRECEPTOR,
        ]);
    }

    /**
     * Verifica si puede cargar notas
     */
    public function puedeCargarNotas(): bool
    {
        return in_array($this->tipo, [
            TipoUsuario::ADMIN,
            TipoUsuario::PROFESOR,
        ]);
    }

    /**
     * Verifica si puede aprobar notas finales
     */
    public function puedeAprobarNotas(): bool
    {
        return in_array($this->tipo, [
            TipoUsuario::ADMIN,
            TipoUsuario::BEDEL,
        ]);
    }

    // ========================================
    // RELACIONES
    // ========================================

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'profesor_id');
    }

    public function username()
    {
        return 'dni';
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}