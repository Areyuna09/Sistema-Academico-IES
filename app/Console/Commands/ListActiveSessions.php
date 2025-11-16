<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ListActiveSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:list {--active : Mostrar solo sesiones activas (no expiradas)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listar todas las sesiones en la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lifetime = config('session.lifetime') * 60;
        $expiredTime = time() - $lifetime;

        $query = DB::table('sessions')
            ->select('id', 'user_id', 'ip_address', 'last_activity');

        if ($this->option('active')) {
            $query->where('last_activity', '>=', $expiredTime);
        }

        $sessions = $query->orderBy('last_activity', 'desc')->get();

        if ($sessions->isEmpty()) {
            $this->info('No hay sesiones en la base de datos.');
            return 0;
        }

        $tableData = [];
        foreach ($sessions as $session) {
            $user = $session->user_id ? User::find($session->user_id) : null;
            $lastActivity = date('Y-m-d H:i:s', $session->last_activity);
            $isExpired = $session->last_activity < $expiredTime;

            $tableData[] = [
                'ID' => substr($session->id, 0, 10) . '...',
                'Usuario' => $user ? "{$user->name} (ID: {$user->id})" : 'Invitado',
                'IP' => $session->ip_address ?? 'N/A',
                'Última actividad' => $lastActivity,
                'Estado' => $isExpired ? 'Expirada' : 'Activa',
            ];
        }

        $this->table(
            ['ID', 'Usuario', 'IP', 'Última actividad', 'Estado'],
            $tableData
        );

        $totalActivas = collect($tableData)->where('Estado', 'Activa')->count();
        $totalExpiradas = collect($tableData)->where('Estado', 'Expirada')->count();

        $this->newLine();
        $this->info("Total de sesiones: " . count($tableData));
        $this->info("Activas: {$totalActivas} | Expiradas: {$totalExpiradas}");

        return 0;
    }
}
