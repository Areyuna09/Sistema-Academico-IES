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
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('alumno_id')->comment('ID del alumno');
            $table->unsignedInteger('materia_id')->comment('ID de la materia');
            $table->unsignedInteger('carrera_id')->comment('ID de la carrera');
            $table->unsignedBigInteger('periodo_id')->comment('ID del período de inscripción');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada'])->default('confirmada');
            $table->text('observaciones')->nullable()->comment('Observaciones sobre la inscripción');
            $table->timestamp('fecha_inscripcion')->useCurrent()->comment('Fecha y hora de inscripción');
            $table->timestamp('fecha_cancelacion')->nullable()->comment('Fecha y hora de cancelación si aplica');
            $table->timestamps();

            // Índices para mejorar búsquedas
            $table->index('alumno_id');
            $table->index('materia_id');
            $table->index('periodo_id');
            $table->index('estado');

            // Índice compuesto para evitar inscripciones duplicadas
            $table->unique(['alumno_id', 'materia_id', 'periodo_id'], 'unique_inscripcion');

            // Comentario de la tabla
            $table->comment('Tabla de inscripciones a materias por período académico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};
