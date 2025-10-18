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
            [
                'clave' => 'validar_cupo_materias',
                'nombre' => 'Validación de Cupo de Materias',
                'descripcion' => 'Verifica que el alumno no exceda el máximo de materias por período',
                'categoria' => 'validaciones',
                'activo' => false,
                'orden' => 32,
                'icono' => 'bx-list-ul',
            ],
            [
                'clave' => 'validar_horarios_mesas',
                'nombre' => 'Validación de Horarios de Mesas',
                'descripcion' => 'Verifica que no haya conflictos de horarios al inscribirse a mesas de examen',
                'categoria' => 'validaciones',
                'activo' => false,
                'orden' => 33,
                'icono' => 'bx-time-five',
            ],
            [
                'clave' => 'validar_deudas_alumno',
                'nombre' => 'Validación de Deudas',
                'descripcion' => 'Verifica que el alumno no tenga deudas administrativas antes de inscribirse',
                'categoria' => 'validaciones',
                'activo' => false,
                'orden' => 34,
                'icono' => 'bx-dollar',
            ],

            // ===== PERMISOS Y ACCESOS =====
            [
                'clave' => 'alumno_ver_notas',
                'nombre' => 'Alumnos Pueden Ver Notas',
                'descripcion' => 'Permite que los alumnos vean sus notas en el expediente',
                'categoria' => 'permisos',
                'activo' => false,
                'orden' => 40,
                'icono' => 'bx-show',
            ],
            [
                'clave' => 'alumno_inscripcion_fuera_periodo',
                'nombre' => 'Inscripción Fuera de Período',
                'descripcion' => 'Permite que alumnos se inscriban aunque esté fuera del período establecido',
                'categoria' => 'permisos',
                'activo' => false,
                'orden' => 41,
                'icono' => 'bx-calendar-x',
            ],
            [
                'clave' => 'profesor_modificar_notas',
                'nombre' => 'Profesores Modifican Notas Aprobadas',
                'descripcion' => 'Permite a los profesores modificar notas que ya fueron aprobadas por administración',
                'categoria' => 'permisos',
                'activo' => false,
                'orden' => 42,
                'icono' => 'bx-edit-alt',
            ],
            [
                'clave' => 'alumno_desinscripcion',
                'nombre' => 'Desinscripción de Materias',
                'descripcion' => 'Permite que los alumnos se desinscriban de materias por sí mismos',
                'categoria' => 'permisos',
                'activo' => false,
                'orden' => 43,
                'icono' => 'bx-x-circle',
            ],

            // ===== REPORTES Y ESTADÍSTICAS =====
            [
                'clave' => 'reportes_inscripciones',
                'nombre' => 'Reportes de Inscripciones',
                'descripcion' => 'Generación de reportes estadísticos de inscripciones por período',
                'categoria' => 'reportes',
                'activo' => false,
                'orden' => 50,
                'icono' => 'bx-bar-chart',
            ],
            [
                'clave' => 'reportes_mesas',
                'nombre' => 'Reportes de Mesas de Examen',
                'descripcion' => 'Estadísticas y reportes de asistencia y resultados de mesas',
                'categoria' => 'reportes',
                'activo' => false,
                'orden' => 51,
                'icono' => 'bx-pie-chart',
            ],
            [
                'clave' => 'reportes_rendimiento',
                'nombre' => 'Reportes de Rendimiento Académico',
                'descripcion' => 'Análisis de rendimiento por materia, carrera y cohorte',
                'categoria' => 'reportes',
                'activo' => false,
                'orden' => 52,
                'icono' => 'bx-line-chart',
            ],

            // ===== FUNCIONALIDADES AVANZADAS =====
            [
                'clave' => 'equivalencias',
                'nombre' => 'Gestión de Equivalencias',
                'descripcion' => 'Administración de equivalencias de materias para alumnos que cambian de carrera o institución',
                'categoria' => 'avanzado',
                'activo' => false,
                'orden' => 60,
                'icono' => 'bx-transfer',
            ],
            [
                'clave' => 'historico_alumnos',
                'nombre' => 'Histórico de Alumnos',
                'descripcion' => 'Mantiene registro histórico de todos los cambios en datos de alumnos',
                'categoria' => 'avanzado',
                'activo' => false,
                'orden' => 61,
                'icono' => 'bx-history',
            ],
            [
                'clave' => 'modo_mantenimiento',
                'nombre' => 'Modo Mantenimiento',
                'descripcion' => 'Desactiva temporalmente el acceso al sistema para todos excepto administradores',
                'categoria' => 'avanzado',
                'activo' => false,
                'orden' => 62,
                'icono' => 'bx-wrench',
            ],
            [
                'clave' => 'backup_automatico',
                'nombre' => 'Backup Automático',
                'descripcion' => 'Genera copias de seguridad automáticas de la base de datos',
                'categoria' => 'avanzado',
                'activo' => false,
                'orden' => 63,
                'icono' => 'bx-data',
            ],
            [
                'clave' => 'logs_auditoria',
                'nombre' => 'Logs de Auditoría',
                'descripcion' => 'Registra todas las acciones importantes realizadas en el sistema',
                'categoria' => 'avanzado',
                'activo' => true,
                'orden' => 64,
                'icono' => 'bx-receipt',
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
