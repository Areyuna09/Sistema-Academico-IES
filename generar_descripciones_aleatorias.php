<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Alumno;

echo "═══════════════════════════════════════════════════════════════════\n";
echo "   GENERADOR DE DESCRIPCIONES ALEATORIAS (SIN API)\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// Frases motivacionales por nivel de progreso
$frasesPorNivel = [
    'inicio' => [ // 0-5 materias aprobadas
        "Cada gran desarrollador comenzó donde estás ahora",
        "Tu código puede cambiar el mundo",
        "El primer paso es el más importante",
        "Aprende los fundamentos, dominarás el futuro",
        "Cada línea de código te acerca a tus sueños",
    ],
    'avanzando' => [ // 6-12 materias aprobadas
        "Estás construyendo las bases de tu éxito",
        "La persistencia es tu mejor algoritmo",
        "Cada proyecto te hace más fuerte",
        "El conocimiento es tu mejor inversión",
        "Sigue así, lo estás haciendo muy bien",
    ],
    'mitad' => [ // 13-18 materias aprobadas
        "Ya recorriste la mitad del camino",
        "Tus habilidades crecen con cada desafío",
        "El aprendizaje constante es tu superpoder",
        "Cada bug resuelto es un paso adelante",
        "La tecnología espera tus ideas",
    ],
    'final' => [ // 19-25 materias aprobadas
        "Estás a punto de alcanzar tu meta",
        "El mundo tech necesita tu talento",
        "Casi listo para cambiar el mundo con código",
        "Tu título está más cerca que nunca",
        "La meta está cerca, no te rindas ahora",
    ],
    'graduado' => [ // 26+ materias aprobadas
        "Felicitaciones por completar tu carrera",
        "Tu conocimiento merece ser compartido",
        "El mundo espera tus soluciones",
        "Listo para dejar tu huella en la tecnología",
        "Eres el profesional que siempre quisiste ser",
    ],
];

// Seleccionar alumnos sin descripción
$alumnos = Alumno::where('carrera', 1)
    ->whereNull('descripcion_personalizada')
    ->get();

if ($alumnos->count() === 0) {
    echo "✅ Todos los alumnos ya tienen descripción personalizada\n\n";
    exit(0);
}

echo "🎯 Generando descripciones para {$alumnos->count()} alumnos...\n\n";

foreach ($alumnos as $alumno) {
    // Obtener contexto del alumno
    $historial = \App\Models\AlumnoMateria::where('alumno', $alumno->id)
        ->where('carrera', $alumno->carrera)
        ->get();

    $aprobadas = $historial->where('rendida', '1')->count();

    // Determinar nivel
    $nivel = 'inicio';
    if ($aprobadas >= 26) {
        $nivel = 'graduado';
    } elseif ($aprobadas >= 19) {
        $nivel = 'final';
    } elseif ($aprobadas >= 13) {
        $nivel = 'mitad';
    } elseif ($aprobadas >= 6) {
        $nivel = 'avanzando';
    }

    // Seleccionar frase aleatoria del nivel correspondiente
    $frasesNivel = $frasesPorNivel[$nivel];
    $frase = $frasesNivel[array_rand($frasesNivel)];

    // Guardar en base de datos
    $alumno->descripcion_personalizada = $frase;
    $alumno->save();

    echo "✓ {$alumno->nombre_completo} ({$aprobadas} aprobadas - {$nivel}): \"{$frase}\"\n";
}

echo "\n═══════════════════════════════════════════════════════════════════\n";
echo "   PROCESO COMPLETADO\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";
echo "✅ {$alumnos->count()} descripciones generadas\n";
echo "💰 Costo: \$0.00 (sin uso de API)\n\n";
