<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TipoUsuario;

class TiposUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['id' => TipoUsuario::ADMIN, 'nombre' => 'Administrador'],
            ['id' => TipoUsuario::USUARIO, 'nombre' => 'Usuario'],
            ['id' => TipoUsuario::PROFESOR, 'nombre' => 'Profesor'],
            ['id' => TipoUsuario::ALUMNO, 'nombre' => 'Alumno'],
            ['id' => TipoUsuario::DIRECTIVO, 'nombre' => 'Directivo'],
            ['id' => TipoUsuario::SUPERVISOR, 'nombre' => 'Supervisor'],
            ['id' => TipoUsuario::BEDEL, 'nombre' => 'Bedel'],
            ['id' => TipoUsuario::PRECEPTOR, 'nombre' => 'Preceptor'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tbl_tipos_usuarios')->updateOrInsert(
                ['id' => $tipo['id']],
                ['nombre' => $tipo['nombre']]
            );
        }

        $this->command->info('✅ Tipos de usuario creados/actualizados correctamente.');
    }
}