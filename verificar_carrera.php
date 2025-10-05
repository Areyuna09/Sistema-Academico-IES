<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Carrera;
use Illuminate\Support\Facades\DB;

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   VERIFICANDO TABLA DE CARRERAS\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// Ver estructura
echo "ESTRUCTURA DE tbl_carreras:\n";
$columns = DB::select("DESCRIBE tbl_carreras");
foreach ($columns as $col) {
    echo "  - {$col->Field} ({$col->Type})\n";
}

echo "\n\nCARRERAS EN BASE DE DATOS:\n";
$carreras = Carrera::all();

foreach ($carreras as $c) {
    echo "\nID: {$c->Id}\n";
    foreach ($c->getAttributes() as $campo => $valor) {
        if ($valor) {
            echo "  {$campo}: {$valor}\n";
        }
    }
}
