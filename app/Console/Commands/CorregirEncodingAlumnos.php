<?php

namespace App\Console\Commands;

use App\Models\Alumno;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CorregirEncodingAlumnos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alumnos:fix-encoding
                            {--dry-run : Solo mostrar qué se corregiría sin hacer cambios}
                            {--force : Ejecutar sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corregir encoding incorrecto en nombres y apellidos de alumnos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Analizando encoding de alumnos...');
        $this->newLine();

        // Obtener todos los alumnos
        $alumnos = Alumno::all();
        $problemasEncontrados = [];
        $totalProblemas = 0;

        // Detectar problemas de encoding
        foreach ($alumnos as $alumno) {
            $problemas = [];

            // Verificar nombre
            if ($this->tieneProblemaEncoding($alumno->nombre)) {
                $nombreCorregido = $this->corregirEncoding($alumno->nombre);
                $problemas['nombre'] = [
                    'original' => $alumno->nombre,
                    'corregido' => $nombreCorregido,
                ];
            }

            // Verificar apellido
            if ($this->tieneProblemaEncoding($alumno->apellido)) {
                $apellidoCorregido = $this->corregirEncoding($alumno->apellido);
                $problemas['apellido'] = [
                    'original' => $alumno->apellido,
                    'corregido' => $apellidoCorregido,
                ];
            }

            if (!empty($problemas)) {
                $problemasEncontrados[$alumno->id] = [
                    'dni' => $alumno->dni,
                    'problemas' => $problemas,
                ];
                $totalProblemas++;
            }
        }

        if ($totalProblemas === 0) {
            $this->info('✅ No se encontraron problemas de encoding.');
            return Command::SUCCESS;
        }

        // Mostrar resumen
        $this->warn("⚠️  Se encontraron {$totalProblemas} alumnos con problemas de encoding:");
        $this->newLine();

        // Mostrar primeros 10 ejemplos
        $ejemplos = array_slice($problemasEncontrados, 0, 10, true);

        $this->table(
            ['ID', 'DNI', 'Campo', 'Original', 'Corregido'],
            collect($ejemplos)->flatMap(function ($data, $id) {
                $rows = [];
                foreach ($data['problemas'] as $campo => $valores) {
                    $rows[] = [
                        $id,
                        $data['dni'],
                        ucfirst($campo),
                        $valores['original'],
                        $valores['corregido'],
                    ];
                }
                return $rows;
            })->toArray()
        );

        if (count($problemasEncontrados) > 10) {
            $this->info('... y ' . (count($problemasEncontrados) - 10) . ' más.');
            $this->newLine();
        }

        // Modo dry-run
        if ($this->option('dry-run')) {
            $this->info('🔍 Modo dry-run: No se realizaron cambios.');
            return Command::SUCCESS;
        }

        // Confirmar antes de proceder
        if (!$this->option('force')) {
            if (!$this->confirm('¿Deseas corregir estos registros?', true)) {
                $this->info('Operación cancelada.');
                return Command::SUCCESS;
            }
        }

        // Aplicar correcciones
        $this->info('🔧 Aplicando correcciones...');
        $this->newLine();

        $corregidos = 0;
        $errores = 0;

        $progressBar = $this->output->createProgressBar(count($problemasEncontrados));
        $progressBar->start();

        foreach ($problemasEncontrados as $id => $data) {
            try {
                $alumno = Alumno::find($id);

                if (isset($data['problemas']['nombre'])) {
                    $alumno->nombre = $data['problemas']['nombre']['corregido'];
                }

                if (isset($data['problemas']['apellido'])) {
                    $alumno->apellido = $data['problemas']['apellido']['corregido'];
                }

                $alumno->save();
                $corregidos++;
            } catch (\Exception $e) {
                $this->error("Error al corregir alumno ID {$id}: " . $e->getMessage());
                $errores++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Resumen final
        $this->info("✅ Corrección completada:");
        $this->info("   - Registros corregidos: {$corregidos}");
        if ($errores > 0) {
            $this->error("   - Errores: {$errores}");
        }

        return Command::SUCCESS;
    }

    /**
     * Detectar si un texto tiene problemas de encoding
     */
    protected function tieneProblemaEncoding(?string $texto): bool
    {
        if (empty($texto)) {
            return false;
        }

        // Detectar la secuencia de bytes 0xC3 que es característica del double-encoding UTF-8
        // Cuando caracteres acentuados españoles se guardan como Latin1 y se leen como UTF-8
        // aparece la letra Ã seguida de otro carácter

        // Buscar: Ã seguida de cualquier carácter especial (patrón de double-encoding)
        if (preg_match('/\xC3[\x80-\xBF]/', $texto)) {
            return true;
        }

        return false;
    }

    /**
     * Corregir encoding de un texto
     */
    protected function corregirEncoding(?string $texto): ?string
    {
        if (empty($texto)) {
            return $texto;
        }

        // El problema: Double-encoding UTF-8
        // Los datos originales estaban en Latin1/ISO-8859-1
        // Se guardaron mal como UTF-8 en la DB
        // Resultado: "ü" se guardó como "Ã¼" (bytes UTF-8 de ü, pero interpretados como ISO-8859-1)

        // Solución en 2 pasos:
        // 1. Convertir de UTF-8 a ISO-8859-1 para "deshacer" el double-encoding
        $paso1 = mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8');

        // 2. Convertir de vuelta a UTF-8 para guardar correctamente
        $corregido = mb_convert_encoding($paso1, 'UTF-8', 'ISO-8859-1');

        return $corregido;
    }
}
