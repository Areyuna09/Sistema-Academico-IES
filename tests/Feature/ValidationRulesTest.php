<?php

use App\Models\User;
use App\Models\Carrera;

test('DNI validation accepts only numeric values with max 10 characters', function () {
    $user = User::factory()->create(['tipo' => 1]);

    // DNI válido
    $response = $this->actingAs($user)->post(route('admin.usuarios.store'), [
        'dni' => '12345678',
        'nombre' => 'Test Usuario',
        'email' => 'test@example.com',
        'tipo' => 2,
        'clave' => 'password123',
    ]);

    $response->assertSessionHasNoErrors('dni');

    // DNI con letras (debe fallar)
    $response = $this->actingAs($user)->post(route('admin.usuarios.store'), [
        'dni' => '1234abc56',
        'nombre' => 'Test Usuario',
        'email' => 'test2@example.com',
        'tipo' => 2,
        'clave' => 'password123',
    ]);

    $response->assertSessionHasErrors('dni');

    // DNI mayor a 10 caracteres (debe fallar)
    $response = $this->actingAs($user)->post(route('admin.usuarios.store'), [
        'dni' => '12345678901',
        'nombre' => 'Test Usuario',
        'email' => 'test3@example.com',
        'tipo' => 2,
        'clave' => 'password123',
    ]);

    $response->assertSessionHasErrors('dni');
});

test('nombre field requires at least one letter', function () {
    $user = User::factory()->create(['tipo' => 1]);

    // Nombre válido
    $response = $this->actingAs($user)->post(route('admin.usuarios.store'), [
        'dni' => '87654321',
        'nombre' => 'Juan Pérez',
        'email' => 'juan@example.com',
        'tipo' => 2,
        'clave' => 'password123',
    ]);

    $response->assertSessionHasNoErrors('nombre');

    // Solo números (debe fallar)
    $response = $this->actingAs($user)->post(route('admin.usuarios.store'), [
        'dni' => '87654322',
        'nombre' => '12345678',
        'email' => 'test@example.com',
        'tipo' => 2,
        'clave' => 'password123',
    ]);

    $response->assertSessionHasErrors('nombre');
});

test('email validation enforces max 100 characters', function () {
    $user = User::factory()->create(['tipo' => 1]);

    // Email válido
    $response = $this->actingAs($user)->post(route('admin.usuarios.store'), [
        'dni' => '11111111',
        'nombre' => 'Test User',
        'email' => 'valid@example.com',
        'tipo' => 2,
        'clave' => 'password123',
    ]);

    $response->assertSessionHasNoErrors('email');

    // Email mayor a 100 caracteres (debe fallar)
    $longEmail = str_repeat('a', 90) . '@example.com'; // 102 caracteres
    $response = $this->actingAs($user)->post(route('admin.usuarios.store'), [
        'dni' => '11111112',
        'nombre' => 'Test User',
        'email' => $longEmail,
        'tipo' => 2,
        'clave' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
});

test('telefono validation accepts only valid phone formats', function () {
    $user = User::factory()->create(['tipo' => 1]);

    // Teléfono válido
    $response = $this->actingAs($user)->post(route('admin.usuarios.store'), [
        'dni' => '22222222',
        'nombre' => 'Test User',
        'email' => 'phone@example.com',
        'telefono' => '381-4567890',
        'tipo' => 2,
        'clave' => 'password123',
    ]);

    $response->assertSessionHasNoErrors('telefono');

    // Teléfono con letras (debe fallar)
    $response = $this->actingAs($user)->post(route('admin.usuarios.store'), [
        'dni' => '22222223',
        'nombre' => 'Test User',
        'email' => 'phone2@example.com',
        'telefono' => '381-ABC-DEFG',
        'tipo' => 2,
        'clave' => 'password123',
    ]);

    $response->assertSessionHasErrors('telefono');
});

test('carrera nombre validation max 55 characters', function () {
    $user = User::factory()->create(['tipo' => 1]);

    // Nombre válido
    $response = $this->actingAs($user)->post(route('admin.carreras.store'), [
        'nombre' => 'Tecnicatura Superior en Desarrollo de Software',
        'resolucion' => 'Res. 123/2024',
    ]);

    $response->assertSessionHasNoErrors('nombre');

    // Nombre mayor a 55 caracteres (debe fallar)
    $longName = str_repeat('a', 60);
    $response = $this->actingAs($user)->post(route('admin.carreras.store'), [
        'nombre' => $longName,
        'resolucion' => 'Res. 123/2024',
    ]);

    $response->assertSessionHasErrors('nombre');
});

test('periodo nombre validation max 100 characters', function () {
    $user = User::factory()->create(['tipo' => 1]);

    // Nombre válido
    $response = $this->actingAs($user)->post(route('admin.periodos.store'), [
        'nombre' => 'Primer Cuatrimestre 2025',
        'cuatrimestre' => 1,
        'anio' => 2025,
        'fecha_inicio_inscripcion' => '2025-02-03', // Lunes
        'fecha_fin_inscripcion' => '2025-02-14', // Viernes
        'fecha_inicio_cursada' => '2025-03-03', // Lunes
        'fecha_fin_cursada' => '2025-06-27', // Viernes
        'activo' => false,
    ]);

    $response->assertSessionHasNoErrors('nombre');

    // Nombre mayor a 100 caracteres (debe fallar)
    $longName = str_repeat('a', 105);
    $response = $this->actingAs($user)->post(route('admin.periodos.store'), [
        'nombre' => $longName,
        'cuatrimestre' => 1,
        'anio' => 2025,
        'fecha_inicio_inscripcion' => '2025-02-03',
        'fecha_fin_inscripcion' => '2025-02-14',
        'fecha_inicio_cursada' => '2025-03-03',
        'fecha_fin_cursada' => '2025-06-27',
        'activo' => false,
    ]);

    $response->assertSessionHasErrors('nombre');
});

test('observaciones validation max 500 characters', function () {
    $user = User::factory()->create([
        'tipo' => 3,
        'profesor_id' => 1,
    ]);

    // Crear datos necesarios para el test
    \DB::table('tbl_profesor_tiene_materias')->insert([
        'profesor' => 1,
        'materia' => 1,
        'carrera' => 1,
        'cursado' => '1C',
        'division' => '1A',
    ]);

    \DB::table('tbl_alumnos')->insert([
        'dni' => '99999999',
        'nombre' => 'Test',
        'apellido' => 'Alumno',
        'legajo' => '2024-001',
        'carrera' => 1,
    ]);

    // Observaciones válidas (menos de 500)
    $response = $this->actingAs($user)->post(route('expediente.guardar-asistencia'), [
        'profesor_materia_id' => 1,
        'fecha' => now()->format('Y-m-d'),
        'asistencias' => [
            [
                'alumno_id' => 1,
                'estado' => 'presente',
                'observaciones' => 'Asistió puntualmente',
            ]
        ],
    ]);

    $response->assertSessionHasNoErrors('asistencias.0.observaciones');

    // Observaciones mayores a 500 caracteres (debe fallar)
    $longObservation = str_repeat('a', 505);
    $response = $this->actingAs($user)->post(route('expediente.guardar-asistencia'), [
        'profesor_materia_id' => 1,
        'fecha' => now()->format('Y-m-d'),
        'asistencias' => [
            [
                'alumno_id' => 1,
                'estado' => 'presente',
                'observaciones' => $longObservation,
            ]
        ],
    ]);

    $response->assertSessionHasErrors('asistencias.0.observaciones');
});
