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
        Schema::create('tbl_mesas_examen', function (Blueprint $table) {
            $table->id();
            $table->integer('materia_id');
            $table->date('fecha_examen');
            $table->time('hora_examen');
            $table->tinyInteger('llamado')->comment('1er, 2do o 3er llamado');
            $table->unsignedBigInteger('periodo_id')->nullable();
            $table->integer('cupo_maximo')->default(50);
            $table->string('aula', 100)->nullable();
            $table->integer('presidente_id')->nullable()->comment('Profesor presidente del tribunal');
            $table->integer('vocal1_id')->nullable()->comment('Profesor vocal 1');
            $table->integer('vocal2_id')->nullable()->comment('Profesor vocal 2');
            $table->enum('estado', ['activa', 'cerrada', 'suspendida'])->default('activa');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('materia_id')->references('id')->on('tbl_materias')->onDelete('cascade');
            $table->foreign('periodo_id')->references('id')->on('tbl_periodos_inscripcion')->onDelete('set null');
            $table->foreign('presidente_id')->references('id')->on('tbl_profesores')->onDelete('set null');
            $table->foreign('vocal1_id')->references('id')->on('tbl_profesores')->onDelete('set null');
            $table->foreign('vocal2_id')->references('id')->on('tbl_profesores')->onDelete('set null');

            // Indexes
            $table->index('materia_id');
            $table->index('fecha_examen');
            $table->index('periodo_id');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_mesas_examen');
    }
};
