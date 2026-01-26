<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insertar nuevos tipos de usuario si no existen
        $tipos = [
            ['id' => 7, 'nombre' => 'Bedel'],
            ['id' => 8, 'nombre' => 'Preceptor'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tbl_tipos_usuarios')->updateOrInsert(
                ['id' => $tipo['id']],
                ['nombre' => $tipo['nombre']]
            );
        }

        // Actualizar nombres para mayor claridad
        DB::table('tbl_tipos_usuarios')->updateOrInsert(
            ['id' => 5],
            ['nombre' => 'Directivo']
        );

        DB::table('tbl_tipos_usuarios')->updateOrInsert(
            ['id' => 6],
            ['nombre' => 'Supervisor']
        );

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No eliminar tipos de usuario por seguridad
        // Solo en caso de rollback completo
        DB::table('tbl_tipos_usuarios')
            ->whereIn('id', [7, 8])
            ->delete();

    }
};