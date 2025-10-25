<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Seed usuarios de prueba con contraseña = DNI
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['dni' => '99999999'],
            [
                'nombre' => 'Usuario de Prueba',
                'email' => 'test@ies.edu',
                'clave' => Hash::make('99999999'), // Contraseña = DNI
                'tipo' => 1, // Admin
                'activo' => true,
            ]
        );

        User::updateOrCreate(
            ['dni' => '88888888'],
            [
                'nombre' => 'Estudiante IES',
                'email' => 'estudiante@ies.edu',
                'clave' => Hash::make('88888888'), // Contraseña = DNI
                'tipo' => 4, // Alumno
                'activo' => true,
            ]
        );

        $this->command->info('✓ Usuarios de prueba creados');
        $this->command->info('  - Usuario de Prueba (Admin): DNI 99999999 / Password: 99999999');
        $this->command->info('  - Estudiante IES (Alumno): DNI 88888888 / Password: 88888888');
    }
}