<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Alumno;
use Illuminate\Support\Facades\Hash;

class UserAlumnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Crea usuarios para alumnos existentes en tbl_alumnos
     * Contraseña por defecto: mismo DNI del alumno
     */
    public function run(): void
    {
        // Obtener alumnos que no tienen usuario
        $alumnos = Alumno::whereDoesntHave('user')->get();

        foreach ($alumnos as $alumno) {
            if (!empty($alumno->dni)) {
                // Si el email ya existe, usar DNI + dominio como email único
                $email = $alumno->email;
                if (User::where('email', $email)->exists()) {
                    $email = $alumno->dni . '@ies.edu.ar';
                }

                User::create([
                    'dni' => $alumno->dni,
                    'role' => 'alumno',
                    'alumno_id' => $alumno->id,
                    'name' => $alumno->nombre_completo,
                    'email' => $email,
                    'password' => Hash::make($alumno->dni), // Contraseña = DNI
                ]);

                $this->command->info("Usuario creado para alumno: {$alumno->nombre_completo} (DNI: {$alumno->dni})");
            }
        }

        $this->command->info("Total de usuarios creados: " . $alumnos->count());
    }
}
