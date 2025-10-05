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
        Schema::table('tbl_mesas_examen', function (Blueprint $table) {
            $table->date('fecha_inicio_inscripcion')->nullable()->after('fecha_examen');
            $table->date('fecha_fin_inscripcion')->nullable()->after('fecha_inicio_inscripcion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_mesas_examen', function (Blueprint $table) {
            $table->dropColumn(['fecha_inicio_inscripcion', 'fecha_fin_inscripcion']);
        });
    }
};
