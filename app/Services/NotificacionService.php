<?php

namespace App\Services;

use App\Models\Notificacion;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Profesor;
use Illuminate\Support\Facades\Log;

/**
 * Servicio centralizado para crear notificaciones
 * Maneja la lógica de creación de notificaciones para diferentes eventos del sistema
 */
class NotificacionService
{
    // ==================== ALUMNOS ====================

    /**
     * Notificar al alumno sobre nueva nota cargada
     */
    public function notaC argada(int $alumnoId, string $materia, string $tipoEvaluacion, $nota, string $profesor, string $fecha)
    {
        $alumno = Alumno::find($alumnoId);
        if (!$alumno || !$alumno->user_id) return;

        try {
            Notificacion::crear(
                $alumno->user_id,
                'nota_cargada',
                'Nueva Nota Registrada',
                "Se ha registrado una nota en {$materia}: {$tipoEvaluacion} - {$nota} (por {$profesor})",
                [
                    'icono' => 'bx-book-content',
                    'color' => 'blue',
                    'url' => route('expediente.show', ['alumno' => $alumnoId]),
                    'datos' => [
                        'materia' => $materia,
                        'tipo_evaluacion' => $tipoEvaluacion,
                        'nota' => $nota,
                        'profesor' => $profesor,
                        'fecha' => $fecha,
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error al crear notificación de nota cargada: ' . $e->getMessage());
        }
    }

    /**
     * Notificar al alumno sobre cambio de estado académico
     */
    public function estadoAcademicoActualizado(int $alumnoId, string $materia, string $estado, $promedioFinal = null)
    {
        $alumno = Alumno::find($alumnoId);
        if (!$alumno || !$alumno->user_id) return;

        $estadosTexto = [
            'promocionado' => 'PROMOCIONADO',
            'regular' => 'REGULARIZADO',
            'libre' => 'QUEDADO LIBRE',
            'aprobado' => 'APROBADO',
        ];

        $colores = [
            'promocionado' => 'green',
            'regular' => 'blue',
            'libre' => 'red',
            'aprobado' => 'green',
        ];

        $iconos = [
            'promocionado' => 'bx-trophy',
            'regular' => 'bx-check-circle',
            'libre' => 'bx-x-circle',
            'aprobado' => 'bx-medal',
        ];

        $mensaje = "Has {$estadosTexto[$estado]} en {$materia}";
        if ($promedioFinal) {
            $mensaje .= " con promedio {$promedioFinal}";
        }

        try {
            Notificacion::crear(
                $alumno->user_id,
                'estado_academico_actualizado',
                'Estado Académico Actualizado',
                $mensaje,
                [
                    'icono' => $iconos[$estado] ?? 'bx-info-circle',
                    'color' => $colores[$estado] ?? 'gray',
                    'url' => route('expediente.show', ['alumno' => $alumnoId]),
                    'datos' => [
                        'materia' => $materia,
                        'estado' => $estado,
                        'promedio_final' => $promedioFinal,
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error al crear notificación de estado académico: ' . $e->getMessage());
        }
    }

    /**
     * Notificar al alumno sobre asistencia registrada
     */
    public function asistenciaRegistrada(int $alumnoId, string $materia, string $estado, string $fecha, float $porcentajeActual)
    {
        $alumno = Alumno::find($alumnoId);
        if (!$alumno || !$alumno->user_id) return;

        $estadosTexto = [
            'presente' => 'Presente',
            'ausente' => 'Ausente',
            'tarde' => 'Llegada Tarde',
        ];

        $iconos = [
            'presente' => 'bx-check',
            'ausente' => 'bx-x',
            'tarde' => 'bx-time',
        ];

        $colores = [
            'presente' => 'green',
            'ausente' => 'red',
            'tarde' => 'orange',
        ];

        try {
            Notificacion::crear(
                $alumno->user_id,
                'asistencia_registrada',
                'Asistencia Registrada',
                "Se registró tu asistencia en {$materia}: {$estadosTexto[$estado]} ({$fecha}). Asistencia actual: {$porcentajeActual}%",
                [
                    'icono' => $iconos[$estado],
                    'color' => $colores[$estado],
                    'url' => route('expediente.show', ['alumno' => $alumnoId]),
                    'datos' => [
                        'materia' => $materia,
                        'estado' => $estado,
                        'fecha' => $fecha,
                        'porcentaje_actual' => $porcentajeActual,
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error al crear notificación de asistencia: ' . $e->getMessage());
        }
    }

    /**
     * Alerta de asistencia baja
     */
    public function alertaAsistenciaBaja(int $alumnoId, string $materia, float $porcentaje, int $ausencias, int $clasesTotales)
    {
        $alumno = Alumno::find($alumnoId);
        if (!$alumno || !$alumno->user_id) return;

        try {
            Notificacion::crear(
                $alumno->user_id,
                'alerta_asistencia',
                '⚠️ Alerta de Asistencia',
                "Tu asistencia en {$materia} es del {$porcentaje}% ({$ausencias} ausencias de {$clasesTotales} clases). Mínimo requerido: 75%",
                [
                    'icono' => 'bx-error',
                    'color' => 'red',
                    'url' => route('expediente.show', ['alumno' => $alumnoId]),
                    'datos' => [
                        'materia' => $materia,
                        'porcentaje' => $porcentaje,
                        'ausencias' => $ausencias,
                        'clases_totales' => $clasesTotales,
                        'minimo_requerido' => 75,
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error al crear alerta de asistencia: ' . $e->getMessage());
        }
    }

    /**
     * Notificar cancelación de inscripción a mesa
     */
    public function mesaCancelada(int $alumnoId, string $materia, string $llamado, string $fechaExamen)
    {
        $alumno = Alumno::find($alumnoId);
        if (!$alumno || !$alumno->user_id) return;

        try {
            Notificacion::crear(
                $alumno->user_id,
                'mesa_cancelada',
                'Inscripción Cancelada',
                "Has cancelado tu inscripción a la mesa de {$materia} - {$llamado} llamado ({$fechaExamen})",
                [
                    'icono' => 'bx-calendar-x',
                    'color' => 'orange',
                    'url' => route('mesas.index'),
                    'datos' => [
                        'materia' => $materia,
                        'llamado' => $llamado,
                        'fecha_examen' => $fechaExamen,
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error al crear notificación de mesa cancelada: ' . $e->getMessage());
        }
    }

    /**
     * Notificar resultado de mesa de examen
     */
    public function resultadoMesa(int $alumnoId, string $materia, $nota, bool $aprobado)
    {
        $alumno = Alumno::find($alumnoId);
        if (!$alumno || !$alumno->user_id) return;

        $resultado = $aprobado ? 'APROBADO' : 'DESAPROBADO';
        $color = $aprobado ? 'green' : 'red';
        $icono = $aprobado ? 'bx-medal' : 'bx-x-circle';

        try {
            Notificacion::crear(
                $alumno->user_id,
                'resultado_mesa',
                'Resultado de Mesa de Examen',
                "Resultado de tu examen de {$materia}: {$resultado} - Nota: {$nota}",
                [
                    'icono' => $icono,
                    'color' => $color,
                    'url' => route('expediente.show', ['alumno' => $alumnoId]),
                    'datos' => [
                        'materia' => $materia,
                        'nota' => $nota,
                        'aprobado' => $aprobado,
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error al crear notificación de resultado de mesa: ' . $e->getMessage());
        }
    }

    /**
     * Recordatorio de mesa próxima
     */
    public function recordatorioMesa(int $alumnoId, string $materia, string $fecha, string $hora)
    {
        $alumno = Alumno::find($alumnoId);
        if (!$alumno || !$alumno->user_id) return;

        try {
            Notificacion::crear(
                $alumno->user_id,
                'recordatorio_mesa',
                'Recordatorio: Mesa de Examen Próxima',
                "Tienes mesa de {$materia} el {$fecha} a las {$hora}",
                [
                    'icono' => 'bx-calendar-event',
                    'color' => 'blue',
                    'url' => route('mesas.index'),
                    'datos' => [
                        'materia' => $materia,
                        'fecha' => $fecha,
                        'hora' => $hora,
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error al crear recordatorio de mesa: ' . $e->getMessage());
        }
    }

    /**
     * Notificar nueva mesa disponible
     */
    public function nuevaMesaDisponible(array $alumnosIds, string $materia, string $llamado, string $fecha)
    {
        foreach ($alumnosIds as $alumnoId) {
            $alumno = Alumno::find($alumnoId);
            if (!$alumno || !$alumno->user_id) continue;

            try {
                Notificacion::crear(
                    $alumno->user_id,
                    'nueva_mesa_disponible',
                    'Nueva Mesa de Examen Disponible',
                    "Se abrió una nueva mesa de {$materia} - {$llamado} llamado ({$fecha})",
                    [
                        'icono' => 'bx-calendar-plus',
                        'color' => 'green',
                        'url' => route('mesas.index'),
                        'datos' => [
                            'materia' => $materia,
                            'llamado' => $llamado,
                            'fecha' => $fecha,
                        ],
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Error al notificar nueva mesa a alumno {$alumnoId}: " . $e->getMessage());
            }
        }
    }

    /**
     * Notificar período de inscripción abierto
     */
    public function periodoInscripcionAbierto(array $alumnosIds, string $cuatrimestre, string $año, string $fechaFin)
    {
        foreach ($alumnosIds as $alumnoId) {
            $alumno = Alumno::find($alumnoId);
            if (!$alumno || !$alumno->user_id) continue;

            try {
                Notificacion::crear(
                    $alumno->user_id,
                    'periodo_inscripcion_abierto',
                    'Período de Inscripción Abierto',
                    "Abierta inscripción para {$cuatrimestre} {$año} hasta {$fechaFin}",
                    [
                        'icono' => 'bx-calendar-check',
                        'color' => 'green',
                        'url' => route('inscripciones.index'),
                        'datos' => [
                            'cuatrimestre' => $cuatrimestre,
                            'año' => $año,
                            'fecha_fin' => $fechaFin,
                        ],
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Error al notificar período a alumno {$alumnoId}: " . $e->getMessage());
            }
        }
    }

    // ==================== PROFESORES ====================

    /**
     * Notificar alumno inscrito a materia
     */
    public function alumnoInscrito(int $profesorId, string $alumno, string $materia)
    {
        $profesor = Profesor::find($profesorId);
        if (!$profesor || !$profesor->user_id) return;

        try {
            Notificacion::crear(
                $profesor->user_id,
                'alumno_inscrito',
                'Nuevo Alumno Inscrito',
                "{$alumno} se ha inscrito a tu materia {$materia}",
                [
                    'icono' => 'bx-user-plus',
                    'color' => 'blue',
                    'url' => route('profesor.mis-materias.index'),
                    'datos' => [
                        'alumno' => $alumno,
                        'materia' => $materia,
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error al notificar alumno inscrito: ' . $e->getMessage());
        }
    }

    /**
     * Notificar alumno inscrito a mesa
     */
    public function alumnoInscritoMesa(int $profesorId, string $alumno, string $materia, string $llamado, string $fecha)
    {
        $profesor = Profesor::find($profesorId);
        if (!$profesor || !$profesor->user_id) return;

        try {
            Notificacion::crear(
                $profesor->user_id,
                'alumno_inscrito_mesa',
                'Inscripción a Mesa',
                "{$alumno} se inscribió a tu mesa de {$materia} - {$llamado} llamado ({$fecha})",
                [
                    'icono' => 'bx-calendar-check',
                    'color' => 'blue',
                    'url' => route('admin.mesas.index'), // ajustar según ruta real
                    'datos' => [
                        'alumno' => $alumno,
                        'materia' => $materia,
                        'llamado' => $llamado,
                        'fecha' => $fecha,
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error al notificar inscripción a mesa: ' . $e->getMessage());
        }
    }

    /**
     * Notificar alumno canceló mesa
     */
    public function alumnoCanceloMesa(int $profesorId, string $alumno, string $materia)
    {
        $profesor = Profesor::find($profesorId);
        if (!$profesor || !$profesor->user_id) return;

        try {
            Notificacion::crear(
                $profesor->user_id,
                'alumno_cancelo_mesa',
                'Cancelación de Inscripción',
                "{$alumno} canceló su inscripción a la mesa de {$materia}",
                [
                    'icono' => 'bx-calendar-x',
                    'color' => 'orange',
                    'url' => route('admin.mesas.index'),
                    'datos' => [
                        'alumno' => $alumno,
                        'materia' => $materia,
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error al notificar cancelación de mesa: ' . $e->getMessage());
        }
    }

    /**
     * Recordatorio de carga de notas
     */
    public function recordatorioCargarNotas(int $profesorId, string $materia, string $fechaLimite)
    {
        $profesor = Profesor::find($profesorId);
        if (!$profesor || !$profesor->user_id) return;

        try {
            Notificacion::crear(
                $profesor->user_id,
                'recordatorio_carga',
                'Recordatorio: Finaliza el Cuatrimestre',
                "Recuerda cargar las notas finales y asistencias de {$materia}. Fecha límite: {$fechaLimite}",
                [
                    'icono' => 'bx-time-five',
                    'color' => 'orange',
                    'url' => route('expediente.index'),
                    'datos' => [
                        'materia' => $materia,
                        'fecha_limite' => $fechaLimite,
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error al crear recordatorio de carga: ' . $e->getMessage());
        }
    }

    // ==================== DIRECTIVOS (Tipo 5) ====================

    /**
     * Notificar nuevo legajo pendiente de revisión
     */
    public function nuevoLegajoPendiente(array $directivosIds, string $alumno, string $materia, int $legajoId)
    {
        foreach ($directivosIds as $directivoId) {
            $directivo = \App\Models\User::find($directivoId);
            if (!$directivo) continue;

            try {
                Notificacion::crear(
                    $directivo->id,
                    'legajo_pendiente_revision',
                    'Nuevo Legajo Pendiente',
                    "Nuevo legajo de {$alumno} en {$materia} requiere tu revisión",
                    [
                        'icono' => 'bx-file',
                        'color' => 'blue',
                        'url' => route('directivo.show', ['id' => $legajoId]),
                        'datos' => [
                            'legajo_id' => $legajoId,
                            'alumno' => $alumno,
                            'materia' => $materia,
                        ],
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Error al notificar legajo pendiente a directivo {$directivoId}: " . $e->getMessage());
            }
        }
    }

    /**
     * Notificar observaciones del Supervisor al Directivo
     */
    public function observacionesSupervisor(int $directivoId, string $alumno, string $materia, string $observaciones, int $legajoId)
    {
        $directivo = \App\Models\User::find($directivoId);
        if (!$directivo) return;

        try {
            Notificacion::crear(
                $directivo->id,
                'legajo_observaciones_supervisor',
                'Observaciones del Supervisor',
                "El Supervisor devolvió el legajo de {$alumno} - {$materia} con observaciones",
                [
                    'icono' => 'bx-error-circle',
                    'color' => 'orange',
                    'url' => route('directivo.show', ['id' => $legajoId]),
                    'datos' => [
                        'legajo_id' => $legajoId,
                        'alumno' => $alumno,
                        'materia' => $materia,
                        'observaciones' => $observaciones,
                    ],
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error al notificar observaciones de supervisor a directivo: ' . $e->getMessage());
        }
    }

    // ==================== SUPERVISORES (Tipo 6) ====================

    /**
     * Notificar legajo aprobado por Directivo
     */
    public function legajoAprobadoDirectivo(array $supervisoresIds, string $alumno, string $materia, string $directivo, int $legajoId)
    {
        foreach ($supervisoresIds as $supervisorId) {
            $supervisor = \App\Models\User::find($supervisorId);
            if (!$supervisor) continue;

            try {
                Notificacion::crear(
                    $supervisor->id,
                    'legajo_aprobado_directivo',
                    'Legajo Aprobado por Directivo',
                    "Nuevo legajo pendiente de supervisión: {$alumno} - {$materia} (aprobado por {$directivo})",
                    [
                        'icono' => 'bx-check-circle',
                        'color' => 'blue',
                        'url' => route('supervisor.show', ['id' => $legajoId]),
                        'datos' => [
                            'legajo_id' => $legajoId,
                            'alumno' => $alumno,
                            'materia' => $materia,
                            'directivo' => $directivo,
                        ],
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Error al notificar legajo aprobado a supervisor {$supervisorId}: " . $e->getMessage());
            }
        }
    }

    /**
     * Notificar aprobación final de legajo
     */
    public function legajoAprobadoFinal(array $usuariosIds, string $alumno, string $materia, string $supervisor, int $legajoId, string $tipoUsuario = 'admin')
    {
        foreach ($usuariosIds as $usuarioId) {
            $usuario = \App\Models\User::find($usuarioId);
            if (!$usuario) continue;

            $url = $tipoUsuario === 'directivo'
                ? route('directivo.show', ['id' => $legajoId])
                : route('expediente.show', ['alumno' => $legajoId]);

            try {
                Notificacion::crear(
                    $usuario->id,
                    'legajo_aprobado_final',
                    'Legajo Aprobado Finalmente',
                    "Legajo de {$alumno} - {$materia} aprobado finalmente por {$supervisor}. Listo para Oficina de Títulos.",
                    [
                        'icono' => 'bx-check-double',
                        'color' => 'green',
                        'url' => $url,
                        'datos' => [
                            'legajo_id' => $legajoId,
                            'alumno' => $alumno,
                            'materia' => $materia,
                            'supervisor' => $supervisor,
                        ],
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Error al notificar aprobación final a usuario {$usuarioId}: " . $e->getMessage());
            }
        }
    }

    // ==================== ADMINISTRADORES ====================

    /**
     * Notificar nueva excepción solicitada
     */
    public function nuevaExcepcion(string $alumno, string $tipo, int $excepcionId)
    {
        // Obtener todos los usuarios admin (tipo 1)
        $admins = User::where('tipo', 1)->where('activo', true)->get();

        foreach ($admins as $admin) {
            try {
                Notificacion::crear(
                    $admin->id,
                    'nueva_excepcion',
                    'Nueva Solicitud de Excepción',
                    "{$alumno} solicitó excepción: {$tipo}",
                    [
                        'icono' => 'bx-error-circle',
                        'color' => 'orange',
                        'url' => route('admin.excepciones.index'),
                        'datos' => [
                            'excepcion_id' => $excepcionId,
                            'alumno' => $alumno,
                            'tipo' => $tipo,
                        ],
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Error al notificar nueva excepción a admin {$admin->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Notificar nota pendiente de aprobación
     */
    public function notaPendienteAprobacion(string $profesor, string $alumno, string $materia, $nota)
    {
        $admins = User::where('tipo', 1)->where('activo', true)->get();

        foreach ($admins as $admin) {
            try {
                Notificacion::crear(
                    $admin->id,
                    'nota_pendiente_aprobacion',
                    'Nota Pendiente de Aprobación',
                    "{$profesor} cargó una nota final de {$alumno} en {$materia}: {$nota}",
                    [
                        'icono' => 'bx-task',
                        'color' => 'blue',
                        'url' => route('expediente.index'),
                        'datos' => [
                            'profesor' => $profesor,
                            'alumno' => $alumno,
                            'materia' => $materia,
                            'nota' => $nota,
                        ],
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Error al notificar nota pendiente a admin {$admin->id}: " . $e->getMessage());
            }
        }
    }
}
