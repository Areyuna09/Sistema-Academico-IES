<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== LIMPIEZA COMPLETA DE CORRELATIVAS ===\n\n";

// PASO 1: Limpiar TODAS las materias sin excepción
echo "PASO 1: Limpiando TODAS las correlativas...\n";
$affected = DB::table('tbl_materias')->update([
    'paracursar_regular' => null,
    'paracursar_rendido' => null
]);
echo "✓ {$affected} materias limpiadas\n\n";

// PASO 2: Configurar SOLO las 11 materias que deben tener correlativas
echo "PASO 2: Configurando las 11 materias con correlativas correctas...\n\n";

$correlativas_correctas = [
    // Programación II (ID 8) requiere Programación I (ID 6)
    8 => 6,
    // Programación III (ID 15) requiere Programación II (ID 8)
    15 => 8,
    // Sistemas de Información II (ID 19) requiere Sistemas de Información I (ID 4)
    19 => 4,
    // Matemática II (ID 13) requiere Matemática I (ID 2)
    13 => 2,
    // Matemática III (ID 18) requiere Matemática II (ID 13)
    18 => 13,
    // Sistemas Operativos y Redes II (ID 23) requiere Sistemas Operativos y Redes I (ID 11)
    23 => 11,
    // Desarrollo de Software II (ID 25) requiere Desarrollo de Software I (ID 20)
    25 => 20,
    // Practica Profesionalizante II (ID 17) requiere Practica Profesionalizante I (ID 10)
    17 => 10,
    // Practica Profesionalizante III (ID 21) requiere Practica Profesionalizante II (ID 17)
    21 => 17,
    // Practica Profesionalizante IV (ID 24) requiere Practica Profesionalizante III (ID 21)
    24 => 21,
    // Practica Profesionalizante V (ID 27) requiere Practica Profesionalizante IV (ID 24)
    27 => 24,
];

foreach ($correlativas_correctas as $materiaId => $correlativaId) {
    $materia = DB::table('tbl_materias')->where('id', $materiaId)->first();
    $correlativa = DB::table('tbl_materias')->where('id', $correlativaId)->first();

    if ($materia && $correlativa) {
        DB::table('tbl_materias')
            ->where('id', $materiaId)
            ->update([
                'paracursar_regular' => (string)$correlativaId
            ]);

        echo "✓ {$materia->nombre} → requiere (regular): {$correlativa->nombre}\n";
    } else {
        echo "✗ ERROR: No se encontró materia ID {$materiaId} o correlativa ID {$correlativaId}\n";
    }
}

echo "\n=== VERIFICACIÓN FINAL ===\n\n";

$todas_con_correlativas = DB::table('tbl_materias')
    ->where(function($query) {
        $query->whereNotNull('paracursar_regular')
              ->orWhereNotNull('paracursar_rendido');
    })
    ->select('id', 'nombre', 'paracursar_regular', 'paracursar_rendido')
    ->orderBy('id')
    ->get();

echo "Total de materias con correlativas: " . $todas_con_correlativas->count() . "\n\n";

foreach ($todas_con_correlativas as $m) {
    echo "ID {$m->id}: {$m->nombre}\n";
    if ($m->paracursar_regular) {
        $corr = DB::table('tbl_materias')->where('id', $m->paracursar_regular)->first();
        echo "  → Regular: {$corr->nombre}\n";
    }
    if ($m->paracursar_rendido) {
        echo "  → Rendido: {$m->paracursar_rendido}\n";
    }
}

if ($todas_con_correlativas->count() == 11) {
    echo "\n✅ PERFECTO: Exactamente 11 materias con correlativas (correcto)\n";
} else {
    echo "\n❌ ERROR: Se esperaban 11 materias pero hay " . $todas_con_correlativas->count() . "\n";
}
