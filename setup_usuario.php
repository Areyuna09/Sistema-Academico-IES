<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Alumno;
use App\Models\AlumnoMateria;
use App\Models\Materia;
use App\Models\PeriodoInscripcion;
use Illuminate\Support\Facades\Hash;

$dni = '46180633';

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   CONFIGURACIÓN DE USUARIO: {$dni}\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// 1. Verificar si existe el alumno
echo "1️⃣  Verificando alumno en base de datos...\n";
$alumno = Alumno::where('dni', $dni)->first();

if (!$alumno) {
    echo "❌ Alumno con DNI {$dni} NO encontrado en tbl_alumnos\n";
    echo "Por favor proporciona los siguientes datos para crear el alumno:\n";
    exit(1);
}

echo "✓ Alumno encontrado:\n";
echo "  ID: {$alumno->id}\n";
echo "  Nombre: {$alumno->nombre_completo}\n";
echo "  Carrera: {$alumno->carrera}\n\n";

// 2. Verificar/Crear usuario
echo "2️⃣  Verificando usuario de acceso...\n";
$user = User::where('dni', $dni)->first();

if (!$user) {
    echo "⚠️  Usuario NO existe, creándolo...\n";
    $user = User::create([
        'dni' => $dni,
        'name' => $alumno->nombre_completo,
        'email' => $dni . '@ies.edu.ar',
        'password' => Hash::make($dni),
        'role' => 'alumno',
        'alumno_id' => $alumno->id,
    ]);
    echo "✓ Usuario creado\n";
} else {
    echo "✓ Usuario ya existe\n";
}

echo "  Login: {$user->dni}\n";
echo "  Password: {$dni}\n\n";

// 3. Verificar historial académico
echo "3️⃣  Verificando historial académico...\n";
$historial = AlumnoMateria::where('alumno', $alumno->id)
    ->where('carrera', $alumno->carrera)
    ->get();

echo "✓ Total de materias en legajo: " . $historial->count() . "\n";
$regulares = $historial->where('cursada', '1')->count();
$aprobadas = $historial->where('rendida', '1')->count();
echo "  Materias regulares: {$regulares}\n";
echo "  Materias aprobadas: {$aprobadas}\n\n";

if ($historial->count() === 0) {
    echo "⚠️  NO tienes materias en tu historial académico\n";
    echo "¿Quieres cargar TODAS las materias como aprobadas? (s/n): ";

    // Para automatizar, cargamos todas
    echo "s\n";
    echo "\n4️⃣  Cargando todas las materias como APROBADAS...\n";

    $materias = Materia::where('carrera', $alumno->carrera)->get();
    echo "Total de materias a cargar: " . $materias->count() . "\n";

    foreach ($materias as $materia) {
        AlumnoMateria::create([
            'alumno' => $alumno->id,
            'carrera' => $alumno->carrera,
            'materia' => $materia->id,
            'cursada' => '1',
            'rendida' => '1',
            'nota' => 7,
            'fecha' => now(),
        ]);
        echo "  ✓ {$materia->nombre}\n";
    }
    echo "\n✅ Todas las materias cargadas como aprobadas\n\n";
}

// 4. Configurar período de inscripción
echo "5️⃣  Configurando período de inscripción (2° Cuatrimestre 2025)...\n";

// Desactivar todos los períodos
PeriodoInscripcion::query()->update(['activo' => false]);

$periodo = PeriodoInscripcion::where('cuatrimestre', '2')
    ->where('anio', 2025)
    ->first();

if ($periodo) {
    $periodo->activo = true;
    $periodo->fecha_inicio_inscripcion = now()->subDays(1);
    $periodo->fecha_fin_inscripcion = now()->addDays(2);
    $periodo->save();
    echo "✓ Período activado y fechas actualizadas\n";
} else {
    $periodo = PeriodoInscripcion::create([
        'nombre' => '2° Cuatrimestre 2025',
        'cuatrimestre' => '2',
        'anio' => 2025,
        'fecha_inicio_inscripcion' => now()->subDays(1),
        'fecha_fin_inscripcion' => now()->addDays(2),
        'fecha_inicio_cursada' => now()->addDays(7),
        'fecha_fin_cursada' => now()->addMonths(4),
        'activo' => true,
    ]);
    echo "✓ Período creado y activado\n";
}

echo "  Inscripción: " . $periodo->fecha_inicio_inscripcion->format('d/m/Y') . " al " . $periodo->fecha_fin_inscripcion->format('d/m/Y') . "\n";
echo "  Días restantes: " . now()->diffInDays($periodo->fecha_fin_inscripcion, false) . "\n\n";

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   CONFIGURACIÓN COMPLETA ✅\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "Ahora puedes acceder al sistema:\n";
echo "  URL: http://localhost:8000\n";
echo "  Usuario: {$dni}\n";
echo "  Password: {$dni}\n\n";
