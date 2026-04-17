<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Materia;
use App\Models\PlanEstudioMateria;
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
 * Controlador para gestión de inscripciones a materias.
 * Integra el motor de correlativas con la interfaz Vue.
 *
 * Las materias que se muestran al alumno se filtran por su plan de estudio,
 * resuelto por Alumno::resolverPlan(). Si el alumno no tiene plan asignado
 * explícitamente, se usa el año de ingreso como heurística. Ver modelo Alumno.
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
        $user = $request->user();

        if (!$user->alumno_id) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes un perfil de alumno asociado');
        }

        $alumno = $user->alumno;

        if (!$alumno) {
            return redirect()->route('dashboard')
                ->with('error', 'Alumno no encontrado');
        }

        $carreraId = $alumno->carrera;
        $carrera = Carrera::find($carreraId);

        if (!$carrera) {
            return redirect()->route('dashboard')
                ->with('error', 'Tu perfil no tiene una carrera asignada. Contactá a Secretaría Académica.');
        }

        $periodoActivo = PeriodoInscripcion::activo();

        if (!$periodoActivo) {
            return redirect()->route('dashboard')
                ->with('warning', 'No hay un período de inscripción activo en este momento');
        }

        // Historial: materias ya aprobadas (nota >= 4) — se excluyen de la lista
        $historialAlumno = \App\Models\AlumnoMateria::where('alumno', $alumno->id)
            ->where('carrera', $carreraId)
            ->whereNotNull('nota')
            ->where('nota', '>=', 4)
            ->pluck('materia')
            ->toArray();

        // -----------------------------------------------------------------------
        // Resolver plan de estudio del alumno y filtrar materias por él
        // -----------------------------------------------------------------------
        $planAlumno = $alumno->resolverPlan();

        if ($planAlumno) {
            // Obtener IDs de materias que pertenecen al plan del alumno
            $materiasDelPlan = PlanEstudioMateria::where('plan_estudio_id', $planAlumno->id)
                ->pluck('materia_id')
                ->toArray();

            $materias = Materia::with(['profesores:id,dni,apellido,nombre', 'carrera'])
                ->whereIn('id', $materiasDelPlan)
                ->where('semestre', $periodoActivo->cuatrimestre)
                ->whereNotIn('id', $historialAlumno)
                ->get();
        } else {
            // Sin plan definido: comportamiento anterior como fallback
            // (muestra todas las materias de la carrera en el cuatrimestre)
            $materias = Materia::with(['profesores:id,dni,apellido,nombre', 'carrera'])
                ->deCarrera($carreraId)
                ->where('semestre', $periodoActivo->cuatrimestre)
                ->whereNotIn('id', $historialAlumno)
                ->get();
        }
        // -----------------------------------------------------------------------

        // Materias ya inscriptas en este período
        $materiasInscritasIds = Inscripcion::where('alumno_id', $alumno->id)
            ->where('periodo_id', $periodoActivo->id)
            ->where('estado', '!=', 'cancelada')
            ->pluck('materia_id')
            ->toArray();

        // Mapa para búsquedas O(1) al armar correlativas
        $materiasMap = $materias->keyBy('id');

        // Para cada materia, validar correlativas y armar el DTO para el frontend
        $materiasConEstado = $materias->map(function ($materia) use ($alumno, $carreraId, $materiasMap, $materiasInscritasIds) {
            $yaInscrito = in_array($materia->id, $materiasInscritasIds);

            $validacion = $this->motorCorrelativas->validarParaCursar(
                $alumno->dni,
                $materia->id,
                $carreraId
            );

            $profesoresNombres = $materia->profesores->map(function ($profesor) {
                return $profesor->nombre_completo;
            })->toArray();

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
                            'tipo' => 'Regular',
                        ];
                    }
                }

                foreach ($idsRendido as $id) {
                    $correlativa = $materiasMap->get($id);
                    if ($correlativa && !in_array($id, $idsRegular)) {
                        $correlativasNecesarias[] = [
                            'id' => $correlativa->id,
                            'nombre' => $correlativa->nombre,
                            'tipo' => 'Aprobada',
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
            // Plan de estudio resuelto — el frontend puede mostrarlo si quiere
            'plan' => $planAlumno ? [
                'id' => $planAlumno->id,
                'nombre' => $planAlumno->nombre,
                'anio' => $planAlumno->anio,
                'resolucion' => $planAlumno->resolucion ?? null,
            ] : null,
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

        $periodoActivo = PeriodoInscripcion::activo();

        if (!$periodoActivo) {
            return redirect()->route('inscripciones.index')
                ->with('error', 'No hay un período de inscripción activo');
        }

        if (!$periodoActivo->inscripcionesAbiertas()) {
            return redirect()->route('inscripciones.index')
                ->with('error', 'El período de inscripción no está abierto en este momento');
        }

        $carreraId = $alumno->carrera;
        $materiasInscritas = 0;
        $errores = [];

        // Resolver plan UNA sola vez fuera del loop
        $planAlumno = $alumno->resolverPlan();

        // Si hay plan, precargar sus IDs para validar en O(1) dentro del loop
        $idsDelPlan = $planAlumno
            ? PlanEstudioMateria::where('plan_estudio_id', $planAlumno->id)
                ->pluck('materia_id')
                ->flip() // convierte a [id => index] para usar isset()
                ->toArray()
            : null;

        DB::beginTransaction();

        try {
            foreach ($validated['materias'] as $materiaId) {
                // Validar que la materia pertenezca a la carrera y al cuatrimestre activo
                $materia = Materia::where('id', $materiaId)
                    ->where('carrera', $carreraId)
                    ->where('semestre', $periodoActivo->cuatrimestre)
                    ->first();

                if (!$materia) {
                    $errores[] = "La materia ID {$materiaId} no corresponde a tu carrera o al cuatrimestre activo";
                    continue;
                }

                // Validar que la materia esté en el plan del alumno
                // (evita que alguien manipule el request y se inscriba a materias de otro plan)
                if ($idsDelPlan !== null && !isset($idsDelPlan[$materiaId])) {
                    $errores[] = "La materia \"{$materia->nombre}\" no pertenece a tu plan de estudios ({$planAlumno->nombre})";
                    \Log::warning('⚠️ Intento de inscripción fuera del plan', [
                        'alumno_id' => $alumno->id,
                        'materia_id' => $materiaId,
                        'plan_id' => $planAlumno->id,
                    ]);
                    continue;
                }

                // Validar correlativas
                $validacion = $this->motorCorrelativas->validarParaCursar(
                    $alumno->dni,
                    $materiaId,
                    $carreraId
                );

                if (!$validacion['puede_cursar']) {
                    $errores[] = "No cumplís los requisitos para cursar: {$materia->nombre}";
                    \Log::warning('❌ Correlativas no cumplidas', [
                        'materia_id' => $materiaId,
                        'materia' => $materia->nombre,
                        'validacion' => $validacion,
                    ]);
                    continue;
                }

                // Verificar inscripción duplicada en este período
                $inscripcionExistente = Inscripcion::where('alumno_id', $alumno->id)
                    ->where('materia_id', $materiaId)
                    ->where('periodo_id', $periodoActivo->id)
                    ->where('estado', '!=', 'cancelada')
                    ->first();

                if ($inscripcionExistente) {
                    $errores[] = "Ya estás inscrito en: {$materia->nombre}";
                    continue;
                }

                // Crear inscripción
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
                    'turno' => 1,
                    'fecha' => now(),
                    'curso' => $alumno->curso ?? 1,
                    'division' => $alumno->division ?? 1,
                    'cursado' => 'cursando',
                    'mesa' => null,
                ]);

                $materiasInscritas++;
            }

            DB::commit();

            \Log::info('📊 Resultado de inscripciones', [
                'materias_inscritas' => $materiasInscritas,
                'errores' => $errores,
            ]);

            if ($materiasInscritas > 0) {
                $inscripcionesCreadas = Inscripcion::with('materia')
                    ->where('alumno_id', $alumno->id)
                    ->where('periodo_id', $periodoActivo->id)
                    ->whereIn('materia_id', $validated['materias'])
                    ->get();

                // Enviar email de comprobante
                try {
                    $emailDestino = $alumno->email ?? $user->email;

                    if ($emailDestino) {
                        Mail::to($emailDestino)->send(
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
                'materias_count' => count($validated['materias'] ?? []),
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

        $inscripciones = Inscripcion::with('materia')
            ->where('alumno_id', $alumno->id)
            ->where('periodo_id', $periodoActivo->id)
            ->where('estado', 'confirmada')
            ->get();

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
            Mail::to($emailDestino)->send(new \App\Mail\ComprobanteInscripcion(
                $alumno,
                $inscripciones,
                $periodo
            ));

            \Log::info('Comprobante de inscripción enviado exitosamente', [
                'email_destino' => $emailDestino,
                'alumno' => $alumno->nombre_completo,
                'materias' => $inscripciones->pluck('materia.nombre'),
                'periodo' => $periodo->nombre,
            ]);

            return true;
        } catch (\Exception $e) {
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

        $inscripciones = Inscripcion::with('materia')
            ->where('alumno_id', $alumno->id)
            ->where('periodo_id', $periodoActivo->id)
            ->where('estado', 'confirmada')
            ->get();

        if ($inscripciones->isEmpty()) {
            abort(404, 'No tienes inscripciones en el período actual');
        }

        $configuracion = Configuracion::get();

        $pdf = \PDF::loadView('pdfs.comprobante-inscripcion', [
            'alumno' => $alumno,
            'inscripciones' => $inscripciones,
            'periodo' => $periodoActivo,
            'configuracion' => $configuracion,
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('comprobante-inscripcion-' . $alumno->dni . '.pdf');
    }
}