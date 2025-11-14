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
        // Tabla de planes de estudio
        Schema::create('tbl_planes_estudio', function (Blueprint $table) {
            $table->id();
            $table->integer('carrera_id'); // Sin foreign key por incompatibilidad de tipos
            $table->string('nombre', 100); // Ej: "Plan 2024", "Plan 2025"
            $table->year('anio'); // Año del plan
            $table->text('resolucion')->nullable(); // Resolución ministerial
            $table->boolean('activo')->default(true); // Si es el plan vigente
            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index(['carrera_id', 'activo']);
        });

        // Tabla intermedia: materias asignadas a cada plan
        Schema::create('tbl_plan_estudio_materias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_estudio_id');
            $table->integer('materia_id')->unsigned(); // Mismo tipo que tbl_materias.id
            $table->integer('orden')->default(0); // Para ordenar materias dentro del plan
            $table->timestamps();

            // Foreign key solo para plan_estudio_id que es compatible
            $table->foreign('plan_estudio_id')
                ->references('id')
                ->on('tbl_planes_estudio')
                ->onDelete('cascade');

            // NO agregar foreign key para materia_id por incompatibilidad de tipos
            // La integridad referencial se manejará a nivel de aplicación

            // Evitar duplicados
            $table->unique(['plan_estudio_id', 'materia_id']);
            $table->index('plan_estudio_id');
            $table->index('materia_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_plan_estudio_materias');
        Schema::dropIfExists('tbl_planes_estudio');
    }
};
