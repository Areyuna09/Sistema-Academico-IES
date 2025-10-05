<?php

/**
 * Script para crear un usuario de prueba en tbl_usuarios con contraseña conocida
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "=== Creando usuario de prueba en tbl_usuarios ===\n\n";

$dni = '46180633';
$password = '123456';

// Verificar si el usuario ya existe
$existente = DB::table('tbl_usuarios')->where('dni', $dni)->first();

if ($existente) {
    echo "Usuario con DNI {$dni} ya existe. Actualizando contraseña...\n";

    DB::table('tbl_usuarios')
        ->where('dni', $dni)
        ->update([
            'clave' => Hash::make($password),
            'updated_at' => now(),
        ]);

    echo "✓ Contraseña actualizada exitosamente\n";
} else {
    echo "Creando nuevo usuario...\n";

    // Buscar alumno con ese DNI
    $alumno = DB::table('tbl_alumnos')->where('dni', $dni)->first();

    if ($alumno) {
        DB::table('tbl_usuarios')->insert([
            'dni' => $dni,
            'nombre' => $alumno->nombre . ' ' . $alumno->apellido,
            'usuario' => $dni,
            'clave' => Hash::make($password),
            'email' => $alumno->email ?? 'test@ies.edu.ar',
            'telefono' => $alumno->telefono,
            'tipo' => 4, // alumno
            'pais' => 1,
            'provincia' => 1,
            'sexo' => 1,
            'avatar' => null,
            'alumno_id' => $alumno->id,
            'profesor_id' => null,
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'email_verified_at' => null,
        ]);

        echo "✓ Usuario creado exitosamente\n";
    } else {
        echo "✗ No se encontró alumno con DNI {$dni}\n";
    }
}

echo "\n=== Datos del usuario ===\n";
echo "DNI: {$dni}\n";
echo "Contraseña: {$password}\n";
echo "\n¡Listo! Ahora puedes hacer login.\n";
