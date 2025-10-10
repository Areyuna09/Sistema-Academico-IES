<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== MATERIAS ACTUALES Y SUS CORRELATIVAS ===\n\n";

$materias = DB::table('tbl_materias')
    ->select('id', 'nombre', 'anno', 'semestre', 'paracursar_regular', 'paracursar_rendido')
    ->orderBy('nombre')
    ->get();

foreach ($materias as $materia) {
    echo "ID: {$materia->id} | {$materia->nombre} (Año {$materia->anno}, Sem {$materia->semestre})\n";
    echo "  Para cursar (regular): " . ($materia->paracursar_regular ?: 'Sin correlativas') . "\n";
    echo "  Para cursar (rendido): " . ($materia->paracursar_rendido ?: 'Sin correlativas') . "\n";
    echo "\n";
}

echo "\n=== AJUSTANDO CORRELATIVAS ===\n\n";

// Primero, limpiar todas las correlativas
DB::table('tbl_materias')->update([
    'paracursar_regular' => null,
    'paracursar_rendido' => null
]);

echo "✓ Todas las correlativas limpiadas\n\n";

// Ahora configurar solo las que tienen correlativas
// Usando los IDs exactos de la base de datos

$correlativas = [
    // Programación II (ID 8) requiere Programación I (ID 6)
    8 => ['correlativa_id' => 6],
    // Programación III (ID 15) requiere Programación II (ID 8)
    15 => ['correlativa_id' => 8],
    // Sistemas de Información II (ID 19) requiere Sistemas de Información I (ID 4)
    19 => ['correlativa_id' => 4],
    // Matemática II (ID 13) requiere Matemática I (ID 2)
    13 => ['correlativa_id' => 2],
    // Matemática III (ID 18) requiere Matemática II (ID 13)
    18 => ['correlativa_id' => 13],
    // Sistemas Operativos y Redes II (ID 23) requiere Sistemas Operativos y Redes I (ID 11)
    23 => ['correlativa_id' => 11],
    // Desarrollo de Software II (ID 25) requiere Desarrollo de Software I (ID 20)
    25 => ['correlativa_id' => 20],
    // Practica Profesionalizante II (ID 17) requiere Practica Profesionalizante I (ID 10)
    17 => ['correlativa_id' => 10],
    // Practica Profesionalizante III (ID 21) requiere Practica Profesionalizante II (ID 17)
    21 => ['correlativa_id' => 17],
    // Practica Profesionalizante IV (ID 24) requiere Practica Profesionalizante III (ID 21)
    24 => ['correlativa_id' => 21],
    // Practica Profesionalizante V (ID 27) requiere Practica Profesionalizante IV (ID 24)
    27 => ['correlativa_id' => 24],
];

foreach ($correlativas as $materiaId => $config) {
    // Buscar la materia
    $materia = DB::table('tbl_materias')
        ->where('id', $materiaId)
        ->first();

    if (!$materia) {
        echo "⚠ No se encontró materia ID: {$materiaId}\n";
        continue;
    }

    // Buscar la correlativa
    $correlativa = DB::table('tbl_materias')
        ->where('id', $config['correlativa_id'])
        ->first();

    if (!$correlativa) {
        echo "⚠ No se encontró la correlativa ID: {$config['correlativa_id']} para {$materia->nombre}\n";
        continue;
    }

    // Actualizar
    DB::table('tbl_materias')
        ->where('id', $materia->id)
        ->update([
            'paracursar_regular' => $correlativa->id
        ]);

    echo "✓ {$materia->nombre} ahora requiere (regular): {$correlativa->nombre}\n";
}

echo "\n=== RESUMEN FINAL ===\n\n";

$materiasConCorrelativas = DB::table('tbl_materias')
    ->whereNotNull('paracursar_regular')
    ->orWhereNotNull('paracursar_rendido')
    ->orderBy('nombre')
    ->get();

echo "Materias con correlativas configuradas: " . $materiasConCorrelativas->count() . "\n\n";

foreach ($materiasConCorrelativas as $materia) {
    echo "{$materia->nombre}\n";
    if ($materia->paracursar_regular) {
        $corrIds = explode(':', $materia->paracursar_regular);
        foreach ($corrIds as $corrId) {
            $corr = DB::table('tbl_materias')->where('id', $corrId)->first();
            if ($corr) {
                echo "  → Requiere (regular): {$corr->nombre}\n";
            }
        }
    }
    if ($materia->paracursar_rendido) {
        $corrIds = explode(':', $materia->paracursar_rendido);
        foreach ($corrIds as $corrId) {
            $corr = DB::table('tbl_materias')->where('id', $corrId)->first();
            if ($corr) {
                echo "  → Requiere (aprobada): {$corr->nombre}\n";
            }
        }
    }
    echo "\n";
}

echo "✅ Proceso completado\n";
