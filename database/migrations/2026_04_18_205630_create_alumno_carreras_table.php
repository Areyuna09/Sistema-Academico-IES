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

        // Migrar datos existentes de carrera2 al nuevo tabla
        \DB::table('tbl_alumnos')
            ->whereNotNull('carrera2')
            ->get()
            ->each(function ($alumno) {
                // Resolver plan para la segunda carrera
                $annoIngreso = (int) ($alumno->anno2 ?? date('Y'));
                $plan = \DB::table('tbl_planes_estudio')
                    ->where('carrera_id', $alumno->carrera2)
                    ->where('anio', '<=', $annoIngreso)
                    ->orderBy('anio', 'desc')
                    ->first();
                if (!$plan) {
                    $plan = \DB::table('tbl_planes_estudio')
                        ->where('carrera_id', $alumno->carrera2)
                        ->orderBy('anio', 'asc')
                        ->first();
                }

                \DB::table('tbl_alumno_carreras')->insert([
                    'alumno_id'       => $alumno->id,
                    'carrera_id'      => $alumno->carrera2,
                    'legajo'          => $alumno->legajo2,
                    'anno'            => $alumno->anno2,
                    'curso'           => $alumno->curso2,
                    'division'        => $alumno->division2,
                    'plan_estudio_id' => $plan?->id,
                ]);
            });

        // Quitar columnas carrera2 de tbl_alumnos
        Schema::table('tbl_alumnos', function (Blueprint $table) {
            $table->dropColumn(['carrera2', 'legajo2', 'anno2', 'curso2', 'division2']);
        });
    }

    public function down(): void
    {
        Schema::table('tbl_alumnos', function (Blueprint $table) {
            $table->unsignedInteger('carrera2')->nullable();
            $table->string('legajo2', 50)->nullable();
            $table->integer('anno2')->nullable();
            $table->integer('curso2')->nullable();
            $table->string('division2', 10)->nullable();
        });

        Schema::dropIfExists('tbl_alumno_carreras');
    }
};
