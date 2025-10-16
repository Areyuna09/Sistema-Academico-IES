<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('tbl_historial_notas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('nota_id');
            $table->string('accion',100);
            $table->string('usuario',255);
            $table->timestamp('fecha')->nullable();
        });
    }
    public function down() {
        Schema::dropIfExists('tbl_historial_notas');
    }
};
