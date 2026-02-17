<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permisos_rol', function (Blueprint $table) {
            $table->id();
            $table->string('permiso');
            $table->unsignedTinyInteger('tipo_usuario');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['permiso', 'tipo_usuario']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permisos_rol');
    }
};
