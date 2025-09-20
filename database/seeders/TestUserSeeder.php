<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Usuario de Prueba',
            'email' => 'test@ies.edu',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Estudiante IES',
            'email' => 'estudiante@ies.edu',
            'password' => Hash::make('123456'),
            'email_verified_at' => now(),
        ]);
    }
}