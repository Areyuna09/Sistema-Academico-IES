<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('Email de prueba desde Sistema Académico IES - ' . now(), function ($message) {
        $message->to('ramonareyuna09@gmail.com')
                ->subject('✅ Prueba de Email IES - ' . now()->format('H:i:s'));
    });

    echo "✅ Email enviado exitosamente a ramonareyuna09@gmail.com\n";
    echo "Revisa tu bandeja de entrada o carpeta de spam\n";

} catch (Exception $e) {
    echo "❌ Error al enviar email: " . $e->getMessage() . "\n";
}
