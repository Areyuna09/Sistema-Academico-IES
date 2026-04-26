<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_alumnos', function (Blueprint $table) {
            $table->unsignedInteger('carrera2')->nullable()->after('carrera')->comment('Segunda carrera del alumno (opcional)');
            $table->string('legajo2', 50)->nullable()->after('legajo');
            $table->integer('anno2')->nullable()->after('anno');
            $table->integer('curso2')->nullable()->after('curso');
            $table->string('division2', 10)->nullable()->after('division');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_alumnos', function (Blueprint $table) {
            $table->dropColumn(['carrera2', 'legajo2', 'anno2', 'curso2', 'division2']);
        });
    }
};
