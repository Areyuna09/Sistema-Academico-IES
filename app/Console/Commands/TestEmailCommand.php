<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email : El correo electrónico de destino}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Envía un correo de prueba para verificar la configuración SMTP';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info("Enviando correo de prueba a: {$email}");
        $this->info("Usando host: " . config('mail.mailers.smtp.host'));
        $this->info("Puerto: " . config('mail.mailers.smtp.port'));
        $this->newLine();

        try {
            Mail::raw('Este es un correo de prueba del Sistema Académico IES. Si recibiste este mensaje, la configuración SMTP está funcionando correctamente.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Prueba de Configuración SMTP - Sistema Académico IES')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            $this->info('✅ Correo enviado exitosamente!');
            $this->info('Revisa la bandeja de entrada de: ' . $email);
            $this->newLine();
            $this->warn('Nota: Si no lo ves, revisa la carpeta de spam.');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Error al enviar el correo:');
            $this->error($e->getMessage());
            $this->newLine();
            $this->warn('Verifica que:');
            $this->warn('1. Tu correo y contraseña de aplicación estén correctos en el archivo .env');
            $this->warn('2. Hayas generado una "Contraseña de aplicación" en Google (no uses tu contraseña normal)');
            $this->warn('3. Tu cuenta de Gmail tenga la verificación en 2 pasos activada');

            return Command::FAILURE;
        }
    }
}
