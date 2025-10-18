<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Alumno;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\DB;

echo "==============================================\n";
echo "  Limpiar Inscripciones - Alumno de Prueba\n";
echo "==============================================\n\n";

// Buscar alumno
$alumno = Alumno::where('dni', '12345678')->first();

if (!$alumno) {
    echo "❌ Alumno con DNI 12345678 no encontrado\n";
    exit(1);
}

echo "✅ Alumno encontrado:\n";
echo "   ID: {$alumno->id}\n";
echo "   Nombre: {$alumno->nombre_completo}\n";
echo "   DNI: {$alumno->dni}\n\n";

// Contar inscripciones
$count = Inscripcion::where('alumno_id', $alumno->id)->count();
echo "📊 Inscripciones actuales: {$count}\n\n";

if ($count > 0) {
    // Eliminar inscripciones
    Inscripcion::where('alumno_id', $alumno->id)->delete();
    echo "🗑️  Inscripciones eliminadas: {$count}\n";

    // También limpiar de la tabla legacy
    $countLegacy = DB::table('tbl_alumnos_cursa')
        ->where('dni', '12345678')
        ->delete();
    echo "🗑️  Registros legacy eliminados: {$countLegacy}\n";
} else {
    echo "ℹ️  No hay inscripciones para eliminar\n";
}

echo "\n✅ Proceso completado\n";
