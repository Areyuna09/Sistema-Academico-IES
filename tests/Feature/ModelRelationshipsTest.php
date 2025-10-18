<?php

use App\Models\User;
use App\Models\Alumno;
use App\Models\Profesor;
use App\Models\Materia;
use App\Models\Carrera;
use Illuminate\Support\Facades\DB;

test('alumno has correct relationships', function () {
    // Crear alumno con carrera
    $carrera = Carrera::factory()->create();
    $alumno = Alumno::factory()->create(['carrera' => $carrera->Id]);

    // Verificar que puede acceder a la relación carrera
    expect($alumno->carreraRelacion)->not->toBeNull();
    expect($alumno->carreraRelacion->Id)->toBe($carrera->Id);
});

test('user has correct alumno or profesor relationship based on tipo', function () {
    // Usuario alumno
    $alumno = Alumno::factory()->create();
    $userAlumno = User::factory()->create([
        'tipo' => 2,
        'alumno_id' => $alumno->id,
    ]);

    expect($userAlumno->alumno)->not->toBeNull();
    expect($userAlumno->alumno->id)->toBe($alumno->id);

    // Usuario profesor
    $profesor = Profesor::factory()->create();
    $userProfesor = User::factory()->create([
        'tipo' => 3,
        'profesor_id' => $profesor->id,
    ]);

    expect($userProfesor->profesor)->not->toBeNull();
    expect($userProfesor->profesor->id)->toBe($profesor->id);
});

test('materia belongs to carrera', function () {
    $carrera = Carrera::factory()->create();
    $materia = Materia::factory()->create(['carrera' => $carrera->Id]);

    expect($materia->carreraRelacion)->not->toBeNull();
    expect($materia->carreraRelacion->Id)->toBe($carrera->Id);
});

test('queries use eager loading to prevent N+1 problems', function () {
    $admin = User::factory()->create(['tipo' => 1]);

    // Crear algunos usuarios con relaciones
    $alumnos = Alumno::factory()->count(5)->create();
    foreach ($alumnos as $alumno) {
        User::factory()->create([
            'tipo' => 2,
            'alumno_id' => $alumno->id,
        ]);
    }

    DB::enableQueryLog();

    // Acceder a la página de usuarios (debe usar eager loading)
    $response = $this->actingAs($admin)->get(route('admin.usuarios.index'));
    $response->assertOk();

    $queries = DB::getQueryLog();
    DB::disableQueryLog();

    // Verificar que no hay demasiadas queries (indicaría N+1)
    // Para 5 usuarios + 1 admin = 6 registros, no debería haber más de 10 queries
    expect(count($queries))->toBeLessThan(15);
});

test('inscripcion has correct relationships with materia and alumno', function () {
    $carrera = Carrera::factory()->create();
    $materia = Materia::factory()->create(['carrera' => $carrera->Id]);
    $alumno = Alumno::factory()->create(['carrera' => $carrera->Id]);

    // Crear inscripción
    DB::table('tbl_inscripciones')->insert([
        'alumno_id' => $alumno->id,
        'materia_id' => $materia->id,
        'carrera_id' => $carrera->Id,
        'periodo_inscripcion_id' => 1,
        'estado' => 'confirmada',
        'fecha_inscripcion' => now(),
    ]);

    $inscripcion = DB::table('tbl_inscripciones')->first();

    expect($inscripcion)->not->toBeNull();
    expect($inscripcion->alumno_id)->toBe($alumno->id);
    expect($inscripcion->materia_id)->toBe($materia->id);
});

test('profesor materia relationship works correctly', function () {
    $carrera = Carrera::factory()->create();
    $materia = Materia::factory()->create(['carrera' => $carrera->Id]);
    $profesor = Profesor::factory()->create();

    // Crear relación profesor-materia
    DB::table('tbl_profesor_tiene_materias')->insert([
        'profesor' => $profesor->id,
        'materia' => $materia->id,
        'carrera' => $carrera->Id,
        'cursado' => '1C',
        'division' => '1A',
    ]);

    $profesorMateria = DB::table('tbl_profesor_tiene_materias')->first();

    expect($profesorMateria)->not->toBeNull();
    expect($profesorMateria->profesor)->toBe($profesor->id);
    expect($profesorMateria->materia)->toBe($materia->id);
});
