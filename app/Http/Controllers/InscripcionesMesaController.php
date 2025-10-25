<?php

namespace App\Http\Controllers;

use App\Models\MesaExamen;
use App\Models\InscripcionMesa;
use App\Models\AlumnoMateria;
use App\Models\Notificacion;
use App\Services\MotorCorrelativasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InscripcionesMesaController extends Controller
{
    protected $motorCorrelativas;

    public function __construct(MotorCorrelativasService $motorCorrelativas)
    {
        $this->motorCorrelativas = $motorCorrelativas;
    }

    /**
     * Mostrar mesas disponibles para el alumno
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->alumno_id) {
            abort(403, 'No tienes un perfil de alumno asociado');
        }

        $alumno = $user->alumno;

        // Obtener mesas disponibles (activas, futuras, y dentro del período de inscripción)
        // Optimización: Eager loading con select específico para reducir datos
        $mesasQuery = MesaExamen::with([
                'materia:id,nombre,anno,carrera',
                'materia.carrera:Id,nombre',
                'periodo:id,nombre',
                'presidente:id,dni,apellido,nombre',
                'vocal1:id,dni,apellido,nombre',
                'vocal2:id,dni,apellido,nombre'
            ])
            ->where('estado', 'activa')
            ->whereDate('fecha_examen', '>=', now())
            ->where(function($q) {
                $q->whereNull('fecha_inicio_inscripcion')
                  ->orWhere(function($q2) {
                      $q2->whereDate('fecha_inicio_inscripcion', '<=', now())
                         ->whereDate('fecha_fin_inscripcion', '>=', now());
                  });
            })
            ->get();

        // Determinar período de inscripción activo
        $periodosInscripcion = MesaExamen::where('estado', 'activa')
            ->whereNotNull('fecha_inicio_inscripcion')
            ->whereNotNull('fecha_fin_inscripcion')
            ->whereDate('fecha_inicio_inscripcion', '<=', now())
            ->whereDate('fecha_fin_inscripcion', '>=', now())
            ->select('fecha_inicio_inscripcion', 'fecha_fin_inscripcion', 'llamado')
            ->groupBy('fecha_inicio_inscripcion', 'fecha_fin_inscripcion', 'llamado')
            ->first();

        $periodoActivo = null;
        if ($periodosInscripcion) {
            $periodoActivo = [
                'llamado' => $periodosInscripcion->llamado,
                'fecha_inicio' => $periodosInscripcion->fecha_inicio_inscripcion->format('d/m/Y'),
                'fecha_fin' => $periodosInscripcion->fecha_fin_inscripcion->format('d/m/Y'),
                'dias_restantes' => now()->diffInDays($periodosInscripcion->fecha_fin_inscripcion, false),
            ];
        }

        // Optimización: Obtener todas las inscripciones del alumno de una sola vez
        $inscripcionesAlumno = InscripcionMesa::where('alumno_id', $alumno->id)
            ->whereIn('mesa_id', $mesasQuery->pluck('id'))
            ->get()
            ->keyBy('mesa_id');

        $mesas = $mesasQuery->map(function ($mesa) use ($alumno, $inscripcionesAlumno) {
                // Verificar si el alumno ya está inscripto (usando el mapa precargado)
                $inscripcion = $inscripcionesAlumno->get($mesa->id);

                // Validar correlativas para esta materia
                $validacion = $this->motorCorrelativas->validarCorrelativasParaExamen(
                    $alumno->id,
                    $mesa->materia_id
                );

                // Obtener cantidad de inscriptos
                $cantidadInscriptos = $mesa->inscripciones()
                    ->whereIn('estado', ['inscripto', 'presente', 'aprobado', 'desaprobado'])
                    ->count();

                $materiaData = $mesa->getRelation('materia');
                $carreraData = $materiaData->getRelation('carrera');

                // Determinar el motivo de bloqueo
                $motivoBloqueo = null;
                $yaAprobada = false;

                if (!$validacion['puede_rendir']) {
                    if (str_contains($validacion['mensaje'], 'Ya tiene esta materia aprobada')) {
                        $motivoBloqueo = 'Ya aprobaste esta materia';
                        $yaAprobada = true;
                    } elseif (str_contains($validacion['mensaje'], 'Debe tener la materia regularizada')) {
                        $motivoBloqueo = 'Debes regularizar la materia primero';
                    } elseif (!empty($validacion['correlativas_faltantes'])) {
                        $count = count($validacion['correlativas_faltantes']);
                        $motivoBloqueo = "Te " . ($count === 1 ? 'falta' : 'faltan') . " {$count} correlativa" . ($count === 1 ? '' : 's');
                    } else {
                        $motivoBloqueo = 'No cumples los requisitos';
                    }
                }

                return [
                    'id' => $mesa->id,
                    'materia' => [
                        'id' => $materiaData->id,
                        'nombre' => $materiaData->nombre,
                        'carrera' => $carreraData->nombre,
                        'anno' => $materiaData->anno,
                    ],
                    'fecha_examen' => $mesa->fecha_examen->format('Y-m-d'),
                    'fecha_examen_formatted' => $mesa->fecha_examen->format('d/m/Y'),
                    'hora_examen' => $mesa->hora_examen,
                    'llamado' => $mesa->llamado,
                    'aula' => $mesa->aula,
                    'cantidad_inscriptos' => $cantidadInscriptos,
                    'tribunal' => [
                        'presidente' => $mesa->presidente?->nombre_completo,
                        'vocal1' => $mesa->vocal1?->nombre_completo,
                        'vocal2' => $mesa->vocal2?->nombre_completo,
                    ],
                    'ya_inscripto' => $inscripcion !== null,
                    'estado_inscripcion' => $inscripcion?->estado,
                    'puede_inscribirse' => $validacion['puede_rendir'],
                    'correlativas_cumplidas' => $validacion['puede_rendir'],
                    'correlativas_faltantes' => $validacion['correlativas_faltantes'] ?? [],
                    'motivo_bloqueo' => $motivoBloqueo,
                    'ya_aprobada' => $yaAprobada,
                    'observaciones' => $mesa->observaciones,
                ];
            });

        // Cargar relación de carrera
        $alumno->load('carreraRelacion');
        $carrera = $alumno->carreraRelacion;

        return Inertia::render('Mesas/Index', [
            'mesas' => $mesas,
            'estudiante' => [
                'dni' => $alumno->dni,
                'nombre' => $alumno->nombre_completo,
                'anio_cursado' => $alumno->curso ?? null,
                'descripcion' => $alumno->descripcion_personalizada ?? null,
            ],
            'carrera' => [
                'id' => $carrera->Id ?? null,
                'nombre' => $carrera->nombre ?? 'Sin carrera',
            ],
            'periodoActivo' => $periodoActivo,
            'anio' => $alumno->curso ?? 'Sin especificar',
        ]);
    }

    /**
     * Inscribir alumno a una mesa
     */
    public function inscribir(Request $request, MesaExamen $mesa)
    {
        $user = $request->user();

        if (!$user->alumno_id) {
            abort(403, 'No tienes un perfil de alumno asociado');
        }

        $alumno = $user->alumno;

        // Verificar que la mesa esté activa
        if ($mesa->estado !== 'activa') {
            return back()->withErrors(['error' => 'Esta mesa no está disponible para inscripción.']);
        }

        // Verificar que sea una fecha futura
        if ($mesa->fecha_examen->isPast()) {
            return back()->withErrors(['error' => 'No puedes inscribirte a una mesa que ya pasó.']);
        }

        // Verificar que no esté ya inscripto
        $inscripcionExistente = InscripcionMesa::where('mesa_id', $mesa->id)
            ->where('alumno_id', $alumno->id)
            ->first();

        if ($inscripcionExistente) {
            return back()->withErrors(['error' => 'Ya estás inscripto a esta mesa.']);
        }

        // Validar correlativas
        $validacion = $this->motorCorrelativas->validarCorrelativasParaExamen(
            $alumno->id,
            $mesa->materia_id
        );

        if (!$validacion['puede_rendir']) {
            return back()->withErrors([
                'error' => 'No cumples con las correlativas requeridas para rendir esta materia.',
                'correlativas_faltantes' => $validacion['correlativas_faltantes']
            ]);
        }

        // Crear inscripción
        $inscripcion = InscripcionMesa::create([
            'mesa_id' => $mesa->id,
            'alumno_id' => $alumno->id,
            'estado' => 'inscripto',
            'fecha_inscripcion' => now(),
        ]);

        // Enviar email con comprobante inmediatamente
        try {
            if ($alumno->email) {
                \Mail::to($alumno->email)->send(new \App\Mail\ComprobanteMesaExamen($alumno, $mesa, $inscripcion));
                \Log::info('Email de comprobante de mesa enviado', ['email' => $alumno->email]);
            }
        } catch (\Exception $e) {
            \Log::error('Error al enviar email de comprobante de mesa: ' . $e->getMessage());
        }

        // Crear notificación para el alumno
        try {
            Notificacion::crear(
                $user->id,
                'mesa_inscripcion_confirmada',
                'Inscripción a Mesa Confirmada',
                "Te has inscrito exitosamente a la mesa de examen de {$mesa->materia->nombre} - {$mesa->llamado} llamado",
                [
                    'icono' => 'bx-calendar-check',
                    'color' => 'green',
                    'url' => route('mesas.confirmacion', ['inscripcion' => $inscripcion->id]),
                    'datos' => [
                        'mesa_id' => $mesa->id,
                        'materia_id' => $mesa->materia_id,
                        'fecha_examen' => $mesa->fecha_examen->format('d/m/Y'),
                    ],
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Error al crear notificación de mesa: ' . $e->getMessage());
        }

        return redirect()->route('mesas.confirmacion', ['inscripcion' => $inscripcion->id])
            ->with('success', 'Te inscribiste exitosamente a la mesa de examen.');
    }

    /**
     * Cancelar inscripción a una mesa
     */
    public function cancelar(Request $request, MesaExamen $mesa)
    {
        $user = $request->user();

        if (!$user->alumno_id) {
            abort(403, 'No tienes un perfil de alumno asociado');
        }

        $alumno = $user->alumno;

        $inscripcion = InscripcionMesa::where('mesa_id', $mesa->id)
            ->where('alumno_id', $alumno->id)
            ->first();

        if (!$inscripcion) {
            return back()->withErrors(['error' => 'No estás inscripto a esta mesa.']);
        }

        // Solo se puede cancelar si está en estado 'inscripto'
        if ($inscripcion->estado !== 'inscripto') {
            return back()->withErrors(['error' => 'No puedes cancelar esta inscripción.']);
        }

        // No permitir cancelar si falta menos de 48 horas
        $horasRestantes = now()->diffInHours($mesa->fecha_examen, false);
        if ($horasRestantes < 48) {
            return back()->withErrors(['error' => 'No puedes cancelar la inscripción con menos de 48 horas de anticipación.']);
        }

        $inscripcion->delete();

        // Crear notificación de cancelación
        try {
            Notificacion::crear(
                $user->id,
                'mesa_cancelacion',
                'Inscripción Cancelada',
                "Has cancelado tu inscripción a la mesa de examen de {$mesa->materia->nombre} - {$mesa->llamado} llamado",
                [
                    'icono' => 'bx-calendar-x',
                    'color' => 'orange',
                    'url' => route('mesas.index'),
                    'datos' => [
                        'mesa_id' => $mesa->id,
                        'materia_nombre' => $mesa->materia->nombre,
                        'fecha_examen' => $mesa->fecha_examen->format('d/m/Y'),
                    ],
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Error al crear notificación de cancelación de mesa: ' . $e->getMessage());
        }

        return redirect()->route('mesas.index')
            ->with('success', 'Inscripción cancelada exitosamente.');
    }

    /**
     * Mostrar confirmación de inscripción
     */
    public function confirmacion(Request $request, $inscripcionId)
    {
        $user = $request->user();

        if (!$user->alumno_id) {
            abort(403, 'No tienes un perfil de alumno asociado');
        }

        $inscripcion = InscripcionMesa::with(['mesa.materia.carrera', 'mesa.presidente', 'mesa.vocal1', 'mesa.vocal2', 'alumno'])
            ->where('id', $inscripcionId)
            ->where('alumno_id', $user->alumno_id)
            ->firstOrFail();

        $materiaData = $inscripcion->mesa->getRelation('materia');
        $carreraData = $materiaData->getRelation('carrera');

        return Inertia::render('Mesas/Confirmacion', [
            'inscripcion' => [
                'id' => $inscripcion->id,
                'fecha_inscripcion' => $inscripcion->fecha_inscripcion->format('d/m/Y H:i'),
                'estado' => $inscripcion->estado,
                'mesa' => [
                    'id' => $inscripcion->mesa->id,
                    'materia' => $materiaData->nombre,
                    'carrera' => $carreraData->nombre,
                    'fecha_examen' => $inscripcion->mesa->fecha_examen->format('d/m/Y'),
                    'hora_examen' => $inscripcion->mesa->hora_examen,
                    'llamado' => $inscripcion->mesa->llamado,
                    'aula' => $inscripcion->mesa->aula,
                    'tribunal' => [
                        'presidente' => $inscripcion->mesa->presidente?->nombre_completo,
                        'vocal1' => $inscripcion->mesa->vocal1?->nombre_completo,
                        'vocal2' => $inscripcion->mesa->vocal2?->nombre_completo,
                    ],
                ],
                'alumno' => [
                    'nombre_completo' => $inscripcion->alumno->nombre_completo,
                    'dni' => $inscripcion->alumno->dni,
                ],
            ],
        ]);
    }

    /**
     * Descargar comprobante de inscripción a mesa en PDF
     */
    public function descargarComprobante(Request $request, $inscripcionId)
    {
        $user = $request->user();

        if (!$user->alumno_id) {
            abort(403, 'No tienes un perfil de alumno asociado');
        }

        $inscripcion = InscripcionMesa::with(['mesa.materia.carrera', 'mesa.presidente', 'mesa.vocal1', 'mesa.vocal2', 'alumno'])
            ->where('id', $inscripcionId)
            ->where('alumno_id', $user->alumno_id)
            ->firstOrFail();

        // Extraer relaciones explícitamente para evitar problemas en Blade
        $materiaData = $inscripcion->mesa->getRelation('materia');
        $carreraData = $materiaData->getRelation('carrera');
        $alumnoData = $inscripcion->getRelation('alumno');

        // Preparar datos para la vista
        $datosComprobante = [
            'inscripcion_id' => $inscripcion->id,
            'fecha_inscripcion' => $inscripcion->fecha_inscripcion,
            'estado' => $inscripcion->estado,
            'mesa' => [
                'llamado' => $inscripcion->mesa->llamado,
                'fecha_examen' => $inscripcion->mesa->fecha_examen,
                'hora_examen' => $inscripcion->mesa->hora_examen,
                'aula' => $inscripcion->mesa->aula,
                'observaciones' => $inscripcion->mesa->observaciones,
            ],
            'materia' => [
                'nombre' => $materiaData->nombre,
            ],
            'carrera' => [
                'nombre' => $carreraData->nombre,
            ],
            'alumno' => [
                'nombre_completo' => $alumnoData->nombre_completo,
                'dni' => $alumnoData->dni,
                'email' => $alumnoData->email,
                'legajo' => $alumnoData->legajo,
            ],
            'tribunal' => [
                'presidente' => $inscripcion->mesa->presidente?->nombre_completo,
                'vocal1' => $inscripcion->mesa->vocal1?->nombre_completo,
                'vocal2' => $inscripcion->mesa->vocal2?->nombre_completo,
            ],
        ];

        // Obtener configuración global
        $configuracion = \App\Models\Configuracion::get();

        // Generar PDF
        $pdf = \PDF::loadView('emails.comprobante-mesa-examen', [
            'datos' => $datosComprobante,
            'configuracion' => $configuracion,
        ]);

        return $pdf->download('comprobante-mesa-examen-' . $alumnoData->dni . '.pdf');
    }

    /**
     * Ver mis inscripciones a mesas
     */
    public function misInscripciones(Request $request)
    {
        $user = $request->user();

        if (!$user->alumno_id) {
            abort(403, 'No tienes un perfil de alumno asociado');
        }

        $alumno = $user->alumno;

        $inscripciones = InscripcionMesa::with(['mesa.materia.carrera', 'mesa.periodo'])
            ->where('alumno_id', $alumno->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($inscripcion) {
                return [
                    'id' => $inscripcion->id,
                    'mesa' => [
                        'id' => $inscripcion->mesa->id,
                        'materia' => $inscripcion->mesa->materia->nombre,
                        'carrera' => $inscripcion->mesa->materia->carrera->nombre,
                        'fecha_examen' => $inscripcion->mesa->fecha_examen->format('d/m/Y'),
                        'hora_examen' => $inscripcion->mesa->hora_examen,
                        'llamado' => $inscripcion->mesa->llamado,
                        'aula' => $inscripcion->mesa->aula,
                    ],
                    'fecha_inscripcion' => $inscripcion->fecha_inscripcion->format('d/m/Y H:i'),
                    'estado' => $inscripcion->estado,
                    'nota' => $inscripcion->nota,
                    'observaciones' => $inscripcion->observaciones,
                ];
            });

        return Inertia::render('Mesas/MisInscripciones', [
            'inscripciones' => $inscripciones,
        ]);
    }
}
