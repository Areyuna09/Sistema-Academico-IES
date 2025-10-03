<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_usuarios', function (Blueprint $table) {
            // Agregar campo dni después de id
            if (!Schema::hasColumn('tbl_usuarios', 'dni')) {
                $table->string('dni', 20)->nullable()->after('id');
            }
        });

        // Migrar datos: copiar 'usuario' a 'dni' para los registros existentes
        DB::statement('UPDATE tbl_usuarios SET dni = usuario WHERE dni IS NULL');

        // Ahora hacer el campo dni NOT NULL y único
        Schema::table('tbl_usuarios', function (Blueprint $table) {
            $table->string('dni', 20)->nullable(false)->change();

            // Agregar índice único en dni
            try {
                $table->unique('dni');
            } catch (\Exception $e) {
                // El índice ya existe
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_usuarios', function (Blueprint $table) {
            $table->dropUnique(['dni']);
            $table->dropColumn('dni');
        });
    }
};
