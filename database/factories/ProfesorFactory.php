<?php

namespace Database\Factories;

use App\Models\Profesor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfesorFactory extends Factory
{
    protected $model = Profesor::class;

    public function definition(): array
    {
        return [
            'dni' => $this->faker->unique()->numerify('########'),
            'apellido' => $this->faker->lastName(),
            'nombre' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'telefono' => $this->faker->optional()->numerify('###-#######'),
            'celular' => $this->faker->optional()->numerify('###-#######'),
        ];
    }
}
