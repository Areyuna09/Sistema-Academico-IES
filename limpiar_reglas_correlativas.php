<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== LIMPIEZA Y CONFIGURACIÓN DE REGLAS CORRELATIVAS ===\n\n";

// PASO 1: Borrar TODAS las reglas existentes
echo "PASO 1: Borrando todas las reglas de correlativas...\n";
$deleted = DB::table('reglas_correlativas')->delete();
echo "✓ {$deleted} reglas eliminadas\n\n";

// PASO 2: Obtener la carrera TSDS (Tecnicatura Superior en Desarrollo de Software)
$carrera = DB::table('tbl_carreras')->where('nombre', 'LIKE', '%Desarrollo%Software%')->first();

if (!$carrera) {
    echo "⚠ No se encontró la carrera de Desarrollo de Software, usando carrera ID 1\n\n";
    $carreraId = 1;
} else {
    $carreraId = $carrera->Id;
    echo "✓ Carrera encontrada: {$carrera->nombre} (ID: {$carreraId})\n\n";
}

// PASO 3: Configurar las reglas correctas según el plan de estudios
echo "PASO 2: Configurando reglas correctas según plan de estudios...\n\n";

$reglas_correctas = [
    // 2° AÑO - 1er Cuatrimestre
    ['Matemática II', 'Matemática I', 'cursar', 'regular'],
    ['Desarrollo de Software I', 'Programación I', 'cursar', 'regular'],
    ['Programación III', 'Programación II', 'cursar', 'regular'],
    ['Base de Datos I', 'Sistemas de Información I', 'cursar', 'regular'],

    // 2° AÑO - 2do Cuatrimestre
    ['Practica Profesionalizante II', 'Practica Profesionalizante I', 'cursar', 'regular'],
    ['Práctica Profesionalizante II', 'Práctica Profesionalizante I', 'cursar', 'regular'],
    ['Matemática III', 'Matemática II', 'cursar', 'regular'],
    ['Sistemas de Información II', 'Sistemas de Información I', 'cursar', 'regular'],
    ['Practica Profesionalizante III', 'Practica Profesionalizante II', 'cursar', 'regular'],
    ['Práctica Profesionalizante III', 'Práctica Profesionalizante II', 'cursar', 'regular'],

    // 3° AÑO - 1er Cuatrimestre
    ['Base de Datos II', 'Base de Datos I', 'cursar', 'regular'],
    ['Desarrollo de Software II', 'Desarrollo de Software I', 'cursar', 'regular'],
    ['Practica Profesionalizante IV', 'Practica Profesionalizante III', 'cursar', 'regular'],
    ['Práctica Profesionalizante IV', 'Práctica Profesionalizante III', 'cursar', 'regular'],

    // 3° AÑO - 2do Cuatrimestre
    ['Sistemas Operativos y Redes II', 'Sistemas Operativos y Redes I', 'cursar', 'regular'],
    ['Practica Profesionalizante V', 'Practica Profesionalizante IV', 'cursar', 'regular'],
    ['Práctica Profesionalizante V', 'Práctica Profesionalizante IV', 'cursar', 'regular'],
];

$creadas = 0;
$ya_creadas = [];

foreach ($reglas_correctas as $regla_data) {
    [$materia_nombre, $correlativa_nombre, $tipo, $estado] = $regla_data;

    // Buscar materia
    $materia = DB::table('tbl_materias')
        ->where('nombre', 'LIKE', "%{$materia_nombre}%")
        ->first();

    if (!$materia) {
        continue;
    }

    // Buscar correlativa
    $correlativa = DB::table('tbl_materias')
        ->where('nombre', 'LIKE', "%{$correlativa_nombre}%")
        ->first();

    if (!$correlativa) {
        continue;
    }

    // Evitar duplicados
    $key = "{$materia->id}_{$correlativa->id}_{$tipo}";
    if (in_array($key, $ya_creadas)) {
        continue;
    }

    // Crear regla
    DB::table('reglas_correlativas')->insert([
        'materia_id' => $materia->id,
        'carrera_id' => $carreraId,
        'tipo' => $tipo,
        'correlativa_id' => $correlativa->id,
        'estado_requerido' => $estado,
        'es_activa' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    echo "✓ {$materia->nombre} → requiere ({$estado}): {$correlativa->nombre}\n";
    $creadas++;
    $ya_creadas[] = $key;
}

echo "\n=== VERIFICACIÓN FINAL ===\n\n";

$total = DB::table('reglas_correlativas')->count();
echo "Total de reglas creadas: {$creadas}\n";
echo "Total de reglas en base de datos: {$total}\n\n";

$reglas_finales = DB::table('reglas_correlativas')
    ->join('tbl_materias as m1', 'reglas_correlativas.materia_id', '=', 'm1.id')
    ->join('tbl_materias as m2', 'reglas_correlativas.correlativa_id', '=', 'm2.id')
    ->select(
        'm1.nombre as materia',
        'm2.nombre as correlativa',
        'reglas_correlativas.tipo',
        'reglas_correlativas.estado_requerido'
    )
    ->orderBy('m1.anno')
    ->orderBy('m1.nombre')
    ->get();

foreach ($reglas_finales as $regla) {
    echo "{$regla->materia}\n";
    echo "  → {$regla->correlativa} ({$regla->tipo}: {$regla->estado_requerido})\n";
}

echo "\n✅ Configuración completada\n";
