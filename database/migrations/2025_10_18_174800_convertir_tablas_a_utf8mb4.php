<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tablas legacy que necesitan conversión a UTF-8
     */
    protected array $tablasLegacy = [
        'cursar_agosto_2_semestre',
        'mesa_agosoto_2_llamado',
        'mesa_agosto_1_llamado',
        'tbl_alumnos',
        'tbl_alumnos_cursa',
        'tbl_alumnos_materias',
        'tbl_alumnos_mesa',
        'tbl_carreras',
        'tbl_materias',
        'tbl_materias_alumno',
        'tbl_materias_alumno_cursa',
        'tbl_materias_alumno_mesa',
        'tbl_profesor_tiene_materias',
        'tbl_profesores',
        'tbl_resoluciones',
        'tbl_tipos_usuarios',
        'tbl_usuarios',
    ];

    /**
     * Run the migrations.
     *
     * Convierte todas las tablas legacy de latin1 a utf8mb4
     * Esta migración es IDEMPOTENTE: puede ejecutarse múltiples veces sin problemas
     */
    public function up(): void
    {
        $database = DB::getDatabaseName();

        // Deshabilitar temporalmente el modo estricto para permitir la conversión
        // Esto es necesario porque algunas tablas legacy tienen fechas '0000-00-00 00:00:00'
        DB::statement("SET SESSION sql_mode = ''");

        foreach ($this->tablasLegacy as $tabla) {
            // Verificar si la tabla existe
            $exists = DB::select("SHOW TABLES LIKE '{$tabla}'");
            if (empty($exists)) {
                echo "⚠️  Tabla {$tabla} no existe, omitiendo...\n";
                continue;
            }

            // Verificar collation actual
            $tableInfo = DB::select("SHOW TABLE STATUS WHERE Name = ?", [$tabla]);

            if (!empty($tableInfo)) {
                $collation = $tableInfo[0]->Collation;

                // Solo convertir si aún está en latin1
                if (str_contains($collation, 'latin1')) {
                    echo "🔧 Convirtiendo tabla {$tabla} de {$collation} a utf8mb4_unicode_ci...\n";

                    DB::statement("ALTER TABLE `{$tabla}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

                    echo "✅ Tabla {$tabla} convertida exitosamente\n";
                } else {
                    echo "✓ Tabla {$tabla} ya está en {$collation}\n";
                }
            }
        }

        echo "\n💡 Próximo paso: Ejecutar 'php artisan alumnos:fix-encoding' para corregir datos mal codificados\n";
    }

    /**
     * Reverse the migrations.
     *
     * Revierte las tablas a latin1 (NO RECOMENDADO en producción)
     * Solo para desarrollo si es necesario
     */
    public function down(): void
    {
        echo "⚠️  ADVERTENCIA: Revertir a latin1 puede causar pérdida de datos con caracteres especiales\n";

        foreach ($this->tablasLegacy as $tabla) {
            $exists = DB::select("SHOW TABLES LIKE '{$tabla}'");
            if (!empty($exists)) {
                echo "🔙 Revirtiendo tabla {$tabla} a latin1_swedish_ci...\n";
                DB::statement("ALTER TABLE `{$tabla}` CONVERT TO CHARACTER SET latin1 COLLATE latin1_swedish_ci");
            }
        }
    }
};
