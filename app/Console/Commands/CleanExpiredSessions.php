<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpiar sesiones expiradas de la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Obtener el lifetime de sesión del config (en segundos)
        $lifetime = config('session.lifetime') * 60; // Convertir minutos a segundos

        // Calcular timestamp de expiración
        $expiredTime = time() - $lifetime;

        // Eliminar sesiones expiradas
        $deleted = DB::table('sessions')
            ->where('last_activity', '<', $expiredTime)
            ->delete();

        if ($deleted > 0) {
            $this->info("Se eliminaron {$deleted} sesión(es) expirada(s).");
        } else {
            $this->info('No hay sesiones expiradas para limpiar.');
        }

        return 0;
    }
}
