<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Materia;
use App\Models\Alumno;
use App\Models\AlumnoMateria;
use App\Services\MotorCorrelativasService;

$dni = '46180633';
$alumno = Alumno::where('dni', $dni)->first();

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   DEBUG CORRELATIVAS - Usuario: {$dni}\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// Buscar Programación II
echo "Buscando Programación II en la base de datos...\n";
$prog2 = Materia::where('carrera', 1)
    ->where('nombre', 'LIKE', '%Programación II%')
    ->first();

if ($prog2) {
    echo "\n=== PROGRAMACIÓN II ===\n";
    echo "ID: {$prog2->id}\n";
    echo "Nombre: {$prog2->nombre}\n";
    echo "Año: {$prog2->anno} | Semestre: {$prog2->semestre}\n";
    echo "Correlativas para cursar (regular): {$prog2->paracursar_regular}\n";
    echo "Correlativas para cursar (aprobada): {$prog2->paracursar_rendido}\n\n";

    // Validar con el motor
    $motor = app(MotorCorrelativasService::class);
    $validacion = $motor->validarParaCursar($dni, $prog2->id, 1);

    echo "Resultado validación:\n";
    echo "  Puede cursar: " . ($validacion['puede_cursar'] ? 'SÍ ✓' : 'NO ✗') . "\n";
    echo "  Mensaje: {$validacion['mensaje']}\n";

    if (!empty($validacion['correlativas_faltantes'])) {
        echo "\nCorrelativas faltantes:\n";
        foreach ($validacion['correlativas_faltantes'] as $faltante) {
            echo "  - Materia ID {$faltante['materia_id']}: Requiere '{$faltante['tipo_requerido']}', tienes '{$faltante['estado_actual']}'\n";

            // Obtener nombre de la materia
            $matFaltante = Materia::find($faltante['materia_id']);
            if ($matFaltante) {
                echo "    Nombre: {$matFaltante->nombre}\n";
            }

            // Ver estado en historial
            $historial = AlumnoMateria::where('alumno', $alumno->id)
                ->where('materia', $faltante['materia_id'])
                ->first();
            if ($historial) {
                echo "    En tu historial: cursada={$historial->cursada}, rendida={$historial->rendida}\n";
            }
        }
    }

    if (!empty($validacion['correlativas_cumplidas'])) {
        echo "\nCorrelativas cumplidas: " . count($validacion['correlativas_cumplidas']) . "\n";
    }
}

// Buscar Matemática III
echo "\n\nBuscando Matemática III en la base de datos...\n";
$mat3 = Materia::where('carrera', 1)
    ->where('nombre', 'LIKE', '%Matemática III%')
    ->first();

if ($mat3) {
    echo "\n=== MATEMÁTICA III ===\n";
    echo "ID: {$mat3->id}\n";
    echo "Nombre: {$mat3->nombre}\n";
    echo "Año: {$mat3->anno} | Semestre: {$mat3->semestre}\n";
    echo "Correlativas para cursar (regular): {$mat3->paracursar_regular}\n";
    echo "Correlativas para cursar (aprobada): {$mat3->paracursar_rendido}\n\n";

    // Validar con el motor
    $motor = app(MotorCorrelativasService::class);
    $validacion = $motor->validarParaCursar($dni, $mat3->id, 1);

    echo "Resultado validación:\n";
    echo "  Puede cursar: " . ($validacion['puede_cursar'] ? 'SÍ ✓' : 'NO ✗') . "\n";
    echo "  Mensaje: {$validacion['mensaje']}\n";

    if (!empty($validacion['correlativas_faltantes'])) {
        echo "\nCorrelativas faltantes:\n";
        foreach ($validacion['correlativas_faltantes'] as $faltante) {
            echo "  - Materia ID {$faltante['materia_id']}: Requiere '{$faltante['tipo_requerido']}', tienes '{$faltante['estado_actual']}'\n";

            // Obtener nombre de la materia
            $matFaltante = Materia::find($faltante['materia_id']);
            if ($matFaltante) {
                echo "    Nombre: {$matFaltante->nombre}\n";
            }

            // Ver estado en historial
            $historial = AlumnoMateria::where('alumno', $alumno->id)
                ->where('materia', $faltante['materia_id'])
                ->first();
            if ($historial) {
                echo "    En tu historial: cursada={$historial->cursada}, rendida={$historial->rendida}\n";
            }
        }
    }

    if (!empty($validacion['correlativas_cumplidas'])) {
        echo "\nCorrelativas cumplidas: " . count($validacion['correlativas_cumplidas']) . "\n";
    }
}

// Verificar TODAS las materias del historial del alumno
echo "\n\n═══════════════════════════════════════════════════════════════════\n";
echo "   HISTORIAL ACADÉMICO COMPLETO\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$historial = AlumnoMateria::where('alumno', $alumno->id)
    ->where('carrera', 1)
    ->get();

foreach ($historial as $h) {
    $materia = Materia::find($h->materia);
    $estados = [];
    if ($h->cursada == '1') $estados[] = 'Regular';
    if ($h->rendida == '1') $estados[] = 'Aprobada';

    echo "ID {$h->materia}: {$materia->nombre} - " . implode(', ', $estados) . "\n";
}

echo "\n";
