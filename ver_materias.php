<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Materia;
use App\Models\Alumno;
use App\Models\AlumnoMateria;

$dni = '46180633';
$alumno = Alumno::where('dni', $dni)->first();

echo "MATERIAS DEL 2DO CUATRIMESTRE (Carrera: {$alumno->carrera}):\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$materias = Materia::where('carrera', $alumno->carrera)
    ->where('semestre', 2)
    ->orderBy('anno')
    ->orderBy('nombre')
    ->get();

foreach ($materias as $m) {
    echo "{$m->anno}° Año - {$m->nombre}\n";
    echo "  ID: {$m->id} | Correlativas regular: {$m->paracursar_regular}\n";

    // Verificar si la tienes en tu historial
    $historial = AlumnoMateria::where('alumno', $alumno->id)
        ->where('materia', $m->id)
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
