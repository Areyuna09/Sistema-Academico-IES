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