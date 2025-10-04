<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReglaCorrelativa;

class ReglasCorrelativasSeeder extends Seeder
{
    /**
     * Poblar reglas de correlativas de ejemplo
     *
     * Estas son las reglas actuales del sistema que estaban hardcodeadas
     * en MotorCorrelativasService. Ahora se pueden gestionar desde la interfaz.
     */
    public function run(): void
    {
        $reglas = [
            // Primer Año - Para Cursar
            ['materia_id' => 2, 'correlativa_id' => 1, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 3, 'correlativa_id' => 1, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 4, 'correlativa_id' => 2, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 5, 'correlativa_id' => 3, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],

            // Segundo Año - Para Cursar
            ['materia_id' => 6, 'correlativa_id' => 4, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 7, 'correlativa_id' => 5, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 8, 'correlativa_id' => 6, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 9, 'correlativa_id' => 7, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 10, 'correlativa_id' => 8, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],

            // Tercer Año - Para Cursar
            ['materia_id' => 11, 'correlativa_id' => 9, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 12, 'correlativa_id' => 10, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 13, 'correlativa_id' => 11, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 14, 'correlativa_id' => 12, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 15, 'correlativa_id' => 13, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 16, 'correlativa_id' => 14, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 17, 'correlativa_id' => 15, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 18, 'correlativa_id' => 16, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 19, 'correlativa_id' => 17, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],
            ['materia_id' => 20, 'correlativa_id' => 18, 'tipo' => 'cursar', 'estado_requerido' => 'regular', 'carrera_id' => 1],

            // Correlativas para Rendir (requieren aprobada)
            ['materia_id' => 11, 'correlativa_id' => 1, 'tipo' => 'rendir', 'estado_requerido' => 'aprobada', 'carrera_id' => 1],
            ['materia_id' => 11, 'correlativa_id' => 2, 'tipo' => 'rendir', 'estado_requerido' => 'aprobada', 'carrera_id' => 1],
            ['materia_id' => 12, 'correlativa_id' => 3, 'tipo' => 'rendir', 'estado_requerido' => 'aprobada', 'carrera_id' => 1],
            ['materia_id' => 12, 'correlativa_id' => 4, 'tipo' => 'rendir', 'estado_requerido' => 'aprobada', 'carrera_id' => 1],
            ['materia_id' => 13, 'correlativa_id' => 5, 'tipo' => 'rendir', 'estado_requerido' => 'aprobada', 'carrera_id' => 1],
        ];

        foreach ($reglas as $regla) {
            ReglaCorrelativa::updateOrCreate(
                [
                    'materia_id' => $regla['materia_id'],
                    'carrera_id' => $regla['carrera_id'],
                    'tipo' => $regla['tipo'],
                    'correlativa_id' => $regla['correlativa_id'],
                ],
                [
                    'estado_requerido' => $regla['estado_requerido'],
                    'es_activa' => true,
                    'observaciones' => 'Regla migrada del sistema anterior',
                ]
            );
        }

        $this->command->info('✅ ' . count($reglas) . ' reglas de correlativas creadas exitosamente');
    }
}
