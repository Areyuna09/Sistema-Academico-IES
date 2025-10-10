<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CONFIGURACIÓN DE CORRELATIVAS SEGÚN PLAN DE ESTUDIO ===\n\n";

// PASO 1: Borrar TODAS las correlativas
echo "PASO 1: Borrando TODAS las correlativas existentes...\n";
DB::table('tbl_materias')->update([
    'paracursar_regular' => null,
    'paracursar_rendido' => null
]);
echo "✓ Todas las correlativas borradas\n\n";

// PASO 2: Configurar correlativas según la imagen del plan de estudios
echo "PASO 2: Configurando correlativas según plan de estudios...\n\n";

// Primero obtenemos los IDs de todas las materias necesarias
$materias_map = [];
$todas_materias = DB::table('tbl_materias')->select('id', 'nombre')->get();
foreach ($todas_materias as $m) {
    $materias_map[$m->nombre] = $m->id;
}

// Configuración según la imagen:
$configuracion = [
    // 2° AÑO
    'Matemática II' => [
        'regular' => ['Matemática I']
    ],
    'Desarrollo de Software I' => [
        'regular' => ['Programación I']
    ],
    'Programación III' => [
        'regular' => ['Programación II']
    ],
    'Base de Datos I' => [
        'regular' => ['Sistemas de Información I']
    ],
    'Práctica Profesionalizante II' => [
        'regular' => ['Práctica Profesionalizante I']  // Usando 'Práctica' con tilde
    ],
    'Practica Profesionalizante II' => [
        'regular' => ['Practica Profesionalizante I']  // Sin tilde también
    ],
    'Matemática III' => [
        'regular' => ['Matemática II']
    ],
    'Sistemas de Información II' => [
        'regular' => ['Sistemas de Información I']
    ],
    'Práctica Profesionalizante III' => [
        'regular' => ['Práctica Profesionalizante II']
    ],
    'Practica Profesionalizante III' => [
        'regular' => ['Practica Profesionalizante II']
    ],

    // 3° AÑO
    'Base de Datos II' => [
        'regular' => ['Base de Datos I']
    ],
    'Desarrollo de Software II' => [
        'regular' => ['Desarrollo de Software I']
    ],
    'Práctica Profesionalizante IV' => [
        'regular' => ['Práctica Profesionalizante III']
    ],
    'Practica Profesionalizante IV' => [
        'regular' => ['Practica Profesionalizante III']
    ],
    'Sistemas Operativos y Redes II' => [
        'regular' => ['Sistemas Operativos y Redes I']
    ],
    'Práctica Profesionalizante V' => [
        'regular' => ['Práctica Profesionalizante IV']
    ],
    'Practica Profesionalizante V' => [
        'regular' => ['Practica Profesionalizante IV']
    ],
];

$configuradas = 0;
$no_encontradas = [];

foreach ($configuracion as $materia_nombre => $config) {
    // Buscar la materia (puede tener variaciones en el nombre)
    $materia = DB::table('tbl_materias')
        ->where('nombre', 'LIKE', "%{$materia_nombre}%")
        ->first();

    if (!$materia) {
        $no_encontradas[] = $materia_nombre;
        continue;
    }

    // Configurar correlativas regulares
    if (isset($config['regular'])) {
        $ids_regular = [];
        foreach ($config['regular'] as $correlativa_nombre) {
            $correlativa = DB::table('tbl_materias')
                ->where('nombre', 'LIKE', "%{$correlativa_nombre}%")
                ->first();

            if ($correlativa) {
                $ids_regular[] = $correlativa->id;
            }
        }

        if (!empty($ids_regular)) {
            $valor_regular = implode(':', $ids_regular);
            DB::table('tbl_materias')
                ->where('id', $materia->id)
                ->update(['paracursar_regular' => $valor_regular]);

            echo "✓ {$materia->nombre} → requiere (regular): ";
            foreach ($ids_regular as $idx => $id_corr) {
                $corr = DB::table('tbl_materias')->where('id', $id_corr)->first();
                echo ($idx > 0 ? ', ' : '') . $corr->nombre;
            }
            echo "\n";
            $configuradas++;
        }
    }
}

echo "\n=== RESUMEN ===\n\n";
echo "Materias configuradas: {$configuradas}\n";

if (!empty($no_encontradas)) {
    echo "\nMaterias no encontradas en BD:\n";
    foreach (array_unique($no_encontradas) as $nom) {
        echo "  - {$nom}\n";
    }
}

echo "\n=== VERIFICACIÓN FINAL ===\n\n";

$materias_con_corr = DB::table('tbl_materias')
    ->whereNotNull('paracursar_regular')
    ->orWhereNotNull('paracursar_rendido')
    ->select('id', 'nombre', 'anno', 'semestre', 'paracursar_regular', 'paracursar_rendido')
    ->orderBy('anno')
    ->orderBy('semestre')
    ->get();

echo "Total de materias con correlativas: " . $materias_con_corr->count() . "\n\n";

foreach ($materias_con_corr as $m) {
    echo "{$m->nombre} (Año {$m->anno}, Sem {$m->semestre})\n";
    if ($m->paracursar_regular) {
        $ids = explode(':', $m->paracursar_regular);
        foreach ($ids as $id) {
            $corr = DB::table('tbl_materias')->where('id', $id)->first();
            if ($corr) {
                echo "  → Requiere (regular): {$corr->nombre}\n";
            }
        }
    }
    echo "\n";
}

echo "✅ Configuración completada\n";
