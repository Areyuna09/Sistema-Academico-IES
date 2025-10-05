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
        Schema::table('tbl_usuarios', function (Blueprint $table) {
            $table->integer('pais')->nullable()->change();
            $table->integer('provincia')->nullable()->change();
            $table->integer('sexo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_usuarios', function (Blueprint $table) {
            $table->integer('pais')->nullable(false)->change();
            $table->integer('provincia')->nullable(false)->change();
            $table->integer('sexo')->nullable(false)->change();
        });
    }
};
