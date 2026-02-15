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
        Schema::table('tbl_profesor_tiene_materias', function (Blueprint $table) {
            $table->unsignedBigInteger('periodo_id')->nullable()->after('division');
            $table->foreign('periodo_id')->references('id')->on('tbl_periodos_inscripcion')->nullOnDelete();
            $table->index('periodo_id');
        });

        // Asignar registros existentes al período activo (o al más reciente)
        $periodoActivo = DB::table('tbl_periodos_inscripcion')->where('activo', true)->first();
        if (!$periodoActivo) {
            $periodoActivo = DB::table('tbl_periodos_inscripcion')->orderByDesc('anio')->orderByDesc('cuatrimestre')->first();
        }
        if ($periodoActivo) {
            DB::table('tbl_profesor_tiene_materias')->whereNull('periodo_id')->update(['periodo_id' => $periodoActivo->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_profesor_tiene_materias', function (Blueprint $table) {
            $table->dropForeign(['periodo_id']);
            $table->dropIndex(['periodo_id']);
            $table->dropColumn('periodo_id');
        });
    }
};
