<?php

/**
 * Script para debuggear autenticación
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

echo "=== Debug de Autenticación ===\n\n";

$dni = '46180633';
$password = '123456';

// 1. Verificar usuario en tbl_usuarios
echo "1. Buscando usuario con DNI: {$dni}\n";
$usuario = DB::table('tbl_usuarios')->where('dni', $dni)->first();

if ($usuario) {
    echo "✓ Usuario encontrado:\n";
    echo "  - ID: {$usuario->id}\n";
    echo "  - Nombre: {$usuario->nombre}\n";
    echo "  - Email: {$usuario->email}\n";
    echo "  - Tipo: {$usuario->tipo}\n";
    echo "  - Clave (hash): " . substr($usuario->clave, 0, 20) . "...\n\n";

    // 2. Verificar que la contraseña coincida
    echo "2. Verificando contraseña...\n";
    if (Hash::check($password, $usuario->clave)) {
        echo "✓ La contraseña es correcta\n\n";
    } else {
        echo "✗ La contraseña NO coincide\n";
        echo "  Rehasheando contraseña...\n";

        $newHash = Hash::make($password);
        DB::table('tbl_usuarios')
            ->where('dni', $dni)
            ->update(['clave' => $newHash]);

        echo "  ✓ Contraseña actualizada\n\n";
    }

    // 3. Probar autenticación con Auth::attempt
    echo "3. Probando autenticación con Auth::attempt...\n";

    // Probar con dni + clave
    $credentials = ['dni' => $dni, 'clave' => $password];
    echo "  Intentando con: " . json_encode($credentials) . "\n";

    if (Auth::attempt($credentials)) {
        echo "  ✓ Autenticación exitosa\n";
        Auth::logout();
    } else {
        echo "  ✗ Autenticación fallida\n";

        // Intentar manualmente
        echo "\n  Intentando autenticación manual...\n";
        $user = \App\Models\User::where('dni', $dni)->first();
        if ($user) {
            echo "  - Usuario encontrado por modelo\n";
            echo "  - Auth Identifier Name: " . $user->getAuthIdentifierName() . "\n";
            echo "  - Auth Identifier: " . $user->getAuthIdentifier() . "\n";
            echo "  - Auth Password: " . substr($user->getAuthPassword(), 0, 20) . "...\n";

            if (Hash::check($password, $user->getAuthPassword())) {
                echo "  - ✓ Password check manual exitoso\n";
                Auth::login($user);
                echo "  - ✓ Login manual exitoso\n";
                Auth::logout();
            }
        }
    }

    // 4. Ver qué columna usa el modelo User para password
    echo "\n4. Verificando configuración del modelo User...\n";
    $user = new \App\Models\User();
    echo "  - Tabla: {$user->getTable()}\n";
    echo "  - Columna de password: " . (method_exists($user, 'getAuthPassword') ? 'usa getAuthPassword()' : 'password') . "\n";

} else {
    echo "✗ Usuario NO encontrado\n";
}

echo "\n=== Fin del debug ===\n";
