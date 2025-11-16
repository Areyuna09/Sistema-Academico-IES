<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearAllSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:clear {--user= : Cerrar solo las sesiones de un usuario específico}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cerrar todas las sesiones activas o de un usuario específico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user');

        if ($userId) {
            // Cerrar sesiones de un usuario específico
            $deleted = DB::table('sessions')
                ->where('user_id', $userId)
                ->delete();

            $this->info("Se cerraron {$deleted} sesión(es) del usuario ID: {$userId}");
        } else {
            // Confirmar antes de cerrar TODAS las sesiones
            if ($this->confirm('¿Estás seguro de que querés cerrar TODAS las sesiones activas?')) {
                $deleted = DB::table('sessions')->delete();
                $this->info("Se cerraron {$deleted} sesión(es) en total.");
                $this->warn('Todos los usuarios han sido desconectados.');
            } else {
                $this->info('Operación cancelada.');
            }
        }

        return 0;
    }
}
