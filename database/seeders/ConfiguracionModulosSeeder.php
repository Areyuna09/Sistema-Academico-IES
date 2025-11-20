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
                'clave' => 'gestion_notas',
                'nombre' => 'Gestión de Notas',
                'descripcion' => 'Permite a los profesores cargar y gestionar notas de los alumnos',
                'categoria' => 'modulos',
                'activo' => true,
                'orden' => 3,
                'icono' => 'bx-edit',
            ],
            [
                'clave' => 'validacion_notas',
                'nombre' => 'Validación de Notas (Admin)',
                'descripcion' => 'Panel administrativo para aprobar o rechazar notas cargadas por profesores',
                'categoria' => 'modulos',
                'activo' => true,
                'orden' => 4,
                'icono' => 'bx-check-circle',
            ],
            [
                'clave' => 'excepciones',
                'nombre' => 'Excepciones Académicas',
                'descripcion' => 'Gestión de excepciones para casos especiales (correlativas, inscripciones fuera de período, etc.)',
                'categoria' => 'modulos',
                'activo' => true,
                'orden' => 5,
                'icono' => 'bx-error',
            ],
            [
                'clave' => 'expediente',
                'nombre' => 'Expediente del Alumno',
                'descripcion' => 'Visualización del historial académico completo del alumno',
                'categoria' => 'modulos',
                'activo' => true,
                'orden' => 6,
                'icono' => 'bx-folder-open',
            ],
            [
                'clave' => 'dashboard',
                'nombre' => 'Dashboard',
                'descripcion' => 'Panel principal con resumen de información para cada tipo de usuario',
                'categoria' => 'modulos',
                'activo' => true,
                'orden' => 7,
                'icono' => 'bx-home',
            ],

            // ===== GESTIÓN ADMINISTRATIVA =====
            [
                'clave' => 'gestion_periodos',
                'nombre' => 'Gestión de Períodos',
                'descripcion' => 'Administración de períodos lectivos (cuatrimestres, fechas de inscripción, etc.)',
                'categoria' => 'administracion',
                'activo' => true,
                'orden' => 10,
                'icono' => 'bx-calendar',
            ],
            [
                'clave' => 'gestion_carreras',
                'nombre' => 'Gestión de Carreras',
                'descripcion' => 'Alta, baja y modificación de carreras de la institución',
                'categoria' => 'administracion',
                'activo' => true,
                'orden' => 11,
                'icono' => 'bx-briefcase',
            ],
            [
                'clave' => 'gestion_materias',
                'nombre' => 'Gestión de Materias',
                'descripcion' => 'Administración de materias, correlativas y planes de estudio',
                'categoria' => 'administracion',
                'activo' => true,
                'orden' => 12,
                'icono' => 'bx-book',
            ],
            [
                'clave' => 'gestion_alumnos',
                'nombre' => 'Gestión de Alumnos',
                'descripcion' => 'Alta, baja y modificación de datos de alumnos',
                'categoria' => 'administracion',
                'activo' => true,
                'orden' => 13,
                'icono' => 'bx-user',
            ],
            [
                'clave' => 'gestion_profesores',
                'nombre' => 'Gestión de Profesores',
                'descripcion' => 'Administración de profesores y asignación de materias',
                'categoria' => 'administracion',
                'activo' => true,
                'orden' => 14,
                'icono' => 'bx-chalkboard',
            ],
            [
                'clave' => 'gestion_usuarios',
                'nombre' => 'Gestión de Usuarios',
                'descripcion' => 'Administración de cuentas de usuario y permisos',
                'categoria' => 'administracion',
                'activo' => true,
                'orden' => 15,
                'icono' => 'bx-group',
            ],

            // ===== NOTIFICACIONES Y COMUNICACIÓN =====
            [
                'clave' => 'notificaciones_sistema',
                'nombre' => 'Notificaciones del Sistema',
                'descripcion' => 'Notificaciones en la aplicación web (campana de notificaciones)',
                'categoria' => 'comunicacion',
                'activo' => true,
                'orden' => 20,
                'icono' => 'bx-bell',
            ],
            [
                'clave' => 'correos_comprobantes',
                'nombre' => 'Correos de Comprobantes',
                'descripcion' => 'Envío automático de comprobantes de inscripción por email',
                'categoria' => 'comunicacion',
                'activo' => true,
                'orden' => 21,
                'icono' => 'bx-envelope',
            ],
            [
                'clave' => 'correos_notas',
                'nombre' => 'Correos de Notificación de Notas',
                'descripcion' => 'Envío de emails a profesores cuando se aprueban/rechazan notas',
                'categoria' => 'comunicacion',
                'activo' => true,
                'orden' => 22,
                'icono' => 'bx-mail-send',
            ],

            // ===== VALIDACIONES Y REGLAS =====
            [
                'clave' => 'validar_correlativas',
                'nombre' => 'Validación de Correlativas',
                'descripcion' => 'Verifica que el alumno tenga aprobadas las materias correlativas antes de inscribirse',
                'categoria' => 'validaciones',
                'activo' => true,
                'orden' => 30,
                'icono' => 'bx-link',
            ],
            [
                'clave' => 'validar_periodo_inscripcion',
                'nombre' => 'Validación de Período de Inscripción',
                'descripcion' => 'Solo permite inscribirse dentro de las fechas establecidas del período',
                'categoria' => 'validaciones',
                'activo' => true,
                'orden' => 31,
                'icono' => 'bx-time',
            ],

            // ===== PERMISOS Y ACCESOS =====
            [
                'clave' => 'alumno_ver_notas',
                'nombre' => 'Alumnos Pueden Ver Notas',
                'descripcion' => 'Permite que los alumnos vean sus notas en el expediente (actualmente bloqueado)',
                'categoria' => 'permisos',
                'activo' => false,
                'orden' => 40,
                'icono' => 'bx-show',
            ],

            // ===== FUNCIONALIDADES AVANZADAS =====
            [
                'clave' => 'equivalencias',
                'nombre' => 'Marcación de Equivalencias',
                'descripcion' => 'Permite marcar materias como equivalencia en el legajo del alumno (gestión manual)',
                'categoria' => 'avanzado',
                'activo' => true,
                'orden' => 60,
                'icono' => 'bx-transfer',
            ],
            [
                'clave' => 'historico_alumnos',
                'nombre' => 'Histórico de Cambios en Legajos',
                'descripcion' => 'Registra automáticamente todos los cambios realizados en legajos académicos',
                'categoria' => 'avanzado',
                'activo' => true,
                'orden' => 61,
                'icono' => 'bx-history',
            ],
            [
                'clave' => 'logs_auditoria',
                'nombre' => 'Logs de Auditoría',
                'descripcion' => 'Registra todas las acciones importantes realizadas en el sistema',
                'categoria' => 'avanzado',
                'activo' => true,
                'orden' => 62,
                'icono' => 'bx-receipt',
            ],
            [
                'clave' => 'avatares',
                'nombre' => 'Fotos de Perfil (Avatares)',
                'descripcion' => 'Permite a los usuarios subir y mostrar fotos de perfil personalizadas',
                'categoria' => 'avanzado',
                'activo' => true,
                'orden' => 63,
                'icono' => 'bx-user-circle',
            ],
            [
                'clave' => 'mesas_aula',
                'nombre' => 'Aula en Mesas de Examen',
                'descripcion' => 'Muestra y permite configurar el campo de aula en las mesas de examen',
                'categoria' => 'avanzado',
                'activo' => true,
                'orden' => 64,
                'icono' => 'bx-buildings',
            ],

            // ===== FIRMAS DIGITALES =====
            [
                'clave' => 'firmas_digitales',
                'nombre' => 'Sistema de Firmas Digitales',
                'descripcion' => 'Infraestructura de firmas digitales para documentos PDF (Libro Matriz, Certificados, Títulos)',
                'categoria' => 'firmas_digitales',
                'activo' => true,
                'orden' => 70,
                'icono' => 'bx-pen',
            ],
            [
                'clave' => 'verificacion_integridad_documentos',
                'nombre' => 'Verificación de Integridad de Documentos',
                'descripcion' => 'Valida que los documentos firmados no hayan sido modificados usando hash SHA-256',
                'categoria' => 'firmas_digitales',
                'activo' => true,
                'orden' => 71,
                'icono' => 'bx-check-shield',
            ],
        ];

        foreach ($modulos as $modulo) {
            ConfiguracionModulo::updateOrCreate(
                ['clave' => $modulo['clave']],
                $modulo
            );
        }

        $this->command->info('✅ Configuración de módulos creada exitosamente');
        $this->command->info('📊 Total de módulos configurados: ' . count($modulos));
        $this->command->info('✓ Módulos activos: ' . collect($modulos)->where('activo', true)->count());
        $this->command->info('✗ Módulos inactivos: ' . collect($modulos)->where('activo', false)->count());
    }
}
