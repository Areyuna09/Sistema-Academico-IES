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
        Schema::table('tbl_planes_estudio', function (Blueprint $table) {
            // Campo vigente: indica si es el plan por defecto para nuevos inscriptos
            $table->boolean('vigente')->default(false)->after('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_planes_estudio', function (Blueprint $table) {
            $table->dropColumn('vigente');
        });
    }
};
