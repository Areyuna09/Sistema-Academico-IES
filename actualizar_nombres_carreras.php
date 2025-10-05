<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Carrera;

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   ACTUALIZANDO NOMBRES DE CARRERAS\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$carreras = [
    1 => 'Tecnicatura Superior en Desarrollo de Software',
    2 => 'Profesorado de Educación Inicial',
    3 => 'Profesorado de Educación Primaria',
    4 => 'Tecnicatura Superior en Turismo',
    5 => 'Tecnicatura Superior en Mecatrónica',
];

foreach ($carreras as $id => $nombreCompleto) {
    $carrera = Carrera::find($id);
    if ($carrera) {
        $nombreAnterior = $carrera->nombre;
        $carrera->nombre = $nombreCompleto;
        $carrera->save();
        echo "✓ ID {$id}: {$nombreAnterior} → {$nombreCompleto}\n";
    }
}

echo "\n✅ Carreras actualizadas\n\n";
