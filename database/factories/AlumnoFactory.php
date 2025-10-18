<?php

namespace Database\Factories;

use App\Models\Alumno;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlumnoFactory extends Factory
{
    protected $model = Alumno::class;

    public function definition(): array
    {
        return [
            'dni' => $this->faker->unique()->numerify('########'),
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'legajo' => $this->faker->unique()->numerify('####-###'),
            'email' => $this->faker->unique()->safeEmail(),
            'telefono' => $this->faker->optional()->numerify('###-#######'),
            'celular' => $this->faker->optional()->numerify('###-#######'),
            'carrera' => 1, // Se puede override en el test
        ];
    }
}
