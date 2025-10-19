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
        Schema::create('correlativas', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('materia')->index('fk_correlativas_tbl_materias');
            $table->integer('correlativa')->index('fk_correlativas_tbl_materia_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('correlativas');
    }
};
