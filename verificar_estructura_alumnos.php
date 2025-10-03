<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Alumno;

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   ESTRUCTURA Y DATOS DE TABLA tbl_alumnos\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// 1. Estructura de la tabla
echo "ESTRUCTURA DE LA TABLA:\n";
echo "─────────────────────────────────────────────────────────────────\n";
$columns = DB::select("DESCRIBE tbl_alumnos");
foreach ($columns as $col) {
    $null = $col->Null === 'YES' ? 'NULL' : 'NOT NULL';
    $default = $col->Default !== null ? "DEFAULT '{$col->Default}'" : '';
    echo sprintf("%-20s %-20s %-10s %s\n", $col->Field, $col->Type, $null, $default);
}

// 2. Muestreo de datos
echo "\n\nMUESTREO DE DATOS (primeros 10 alumnos):\n";
echo "─────────────────────────────────────────────────────────────────\n";
$alumnos = Alumno::where('carrera', 1)->limit(10)->get();

echo sprintf("%-12s %-25s %-8s %-8s %-10s %-10s\n",
    "DNI", "Nombre", "curso", "anno", "division", "legajo");
echo "─────────────────────────────────────────────────────────────────\n";

foreach ($alumnos as $a) {
    $nombre = substr($a->apellido . ', ' . $a->nombre, 0, 25);
    echo sprintf("%-12s %-25s %-8s %-8s %-10s %-10s\n",
        $a->dni,
        $nombre,
        $a->curso ?? 'NULL',
        $a->anno ?? 'NULL',
        $a->division ?? 'NULL',
        $a->legajo ?? 'NULL'
    );
}

// 3. Análisis de valores
echo "\n\nANÁLISIS DE VALORES:\n";
echo "─────────────────────────────────────────────────────────────────\n";

$cursoValues = DB::table('tbl_alumnos')
    ->select('curso', DB::raw('COUNT(*) as total'))
    ->whereNotNull('curso')
    ->where('carrera', 1)
    ->groupBy('curso')
    ->orderBy('curso')
    ->get();

echo "\nValores en campo 'curso':\n";
foreach ($cursoValues as $v) {
    echo "  {$v->curso}: {$v->total} alumnos\n";
}

$annoValues = DB::table('tbl_alumnos')
    ->select('anno', DB::raw('COUNT(*) as total'))
    ->whereNotNull('anno')
    ->where('carrera', 1)
    ->groupBy('anno')
    ->orderBy('anno')
    ->get();

echo "\nValores en campo 'anno':\n";
foreach ($annoValues as $v) {
    echo "  {$v->anno}: {$v->total} alumnos\n";
}

// 4. Propuesta de reestructuración
echo "\n\n═══════════════════════════════════════════════════════════════════\n";
echo "   PROPUESTA DE REESTRUCTURACIÓN\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "SEMÁNTICA SUGERIDA:\n";
echo "  • 'curso' o 'anio_ingreso' → Año de ingreso a la carrera (ej: 2023, 2024)\n";
echo "  • 'anno' o 'anio_cursado' → Año que está cursando (1, 2, 3)\n";
echo "  • 'division' → División/Comisión del alumno (1, 2, 3)\n\n";

echo "OBSERVACIÓN:\n";
echo "Actualmente 'anno' parece contener años de ingreso (2023, 2024)\n";
echo "y 'curso' parece contener el año que cursan (1, 2, 3).\n\n";

echo "¿Los campos están invertidos?\n";
echo "Verificar con datos reales para determinar la corrección necesaria.\n\n";
