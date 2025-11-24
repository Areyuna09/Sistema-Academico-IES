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
            $table->dropColumn('aula');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_mesas_examen', function (Blueprint $table) {
            $table->string('aula', 100)->nullable()->after('periodo_id');
        });
    }
};
