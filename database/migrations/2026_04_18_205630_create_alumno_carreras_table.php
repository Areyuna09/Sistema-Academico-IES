<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_alumno_carreras', function (Blueprint $table) {
            $table->id();
            $table->integer('alumno_id');
            $table->integer('carrera_id');
            $table->string('legajo', 50)->nullable();
            $table->integer('anno')->nullable();
            $table->integer('curso')->nullable();
            $table->string('division', 10)->nullable();
            $table->unsignedBigInteger('plan_estudio_id')->nullable();
            $table->foreign('alumno_id')->references('id')->on('tbl_alumnos')->onDelete('cascade');
            $table->foreign('carrera_id')->references('Id')->on('tbl_carreras')->onDelete('cascade');
            $table->foreign('plan_estudio_id')->references('id')->on('tbl_planes_estudio')->onDelete('set null');
            $table->unique(['alumno_id', 'carrera_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_alumno_carreras');
    }
};
