<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConfiguracionModulo;

class ConfiguracionModulosSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = [
            // ===== MÓDULOS PRINCIPALES =====
            [
                'clave' => 'inscripciones',
                'nombre' => 'Inscripciones a Materias',
                'descripcion' => 'Permite a los alumnos inscribirse a las materias del período activo',
                'categoria' => 'modulos',
                'activo' => true,
                'orden' => 1,
                'icono' => 'bx-book-add',
            ],
            [
                'clave' => 'mesas_examen',
                'nombre' => 'Mesas de Examen',
                'descripcion' => 'Gestión completa de mesas de examen: creación, inscripción y administración',
                'categoria' => 'modulos',
                'activo' => true,
                'orden' => 2,
                'icono' => 'bx-file',
            ],
            [
                'clave' => 'excepciones',
                'nombre' => 'Excepciones Académicas',
                'descripcion' => 'Gestión de excepciones para casos especiales (correlativas, inscripciones fuera de período, etc.)',
                'categoria' => 'modulos',
                'activo' => true,
                'orden' => 3,
                'icono' => 'bx-error',
            ],
            [
                'clave' => 'expediente',
                'nombre' => 'Expediente del Alumno',
                'descripcion' => 'Permite a los ALUMNOS ver su historial académico y notas. Admins y profesores siempre tienen acceso.',
                'categoria' => 'modulos',
                'activo' => true,
                'orden' => 4,
                'icono' => 'bx-folder-open',
            ],

            // ===== NOTIFICACIONES =====
            [
                'clave' => 'notificaciones_sistema',
                'nombre' => 'Notificaciones del Sistema',
                'descripcion' => 'Notificaciones en la aplicación web (campana de notificaciones)',
                'categoria' => 'comunicacion',
                'activo' => true,
                'orden' => 10,
                'icono' => 'bx-bell',
            ],

            // ===== PERMISOS ALUMNOS =====
            [
                'clave' => 'alumno_editar_perfil',
                'nombre' => 'Alumnos Pueden Editar Perfil',
                'descripcion' => 'Permite a los ALUMNOS editar información de su perfil (teléfono, celular, etc.). Siempre pueden cambiar contraseña.',
                'categoria' => 'permisos',
                'activo' => true,
                'orden' => 15,
                'icono' => 'bx-user-circle',
            ],

            // ===== FUNCIONALIDADES AVANZADAS =====
            [
                'clave' => 'historico_alumnos',
                'nombre' => 'Histórico de Cambios en Legajos',
                'descripcion' => 'Registra automáticamente todos los cambios realizados en legajos académicos',
                'categoria' => 'avanzado',
                'activo' => true,
                'orden' => 20,
                'icono' => 'bx-history',
            ],
            [
                'clave' => 'logs_auditoria',
                'nombre' => 'Logs de Auditoría',
                'descripcion' => 'Registra todas las acciones importantes realizadas en el sistema',
                'categoria' => 'avanzado',
                'activo' => true,
                'orden' => 21,
                'icono' => 'bx-receipt',
            ],
            [
                'clave' => 'avatares',
                'nombre' => 'Fotos de Perfil (Avatares)',
                'descripcion' => 'Permite a los usuarios subir y mostrar fotos de perfil personalizadas',
                'categoria' => 'avanzado',
                'activo' => true,
                'orden' => 22,
                'icono' => 'bx-user-circle',
            ],
        ];

        // Limpiar módulos antiguos que ya no existen
        $clavesValidas = collect($modulos)->pluck('clave')->toArray();
        ConfiguracionModulo::whereNotIn('clave', $clavesValidas)->delete();

        // Crear o actualizar módulos
        foreach ($modulos as $modulo) {
            ConfiguracionModulo::updateOrCreate(
                ['clave' => $modulo['clave']],
                $modulo
            );
        }

        $this->command->info('✅ Configuración de módulos actualizada');
        $this->command->info('📊 Total de módulos: ' . count($modulos));
    }
}
