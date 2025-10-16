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
        Schema::table('tbl_profesor_tiene_materias', function (Blueprint $table) {
            // Configuración de promoción y regularidad
            $table->decimal('nota_minima_promocion', 4, 2)->default(7.00)->after('division');
            $table->decimal('nota_minima_regularidad', 4, 2)->default(4.00)->after('nota_minima_promocion');
            $table->boolean('permite_promocion')->default(true)->after('nota_minima_regularidad');
            $table->integer('porcentaje_asistencia_minimo')->default(75)->after('permite_promocion');

            // Información adicional
            $table->text('criterios_evaluacion')->nullable()->after('porcentaje_asistencia_minimo');
            $table->boolean('configuracion_completa')->default(false)->after('criterios_evaluacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_profesor_tiene_materias', function (Blueprint $table) {
            $table->dropColumn([
                'nota_minima_promocion',
                'nota_minima_regularidad',
                'permite_promocion',
                'porcentaje_asistencia_minimo',
                'criterios_evaluacion',
                'configuracion_completa'
            ]);
        });
    }
};
