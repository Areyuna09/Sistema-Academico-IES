<?php

namespace Database\Factories;

use App\Models\Carrera;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarreraFactory extends Factory
{
    protected $model = Carrera::class;

    public function definition(): array
    {
        return [
            'nombre' => 'Tecnicatura Superior en ' . $this->faker->word(),
            'resolucion' => 'Res. ' . $this->faker->numberBetween(100, 9999) . '/' . $this->faker->year(),
        ];
    }
}
