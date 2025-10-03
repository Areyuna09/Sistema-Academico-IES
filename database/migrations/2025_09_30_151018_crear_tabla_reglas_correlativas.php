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
        Schema::create('reglas_correlativas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('materia_id')->comment('ID de la materia (FK a tbl_materias)');
            $table->unsignedInteger('carrera_id')->comment('ID de la carrera (FK a tbl_carreras)');
            $table->enum('tipo', ['cursar', 'rendir'])->comment('Tipo de validación');
            $table->unsignedInteger('correlativa_id')->comment('ID de la materia correlativa');
            $table->enum('estado_requerido', ['regular', 'aprobada'])->comment('Estado que debe tener la correlativa');
            $table->boolean('es_activa')->default(true)->comment('Permite activar/desactivar regla');
            $table->json('excepciones')->nullable()->comment('Casos especiales en formato JSON');
            $table->text('observaciones')->nullable()->comment('Notas adicionales sobre la regla');
            $table->timestamps();

            // Índices para mejorar performance
            $table->index('materia_id');
            $table->index('carrera_id');
            $table->index(['materia_id', 'carrera_id', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reglas_correlativas');
    }
};
