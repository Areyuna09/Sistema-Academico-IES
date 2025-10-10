<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CORRECCIÓN DE CARRERA: MECATRÓNICA → MINERÍA ===\n\n";

// Buscar la carrera de Mecatrónica
$carrera = DB::table('tbl_carreras')->where('nombre', 'LIKE', '%Mecatrónica%')->first();

if ($carrera) {
    echo "✓ Encontrada: ID {$carrera->Id} - {$carrera->nombre}\n";

    // Actualizar a Minería
    DB::table('tbl_carreras')
        ->where('Id', $carrera->Id)
        ->update(['nombre' => 'Tecnicatura Superior en Minería']);

    echo "✓ Actualizada a: Tecnicatura Superior en Minería\n\n";
} else {
    echo "⚠ No se encontró la carrera de Mecatrónica\n\n";
}

echo "=== CARRERAS ACTUALIZADAS ===\n\n";

$carreras = DB::table('tbl_carreras')->select('Id', 'nombre')->orderBy('Id')->get();

foreach ($carreras as $c) {
    echo "ID {$c->Id}: {$c->nombre}\n";
}

echo "\n✅ Corrección completada\n";
