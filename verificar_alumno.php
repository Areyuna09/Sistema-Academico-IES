<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Alumno;
use App\Models\AlumnoMateria;

$dni = '46180633';

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   VERIFICACIÓN DE USUARIO Y ALUMNO: {$dni}\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// Usuario
$user = User::where('dni', $dni)->first();
if ($user) {
    echo "USUARIO:\n";
    echo "  DNI: {$user->dni}\n";
    echo "  Nombre: {$user->name}\n";
    echo "  Email: {$user->email}\n";
    echo "  Role: {$user->role}\n";
    echo "  Alumno ID: {$user->alumno_id}\n\n";
}

// Alumno
$alumno = Alumno::where('dni', $dni)->first();
if ($alumno) {
    echo "ALUMNO:\n";
    echo "  ID: {$alumno->id}\n";
    echo "  DNI: {$alumno->dni}\n";
    echo "  Nombre: {$alumno->nombre_completo}\n";
    echo "  Carrera: {$alumno->carrera}\n";
    echo "  Año que cursa (anio): " . ($alumno->anio ?? 'NO ESPECIFICADO') . "\n\n";
}

// Estadísticas del historial
$historial = AlumnoMateria::where('alumno', $alumno->id)
    ->where('carrera', $alumno->carrera)
    ->get();

echo "HISTORIAL ACADÉMICO:\n";
echo "  Total materias: {$historial->count()}\n";
echo "  Aprobadas: " . $historial->where('rendida', '1')->count() . "\n";
echo "  Regulares (solo cursada): " . $historial->where('cursada', '1')->where('rendida', '0')->count() . "\n\n";

// Ver si tiene el campo 'anio' en la tabla
echo "ESTRUCTURA DE TABLA tbl_alumnos:\n";
$columns = DB::select("DESCRIBE tbl_alumnos");
foreach ($columns as $col) {
    echo "  - {$col->Field} ({$col->Type})\n";
}
