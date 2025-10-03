<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Alumno;
use App\Models\Materia;
use App\Models\AlumnoMateria;

$dni = '46180633';
$alumno = Alumno::where('dni', $dni)->first();

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   VERIFICACIÓN DE PRÁCTICAS PROFESIONALIZANTES\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// Buscar todas las prácticas
$practicas = Materia::where('carrera', 1)
    ->where('nombre', 'LIKE', '%Practica Profesionalizante%')
    ->orderBy('anno')
    ->orderBy('semestre')
    ->get();

foreach ($practicas as $p) {
    echo "Materia ID {$p->id}: {$p->nombre}\n";
    echo "  Año: {$p->anno} | Semestre: {$p->semestre}\n";
    echo "  Correlativas regular: {$p->paracursar_regular}\n";
    echo "  Correlativas aprobada: {$p->paracursar_rendido}\n";

    // Verificar en historial
    $historial = AlumnoMateria::where('alumno', $alumno->id)
        ->where('materia', $p->id)
        ->first();

    if ($historial) {
        $estado = [];
        if ($historial->cursada == '1') $estado[] = 'Regular';
        if ($historial->rendida == '1') $estado[] = 'Aprobada';
        echo "  Tu estado: " . implode(', ', $estado) . "\n";
    } else {
        echo "  Tu estado: NO CURSADA\n";
    }
    echo "\n";
}

// Buscar específicamente Práctica IV
echo "Buscando Práctica Profesionalizante IV...\n";
$practica4 = Materia::where('carrera', 1)
    ->where('nombre', 'LIKE', '%Practica Profesionalizante IV%')
    ->first();

if ($practica4) {
    $historial4 = AlumnoMateria::where('alumno', $alumno->id)
        ->where('materia', $practica4->id)
        ->first();

    if ($historial4 && $historial4->rendida == '1') {
        echo "✓ Tienes Práctica IV APROBADA\n";
    } else {
        echo "✗ NO tienes Práctica IV aprobada\n";
        echo "  Creando registro de Práctica IV como aprobada...\n";

        AlumnoMateria::create([
            'alumno' => $alumno->id,
            'carrera' => $alumno->carrera,
            'materia' => $practica4->id,
            'cursada' => '1',
            'rendida' => '1',
            'nota' => 7,
            'fecha' => now(),
        ]);

        echo "  ✅ Práctica IV marcada como aprobada\n";
    }
}

echo "\n";
