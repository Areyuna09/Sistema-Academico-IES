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
        Schema::create('excepciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alumno_id');
            $table->enum('tipo', [
                'correlativa',
                'reingreso',
                'cambio_carrera',
                'cambio_plan',
                'justificacion_inasistencia',
                'otra'
            ]);
            $table->string('descripcion', 500);
            $table->text('justificacion_administrativa')->nullable();
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');

            // Referencias opcionales a materias/mesas si aplica
            $table->unsignedBigInteger('materia_id')->nullable();
            $table->unsignedBigInteger('mesa_examen_id')->nullable();

            // Auditoría
            $table->unsignedBigInteger('solicitado_por')->nullable(); // usuario que solicitó
            $table->unsignedBigInteger('aprobado_por')->nullable(); // admin que aprobó/rechazó
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->timestamp('fecha_resolucion')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('alumno_id')->references('id')->on('tbl_alumnos')->onDelete('cascade');
            $table->foreign('materia_id')->references('id')->on('tbl_materias')->onDelete('set null');
            $table->foreign('solicitado_por')->references('id')->on('users')->onDelete('set null');
            $table->foreign('aprobado_por')->references('id')->on('users')->onDelete('set null');

            // Indices
            $table->index('estado');
            $table->index('tipo');
            $table->index('fecha_solicitud');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excepciones');
    }
};
