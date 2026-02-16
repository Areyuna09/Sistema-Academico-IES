<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Materia;
use App\Models\PeriodoInscripcion;
use App\Models\Inscripcion;
use App\Models\Configuracion;
use App\Models\Notificacion;
use App\Services\MotorCorrelativasService;
use App\Traits\HandlesErrors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

/**
 * Controlador para gestión de inscripciones a materias
 * Integra el motor de correlativas con la interfaz Vue
 */
class InscripcionesController extends Controller
{
    use HandlesErrors;
    protected MotorCorrelativasService $motorCorrelativas;

    public function __construct(MotorCorrelativasService $motorCorrelativas)
    {
        $this->motorCorrelativas = $motorCorrelativas;
    }

    /**
     * Mostrar vista de inscripción a materias
     */
    public function index(Request $request)
    {
        // Obtener usuario autenticado
        $user = $request->user();

        if (!$user->alumno_id) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes un perfil de alumno asociado');
        }

        // Obtener alumno del usuario autenticado
        $alumno = $user->alumno;

        if (!$alumno) {
            return redirect()->route('dashboard')
                ->with('error', 'Alumno no encontrado');
        }

        // Obtener la carrera del alumno
        $carreraId = $alumno->carrera;
        $carrera = Carrera::find($carreraId);

        if (!$carrera) {
            return redirect()->route('dashboard')
                ->with('error', 'Tu perfil no tiene una carrera asignada. Contactá a Secretaría Académica.');
        }

        // Obtener período de inscripción activo
        $periodoActivo = PeriodoInscripcion::activo();

        if (!$periodoActivo) {
            return redirect()->route('dashboard')
                ->with('warning', 'No hay un período de inscripción activo en este momento');
        }

        // Obtener historial académico del alumno
        // La fuente de verdad es la NOTA en el legajo (no el flag rendida)
        $historialAlumno = \App\Models\AlumnoMateria::where('alumno', $alumno->id)
            ->where('carrera', $carreraId)
            ->whereNotNull('nota') // Tiene nota registrada
            ->where('nota', '>=', 4) // Nota aprobada (>= 4)
            ->pluck('materia')
            ->toArray();

        // Obtener año que está cursando el alumno
        // Nota: 'curso' = año que cursa (1, 2, 3), 'anno' = año de ingreso
        $anioAlumno = $alumno->curso ?? 1; // Por defecto 1er año si no está especificado

        // Obtener materias del cuatrimestre activo con profesores
        // Filtrar por: año del alumno, cuatrimestre activo, y excluir materias aprobadas
        // Optimización: Eager loading de profesores para evitar N+1
        $materias = Materia::with(['profesores:id,dni,apellido,nombre', 'carrera'])
            ->deCarrera($carreraId)
            ->where('semestre', $periodoActivo->cuatrimestre)
            ->whereNotIn('id', $historialAlumno) // Excluir materias aprobadas
            ->get();

        // Obtener materias ya inscritas en este período
        $materiasInscritasIds = Inscripcion::where('alumno_id', $alumno->id)
            ->where('periodo_id', $periodoActivo->id)
            ->where('estado', '!=', 'cancelada')
            ->pluck('materia_id')
            ->toArray();

        // Crear mapa de materias por ID para búsquedas rápidas
        $materiasMap = $materias->keyBy('id');

        // Para cada materia, validar si el alumno puede cursarla
        $materiasConEstado = $materias->map(function ($materia) use ($alumno, $carreraId, $materiasMap, $materiasInscritasIds) {
            // Verificar si ya está inscrito
            $yaInscrito = in_array($materia->id, $materiasInscritasIds);

            // Validar correlativas
            $validacion = $this->motorCorrelativas->validarParaCursar(
                $alumno->dni,
                $materia->id,
                $carreraId
            );

            // Obtener profesores reales
            $profesoresNombres = $materia->profesores->map(function($profesor) {
                return $profesor->nombre_completo;
            })->toArray();

            // Obtener nombres de correlativas necesarias usando el mapa para búsqueda O(1)
            $correlativasNecesarias = [];
            if (!empty($materia->paracursar_regular) || !empty($materia->paracursar_rendido)) {
                $idsRegular = !empty($materia->paracursar_regular) ? explode(':', $materia->paracursar_regular) : [];
                $idsRendido = !empty($materia->paracursar_rendido) ? explode(':', $materia->paracursar_rendido) : [];

                foreach ($idsRegular as $id) {
                    $correlativa = $materiasMap->get($id);
                    if ($correlativa) {
                        $correlativasNecesarias[] = [
                            'id' => $correlativa->id,
                            'nombre' => $correlativa->nombre,
                            'tipo' => 'Regular'
                        ];
                    }
                }

                foreach ($idsRendido as $id) {
                    $correlativa = $materiasMap->get($id);
                    if ($correlativa && !in_array($id, $idsRegular)) {
                        $correlativasNecesarias[] = [
                            'id' => $correlativa->id,
                            'nombre' => $correlativa->nombre,
                            'tipo' => 'Aprobada'
                        ];
                    }
                }
            }

            return [
                'id' => $materia->id,
                'nombre' => $materia->nombre,
                'anno' => $materia->anno ?? null,
                'semestre' => $materia->semestre ?? null,
                'profesores' => $profesoresNombres,
                'puede_cursar' => $validacion['puede_cursar'] && !$yaInscrito,
                'ya_inscrito' => $yaInscrito,
                'correlativas_necesarias' => $correlativasNecesarias,
                'correlativas' => [
                    'cumplidas' => $validacion['correlativas_cumplidas'] ?? [],
                    'faltantes' => $validacion['correlativas_faltantes'] ?? [],
                ],
            ];
        });

        return Inertia::render('Inscripciones/Index', [
            'estudiante' => [
                'dni' => $alumno->dni,
                'nombre' => $alumno->nombre_completo,
                'anio_cursado' => $alumno->curso ?? null,
                'anio_ingreso' => $alumno->anno ?? null,
                'descripcion' => $alumno->descripcion_personalizada ?? null,
            ],
            'carrera' => [
                'id' => $carrera->Id,
                'nombre' => $carrera->nombre ?? 'Sin descripción',
            ],
            'periodo' => [
                'nombre' => $periodoActivo->nombre,
                'fecha_inicio' => $periodoActivo->fecha_inicio_inscripcion->format('d/m/Y'),
                'fecha_fin' => $periodoActivo->fecha_fin_inscripcion->format('d/m/Y'),
                'inscripciones_abiertas' => $periodoActivo->inscripcionesAbiertas(),
                'dias_restantes' => $periodoActivo->diasRestantes(),
            ],
            'materias' => $materiasConEstado,
            'anio' => $alumno->curso ?? 'Sin especificar',
        ]);
    }

    /**
     * Procesar inscripción a materias seleccionadas
     */
    public function store(Request $request)
    {
        \Log::info('📥 POST /inscripciones recibido', ['data' => $request->all()]);

        $validated = $request->validate([
            'materias' => 'required|array|min:1|max:5',
            'materias.*' => 'required|integer|exists:tbl_materias,id',
        ]);

        \Log::info('✅ Validación exitosa', ['validated' => $validated]);

        // Obtener usuario autenticado y su alumno
        $user = $request->user();

        if (!$user->alumno_id) {
            return redirect()->route('inscripciones.index')
                ->with('error', 'No tienes un perfil de alumno asociado');
        }

        $alumno = $user->alumno;

        if (!$alumno) {
            return redirect()->route('inscripciones.index')
                ->with('error', 'Alumno no encontrado');
        }

        // Obtener período de inscripción activo
        $periodoActivo = PeriodoInscripcion::activo();

        if (!$periodoActivo) {
            return redirect()->route('inscripciones.index')
                ->with('error', 'No hay un período de inscripción activo');
        }

        // Verificar que las inscripciones estén abiertas
        if (!$periodoActivo->inscripcionesAbiertas()) {
            return redirect()->route('inscripciones.index')
                ->with('error', 'El período de inscripción no está abierto en este momento');
        }

        $carreraId = $alumno->carrera;
        $materiasInscritas = 0;
        $errores = [];

        DB::beginTransaction();

        try {
            foreach ($validated['materias'] as $materiaId) {
                // Validar que la materia pertenezca a la carrera del alumno
                $materia = Materia::where('id', $materiaId)
                    ->where('carrera', $carreraId)
                    ->where('semestre', $periodoActivo->cuatrimestre)
                    ->first();

                if (!$materia) {
                    $errores[] = "La materia ID {$materiaId} no corresponde a tu carrera o al cuatrimestre activo";
                    continue;
                }

                // Validar correlativas
                $validacion = $this->motorCorrelativas->validarParaCursar(
                    $alumno->dni,
                    $materiaId,
                    $carreraId
                );

                if (!$validacion['puede_cursar']) {
                    $error = "No cumples los requisitos para cursar: {$materia->nombre}";
                    $errores[] = $error;
                    \Log::warning('❌ Validación de correlativas falló', [
                        'materia_id' => $materiaId,
                        'materia' => $materia->nombre,
                        'validacion' => $validacion
                    ]);
                    continue;
                }

                // Verificar si ya está inscrito en este período
                $inscripcionExistente = Inscripcion::where('alumno_id', $alumno->id)
                    ->where('materia_id', $materiaId)
                    ->where('periodo_id', $periodoActivo->id)
                    ->where('estado', '!=', 'cancelada')
                    ->first();

                if ($inscripcionExistente) {
                    $errores[] = "Ya estás inscrito en: {$materia->nombre}";
                    continue;
                }

                // Crear inscripción en tabla nueva
                Inscripcion::create([
                    'alumno_id' => $alumno->id,
                    'materia_id' => $materiaId,
                    'carrera_id' => $carreraId,
                    'periodo_id' => $periodoActivo->id,
                    'estado' => 'confirmada',
                    'fecha_inscripcion' => now(),
                ]);

                // Guardar también en tabla legacy tbl_alumnos_cursa
                DB::table('tbl_alumnos_cursa')->insert([
                    'dni' => $alumno->dni,
                    'nombre' => $alumno->nombre,
                    'apellido' => $alumno->apellido,
                    'email' => $user->email,
                    'telefono' => $alumno->telefono ?? '',
                    'celular' => $alumno->celular ?? '',
                    'legajo' => $alumno->legajo ?? '',
                    'anno' => $alumno->anno ?? date('Y'),
                    'carrera' => $carreraId,
                    'materia' => $materiaId,
                    'turno' => 1, // Por defecto turno 1
                    'fecha' => now(),
                    'curso' => $alumno->curso ?? 1,
                    'division' => $alumno->division ?? 1,
                    'cursado' => 'cursando', // Estado por defecto
                    'mesa' => null,
                ]);

                $materiasInscritas++;
            }

            DB::commit();

            \Log::info('📊 Resultado de inscripciones', [
                'materias_inscritas' => $materiasInscritas,
                'errores' => $errores
            ]);

            // Si hubo inscripciones exitosas, enviar email y redirigir a confirmación
            if ($materiasInscritas > 0) {
                // Obtener inscripciones recién creadas
                $inscripcionesCreadas = Inscripcion::with('materia')
                    ->where('alumno_id', $alumno->id)
                    ->where('periodo_id', $periodoActivo->id)
                    ->whereIn('materia_id', $validated['materias'])
                    ->get();

                // Enviar email de comprobante inmediatamente
                try {
                    $emailDestino = $alumno->email ?? $user->email;

                    if ($emailDestino) {
                        // Enviar inmediatamente sin cola
                        \Mail::to($emailDestino)->send(
                            new \App\Mail\ComprobanteInscripcion(
                                $alumno,
                                $inscripcionesCreadas,
                                $periodoActivo
                            )
                        );
                        \Log::info('Email de comprobante enviado', ['email' => $emailDestino]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error al enviar email de comprobante: ' . $e->getMessage());
                    // No fallar la inscripción por error en email
                }

                // Crear notificación para el alumno
                try {
                    $materiasNombres = $inscripcionesCreadas->pluck('materia.nombre')->take(3)->join(', ');
                    $cantidadMaterias = $inscripcionesCreadas->count();
                    $restoMaterias = $cantidadMaterias > 3 ? ' y ' . ($cantidadMaterias - 3) . ' más' : '';

                    Notificacion::crear(
                        $user->id,
                        'inscripcion_confirmada',
                        'Inscripción Confirmada',
                        "Te has inscrito exitosamente a {$cantidadMaterias} " . ($cantidadMaterias === 1 ? 'materia' : 'materias') . ": {$materiasNombres}{$restoMaterias}",
                        [
                            'icono' => 'bx-check-circle',
                            'color' => 'green',
                            'url' => route('inscripciones.confirmacion'),
                            'datos' => [
                                'periodo_id' => $periodoActivo->id,
                                'cantidad_materias' => $cantidadMaterias,
                            ],
                        ]
                    );
                } catch (\Exception $e) {
                    \Log::error('Error al crear notificación de inscripción: ' . $e->getMessage());
                }

                // Preparar mensaje de respuesta
                if (count($errores) === 0) {
                    return redirect()->route('inscripciones.confirmacion');
                } else {
                    return redirect()->route('inscripciones.confirmacion')
                        ->with('warning', 'Algunas materias no pudieron inscribirse: ' . implode(', ', $errores));
                }
            } else {
                return redirect()->route('inscripciones.index')
                    ->with('error', 'No se pudo completar ninguna inscripción. ' . implode(', ', $errores));
            }

        } catch (\Exception $e) {
            DB::rollBack();

            $this->handleError($e, 'procesar inscripciones', [
                'alumno_id' => $alumno->id,
                'materias_count' => count($materiasIds ?? [])
            ]);

            return redirect()->route('inscripciones.index')
                ->with('error', $this->getFriendlyErrorMessage($e, 'Error al procesar las inscripciones'));
        }
    }

    /**
     * Mostrar página de confirmación de inscripción
     */
    public function confirmacion(Request $request)
    {
        $user = $request->user();

        if (!$user->alumno_id) {
            return redirect()->route('dashboard');
        }

        $alumno = $user->alumno;
        $periodoActivo = PeriodoInscripcion::activo();

        if (!$periodoActivo) {
            return redirect()->route('dashboard');
        }

        // Obtener inscripciones del período actual
        $inscripciones = Inscripcion::with('materia')
            ->where('alumno_id', $alumno->id)
            ->where('periodo_id', $periodoActivo->id)
            ->where('estado', 'confirmada')
            ->get();

        // Determinar email al que se envió el comprobante
        $emailComprobante = $alumno->email ?? $user->email;

        return Inertia::render('Inscripciones/Confirmacion', [
            'estudiante' => [
                'dni' => $alumno->dni,
                'nombre' => $alumno->nombre_completo,
                'email' => $emailComprobante,
            ],
            'inscripciones' => $inscripciones,
            'periodo' => [
                'nombre' => $periodoActivo->nombre,
            ],
        ]);
    }

    /**
     * Enviar email con comprobante de inscripción
     */
    protected function enviarComprobanteEmail($emailDestino, $alumno, $inscripciones, $periodo)
    {
        try {
            // Enviar email con comprobante
            Mail::to($emailDestino)->send(new \App\Mail\ComprobanteInscripcion(
                $alumno,
                $inscripciones,
                $periodo
            ));

            // Registrar en log
            \Log::info('Comprobante de inscripción enviado exitosamente', [
                'email_destino' => $emailDestino,
                'alumno' => $alumno->nombre_completo,
                'materias' => $inscripciones->pluck('materia.nombre'),
                'periodo' => $periodo->nombre,
            ]);

            return true;
        } catch (\Exception $e) {
            // Registrar error pero no fallar la inscripción
            \Log::error('Error al enviar comprobante de inscripción', [
                'error' => $e->getMessage(),
                'email_destino' => $emailDestino,
            ]);

            return false;
        }
    }

    /**
     * Descargar comprobante de inscripción en PDF
     */
    public function descargarComprobante(Request $request)
    {
        $user = $request->user();

        if (!$user->alumno_id) {
            abort(403, 'No tienes un perfil de alumno asociado');
        }

        $alumno = $user->alumno;
        $periodoActivo = PeriodoInscripcion::activo();

        if (!$periodoActivo) {
            abort(404, 'No hay período de inscripción activo');
        }

        // Obtener inscripciones del período actual
        $inscripciones = Inscripcion::with('materia')
            ->where('alumno_id', $alumno->id)
            ->where('periodo_id', $periodoActivo->id)
            ->where('estado', 'confirmada')
            ->get();

        if ($inscripciones->isEmpty()) {
            abort(404, 'No tienes inscripciones en el período actual');
        }

        // Obtener configuración global
        $configuracion = Configuracion::get();

        // Generar PDF usando la vista específica para PDF (con logo y firma)
        $pdf = \PDF::loadView('pdfs.comprobante-inscripcion', [
            'alumno' => $alumno,
            'inscripciones' => $inscripciones,
            'periodo' => $periodoActivo,
            'configuracion' => $configuracion,
        ]);

        // Configurar orientación y tamaño
        $pdf->setPaper('A4', 'portrait');

        // Mostrar PDF en navegador (vista previa)
        return $pdf->stream('comprobante-inscripcion-' . $alumno->dni . '.pdf');
    }

}
