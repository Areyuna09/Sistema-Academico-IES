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
        Schema::table('tbl_mesas_examen', function (Blueprint $table) {
            $table->dropColumn('cupo_maximo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_mesas_examen', function (Blueprint $table) {
            $table->integer('cupo_maximo')->default(50)->after('periodo_id');
        });
    }
};
