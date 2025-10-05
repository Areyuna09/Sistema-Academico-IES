<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabla de países
        Schema::create('tbl_paises', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->string('nombre', 100);
            $table->string('codigo_iso', 3)->nullable();
            $table->boolean('activo')->default(true);
        });

        // Tabla de provincias
        Schema::create('tbl_provincias', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->string('nombre', 100);
            $table->integer('pais_id')->default(1);
            $table->boolean('activo')->default(true);

            $table->foreign('pais_id')->references('id')->on('tbl_paises')->onDelete('cascade');
        });

        // Tabla de sexos
        Schema::create('tbl_sexos', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->string('nombre', 50);
            $table->boolean('activo')->default(true);
        });

        // Insertar datos por defecto
        DB::table('tbl_paises')->insert([
            ['id' => 1, 'nombre' => 'Argentina', 'codigo_iso' => 'ARG', 'activo' => true],
        ]);

        DB::table('tbl_provincias')->insert([
            ['id' => 1, 'nombre' => 'San Juan', 'pais_id' => 1, 'activo' => true],
            ['id' => 2, 'nombre' => 'Mendoza', 'pais_id' => 1, 'activo' => true],
            ['id' => 3, 'nombre' => 'San Luis', 'pais_id' => 1, 'activo' => true],
            ['id' => 4, 'nombre' => 'La Rioja', 'pais_id' => 1, 'activo' => true],
            ['id' => 5, 'nombre' => 'Córdoba', 'pais_id' => 1, 'activo' => true],
            ['id' => 6, 'nombre' => 'Buenos Aires', 'pais_id' => 1, 'activo' => true],
            ['id' => 7, 'nombre' => 'CABA', 'pais_id' => 1, 'activo' => true],
            ['id' => 8, 'nombre' => 'Catamarca', 'pais_id' => 1, 'activo' => true],
            ['id' => 9, 'nombre' => 'Chaco', 'pais_id' => 1, 'activo' => true],
            ['id' => 10, 'nombre' => 'Chubut', 'pais_id' => 1, 'activo' => true],
            ['id' => 11, 'nombre' => 'Corrientes', 'pais_id' => 1, 'activo' => true],
            ['id' => 12, 'nombre' => 'Entre Ríos', 'pais_id' => 1, 'activo' => true],
            ['id' => 13, 'nombre' => 'Formosa', 'pais_id' => 1, 'activo' => true],
            ['id' => 14, 'nombre' => 'Jujuy', 'pais_id' => 1, 'activo' => true],
            ['id' => 15, 'nombre' => 'La Pampa', 'pais_id' => 1, 'activo' => true],
            ['id' => 16, 'nombre' => 'Misiones', 'pais_id' => 1, 'activo' => true],
            ['id' => 17, 'nombre' => 'Neuquén', 'pais_id' => 1, 'activo' => true],
            ['id' => 18, 'nombre' => 'Río Negro', 'pais_id' => 1, 'activo' => true],
            ['id' => 19, 'nombre' => 'Salta', 'pais_id' => 1, 'activo' => true],
            ['id' => 20, 'nombre' => 'Santa Cruz', 'pais_id' => 1, 'activo' => true],
            ['id' => 21, 'nombre' => 'Santa Fe', 'pais_id' => 1, 'activo' => true],
            ['id' => 22, 'nombre' => 'Santiago del Estero', 'pais_id' => 1, 'activo' => true],
            ['id' => 23, 'nombre' => 'Tierra del Fuego', 'pais_id' => 1, 'activo' => true],
            ['id' => 24, 'nombre' => 'Tucumán', 'pais_id' => 1, 'activo' => true],
        ]);

        DB::table('tbl_sexos')->insert([
            ['id' => 1, 'nombre' => 'Masculino', 'activo' => true],
            ['id' => 2, 'nombre' => 'Femenino', 'activo' => true],
            ['id' => 3, 'nombre' => 'Otro', 'activo' => true],
        ]);

        // Agregar foreign keys a tbl_usuarios
        Schema::table('tbl_usuarios', function (Blueprint $table) {
            $table->foreign('pais')->references('id')->on('tbl_paises')->onDelete('set null');
            $table->foreign('provincia')->references('id')->on('tbl_provincias')->onDelete('set null');
            $table->foreign('sexo')->references('id')->on('tbl_sexos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar foreign keys primero
        Schema::table('tbl_usuarios', function (Blueprint $table) {
            $table->dropForeign(['pais']);
            $table->dropForeign(['provincia']);
            $table->dropForeign(['sexo']);
        });

        Schema::dropIfExists('tbl_provincias');
        Schema::dropIfExists('tbl_sexos');
        Schema::dropIfExists('tbl_paises');
    }
};
