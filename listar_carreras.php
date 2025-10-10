<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CARRERAS EN LA BASE DE DATOS ===\n\n";

$carreras = DB::table('tbl_carreras')->select('Id', 'nombre')->get();

foreach ($carreras as $carrera) {
    echo "ID {$carrera->Id}: {$carrera->nombre}\n";
}

echo "\n=== CARRERAS CORRECTAS SEGÚN EL IES ===\n\n";
echo "1. Tecnicatura Superior en Desarrollo de Software\n";
echo "2. Tecnicatura Superior en Turismo\n";
echo "3. Tecnicatura Superior en Minería\n";
echo "4. Profesorado de Educación Primaria (PEP)\n";
echo "5. Profesorado de Educación Inicial (PEI)\n";
