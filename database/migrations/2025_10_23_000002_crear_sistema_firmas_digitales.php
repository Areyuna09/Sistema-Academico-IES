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
        // Tabla para registrar firmas digitales en documentos PDF generados
        Schema::create('tbl_firmas_digitales', function (Blueprint $table) {
            $table->id();

            // Tipo de documento firmado
            $table->enum('tipo_documento', [
                'libro_matriz',
                'certificado_academico',
                'titulo_oficial',
                'comprobante_inscripcion',
                'comprobante_mesa',
                'acta_examen',
                'otro'
            ]);

            // Referencia al alumno o entidad relacionada
            $table->integer('alumno_id')->nullable(); // INT signed porque tbl_alumnos.id es INT signed
            $table->unsignedBigInteger('referencia_id')->nullable(); // ID genérico para otros documentos

            // Nombre del archivo PDF generado
            $table->string('nombre_archivo');
            $table->string('ruta_archivo')->nullable(); // Opcional: si se guarda el PDF

            // Firmante (Directivo o Supervisor)
            $table->unsignedBigInteger('firmante_id');
            $table->enum('tipo_firmante', ['directivo', 'supervisor', 'admin']);
            $table->string('nombre_firmante', 200); // Nombre al momento de firmar
            $table->string('cargo_firmante', 100)->nullable(); // Cargo al momento de firmar

            // Hash SHA-256 del contenido del PDF
            $table->string('hash_documento', 64);

            // Metadatos del documento (JSON)
            $table->json('metadatos')->nullable();

            // Auditoría
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('fecha_firma');

            // Verificación de integridad
            $table->boolean('verificado')->default(true);
            $table->timestamp('fecha_verificacion')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('alumno_id')
                  ->references('id')
                  ->on('tbl_alumnos')
                  ->onDelete('set null');

            $table->foreign('firmante_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');

            // Índices
            $table->index(['tipo_documento', 'alumno_id']);
            $table->index(['firmante_id', 'fecha_firma']);
            $table->index('hash_documento');
            $table->index('nombre_archivo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_firmas_digitales');
    }
};
