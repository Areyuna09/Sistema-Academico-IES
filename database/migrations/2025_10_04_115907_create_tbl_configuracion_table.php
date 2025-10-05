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
        Schema::create('tbl_configuracion', function (Blueprint $table) {
            $table->id();

            // Información de la institución
            $table->string('nombre_institucion')->default('Instituto de Educación Superior');
            $table->string('logo_path')->nullable();

            // Datos de contacto
            $table->text('direccion')->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('sitio_web', 255)->nullable();

            // Información para documentos
            $table->text('footer_documentos')->nullable();
            $table->string('firma_digital_path')->nullable();
            $table->string('cargo_firma')->nullable(); // Ej: "Director/a", "Rector/a"

            // Horarios de atención
            $table->text('horarios_atencion')->nullable();

            $table->timestamps();
        });

        // Insertar registro por defecto
        DB::table('tbl_configuracion')->insert([
            'nombre_institucion' => 'Instituto de Educación Superior',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_configuracion');
    }
};
