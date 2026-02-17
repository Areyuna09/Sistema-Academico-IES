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
        return $this->attributes['nombre'] ?? '';
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

    public function puedeCrear(): bool
    {
        return PermisoRol::tienePermiso('puedeCrear', $this->tipo);
    }

    public function puedeModificar(): bool
    {
        return PermisoRol::tienePermiso('puedeModificar', $this->tipo);
    }

    public function puedeEliminar(): bool
    {
        return PermisoRol::tienePermiso('puedeEliminar', $this->tipo);
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

    public function puedeGestionarUsuarios(): bool
    {
        return PermisoRol::tienePermiso('puedeGestionarUsuarios', $this->tipo);
    }

    public function puedeGestionarInscripciones(): bool
    {
        return PermisoRol::tienePermiso('puedeGestionarInscripciones', $this->tipo);
    }

    public function puedeRevisarLegajos(): bool
    {
        return PermisoRol::tienePermiso('puedeRevisarLegajos', $this->tipo);
    }

    public function puedeSupervisar(): bool
    {
        return PermisoRol::tienePermiso('puedeSupervisar', $this->tipo);
    }

    public function puedeGestionarMesas(): bool
    {
        return PermisoRol::tienePermiso('puedeGestionarMesas', $this->tipo);
    }

    public function puedeTomarAsistencias(): bool
    {
        return PermisoRol::tienePermiso('puedeTomarAsistencias', $this->tipo);
    }

    public function puedeCargarNotas(): bool
    {
        return PermisoRol::tienePermiso('puedeCargarNotas', $this->tipo);
    }

    public function puedeAprobarNotas(): bool
    {
        return PermisoRol::tienePermiso('puedeAprobarNotas', $this->tipo);
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