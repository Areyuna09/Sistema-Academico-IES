<?php

namespace Tests\Feature\Academic;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Materia;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PlanEstudioTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    private function legacyAvailable(): bool
    {
        return Schema::hasTable('tbl_alumnos') && Schema::hasTable('tbl_materias') && Schema::hasTable('tbl_carreras');
    }

    public function test_alumno_ve_su_plan_de_estudio(): void
    {
        if (!$this->legacyAvailable()) {
            $this->markTestSkipped('Tablas legacy no disponibles en entorno de test.');
        }

        // Tomar un alumno real si existe
        $alumno = Alumno::query()->first();
        $this->assertNotNull($alumno, 'Se requiere al menos un alumno en la base.');

        // Usuario asociado
        $user = User::query()->first() ?: User::create([
            'dni' => '99999999',
            'nombre' => 'Alumno Test',
            'usuario' => 'alumno.test',
            'clave' => bcrypt('secret'),
            'email' => 'alumno@example.com',
            'tipo' => 4,
            'alumno_id' => $alumno->id,
        ]);

        $this->actingAs($user, 'sanctum');

        $resp = $this->getJson("/api/alumnos/{$alumno->id}/plan-estudio");
        $resp->assertOk()->assertJsonStructure([
            'alumno' => ['id', 'nombre', 'carrera' => ['id', 'nombre']],
            'resumen' => ['totalMaterias', 'pendientes', 'regularizadas', 'promocionadas', 'aprobadas', 'avancePorcentaje'],
            'materias' => [
                ['materia' => ['id', 'nombre'], 'estado']
            ],
        ]);
    }

    public function test_admin_ve_plan_de_carrera(): void
    {
        if (!$this->legacyAvailable()) {
            $this->markTestSkipped('Tablas legacy no disponibles en entorno de test.');
        }

        $carrera = Carrera::query()->first();
        $this->assertNotNull($carrera, 'Se requiere al menos una carrera.');

        $admin = User::create([
            'dni' => '11112222',
            'nombre' => 'Admin',
            'usuario' => 'admin',
            'clave' => bcrypt('secret'),
            'email' => 'admin@example.com',
            'tipo' => 1,
        ]);

        $this->actingAs($admin, 'sanctum');
        $resp = $this->getJson("/api/carreras/{$carrera->Id}/plan");
        $resp->assertOk()->assertJsonStructure([
            'carrera' => ['id', 'nombre'],
            'materias' => [
                ['id', 'nombre']
            ],
        ]);
    }

    public function test_alumno_actualiza_estado_de_materia(): void
    {
        if (!$this->legacyAvailable()) {
            $this->markTestSkipped('Tablas legacy no disponibles en entorno de test.');
        }

        $alumno = Alumno::query()->first();
        $this->assertNotNull($alumno);
        $materia = Materia::query()->where('carrera', $alumno->carrera)->first();
        $this->assertNotNull($materia);

        $user = User::query()->first() ?: User::create([
            'dni' => '99999999',
            'nombre' => 'Alumno Test',
            'usuario' => 'alumno.test',
            'clave' => bcrypt('secret'),
            'email' => 'alumno@example.com',
            'tipo' => 4,
            'alumno_id' => $alumno->id,
        ]);

        $this->actingAs($user, 'sanctum');

        $resp = $this->patchJson("/api/alumnos/{$alumno->id}/materias/{$materia->id}", [
            'estado' => 'regularizada',
            'nota' => 7,
        ]);
        $resp->assertOk();
    }

    public function test_no_puede_ver_plan_de_otro_alumno(): void
    {
        if (!$this->legacyAvailable()) {
            $this->markTestSkipped('Tablas legacy no disponibles en entorno de test.');
        }

        $alumnos = Alumno::query()->take(2)->get();
        if ($alumnos->count() < 2) {
            $this->markTestSkipped('Se requieren 2 alumnos para esta prueba.');
        }

        $propio = $alumnos[0];
        $otro = $alumnos[1];

        $user = User::create([
            'dni' => '22223333',
            'nombre' => 'Alumno X',
            'usuario' => 'alumno.x',
            'clave' => bcrypt('secret'),
            'email' => 'x@example.com',
            'tipo' => 4,
            'alumno_id' => $propio->id,
        ]);

        $this->actingAs($user, 'sanctum');

        $resp = $this->getJson("/api/alumnos/{$otro->id}/plan-estudio");
        $resp->assertForbidden();
    }
}

