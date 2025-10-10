<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_asistencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profesor_materia_id')->comment('FK a tbl_profesor_tiene_materias');
            $table->unsignedBigInteger('alumno_id')->comment('FK a tbl_alumnos');
            $table->date('fecha');
            $table->enum('estado', ['presente', 'ausente', 'tarde', 'justificada']);
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('registrado_por')->comment('ID del profesor que registró');
            $table->timestamps();

            // Índices
            $table->unique(['profesor_materia_id', 'alumno_id', 'fecha'], 'unique_asistencia');
            $table->index('fecha', 'idx_fecha');
            $table->index('alumno_id', 'idx_alumno');

            // Foreign keys (opcional - ajustar según tu BD)
            // $table->foreign('profesor_materia_id')->references('id')->on('tbl_profesor_tiene_materias')->onDelete('cascade');
            // $table->foreign('alumno_id')->references('id')->on('tbl_alumnos')->onDelete('cascade');
            // $table->foreign('registrado_por')->references('id')->on('tbl_usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_asistencias');
    }
};