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
        Schema::create('solicitudes_cambio_email', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('dni', 20);
            $table->string('email_actual', 100);
            $table->string('email_nuevo', 100);
            $table->string('motivo', 500)->nullable();
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->integer('revisado_por')->nullable();
            $table->timestamp('fecha_revision')->nullable();
            $table->text('comentario_admin')->nullable();
            $table->string('ip_solicitud', 45)->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('tbl_usuarios')->onDelete('cascade');
            $table->foreign('revisado_por')->references('id')->on('tbl_usuarios')->onDelete('set null');

            // Indexes
            $table->index('estado');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_cambio_email');
    }
};
