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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            // Tipo de notificación
            $table->enum('tipo', [
                'inscripcion_materia',
                'inscripcion_mesa',
                'nota_aprobada',
                'excepcion_aprobada',
                'excepcion_rechazada',
                'recordatorio',
                'sistema'
            ]);

            // Contenido
            $table->string('titulo', 200);
            $table->text('mensaje');
            $table->string('icono', 50)->default('bx-bell'); // icono de boxicons
            $table->string('color', 20)->default('blue'); // blue, green, red, yellow

            // Estado
            $table->boolean('leida')->default(false);
            $table->timestamp('fecha_lectura')->nullable();

            // Enlaces opcionales
            $table->string('url')->nullable(); // URL de redirección
            $table->json('datos')->nullable(); // Datos adicionales en JSON

            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Indices
            $table->index(['user_id', 'leida']);
            $table->index('tipo');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
