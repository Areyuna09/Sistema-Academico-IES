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
        // Agregar campos de revisión a la tabla de legajos (tbl_alumnos_materias)
        Schema::table('tbl_alumnos_materias', function (Blueprint $table) {
            // Estado del workflow de revisión
            $table->enum('estado_revision', [
                'pendiente',
                'aprobado_directivo',
                'observaciones_directivo',
                'aprobado_supervisor',
                'observaciones_supervisor',
                'aprobado_final'
            ])->default('pendiente')->after('libro_corte');

            // Revisión por Directivo (Tipo 5)
            $table->unsignedBigInteger('revisado_por_directivo')->nullable()->after('estado_revision');
            $table->timestamp('fecha_revision_directivo')->nullable()->after('revisado_por_directivo');
            $table->text('observaciones_directivo')->nullable()->after('fecha_revision_directivo');

            // Revisión por Supervisor (Tipo 6)
            $table->unsignedBigInteger('revisado_por_supervisor')->nullable()->after('observaciones_directivo');
            $table->timestamp('fecha_revision_supervisor')->nullable()->after('revisado_por_supervisor');
            $table->text('observaciones_supervisor')->nullable()->after('fecha_revision_supervisor');

            // Foreign keys
            $table->foreign('revisado_por_directivo')->references('id')->on('users')->onDelete('set null');
            $table->foreign('revisado_por_supervisor')->references('id')->on('users')->onDelete('set null');
        });

        // Crear tabla para historial de revisiones (trazabilidad completa)
        Schema::create('tbl_historial_revisiones', function (Blueprint $table) {
            $table->id();
            $table->integer('alumno_materia_id'); // INT signed porque tbl_alumnos_materias.Id es INT signed
            $table->unsignedBigInteger('revisor_id'); // Usuario que realizó la revisión
            $table->enum('tipo_revisor', ['directivo', 'supervisor']); // Tipo de revisor
            $table->enum('accion', ['aprobar', 'rechazar', 'observar']); // Acción realizada
            $table->text('observaciones')->nullable();
            $table->string('estado_anterior', 50);
            $table->string('estado_nuevo', 50);
            $table->timestamps();

            // Foreign keys
            $table->foreign('alumno_materia_id')->references('Id')->on('tbl_alumnos_materias')->onDelete('cascade');
            $table->foreign('revisor_id')->references('id')->on('users')->onDelete('cascade');

            // Índices para búsquedas rápidas
            $table->index(['alumno_materia_id', 'created_at']);
            $table->index(['revisor_id', 'tipo_revisor']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_alumnos_materias', function (Blueprint $table) {
            $table->dropForeign(['revisado_por_directivo']);
            $table->dropForeign(['revisado_por_supervisor']);
            $table->dropColumn([
                'estado_revision',
                'revisado_por_directivo',
                'fecha_revision_directivo',
                'observaciones_directivo',
                'revisado_por_supervisor',
                'fecha_revision_supervisor',
                'observaciones_supervisor',
            ]);
        });

        Schema::dropIfExists('tbl_historial_revisiones');
    }
};
