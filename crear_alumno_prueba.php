<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Alumno;
use App\Models\AlumnoMateria;
use App\Models\Materia;
use Illuminate\Support\Facades\Hash;

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   CREANDO ALUMNO DE PRUEBA CON MATERIAS PENDIENTES\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$dni = '12345678';
$nombre = 'Prueba';
$apellido = 'Alumno';

// 1. Crear o actualizar alumno
$alumno = Alumno::where('dni', $dni)->first();
if (!$alumno) {
    $alumno = new Alumno();
    $alumno->dni = $dni;
    $alumno->nombre = $nombre;
    $alumno->apellido = $apellido;
    $alumno->carrera = 1;
    $alumno->anno = '2'; // Está cursando 2do año
    $alumno->curso = 2023; // Ingresó en 2023
    $alumno->division = 1; // División 1
    $alumno->legajo = 'LEG-' . $dni;
    $alumno->fecha = now(); // Campo requerido
    $alumno->save();
    echo "✓ Alumno creado: {$apellido}, {$nombre}\n";
} else {
    echo "✓ Alumno ya existe: {$alumno->apellido}, {$alumno->nombre}\n";
    // Actualizar año
    $alumno->anno = '2';
    $alumno->save();
}

echo "  DNI: {$dni}\n";
echo "  Año que cursa: {$alumno->anno}\n";
echo "  Carrera: {$alumno->carrera}\n\n";

// 2. Crear usuario
$user = User::where('dni', $dni)->first();
if (!$user) {
    $user = User::create([
        'dni' => $dni,
        'name' => "{$apellido}, {$nombre}",
        'email' => $dni . '@ies.edu.ar',
        'password' => Hash::make($dni),
        'role' => 'alumno',
        'alumno_id' => $alumno->id,
    ]);
    echo "✓ Usuario creado\n";
} else {
    echo "✓ Usuario ya existe\n";
}

echo "  Login: {$dni}\n";
echo "  Password: {$dni}\n\n";

// 3. Limpiar historial previo
AlumnoMateria::where('alumno', $alumno->id)->delete();

// 4. Cargar historial académico selectivo
echo "Cargando historial académico:\n\n";

// Obtener todas las materias
$todasMaterias = Materia::where('carrera', 1)->orderBy('anno')->orderBy('semestre')->get();

// === 1ER AÑO - 1ER CUATRIMESTRE ===
echo "1° AÑO - 1° CUATRIMESTRE:\n";
$materias1erCuatr = [
    'Comprensión y Producción de Texto' => 'aprobada',
    'Matemática I' => 'aprobada',
    'Procesamiento de Datos' => 'aprobada',
    'Sistemas de Información I' => 'regular', // SOLO REGULAR, no aprobada
    'Contexto Socio Económico Productivo' => 'aprobada',
    'Programación I' => 'aprobada',
];

foreach ($materias1erCuatr as $nombreMateria => $estado) {
    $materia = $todasMaterias->first(function($m) use ($nombreMateria) {
        return stripos($m->nombre, $nombreMateria) !== false;
    });

    if ($materia) {
        AlumnoMateria::create([
            'alumno' => $alumno->id,
            'carrera' => $alumno->carrera,
            'materia' => $materia->id,
            'cursada' => '1',
            'rendida' => $estado === 'aprobada' ? '1' : '0',
            'nota' => $estado === 'aprobada' ? 7 : null,
            'fecha' => now()->subMonths(12),
        ]);
        $estadoTexto = $estado === 'aprobada' ? '✓ APROBADA' : '○ REGULAR';
        echo "  {$estadoTexto} - {$materia->nombre}\n";
    }
}

// === 1ER AÑO - 2DO CUATRIMESTRE ===
echo "\n1° AÑO - 2° CUATRIMESTRE:\n";
$materias2doCuatr = [
    'Inglés Técnico' => 'aprobada',
    'Programación II' => 'pendiente', // NO CURSADA - debe recursar
    'Ambiente Empresarial' => 'aprobada',
    'Práctica Profesionalizante I' => 'regular', // SOLO REGULAR
    'Sistemas Operativos y Redes I' => 'pendiente', // NO CURSADA - debe recursar
];

foreach ($materias2doCuatr as $nombreMateria => $estado) {
    $materia = $todasMaterias->first(function($m) use ($nombreMateria) {
        return stripos($m->nombre, $nombreMateria) !== false;
    });

    if ($materia && $estado !== 'pendiente') {
        AlumnoMateria::create([
            'alumno' => $alumno->id,
            'carrera' => $alumno->carrera,
            'materia' => $materia->id,
            'cursada' => '1',
            'rendida' => $estado === 'aprobada' ? '1' : '0',
            'nota' => $estado === 'aprobada' ? 7 : null,
            'fecha' => now()->subMonths(6),
        ]);
        $estadoTexto = $estado === 'aprobada' ? '✓ APROBADA' : '○ REGULAR';
        echo "  {$estadoTexto} - {$materia->nombre}\n";
    } elseif ($materia) {
        echo "  ✗ PENDIENTE - {$materia->nombre}\n";
    }
}

// === 2DO AÑO - 1ER CUATRIMESTRE ===
echo "\n2° AÑO - 1° CUATRIMESTRE:\n";
$materias2do1er = [
    'Matemática II' => 'aprobada',
    'Desarrollo de Software I' => 'aprobada',
    'Programación III' => 'regular', // SOLO REGULAR
    'Base de Datos I' => 'aprobada',
    'Marco Jurídico' => 'aprobada',
];

foreach ($materias2do1er as $nombreMateria => $estado) {
    $materia = $todasMaterias->first(function($m) use ($nombreMateria) {
        return stripos($m->nombre, $nombreMateria) !== false;
    });

    if ($materia) {
        AlumnoMateria::create([
            'alumno' => $alumno->id,
            'carrera' => $alumno->carrera,
            'materia' => $materia->id,
            'cursada' => '1',
            'rendida' => $estado === 'aprobada' ? '1' : '0',
            'nota' => $estado === 'aprobada' ? 7 : null,
            'fecha' => now()->subMonths(3),
        ]);
        $estadoTexto = $estado === 'aprobada' ? '✓ APROBADA' : '○ REGULAR';
        echo "  {$estadoTexto} - {$materia->nombre}\n";
    }
}

echo "\n";

// Resumen
$historial = AlumnoMateria::where('alumno', $alumno->id)->get();
$aprobadas = $historial->where('rendida', '1')->count();
$regulares = $historial->where('cursada', '1')->where('rendida', '0')->count();

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   RESUMEN\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";
echo "Alumno: {$apellido}, {$nombre}\n";
echo "DNI: {$dni}\n";
echo "Año que cursa: {$alumno->anno}° año\n\n";
echo "Estadísticas:\n";
echo "  - Materias aprobadas: {$aprobadas}\n";
echo "  - Materias solo regulares: {$regulares}\n";
echo "  - Materias pendientes del 1er año 2do cuatr: 2 (Programación II, Sist. Op. y Redes I)\n\n";

echo "Período activo: 2° Cuatrimestre 2025\n";
echo "El alumno está en 2do año, por lo tanto:\n";
echo "  → Deberían aparecer materias del 2° AÑO - 2° CUATRIMESTRE\n";
echo "  → Las materias del 1er año NO aparecen (aunque estén pendientes)\n";
echo "  → Para recursar materias del 1er año, debería cambiar su 'anno' a 1\n\n";

echo "Login: {$dni} / {$dni}\n\n";
