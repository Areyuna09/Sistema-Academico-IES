<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Materia;

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   ACTUALIZANDO PLAN DE ESTUDIOS TSDS\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// Plan de estudios correcto
$planEstudios = [
    // 1° AÑO - 1° CUATRIMESTRE
    ['Comprensión y Producción de Texto', 1, 1],
    ['Matemática I', 1, 1],
    ['Procesamiento de Datos', 1, 1],
    ['Sistemas de Información I', 1, 1],
    ['Contexto Socio Económico Productivo', 1, 1],
    ['Programación I', 1, 1],

    // 1° AÑO - 2° CUATRIMESTRE
    ['Inglés Técnico', 1, 2],
    ['Programación II', 1, 2],
    ['Ambiente Empresarial', 1, 2],
    ['Práctica Profesionalizante I', 1, 2],
    ['Sistemas Operativos y Redes I', 1, 2],

    // 2° AÑO - 1° CUATRIMESTRE
    ['Matemática II', 2, 1],
    ['Desarrollo de Software I', 2, 1],
    ['Programación III', 2, 1],
    ['Base de Datos I', 2, 1],
    ['Marco Jurídico', 2, 1],

    // 2° AÑO - 2° CUATRIMESTRE
    ['Práctica Profesionalizante II', 2, 2],
    ['Matemática III', 2, 2],
    ['Sistemas de Información II', 2, 2],
    ['Sistema Administrativo', 2, 2],
    ['Práctica Profesionalizante III', 2, 2],

    // 3° AÑO - 1° CUATRIMESTRE
    ['Base de Datos II', 3, 1],
    ['Desarrollo de Software II', 3, 1],
    ['Práctica Profesionalizante IV', 3, 1],

    // 3° AÑO - 2° CUATRIMESTRE
    ['Ética y Deontología Profesional', 3, 2],
    ['Sistemas Operativos y Redes II', 3, 2],
    ['Práctica Profesionalizante V', 3, 2],
];

foreach ($planEstudios as $plan) {
    [$nombre, $anno, $semestre] = $plan;

    // Buscar la materia (con variaciones de nombres)
    $materia = Materia::where('carrera', 1)
        ->where(function($q) use ($nombre) {
            $q->where('nombre', 'LIKE', "%{$nombre}%")
              ->orWhere('nombre', 'LIKE', '%' . str_replace(' ', '%', $nombre) . '%');
        })
        ->first();

    if ($materia) {
        $cambios = [];
        if ($materia->anno != $anno) {
            $cambios[] = "Año: {$materia->anno} → {$anno}";
            $materia->anno = $anno;
        }
        if ($materia->semestre != $semestre) {
            $cambios[] = "Cuatr: {$materia->semestre} → {$semestre}";
            $materia->semestre = $semestre;
        }

        if (!empty($cambios)) {
            $materia->save();
            echo "✓ {$nombre}\n";
            echo "  " . implode(', ', $cambios) . "\n\n";
        } else {
            echo "○ {$nombre} - Ya está correcto\n";
        }
    } else {
        echo "✗ {$nombre} - NO ENCONTRADA en BD\n";
    }
}

echo "\n═══════════════════════════════════════════════════════════════════\n";
echo "   ACTUALIZACIÓN COMPLETA\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "Verificando materias del 2° cuatrimestre:\n";
$materias2do = Materia::where('carrera', 1)
    ->where('semestre', 2)
    ->orderBy('anno')
    ->get(['id', 'nombre', 'anno']);

echo "\n2° CUATRIMESTRE:\n";
foreach ($materias2do as $m) {
    echo "  {$m->anno}° Año - {$m->nombre}\n";
}
