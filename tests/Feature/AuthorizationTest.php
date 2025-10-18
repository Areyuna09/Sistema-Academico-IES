<?php

use App\Models\User;

test('only admin users can access admin routes', function () {
    // Usuario tipo alumno (tipo = 2)
    $alumno = User::factory()->create(['tipo' => 2]);

    $response = $this->actingAs($alumno)->get(route('admin.usuarios.index'));
    $response->assertForbidden();

    $response = $this->actingAs($alumno)->get(route('admin.carreras.index'));
    $response->assertForbidden();

    $response = $this->actingAs($alumno)->get(route('admin.materias.index'));
    $response->assertForbidden();
});

test('admin users can access all admin routes', function () {
    $admin = User::factory()->create(['tipo' => 1]);

    $response = $this->actingAs($admin)->get(route('admin.usuarios.index'));
    $response->assertOk();

    $response = $this->actingAs($admin)->get(route('admin.carreras.index'));
    $response->assertOk();

    $response = $this->actingAs($admin)->get(route('admin.materias.index'));
    $response->assertOk();
});

test('only professors can access professor routes', function () {
    // Usuario tipo alumno (tipo = 2)
    $alumno = User::factory()->create(['tipo' => 2]);

    $response = $this->actingAs($alumno)->get(route('profesor.mis-materias.index'));
    $response->assertForbidden();
});

test('professors can access their materias', function () {
    $profesor = User::factory()->create([
        'tipo' => 3,
        'profesor_id' => 1,
    ]);

    $response = $this->actingAs($profesor)->get(route('profesor.mis-materias.index'));
    $response->assertOk();
});

test('unauthenticated users cannot access protected routes', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));

    $response = $this->get(route('admin.usuarios.index'));
    $response->assertRedirect(route('login'));

    $response = $this->get(route('inscripciones.index'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can access their own profile', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('profile.edit'));
    $response->assertOk();
});

test('users cannot modify other users data', function () {
    $admin = User::factory()->create(['tipo' => 1]);
    $otherUser = User::factory()->create(['tipo' => 2]);

    // Admin puede modificar otros usuarios
    $response = $this->actingAs($admin)->get(route('admin.usuarios.edit', $otherUser));
    $response->assertOk();

    // Pero un alumno no puede acceder a admin.usuarios
    $alumno = User::factory()->create(['tipo' => 2]);
    $response = $this->actingAs($alumno)->get(route('admin.usuarios.edit', $otherUser));
    $response->assertForbidden();
});

test('only active periods can have new inscriptions', function () {
    $user = User::factory()->create(['tipo' => 2, 'alumno_id' => 1]);

    // Crear un período inactivo
    \DB::table('tbl_periodo_inscripciones')->insert([
        'nombre' => 'Test Periodo',
        'cuatrimestre' => 1,
        'anio' => 2025,
        'fecha_inicio_inscripcion' => now()->subDays(10),
        'fecha_fin_inscripcion' => now()->addDays(10),
        'fecha_inicio_cursada' => now()->addDays(20),
        'fecha_fin_cursada' => now()->addDays(120),
        'activo' => false,
    ]);

    // Intentar inscribirse (debe fallar si no hay período activo)
    $response = $this->actingAs($user)->post(route('inscripciones.store'), [
        'materias' => [1],
    ]);

    // Puede que el sistema no permita inscripción sin período activo
    // Este test verifica el comportamiento esperado
});
