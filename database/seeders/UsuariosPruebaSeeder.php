<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TipoUsuario;
use Illuminate\Support\Facades\Hash;

class UsuariosPruebaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = [
            [
                'dni' => '11111111',
                'nombre' => 'Admin Sistema',
                'email' => 'admin@ies.edu.ar',
                'tipo' => TipoUsuario::ADMIN,
                'descripcion' => 'Administrador del sistema - Permisos totales',
            ],
            [
                'dni' => '22222222',
                'nombre' => 'Bedel Principal',
                'email' => 'bedel@ies.edu.ar',
                'tipo' => TipoUsuario::BEDEL,
                'descripcion' => 'Bedel - Puede crear, modificar y eliminar alumnos/profesores',
            ],
            [
                'dni' => '33333333',
                'nombre' => 'Preceptor General',
                'email' => 'preceptor@ies.edu.ar',
                'tipo' => TipoUsuario::PRECEPTOR,
                'descripcion' => 'Preceptor - Puede gestionar asistencias e inscripciones',
            ],
            [
                'dni' => '44444444',
                'nombre' => 'Directivo Educativo',
                'email' => 'directivo@ies.edu.ar',
                'tipo' => TipoUsuario::DIRECTIVO,
                'descripcion' => 'Directivo - Solo lectura y revisión de legajos (primer nivel)',
            ],
            [
                'dni' => '55555555',
                'nombre' => 'Supervisor Ministerial',
                'email' => 'supervisor@ies.edu.ar',
                'tipo' => TipoUsuario::SUPERVISOR,
                'descripcion' => 'Supervisor - Solo lectura y supervisión final (segundo nivel)',
            ],
            [
                'dni' => '66666666',
                'nombre' => 'Profesor Matemáticas',
                'email' => 'profesor@ies.edu.ar',
                'tipo' => TipoUsuario::PROFESOR,
                'descripcion' => 'Profesor - Puede tomar asistencias y cargar notas',
            ],
            [
                'dni' => '77777777',
                'nombre' => 'Alumno Ejemplo',
                'email' => 'alumno@ies.edu.ar',
                'tipo' => TipoUsuario::ALUMNO,
                'descripcion' => 'Alumno - Puede inscribirse a materias y ver su expediente',
            ],
        ];

        foreach ($usuarios as $userData) {
            $descripcion = $userData['descripcion'];
            unset($userData['descripcion']);

            $user = User::updateOrCreate(
                ['dni' => $userData['dni']],
                array_merge($userData, [
                    'clave' => Hash::make('password123'), // Contraseña de prueba
                    'usuario' => $userData['email'],
                    'activo' => true,
                ])
            );

            $rolNombre = TipoUsuario::getNombre($user->tipo);
            $this->command->info("✅ Usuario creado: {$user->nombre} ({$rolNombre}) - {$descripcion}");
        }

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('CREDENCIALES DE ACCESO (todas usan password: password123)');
        $this->command->info('========================================');
        $this->command->info('Admin:      DNI 11111111 / admin@ies.edu.ar');
        $this->command->info('Bedel:      DNI 22222222 / bedel@ies.edu.ar');
        $this->command->info('Preceptor:  DNI 33333333 / preceptor@ies.edu.ar');
        $this->command->info('Directivo:  DNI 44444444 / directivo@ies.edu.ar');
        $this->command->info('Supervisor: DNI 55555555 / supervisor@ies.edu.ar');
        $this->command->info('Profesor:   DNI 66666666 / profesor@ies.edu.ar');
        $this->command->info('Alumno:     DNI 77777777 / alumno@ies.edu.ar');
        $this->command->info('========================================');
    }
}