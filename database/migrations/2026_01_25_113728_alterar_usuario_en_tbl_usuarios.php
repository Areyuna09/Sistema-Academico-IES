<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_usuarios', function (Blueprint $table) {
            // Cambiar la columna 'usuario' para que soporte emails largos
            // Importante: nullable() debe coincidir con el estado actual o el deseado
            $table->string('usuario', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_usuarios', function (Blueprint $table) {
            // Revertir a la longitud original (ejemplo: 100)
            $table->string('usuario', 100)->nullable()->change();
        });
    }
};
