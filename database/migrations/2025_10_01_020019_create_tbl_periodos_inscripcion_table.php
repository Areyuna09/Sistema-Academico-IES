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
        Schema::create('tbl_periodos_inscripcion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100); // Ej: "1° Cuatrimestre 2025"
            $table->enum('cuatrimestre', ['1', '2']); // 1 o 2
            $table->year('anio'); // 2025, 2026, etc.
            $table->date('fecha_inicio_inscripcion'); // Fecha de inicio
            $table->date('fecha_fin_inscripcion'); // Fecha de cierre
            $table->date('fecha_inicio_cursada'); // Cuando empiezan las clases
            $table->date('fecha_fin_cursada'); // Cuando terminan las clases
            $table->boolean('activo')->default(false); // Solo uno puede estar activo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_periodos_inscripcion');
    }
};
