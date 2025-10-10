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
        Schema::table('tbl_asistencias', function (Blueprint $table) {
            // Tipo de carga: 'diaria' o 'final'
            $table->enum('tipo_carga', ['diaria', 'final'])->default('diaria')->after('estado');

            // Para asistencia final: guardar totales
            $table->unsignedInteger('presentes')->nullable()->after('tipo_carga')->comment('Total presentes (solo para tipo_carga=final)');
            $table->unsignedInteger('ausentes')->nullable()->after('presentes')->comment('Total ausentes (solo para tipo_carga=final)');
            $table->unsignedInteger('tardes')->nullable()->after('ausentes')->comment('Total tardes (solo para tipo_carga=final)');
            $table->unsignedInteger('total_clases')->nullable()->after('tardes')->comment('Total de clases del periodo (solo para tipo_carga=final)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_asistencias', function (Blueprint $table) {
            $table->dropColumn(['tipo_carga', 'presentes', 'ausentes', 'tardes', 'total_clases']);
        });
    }
};
