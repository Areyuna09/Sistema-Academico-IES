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
        Schema::create('configuracion_modulos', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique()->comment('Clave única del módulo para identificarlo en el código');
            $table->string('nombre')->comment('Nombre visible del módulo');
            $table->text('descripcion')->nullable()->comment('Descripción de qué hace el módulo');
            $table->string('categoria')->comment('Categoría para agrupar (modulos, periodos, validaciones, etc.)');
            $table->boolean('activo')->default(true)->comment('Si el módulo está activo o no');
            $table->integer('orden')->default(0)->comment('Orden de visualización');
            $table->string('icono')->nullable()->comment('Icono de Boxicons para mostrar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion_modulos');
    }
};
