<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PeriodoInscripcion;

class PeriodosInscripcionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1° Cuatrimestre 2025 (Pasado)
        PeriodoInscripcion::create([
            'nombre' => '1° Cuatrimestre 2025',
            'cuatrimestre' => '1',
            'anio' => 2025,
            'fecha_inicio_inscripcion' => '2025-04-01', // 1 de abril
            'fecha_fin_inscripcion' => '2025-04-03', // 3 de abril
            'fecha_inicio_cursada' => '2025-04-07',
            'fecha_fin_cursada' => '2025-07-31',
            'activo' => false,
        ]);

        // 2° Cuatrimestre 2025 (ACTIVO)
        PeriodoInscripcion::create([
            'nombre' => '2° Cuatrimestre 2025',
            'cuatrimestre' => '2',
            'anio' => 2025,
            'fecha_inicio_inscripcion' => '2025-08-20', // 20 de agosto
            'fecha_fin_inscripcion' => '2025-08-23', // 23 de agosto
            'fecha_inicio_cursada' => '2025-08-26',
            'fecha_fin_cursada' => '2025-12-15',
            'activo' => true, // Activo actualmente
        ]);

        // 1° Cuatrimestre 2026 (Futuro)
        PeriodoInscripcion::create([
            'nombre' => '1° Cuatrimestre 2026',
            'cuatrimestre' => '1',
            'anio' => 2026,
            'fecha_inicio_inscripcion' => '2026-04-01', // 1 de abril
            'fecha_fin_inscripcion' => '2026-04-03', // 3 de abril
            'fecha_inicio_cursada' => '2026-04-06',
            'fecha_fin_cursada' => '2026-07-31',
            'activo' => false,
        ]);

        $this->command->info('✅ Períodos de inscripción creados exitosamente');
        $this->command->info('📅 Período activo: 2° Cuatrimestre 2025 (20/08 - 23/08)');
    }
}
