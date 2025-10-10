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
        Schema::create('tbl_notas_temporales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profesor_materia_id');
            $table->unsignedBigInteger('alumno_id');
            $table->decimal('nota', 4, 2); // Nota con 2 decimales
            $table->string('tipo_evaluacion', 100); // Parcial 1, Parcial 2, TP, Final, etc.
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->date('fecha');
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('registrado_por'); // Usuario que registró
            $table->unsignedBigInteger('aprobado_por')->nullable(); // Usuario que aprobó/rechazó
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index('profesor_materia_id');
            $table->index('alumno_id');
            $table->index('estado');
            $table->index('fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_notas_temporales');
    }
};
