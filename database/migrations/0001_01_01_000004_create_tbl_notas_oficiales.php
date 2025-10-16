<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('tbl_notas_oficiales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('materia_id');
            $table->string('nota', 20)->nullable();
            $table->timestamp('fecha_registro')->nullable();
            $table->unsignedBigInteger('origen_id')->nullable();
        });
    }
    public function down() {
        Schema::dropIfExists('tbl_notas_oficiales');
    }
};
