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
        Schema::create('tbl_inscripciones_mesa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mesa_id');
            $table->integer('alumno_id');
            $table->timestamp('fecha_inscripcion')->useCurrent();
            $table->enum('estado', ['inscripto', 'presente', 'ausente', 'aprobado', 'desaprobado'])->default('inscripto');
            $table->decimal('nota', 4, 2)->nullable()->comment('Nota temporal hasta confirmar');
            $table->text('observaciones')->nullable();
            $table->boolean('nota_transferida')->default(false)->comment('Si la nota ya fue transferida a tbl_alumnos_materias');
            $table->timestamps();

            // Foreign keys
            $table->foreign('mesa_id')->references('id')->on('tbl_mesas_examen')->onDelete('cascade');
            $table->foreign('alumno_id')->references('id')->on('tbl_alumnos')->onDelete('cascade');

            // Evitar inscripciones duplicadas
            $table->unique(['mesa_id', 'alumno_id']);

            // Indexes
            $table->index('mesa_id');
            $table->index('alumno_id');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_inscripciones_mesa');
    }
};
