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
        Schema::table('correlativas', function (Blueprint $table) {
            $table->foreign(['materia'], 'fk_correlativas_tbl_materias')->references(['id'])->on('tbl_materias')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['correlativa'], 'fk_correlativas_tbl_materia_2')->references(['id'])->on('tbl_materias')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('correlativas', function (Blueprint $table) {
            $table->dropForeign('fk_correlativas_tbl_materias');
            $table->dropForeign('fk_correlativas_tbl_materia_2');
        });
    }
};
