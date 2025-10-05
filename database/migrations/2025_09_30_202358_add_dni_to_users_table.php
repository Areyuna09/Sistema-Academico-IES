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
        Schema::table('users', function (Blueprint $table) {
            // Solo agregar columnas si no existen
            if (!Schema::hasColumn('users', 'dni')) {
                $table->string('dni', 20)->unique()->after('id');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'profesor', 'alumno'])->default('alumno')->after('dni');
            }
            if (!Schema::hasColumn('users', 'alumno_id')) {
                $table->unsignedInteger('alumno_id')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'profesor_id')) {
                $table->unsignedInteger('profesor_id')->nullable()->after('alumno_id');
            }
        });

        // Agregar foreign keys solo si no existen
        Schema::table('users', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $foreignKeys = $sm->listTableForeignKeys('users');

            $hasAlumnoFk = false;
            $hasProfesorFk = false;

            foreach ($foreignKeys as $foreignKey) {
                if (in_array('alumno_id', $foreignKey->getLocalColumns())) {
                    $hasAlumnoFk = true;
                }
                if (in_array('profesor_id', $foreignKey->getLocalColumns())) {
                    $hasProfesorFk = true;
                }
            }

            if (!$hasAlumnoFk && Schema::hasTable('tbl_alumnos')) {
                $table->foreign('alumno_id')->references('id')->on('tbl_alumnos')->onDelete('set null');
            }
            if (!$hasProfesorFk && Schema::hasTable('tbl_profesores')) {
                $table->foreign('profesor_id')->references('id')->on('tbl_profesores')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['alumno_id']);
            $table->dropForeign(['profesor_id']);
            $table->dropColumn(['dni', 'role', 'alumno_id', 'profesor_id']);
            $table->string('email')->nullable(false)->change();
        });
    }
};
