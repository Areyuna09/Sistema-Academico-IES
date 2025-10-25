<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordsToDNI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passwords:reset-to-dni {--force : Forzar sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resetea todas las contraseñas de usuarios a su DNI correspondiente';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('¿Estás seguro de que deseas resetear todas las contraseñas a los DNIs de los usuarios?')) {
                $this->info('Operación cancelada.');
                return 0;
            }
        }

        $this->info('Iniciando reseteo de contraseñas...');

        $bar = $this->output->createProgressBar(User::whereNotNull('dni')->count());
        $bar->start();

        $updated = 0;
        $skipped = 0;
        $errors = 0;

        // Resetear contraseñas de usuarios en tabla 'users'
        User::whereNotNull('dni')
            ->where('dni', '!=', '')
            ->chunk(100, function ($users) use (&$updated, &$skipped, &$errors, $bar) {
                foreach ($users as $user) {
                    try {
                        // Verificar que el DNI sea válido
                        $dni = trim($user->dni);

                        if (empty($dni) || !is_numeric($dni)) {
                            $this->warn("\nUsuario {$user->id} ({$user->name}) tiene DNI inválido: {$user->dni}");
                            $skipped++;
                            $bar->advance();
                            continue;
                        }

                        // Actualizar contraseña al DNI hasheado
                        // El modelo User usa tbl_usuarios con columna 'clave'
                        $user->clave = Hash::make($dni);
                        $user->save();

                        $updated++;
                        $bar->advance();
                    } catch (\Exception $e) {
                        $this->error("\nError al actualizar usuario {$user->id}: " . $e->getMessage());
                        $errors++;
                        $bar->advance();
                    }
                }
            });

        $bar->finish();
        $this->newLine(2);

        // Mostrar resumen
        $this->table(
            ['Resultado', 'Cantidad'],
            [
                ['Actualizados', $updated],
                ['Omitidos (sin DNI válido)', $skipped],
                ['Errores', $errors],
            ]
        );

        if ($updated > 0) {
            $this->info("✅ Se actualizaron exitosamente {$updated} contraseñas.");
        }

        if ($skipped > 0) {
            $this->warn("⚠️  Se omitieron {$skipped} usuarios por no tener DNI válido.");
        }

        if ($errors > 0) {
            $this->error("❌ Hubo {$errors} errores durante el proceso.");
        }

        $this->newLine();
        $this->info('Proceso completado.');
        $this->info('Ahora todos los usuarios pueden ingresar con su DNI como contraseña.');

        return 0;
    }
}
