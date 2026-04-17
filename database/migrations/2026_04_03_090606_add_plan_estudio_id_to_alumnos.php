<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Agrega plan_estudio_id a tbl_alumnos.
 *
 * La columna es nullable para no romper registros existentes.
 * Los alumnos sin plan asignado son resueltos automáticamente por
 * Alumno::resolverPlan() usando el año de ingreso como heurística.
 *
 * Luego de correr esta migración podés poblar el campo con:
 *   php artisan alumnos:asignar-planes
 * (ver documentación interna del proyecto)
 *
 * También corrige filas con fecha = '0000-00-00 00:00:00' que MySQL
 * rechaza al alterar la tabla en modo estricto.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Deshabilitar modo estricto para toda la operación (es session-level, se resetea al terminar)
        DB::statement("SET SESSION sql_mode = ''");

        // Corregir fechas inválidas antes del ALTER TABLE
        DB::statement("
            UPDATE tbl_alumnos
            SET fecha = NULL
            WHERE fecha = '0000-00-00 00:00:00'
               OR fecha = '0000-00-00'
               OR (fecha IS NOT NULL AND fecha < '1000-01-01 00:00:00')
        ");

        // Solo agregar la columna si no existe ya (idempotente)
        if (!Schema::hasColumn('tbl_alumnos', 'plan_estudio_id')) {
            Schema::table('tbl_alumnos', function (Blueprint $table) {
                $table->unsignedBigInteger('plan_estudio_id')
                    ->nullable()
                    ->after('carrera')
                    ->comment('FK al plan de estudio asignado. Null = se resuelve por año de ingreso.');

                $table->foreign('plan_estudio_id')
                    ->references('id')
                    ->on('tbl_planes_estudio')
                    ->nullOnDelete(); // Si se elimina el plan, el campo queda null
            });
        }
    }

    public function down(): void
    {
        Schema::table('tbl_alumnos', function (Blueprint $table) {
            // Eliminar FK primero, luego la columna
            $table->dropForeign(['plan_estudio_id']);
            $table->dropColumn('plan_estudio_id');
        });
    }
};