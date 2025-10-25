<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Profesor;
use App\Models\Carrera;

class UsuariosRolesSeeder extends Seeder
{
    /**
     * Seed usuarios de prueba para todos los roles del sistema
     * Contraseña = DNI para todos los usuarios
     */
    public function run(): void
    {
        // Verificar que exista al menos una carrera
        $carrera = Carrera::first();
        if (!$carrera) {
            $this->command->error('No hay carreras en el sistema. Crea al menos una carrera primero.');
            return;
        }

        $this->command->info('Creando usuarios de prueba con contraseña = DNI...');

        // 1. ADMIN (Tipo 1) - Administrador del Sistema
        $admin = User::updateOrCreate(
            ['dni' => '11111111'],
            [
                'nombre' => 'Administrador del Sistema',
                'email' => 'admin@ies.edu.ar',
                'clave' => Hash::make('11111111'), // Password = DNI
                'tipo' => 1, // Admin
                'activo' => true,
                'telefono' => '3794-111111',
            ]
        );
        $this->command->info("✓ Admin creado - DNI: 11111111 | Password: 11111111");

        // 2. BEDEL (Tipo 2) - Usuario/Bedel
        $bedel = User::updateOrCreate(
            ['dni' => '22222222'],
            [
                'nombre' => 'María Bedel',
                'email' => 'bedel@ies.edu.ar',
                'clave' => Hash::make('22222222'), // Password = DNI
                'tipo' => 2, // Bedel
                'activo' => true,
                'telefono' => '3794-222222',
            ]
        );
        $this->command->info("✓ Bedel creado - DNI: 22222222 | Password: 22222222");

        // 3. PROFESOR (Tipo 3)
        // Primero crear registro en tbl_profesores
        $profesorData = Profesor::updateOrCreate(
            ['dni' => '33333333'],
            [
                'nombre' => 'Juan',
                'apellido' => 'Profesor',
                'email' => 'profesor@ies.edu.ar',
                'telefono' => '3794-333333',
                'titulo' => 'Licenciado en Matemáticas',
            ]
        );

        $profesor = User::updateOrCreate(
            ['dni' => '33333333'],
            [
                'nombre' => 'Juan Profesor',
                'email' => 'profesor@ies.edu.ar',
                'clave' => Hash::make('33333333'), // Password = DNI
                'tipo' => 3, // Profesor
                'activo' => true,
                'telefono' => '3794-333333',
                'profesor_id' => $profesorData->id,
            ]
        );
        $this->command->info("✓ Profesor creado - DNI: 33333333 | Password: 33333333");

        // 4. ALUMNO (Tipo 4)
        // Primero crear registro en tbl_alumnos
        $alumnoData = Alumno::updateOrCreate(
            ['dni' => '44444444'],
            [
                'nombre' => 'Ana',
                'apellido' => 'Estudiante',
                'email' => 'alumno@ies.edu.ar',
                'telefono' => '3794-444444',
                'carrera' => $carrera->Id,
                'anno' => 2024,
                'curso' => 1,
                'legajo' => 'LEG-2024-001',
            ]
        );

        $alumno = User::updateOrCreate(
            ['dni' => '44444444'],
            [
                'nombre' => 'Ana Estudiante',
                'email' => 'alumno@ies.edu.ar',
                'clave' => Hash::make('44444444'), // Password = DNI
                'tipo' => 4, // Alumno
                'activo' => true,
                'telefono' => '3794-444444',
                'alumno_id' => $alumnoData->id,
            ]
        );
        $this->command->info("✓ Alumno creado - DNI: 44444444 | Password: 44444444");

        // 5. DIRECTIVO (Tipo 5) - Nuevo Rol
        $directivo = User::updateOrCreate(
            ['dni' => '55555555'],
            [
                'nombre' => 'Roberto Directivo',
                'email' => 'directivo@ies.edu.ar',
                'clave' => Hash::make('55555555'), // Password = DNI
                'tipo' => 5, // Directivo
                'activo' => true,
                'telefono' => '3794-555555',
            ]
        );
        $this->command->info("✓ Directivo creado - DNI: 55555555 | Password: 55555555");

        // 6. SUPERVISOR (Tipo 6) - Nuevo Rol
        $supervisor = User::updateOrCreate(
            ['dni' => '66666666'],
            [
                'nombre' => 'Carolina Supervisora',
                'email' => 'supervisor@ies.edu.ar',
                'clave' => Hash::make('66666666'), // Password = DNI
                'tipo' => 6, // Supervisor
                'activo' => true,
                'telefono' => '3794-666666',
            ]
        );
        $this->command->info("✓ Supervisor creado - DNI: 66666666 | Password: 66666666");

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  ✓ USUARIOS DE PRUEBA CREADOS EXITOSAMENTE');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('');
        $this->command->table(
            ['Rol', 'DNI', 'Password', 'Email', 'Tipo'],
            [
                ['Administrador', '11111111', '11111111', 'admin@ies.edu.ar', '1'],
                ['Bedel', '22222222', '22222222', 'bedel@ies.edu.ar', '2'],
                ['Profesor', '33333333', '33333333', 'profesor@ies.edu.ar', '3'],
                ['Alumno', '44444444', '44444444', 'alumno@ies.edu.ar', '4'],
                ['Directivo', '55555555', '55555555', 'directivo@ies.edu.ar', '5'],
                ['Supervisor', '66666666', '66666666', 'supervisor@ies.edu.ar', '6'],
            ]
        );
        $this->command->info('');
        $this->command->info('NOTA: Todos los usuarios usan su DNI como contraseña');
        $this->command->info('      Login con DNI (no email)');
        $this->command->info('');
    }
}
