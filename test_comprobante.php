<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Alumno;
use App\Models\Inscripcion;
use App\Models\PeriodoInscripcion;
use Illuminate\Support\Facades\Mail;

try {
    // Obtener alumno
    $alumno = Alumno::where('dni', '46180633')->first();

    if (!$alumno) {
        echo "❌ Alumno no encontrado\n";
        exit;
    }

    echo "✅ Alumno encontrado: {$alumno->nombre_completo}\n";
    echo "✅ Email: " . ($alumno->email ?? 'Sin email') . "\n\n";

    // Obtener período activo
    $periodo = PeriodoInscripcion::activo();

    if (!$periodo) {
        echo "❌ No hay período activo\n";
        exit;
    }

    echo "✅ Período: {$periodo->nombre}\n\n";

    // Obtener inscripciones del alumno en este período
    $inscripciones = Inscripcion::with('materia')
        ->where('alumno_id', $alumno->id)
        ->where('periodo_id', $periodo->id)
        ->where('estado', 'confirmada')
        ->get();

    if ($inscripciones->count() === 0) {
        echo "❌ No hay inscripciones para este alumno\n";
        exit;
    }

    echo "✅ Inscripciones encontradas: {$inscripciones->count()}\n";
    foreach ($inscripciones as $insc) {
        echo "   - {$insc->materia->nombre}\n";
    }
    echo "\n";

    // Enviar email
    $emailDestino = $alumno->email ?? 'ramonareyuna09@gmail.com';

    echo "📧 Enviando comprobante a: {$emailDestino}\n\n";

    Mail::to($emailDestino)->send(new \App\Mail\ComprobanteInscripcion(
        $alumno,
        $inscripciones,
        $periodo
    ));

    echo "✅ Email enviado exitosamente!\n";
    echo "🔍 Revisa tu inbox de Mailtrap: https://mailtrap.io/inboxes\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n" . $e->getTraceAsString() . "\n";
}
