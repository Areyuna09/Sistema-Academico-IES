<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Usar la tabla legacy tbl_usuarios
     */
    protected $table = 'tbl_usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'dni',
        'nombre',
        'usuario',
        'clave',
        'email',
        'telefono',
        'tipo',
        'pais',
        'provincia',
        'sexo',
        'avatar',
        'alumno_id',
        'profesor_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'clave',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'tipo' => 'integer',
        ];
    }

    /**
     * Sobrescribir el nombre del campo de password para Laravel Auth
     */
    public function getAuthPassword()
    {
        return $this->clave;
    }

    /**
     * Obtener el nombre del campo usado para autenticación (username)
     */
    public function getAuthIdentifierName()
    {
        return 'dni';
    }

    /**
     * Obtener el valor del identificador de autenticación
     */
    public function getAuthIdentifier()
    {
        return $this->dni;
    }

    /**
     * Accessor para 'name' (Laravel espera este campo)
     */
    public function getNameAttribute()
    {
        return $this->attributes['nombre'] ?? '';
    }

    /**
     * Mutator para 'name'
     */
    public function setNameAttribute($value)
    {
        $this->attributes['nombre'] = $value;
    }

    /**
     * Accessor para 'password' (mapear a 'clave')
     */
    public function getPasswordAttribute()
    {
        return $this->attributes['clave'] ?? '';
    }

    /**
     * Mutator para 'password' (guardar en 'clave')
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['clave'] = bcrypt($value);
    }

    /**
     * Accessor para 'role' basado en 'tipo'
     * 1 = admin, 2 = usuario, 3 = profesor, 4 = alumno
     */
    public function getRoleAttribute()
    {
        $roleMap = [
            1 => 'admin',
            2 => 'alumno',
            3 => 'profesor',
            4 => 'alumno',
        ];

        return $roleMap[$this->attributes['tipo'] ?? 4] ?? 'alumno';
    }

    /**
     * Mutator para 'role'
     */
    public function setRoleAttribute($value)
    {
        $typeMap = [
            'admin' => 1,
            'profesor' => 3,
            'alumno' => 4,
        ];

        $this->attributes['tipo'] = $typeMap[$value] ?? 4;
    }

    /**
     * Relación con el alumno
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    /**
     * Relación con el profesor
     */
    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'profesor_id');
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Verificar si el usuario es profesor
     */
    public function isProfesor()
    {
        return $this->role === 'profesor';
    }

    /**
     * Verificar si el usuario es alumno
     */
    public function isAlumno()
    {
        return $this->role === 'alumno';
    }

    /**
     * Obtener el campo de username para autenticación (DNI)
     */
    public function username()
    {
        return 'dni';
    }
}
