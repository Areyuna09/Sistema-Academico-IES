<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ConvertirTablesUtf8 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:convert-to-utf8
                            {--dry-run : Solo mostrar qué se ejecutaría sin hacer cambios}
                            {--force : Ejecutar sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convertir todas las tablas de latin1 a utf8mb4';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Analizando base de datos...');
        $this->newLine();

        $database = config('database.connections.mysql.database');

        // Obtener todas las tablas
        $tables = DB::select('SHOW TABLES');
        $tableKey = 'Tables_in_' . $database;

        $tablasConProblemas = [];

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;

            // Obtener información de la tabla
            $tableInfo = DB::select("SHOW TABLE STATUS WHERE Name = ?", [$tableName]);

            if (!empty($tableInfo)) {
                $collation = $tableInfo[0]->Collation;

                // Verificar si la tabla usa latin1
                if (str_contains($collation, 'latin1')) {
                    $tablasConProblemas[] = [
                        'tabla' => $tableName,
                        'collation_actual' => $collation,
                    ];
                }
            }
        }

        if (empty($tablasConProblemas)) {
            $this->info('✅ Todas las tablas ya están en UTF-8.');
            return Command::SUCCESS;
        }

        // Mostrar resumen
        $this->warn("⚠️  Se encontraron " . count($tablasConProblemas) . " tablas con collation latin1:");
        $this->newLine();

        $this->table(
            ['Tabla', 'Collation Actual'],
            collect($tablasConProblemas)->map(fn($t) => [
                $t['tabla'],
                $t['collation_actual']
            ])->toArray()
        );

        if ($this->option('dry-run')) {
            $this->info('🔍 Modo dry-run: Se ejecutarían los siguientes comandos:');
            $this->newLine();

            foreach ($tablasConProblemas as $tabla) {
                $this->line("ALTER TABLE `{$tabla['tabla']}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            }

            return Command::SUCCESS;
        }

        // Confirmar antes de proceder
        if (!$this->option('force')) {
            $this->warn('⚠️  ADVERTENCIA: Esta operación modificará la estructura de las tablas.');
            $this->warn('   Se recomienda hacer un backup antes de continuar.');
            $this->newLine();

            if (!$this->confirm('¿Deseas continuar?', false)) {
                $this->info('Operación cancelada.');
                return Command::SUCCESS;
            }
        }

        // Aplicar conversión
        $this->info('🔧 Convirtiendo tablas a UTF-8...');
        $this->newLine();

        $convertidas = 0;
        $errores = 0;

        $progressBar = $this->output->createProgressBar(count($tablasConProblemas));
        $progressBar->start();

        foreach ($tablasConProblemas as $tabla) {
            try {
                // Convertir tabla a utf8mb4
                DB::statement("ALTER TABLE `{$tabla['tabla']}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $convertidas++;
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Error al convertir tabla {$tabla['tabla']}: " . $e->getMessage());
                $errores++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Resumen final
        $this->info("✅ Conversión completada:");
        $this->info("   - Tablas convertidas: {$convertidas}");
        if ($errores > 0) {
            $this->error("   - Errores: {$errores}");
        }

        $this->newLine();
        $this->info("💡 Próximo paso: Ejecutar 'php artisan alumnos:fix-encoding' para corregir los datos.");

        return Command::SUCCESS;
    }
}
