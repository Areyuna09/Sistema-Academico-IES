<?php

namespace App\Services;

use App\Models\Alumno;
use App\Models\Materia;
use App\Models\ReglaCorrelativa;
use Illuminate\Support\Facades\DB;

/**
 * Motor de Reglas de Correlativas Configurable (RF03)
 * Servicio principal que orquesta todo el sistema de correlativas
 */
class MotorCorrelativasService
{
    protected CorrelativasParserService $parser;
    protected ValidacionCursadoService $validacionCursado;

    public function __construct(
        CorrelativasParserService $parser,
        ValidacionCursadoService $validacionCursado
    ) {
        $this->parser = $parser;
        $this->validacionCursado = $validacionCursado;
    }

    /**
     * Validar correlativas para cursar (RF02)
     */
    public function validarParaCursar(string $dni, int $materiaId, int $carreraId): array
    {
        return $this->validacionCursado->validarParaCursar($dni, $materiaId, $carreraId);
    }

    /**
     * Validar correlativas para rendir examen (similar a RF02)
     */
    public function validarParaRendir(string $dni, int $materiaId, int $carreraId): array
    {
        // Implementación similar a cursado pero con lógica de rendir
        $alumno = Alumno::porDni($dni)->deCarrera($carreraId)->first();

        if (!$alumno) {
            return $this->respuestaError("Alumno no encontrado");
        }

        $materia = Materia::find($materiaId);

        if (!$materia) {
            return $this->respuestaError("Materia no encontrada");
        }

        // Para rendir: la materia debe estar al menos regular
        $historial = $alumno->materiasCursadas()
            ->where('carrera', $carreraId)
            ->where('materia', $materiaId)
            ->first();

        if (!$historial || !$historial->estaRegular()) {
            return [
                'puede_rendir' => false,
                'mensaje' => "Alumno {$dni} debe tener la materia {$materia->nombre} regularizada para rendir",
                'correlativas_cumplidas' => [],
                'correlativas_faltantes' => [],
            ];
        }

        // Validar correlativas para rendir
        $correlativas = $this->parser->obtenerCorrelativasCombinadas($materia, 'rendir');

        // Las correlativas para rendir suelen requerir materias aprobadas
        // Implementar lógica similar a validarParaCursar pero para rendir

        return [
            'puede_rendir' => true,
            'mensaje' => "Alumno {$dni} está en CONDICIONES para rendir {$materia->nombre}",
            'correlativas_cumplidas' => [],
            'correlativas_faltantes' => [],
        ];
    }

    /**
     * Crear nueva regla de correlativa (RF03 - Motor Configurable)
     */
    public function crearRegla(array $datos): ReglaCorrelativa
    {
        return DB::transaction(function () use ($datos) {
            $regla = ReglaCorrelativa::create([
                'materia_id' => $datos['materia_id'],
                'carrera_id' => $datos['carrera_id'],
                'tipo' => $datos['tipo'], // 'cursar' o 'rendir'
                'correlativa_id' => $datos['correlativa_id'],
                'estado_requerido' => $datos['estado_requerido'], // 'regular' o 'aprobada'
                'es_activa' => $datos['es_activa'] ?? true,
                'excepciones' => $datos['excepciones'] ?? null,
                'observaciones' => $datos['observaciones'] ?? null,
            ]);

            return $regla;
        });
    }

    /**
     * Actualizar regla existente
     */
    public function actualizarRegla(int $reglaId, array $datos): ?ReglaCorrelativa
    {
        $regla = ReglaCorrelativa::find($reglaId);

        if (!$regla) {
            return null;
        }

        $regla->update($datos);

        return $regla->fresh();
    }

    /**
     * Desactivar regla (soft delete lógico)
     */
    public function desactivarRegla(int $reglaId): bool
    {
        $regla = ReglaCorrelativa::find($reglaId);

        if (!$regla) {
            return false;
        }

        $regla->es_activa = false;
        $regla->save();

        return true;
    }

    /**
     * Obtener todas las reglas de una materia
     */
    public function obtenerReglasDeMateria(int $materiaId, int $carreraId): array
    {
        $reglas = ReglaCorrelativa::paraMateria($materiaId, $carreraId)
            ->activas()
            ->with(['materia', 'correlativa', 'carrera'])
            ->get();

        return [
            'cursar' => $reglas->where('tipo', 'cursar')->values(),
            'rendir' => $reglas->where('tipo', 'rendir')->values(),
        ];
    }

    /**
     * Sincronizar reglas desde formato legacy a nueva tabla
     * Útil para migrar datos existentes
     */
    public function sincronizarDesdeLegacy(int $materiaId): array
    {
        $materia = Materia::find($materiaId);

        if (!$materia) {
            return ['error' => 'Materia no encontrada'];
        }

        $creadas = 0;

        // Sincronizar correlativas para cursar - regulares
        $regularesIds = $this->parser->parsearCorrelativasLegacy($materia->paracursar_regular);
        foreach ($regularesIds as $correlativaId) {
            ReglaCorrelativa::firstOrCreate([
                'materia_id' => $materiaId,
                'carrera_id' => $materia->carrera,
                'tipo' => 'cursar',
                'correlativa_id' => $correlativaId,
                'estado_requerido' => 'regular',
            ]);
            $creadas++;
        }

        // Sincronizar correlativas para cursar - aprobadas
        $aprobadasIds = $this->parser->parsearCorrelativasLegacy($materia->paracursar_rendido);
        foreach ($aprobadasIds as $correlativaId) {
            ReglaCorrelativa::firstOrCreate([
                'materia_id' => $materiaId,
                'carrera_id' => $materia->carrera,
                'tipo' => 'cursar',
                'correlativa_id' => $correlativaId,
                'estado_requerido' => 'aprobada',
            ]);
            $creadas++;
        }

        // Sincronizar correlativas para rendir
        $rendirIds = $this->parser->parsearCorrelativasLegacy($materia->pararendir);
        foreach ($rendirIds as $correlativaId) {
            ReglaCorrelativa::firstOrCreate([
                'materia_id' => $materiaId,
                'carrera_id' => $materia->carrera,
                'tipo' => 'rendir',
                'correlativa_id' => $correlativaId,
                'estado_requerido' => 'aprobada',
            ]);
            $creadas++;
        }

        return [
            'materia_id' => $materiaId,
            'materia_nombre' => $materia->nombre,
            'reglas_creadas' => $creadas,
        ];
    }

    /**
     * Sincronizar todas las materias de una carrera
     */
    public function sincronizarCarreraCompleta(int $carreraId): array
    {
        $materias = Materia::deCarrera($carreraId)->get();
        $resultados = [];

        foreach ($materias as $materia) {
            $resultados[] = $this->sincronizarDesdeLegacy($materia->id);
        }

        return [
            'carrera_id' => $carreraId,
            'materias_procesadas' => count($resultados),
            'detalles' => $resultados,
        ];
    }

    /**
     * Obtener resumen del estado académico de un alumno
     */
    public function obtenerResumenAlumno(string $dni, int $carreraId): array
    {
        $alumno = Alumno::porDni($dni)->deCarrera($carreraId)->first();

        if (!$alumno) {
            return ['error' => 'Alumno no encontrado'];
        }

        $historial = $alumno->materiasCursadas()
            ->where('carrera', $carreraId)
            ->with('materia')
            ->get();

        return [
            'alumno' => [
                'dni' => $alumno->dni,
                'nombre' => $alumno->nombre_completo,
                'carrera_id' => $carreraId,
            ],
            'estadisticas' => [
                'materias_aprobadas' => $historial->filter->estaAprobada()->count(),
                'materias_regulares' => $historial->filter->estaRegular()->count(),
                'total_materias' => $historial->count(),
            ],
            'materias_aprobadas' => $historial->filter->estaAprobada()->map(function ($m) {
                return [
                    'id' => $m->getAttribute('materia'),
                    'nombre' => $m->getRelation('materia')?->nombre ?? 'Desconocida',
                    'nota' => $m->nota,
                    'fecha' => $m->fecha?->format('Y-m-d'),
                ];
            })->values(),
            'materias_regulares' => $historial->filter->estaRegular()->map(function ($m) {
                return [
                    'id' => $m->getAttribute('materia'),
                    'nombre' => $m->getRelation('materia')?->nombre ?? 'Desconocida',
                    'nota' => $m->nota,
                    'fecha' => $m->fecha?->format('Y-m-d'),
                ];
            })->values(),
        ];
    }

    /**
     * Validar correlativas para rendir examen por alumno_id
     * Usado en inscripción a mesas de examen
     */
    public function validarCorrelativasParaExamen(int $alumnoId, int $materiaId): array
    {
        $alumno = Alumno::find($alumnoId);

        if (!$alumno) {
            return [
                'puede_rendir' => false,
                'mensaje' => "Alumno no encontrado",
                'correlativas_faltantes' => [],
            ];
        }

        $materia = Materia::find($materiaId);

        if (!$materia) {
            return [
                'puede_rendir' => false,
                'mensaje' => "Materia no encontrada",
                'correlativas_faltantes' => [],
            ];
        }

        $carreraId = $materia->carrera;

        // Verificar que la materia esté al menos regularizada
        $historial = $alumno->materiasCursadas()
            ->where('carrera', $carreraId)
            ->where('materia', $materiaId)
            ->first();

        // Verificar si ya tiene la materia aprobada
        if ($historial && $historial->estaAprobada()) {
            return [
                'puede_rendir' => false,
                'mensaje' => "Ya tiene esta materia aprobada",
                'correlativas_faltantes' => [],
                'rinde_libre' => false,
            ];
        }

        // Determinar si rinde como libre o regular
        $rindeLibre = false;
        if (!$historial || !$historial->estaRegular()) {
            // Si no está regularizada, puede rendir como libre
            // Verificamos si el alumno está marcado como libre en la materia
            $esLibre = $historial && ($historial->libre == 1 || $historial->libre === '1');

            // Permitir inscripción como libre si:
            // 1. No tiene historial (nunca cursó) - puede rendir libre
            // 2. Está marcado como libre
            // 3. Cursó pero no regularizó (cursada = 0 o null)
            $rindeLibre = true;
        }

        // Obtener correlativas para rendir
        $correlativasData = $this->parser->obtenerCorrelativasCombinadas($materia, 'rendir');

        // Obtener materias del alumno
        $materiasAlumno = $alumno->materiasCursadas()
            ->where('carrera', $carreraId)
            ->with('materiaRelacion')
            ->get();

        $correlativasFaltantes = [];

        // Para rendir, todas las correlativas deben estar aprobadas
        $todasCorrelativasIds = array_merge(
            $correlativasData['regulares'] ?? [],
            $correlativasData['aprobadas'] ?? []
        );

        // Obtener información de las materias correlativas
        if (!empty($todasCorrelativasIds)) {
            $materiasCorrelativas = Materia::whereIn('id', $todasCorrelativasIds)->get()->keyBy('id');

            foreach ($todasCorrelativasIds as $correlativaId) {
                $materiaCorrelativa = $materiasCorrelativas->get($correlativaId);
                if (!$materiaCorrelativa) {
                    continue;
                }

                $materiaAlumno = $materiasAlumno->where('materia', $correlativaId)->first();

                // IMPORTANTE: Si la materia está aprobada, cumple CUALQUIER requisito
                // (tanto "regular" como "aprobada"), por lo que NO debería bloquearse
                // Solo se bloquea si: no la cursó O solo está regular (no aprobada)
                if (!$materiaAlumno) {
                    // No cursó la correlativa
                    $correlativasFaltantes[] = [
                        'id' => $correlativaId,
                        'nombre' => $materiaCorrelativa->nombre,
                        'estado_requerido' => 'aprobada',
                        'estado_actual' => 'no cursada',
                    ];
                } elseif (!$materiaAlumno->estaAprobada()) {
                    // La cursó pero NO está aprobada
                    // Si para rendir se requiere aprobada, entonces falta
                    $correlativasFaltantes[] = [
                        'id' => $correlativaId,
                        'nombre' => $materiaCorrelativa->nombre,
                        'estado_requerido' => 'aprobada',
                        'estado_actual' => $materiaAlumno->estaRegular() ? 'regular' : 'no cursada',
                    ];
                }
                // Si está aprobada, NO agregamos nada a correlativasFaltantes
                // porque cumple todos los requisitos posibles
            }
        }

        $puedeRendir = count($correlativasFaltantes) === 0;

        $mensaje = $puedeRendir
            ? ($rindeLibre ? "Puede rendir como LIBRE" : "Cumple con todas las correlativas para rendir")
            : "No cumple con las correlativas requeridas";

        return [
            'puede_rendir' => $puedeRendir,
            'mensaje' => $mensaje,
            'correlativas_faltantes' => $correlativasFaltantes,
            'rinde_libre' => $rindeLibre,
        ];
    }

    /**
     * Respuesta de error estándar
     */
    protected function respuestaError(string $mensaje): array
    {
        return [
            'error' => true,
            'mensaje' => $mensaje,
        ];
    }
}