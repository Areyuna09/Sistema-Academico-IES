<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Alumno;
use App\Models\AlumnoMateria;
use App\Models\Materia;

$dni = '46180633';

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   DETERMINANDO AÑO DE CURSADO PARA ALUMNO: {$dni}\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$alumno = Alumno::where('dni', $dni)->first();

// Obtener historial
$historial = AlumnoMateria::where('alumno', $alumno->id)
    ->where('carrera', $alumno->carrera)
    ->get();

$aprobadas = $historial->where('rendida', '1')->count();
$regulares = $historial->where('cursada', '1')->where('rendida', '0')->count();

echo "Historial académico:\n";
echo "  - Materias aprobadas: {$aprobadas}\n";
echo "  - Materias solo regulares: {$regulares}\n";
echo "  - Total: " . $historial->count() . "\n\n";

// Determinar año según materias aprobadas
// 1er año: 6 materias primer cuatr + 5 segundo cuatr = 11 materias
// 2do año: 5 materias primer cuatr + 5 segundo cuatr = 10 materias
// 3er año: 3 materias primer cuatr + 4 segundo cuatr = 7 materias (algunas son prácticas)

$anioCalculado = 1; // Por defecto primer año

if ($aprobadas >= 20) {
    $anioCalculado = 3; // Tiene TODO aprobado, está en 3er año
    echo "✓ Alumno tiene 20+ materias → Está en 3er año (cursando materias faltantes o terminó)\n";
} elseif ($aprobadas >= 11) {
    $anioCalculado = 3; // Tiene 1er año completo
    echo "✓ Alumno tiene 11+ materias aprobadas → Está en 3er año\n";
} elseif ($aprobadas >= 6) {
    $anioCalculado = 2; // Tiene al menos 1er cuatr de 1er año
    echo "✓ Alumno tiene 6+ materias aprobadas → Está en 2do año\n";
} else {
    $anioCalculado = 1;
    echo "✓ Alumno tiene menos de 6 materias → Está en 1er año\n";
}

echo "\nAño calculado: {$anioCalculado}\n\n";

// Actualizar en base de datos
$alumno->anno = (string)$anioCalculado;
$alumno->save();

echo "✅ Alumno actualizado con anno = {$anioCalculado}\n\n";

// Mostrar materias que le corresponden en el 2do cuatrimestre
echo "Materias del {$anioCalculado}° año - 2° cuatrimestre:\n";
$materias = Materia::where('carrera', 1)
    ->where('anno', $anioCalculado)
    ->where('semestre', 2)
    ->get();

foreach ($materias as $m) {
    // Verificar si ya la tiene aprobada
    $tieneAprobada = $historial->where('materia', $m->id)
        ->where('rendida', '1')
        ->count() > 0;

    $estado = $tieneAprobada ? '✓ APROBADA' : '○ Disponible';
    echo "  {$estado} - {$m->nombre}\n";
}

echo "\n";
