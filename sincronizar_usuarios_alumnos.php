<?php

/**
 * Script para sincronizar usuarios de tbl_usuarios con alumnos de tbl_alumnos
 * Busca alumnos por DNI y actualiza el campo alumno_id en tbl_usuarios
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Sincronizando usuarios con alumnos ===\n\n";

// Obtener todos los usuarios
$usuarios = DB::table('tbl_usuarios')->get();

$sincronizados = 0;
$noEncontrados = 0;

foreach ($usuarios as $usuario) {
    echo "Usuario: {$usuario->nombre} (DNI: {$usuario->dni})\n";

    // Buscar alumno por DNI
    $alumno = DB::table('tbl_alumnos')
        ->where('dni', $usuario->dni)
        ->first();

    if ($alumno) {
        // Actualizar usuario con alumno_id
        DB::table('tbl_usuarios')
            ->where('id', $usuario->id)
            ->update([
                'alumno_id' => $alumno->id,
                'updated_at' => now(),
            ]);

        echo "  ✓ Sincronizado con alumno ID: {$alumno->id} ({$alumno->nombre} {$alumno->apellido})\n";
        $sincronizados++;
    } else {
        echo "  ✗ No se encontró alumno con DNI: {$usuario->dni}\n";
        $noEncontrados++;
    }

    echo "\n";
}

echo "=== Resumen ===\n";
echo "Usuarios sincronizados: $sincronizados\n";
echo "Usuarios sin alumno: $noEncontrados\n";
echo "\n¡Listo!\n";
