<?php

namespace App\Enums;

class TipoUsuario
{
    const ADMIN = 1;
    const USUARIO = 2;
    const PROFESOR = 3;
    const ALUMNO = 4;

    /**
     * Obtener todos los tipos de usuario
     */
    public static function all(): array
    {
        return [
            self::ADMIN => 'Admin',
            self::USUARIO => 'Usuario',
            self::PROFESOR => 'Profesor',
            self::ALUMNO => 'Alumno',
        ];
    }

    /**
     * Obtener el nombre del tipo de usuario
     */
    public static function nombre(int $tipo): string
    {
        return self::all()[$tipo] ?? 'Desconocido';
    }

    /**
     * Validar si un tipo de usuario es válido
     */
    public static function esValido(int $tipo): bool
    {
        return array_key_exists($tipo, self::all());
    }
}
