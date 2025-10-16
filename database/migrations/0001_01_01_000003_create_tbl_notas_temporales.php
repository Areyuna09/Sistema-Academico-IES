<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('tbl_notas_temporales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('materia_id');
            $table->unsignedBigInteger('profesor_id');
            $table->string('nota', 20)->nullable();
            $table->text('observacion')->nullable();
            $table->enum('estado', ['pendiente','aprobada','rechazada'])->default('pendiente');
            $table->timestamp('fecha_carga')->nullable();
        });
    }
    public function down() {
        Schema::dropIfExists('tbl_notas_temporales');
    }
};
