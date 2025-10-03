<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Alumno;
use Illuminate\Support\Facades\DB;

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   ACTUALIZANDO CAMPO 'curso' PARA AÑO QUE CURSA\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "ESTRATEGIA:\n";
echo "  • 'anno' = año de ingreso (mantener como está: 2019, 2020, etc.)\n";
echo "  • 'curso' = año que está cursando (actualizar a: 1, 2, 3)\n\n";

// Actualizar DNI 46180633 (Ramon) - 3er año
echo "Actualizando alumno Ramon (46180633)...\n";
$ramon = Alumno::where('dni', '46180633')->first();
if ($ramon) {
    $ramon->curso = 3; // 3er año
    $ramon->save();
    echo "  ✓ curso = 3 (3er año)\n";
    echo "  ✓ anno = {$ramon->anno} (año de ingreso)\n\n";
}

// Actualizar DNI 12345678 (Alumno Prueba) - 2do año
echo "Actualizando alumno Prueba (12345678)...\n";
$prueba = Alumno::where('dni', '12345678')->first();
if ($prueba) {
    $prueba->curso = 2; // 2do año
    $prueba->anno = '2023'; // Ingresó en 2023
    $prueba->save();
    echo "  ✓ curso = 2 (2do año)\n";
    echo "  ✓ anno = 2023 (año de ingreso)\n\n";
}

// Sugerencia para el resto de alumnos
echo "═══════════════════════════════════════════════════════════════════\n";
echo "   SUGERENCIA PARA OTROS ALUMNOS\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "Para determinar el 'curso' (año que cursan) de otros alumnos,\n";
echo "puedes calcular basado en su año de ingreso:\n\n";

$currentYear = 2025;
$alumnos = Alumno::where('carrera', 1)
    ->whereNotIn('dni', ['46180633', '12345678'])
    ->limit(5)
    ->get();

foreach ($alumnos as $a) {
    $anioIngreso = intval($a->anno ?? $currentYear);
    $anioCursando = min(3, max(1, $currentYear - $anioIngreso + 1));

    echo "DNI {$a->dni} ({$a->apellido}):\n";
    echo "  año ingreso = {$anioIngreso}\n";
    echo "  → debería estar en {$anioCursando}° año\n\n";
}

echo "¿Deseas actualizar automáticamente todos los alumnos? (s/n)\n";
echo "Por ahora solo actualicé los 2 usuarios de prueba.\n\n";
