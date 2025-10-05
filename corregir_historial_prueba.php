<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Alumno;
use App\Models\AlumnoMateria;

$dni = '12345678';
$alumno = Alumno::where('dni', $dni)->first();

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   CORRIGIENDO HISTORIAL ALUMNO PRUEBA\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// Actualizar Sistemas de Información I (ID 4) a APROBADA
$sisInfo1 = AlumnoMateria::where('alumno', $alumno->id)
    ->where('materia', 4)
    ->first();

if ($sisInfo1) {
    $sisInfo1->rendida = '1';
    $sisInfo1->nota = 7;
    $sisInfo1->save();
    echo "✓ Sistemas de Información I → APROBADA\n";
}

// Actualizar Práctica Profesionalizante I (ID 10) a APROBADA
$practica1 = AlumnoMateria::where('alumno', $alumno->id)
    ->where('materia', 10)
    ->first();

if ($practica1) {
    $practica1->rendida = '1';
    $practica1->nota = 7;
    $practica1->save();
    echo "✓ Práctica Profesionalizante I → APROBADA\n";
}

// Agregar Práctica Profesionalizante II (ID 17) como APROBADA
$practica2 = AlumnoMateria::where('alumno', $alumno->id)
    ->where('materia', 17)
    ->first();

if (!$practica2) {
    AlumnoMateria::create([
        'alumno' => $alumno->id,
        'carrera' => $alumno->carrera,
        'materia' => 17,
        'cursada' => '1',
        'rendida' => '1',
        'nota' => 7,
        'fecha' => now()->subMonths(2),
    ]);
    echo "✓ Práctica Profesionalizante II → APROBADA (agregada)\n";
} else {
    $practica2->rendida = '1';
    $practica2->nota = 7;
    $practica2->save();
    echo "✓ Práctica Profesionalizante II → APROBADA\n";
}

echo "\n═══════════════════════════════════════════════════════════════════\n";
echo "   RESUMEN ACTUALIZADO\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$historial = AlumnoMateria::where('alumno', $alumno->id)->get();
$aprobadas = $historial->where('rendida', '1')->count();
$regulares = $historial->where('cursada', '1')->where('rendida', '0')->count();

echo "Materias aprobadas: {$aprobadas}\n";
echo "Materias solo regulares: {$regulares}\n\n";

echo "El alumno ahora debería poder inscribirse en:\n";
echo "  ✓ Sistema Administrativo (sin correlativas)\n";
echo "  ✓ Matemática III (tiene Matemática I aprobada y Matemática II regular)\n";
echo "  ✓ Sistemas de Información II (tiene Sistemas de Info I aprobada)\n";
echo "  ✓ Práctica Profesionalizante III (tiene Práctica II aprobada)\n\n";
