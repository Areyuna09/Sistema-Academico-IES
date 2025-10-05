<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Materia;
use App\Models\Alumno;
use App\Models\AlumnoMateria;

$dni = '12345678';
$alumno = Alumno::where('dni', $dni)->first();

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   MATERIAS 2° AÑO - 2° CUATRIMESTRE\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$materias = Materia::where('carrera', 1)
    ->where('anno', 2)
    ->where('semestre', 2)
    ->orderBy('nombre')
    ->get();

echo "Materias en BD del 2° año - 2° cuatrimestre:\n\n";

foreach ($materias as $m) {
    echo "ID {$m->id}: {$m->nombre}\n";
    echo "  Año: {$m->anno} | Semestre: {$m->semestre}\n";
    echo "  Correlativas regular: " . ($m->paracursar_regular ?: 'ninguna') . "\n";
    echo "  Correlativas aprobada: " . ($m->paracursar_rendido ?: 'ninguna') . "\n";

    // Verificar en historial del alumno
    $historial = AlumnoMateria::where('alumno', $alumno->id)
        ->where('materia', $m->id)
        ->first();

    if ($historial) {
        $estados = [];
        if ($historial->cursada == '1') $estados[] = 'Regular';
        if ($historial->rendida == '1') $estados[] = 'Aprobada';
        echo "  Estado alumno: " . implode(', ', $estados) . "\n";
    } else {
        echo "  Estado alumno: NO CURSADA\n";
    }
    echo "\n";
}

echo "\nSEGÚN EL PLAN OFICIAL, 2° AÑO - 2° CUATRIMESTRE DEBE TENER:\n";
echo "  1. Práctica Profesionalizante II\n";
echo "  2. Matemática III\n";
echo "  3. Sistemas de Información II\n";
echo "  4. Sistema Administrativo (o Gestión Administrativa)\n";
echo "  5. Práctica Profesionalizante III\n\n";

// Buscar si hay Prácticas duplicadas
echo "Buscando todas las Prácticas Profesionalizantes:\n";
$todasPracticas = Materia::where('carrera', 1)
    ->where('nombre', 'LIKE', '%Practica Profesionalizante%')
    ->orderBy('anno')
    ->orderBy('semestre')
    ->get();

foreach ($todasPracticas as $p) {
    echo "  ID {$p->id}: {$p->nombre} - {$p->anno}° año, {$p->semestre}° cuatr.\n";
}

echo "\n";
