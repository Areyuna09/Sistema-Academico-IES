<?php

namespace Database\Factories;

use App\Models\Materia;
use Illuminate\Database\Eloquent\Factories\Factory;

class MateriaFactory extends Factory
{
    protected $model = Materia::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->words(3, true),
            'carrera' => 1, // Se puede override en el test
            'anno' => $this->faker->numberBetween(1, 4),
            'semestre' => $this->faker->numberBetween(1, 2),
            'resolucion' => 'Res. ' . $this->faker->numberBetween(100, 9999) . '/' . $this->faker->year(),
        ];
    }
}
