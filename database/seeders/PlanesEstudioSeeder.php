<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Carrera;
use App\Models\Materia;
use App\Models\PlanEstudio;
use App\Models\PlanEstudioMateria;
use Illuminate\Support\Facades\Log;

class PlanesEstudioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Crea un plan de estudio "Plan 2024" por defecto para cada carrera existente
     * y asigna todas las materias actuales de cada carrera a su respectivo plan.
     */
    public function run(): void
    {
        Log::info('Iniciando creación de planes de estudio por defecto');

        $carreras = Carrera::all();

        foreach ($carreras as $carrera) {
            // Crear plan por defecto para la carrera
            $plan = PlanEstudio::create([
                'carrera_id' => $carrera->Id,
                'nombre' => 'Plan 2024',
                'anio' => 2024,
                'resolucion' => 'Plan inicial del sistema',
                'activo' => true,
            ]);

            Log::info("Plan creado para carrera: {$carrera->nombre}", ['plan_id' => $plan->id]);

            // Obtener todas las materias de la carrera
            $materias = Materia::where('carrera', $carrera->Id)->get();

            // Asignar cada materia al plan con orden basado en año y semestre
            $orden = 1;
            foreach ($materias as $materia) {
                PlanEstudioMateria::create([
                    'plan_estudio_id' => $plan->id,
                    'materia_id' => $materia->id,
                    'orden' => $orden++,
                ]);
            }

            Log::info("Materias asignadas al plan: {$materias->count()}");
        }

        Log::info('Planes de estudio creados exitosamente', ['total_carreras' => $carreras->count()]);
    }
}
