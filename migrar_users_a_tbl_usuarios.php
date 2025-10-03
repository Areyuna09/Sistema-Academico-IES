<?php

/**
 * Script para migrar usuarios de la tabla 'users' a 'tbl_usuarios'
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Migrando usuarios de 'users' a 'tbl_usuarios' ===\n\n";

// Obtener todos los usuarios de la tabla users
$users = DB::table('users')->get();

$migrados = 0;
$existentes = 0;
$errores = 0;

foreach ($users as $user) {
    echo "Usuario: {$user->name} (DNI: {$user->dni})\n";

    // Verificar si ya existe en tbl_usuarios
    $existeEnTblUsuarios = DB::table('tbl_usuarios')
        ->where('dni', $user->dni)
        ->first();

    if ($existeEnTblUsuarios) {
        echo "  → Ya existe en tbl_usuarios\n";

        // Actualizar campos adicionales si es necesario
        DB::table('tbl_usuarios')
            ->where('dni', $user->dni)
            ->update([
                'alumno_id' => $user->alumno_id,
                'profesor_id' => $user->profesor_id,
                'updated_at' => now(),
            ]);

        $existentes++;
    } else {
        // Mapear role a tipo
        $tipoMap = [
            'admin' => 1,
            'profesor' => 3,
            'alumno' => 4,
        ];

        $tipo = $tipoMap[$user->role] ?? 4;

        try {
            // Insertar en tbl_usuarios
            DB::table('tbl_usuarios')->insert([
                'dni' => $user->dni,
                'nombre' => $user->name,
                'usuario' => $user->dni, // Usar DNI como usuario
                'clave' => $user->password, // Ya está hasheado
                'email' => $user->email,
                'telefono' => null,
                'tipo' => $tipo,
                'pais' => 1,
                'provincia' => 1,
                'sexo' => 1,
                'avatar' => null,
                'alumno_id' => $user->alumno_id,
                'profesor_id' => $user->profesor_id,
                'remember_token' => $user->remember_token,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'email_verified_at' => $user->email_verified_at,
            ]);

            echo "  ✓ Migrado exitosamente\n";
            $migrados++;
        } catch (\Exception $e) {
            echo "  ✗ Error: " . $e->getMessage() . "\n";
            $errores++;
        }
    }

    echo "\n";
}

echo "=== Resumen ===\n";
echo "Usuarios migrados: $migrados\n";
echo "Usuarios ya existentes: $existentes\n";
echo "Errores: $errores\n";
echo "\n¡Listo!\n";
