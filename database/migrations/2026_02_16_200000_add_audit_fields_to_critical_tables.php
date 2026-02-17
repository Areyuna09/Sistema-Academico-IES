<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agregar campos de auditoría a las tablas críticas del sistema.
     * Permite rastrear quién realizó operaciones importantes.
     */
    public function up(): void
    {
        // tbl_alumnos_materias (legajo): quién aprobó la nota y cuándo se transfirió
        Schema::table('tbl_alumnos_materias', function (Blueprint $table) {
            $table->unsignedBigInteger('nota_aprobada_por')->nullable()->after('observaciones_supervisor')->comment('Usuario que aprobó la nota y la transfirió al legajo');
            $table->timestamp('fecha_transferencia')->nullable()->after('nota_aprobada_por')->comment('Fecha/hora en que se transfirió al legajo');
        });

        // tbl_profesor_tiene_materias (asignaciones): quién asignó y timestamps
        Schema::table('tbl_profesor_tiene_materias', function (Blueprint $table) {
            $table->unsignedBigInteger('asignado_por')->nullable()->after('configuracion_completa')->comment('Usuario que creó la asignación');
            $table->timestamps();
        });

        // inscripciones (inscripciones a cursado): quién creó y quién canceló
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->unsignedBigInteger('creado_por')->nullable()->after('fecha_cancelacion')->comment('NULL=auto-inscripción, ID=admin que inscribió manualmente');
            $table->unsignedBigInteger('cancelado_por')->nullable()->after('creado_por')->comment('Usuario que canceló la inscripción');
        });

        // tbl_inscripciones_mesa: quién creó
        Schema::table('tbl_inscripciones_mesa', function (Blueprint $table) {
            $table->unsignedBigInteger('creado_por')->nullable()->after('nota_transferida')->comment('NULL=auto-inscripción, ID=admin que inscribió manualmente');
        });

        // tbl_mesas_examen: quién creó y quién modificó
        Schema::table('tbl_mesas_examen', function (Blueprint $table) {
            $table->unsignedBigInteger('creado_por')->nullable()->after('fecha_fin_inscripcion')->comment('Usuario que creó la mesa');
            $table->unsignedBigInteger('modificado_por')->nullable()->after('creado_por')->comment('Último usuario que modificó la mesa');
        });

        // tbl_periodos_inscripcion: quién creó y quién modificó
        Schema::table('tbl_periodos_inscripcion', function (Blueprint $table) {
            $table->unsignedBigInteger('creado_por')->nullable()->after('activo')->comment('Usuario que creó el período');
            $table->unsignedBigInteger('modificado_por')->nullable()->after('creado_por')->comment('Último usuario que modificó el período');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_alumnos_materias', function (Blueprint $table) {
            $table->dropColumn(['nota_aprobada_por', 'fecha_transferencia']);
        });

        Schema::table('tbl_profesor_tiene_materias', function (Blueprint $table) {
            $table->dropColumn(['asignado_por', 'created_at', 'updated_at']);
        });

        Schema::table('inscripciones', function (Blueprint $table) {
            $table->dropColumn(['creado_por', 'cancelado_por']);
        });

        Schema::table('tbl_inscripciones_mesa', function (Blueprint $table) {
            $table->dropColumn(['creado_por']);
        });

        Schema::table('tbl_mesas_examen', function (Blueprint $table) {
            $table->dropColumn(['creado_por', 'modificado_por']);
        });

        Schema::table('tbl_periodos_inscripcion', function (Blueprint $table) {
            $table->dropColumn(['creado_por', 'modificado_por']);
        });
    }
};
