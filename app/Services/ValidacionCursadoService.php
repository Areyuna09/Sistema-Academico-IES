<?php

namespace App\Services;

use App\Models\Alumno;
use App\Models\Materia;
use App\Models\AlumnoMateria;

/**
 * Servicio específico para validar correlativas para CURSADO (RF02)
 * Implementa la lógica de validación automática para inscripción a cursar
 */
class ValidacionCursadoService
{
    protected CorrelativasParserService $parser;

    public function __construct(CorrelativasParserService $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Validar si un alumno puede cursar una materia
     *
     * @param string $dni DNI del alumno
     * @param int $materiaId ID de la materia a cursar
     * @param int $carreraId ID de la carrera
     * @return array [
     *   'puede_cursar' => bool,
     *   'mensaje' => string,
     *   'correlativas_cumplidas' => array,
     *   'correlativas_faltantes' => array,
     *   'detalles' => array
     * ]
     */
    public function validarParaCursar(string $dni, int $materiaId, int $carreraId): array
    {
        // Obtener alumno
        $alumno = Alumno::porDni($dni)->deCarrera($carreraId)->first();

        if (!$alumno) {
            return $this->respuestaError("Alumno con DNI {$dni} no encontrado en la carrera especificada");
        }

        // Obtener materia
        $materia = Materia::find($materiaId);

        if (!$materia) {
            return $this->respuestaError("Materia con ID {$materiaId} no encontrada");
        }

        // Obtener correlativas requeridas
        $correlativas = $this->parser->obtenerCorrelativasCombinadas($materia, 'cursar');

        // Obtener historial del alumno
        $historial = AlumnoMateria::deAlumno($alumno->id, $carreraId)->with('materiaRelacion')->get();

        // Validar correlativas regulares (cursadas)
        $resultadoRegulares = $this->validarMateriasRegulares(
            $correlativas['regulares'],
            $historial
        );

        // Validar correlativas aprobadas (rendidas)
        $resultadoAprobadas = $this->validarMateriasAprobadas(
            $correlativas['aprobadas'],
            $historial
        );

        // Combinar resultados
        $puedeCursar = $resultadoRegulares['cumple'] && $resultadoAprobadas['cumple'];

        $correlativasCumplidas = array_merge(
            $resultadoRegulares['cumplidas'],
            $resultadoAprobadas['cumplidas']
        );

        $correlativasFaltantes = array_merge(
            $resultadoRegulares['faltantes'],
            $resultadoAprobadas['faltantes']
        );

        // Generar mensaje
        $mensaje = $this->generarMensaje(
            $alumno,
            $materia,
            $puedeCursar,
            $correlativasFaltantes
        );

        return [
            'puede_cursar' => $puedeCursar,
            'mensaje' => $mensaje,
            'correlativas_cumplidas' => $correlativasCumplidas,
            'correlativas_faltantes' => $correlativasFaltantes,
            'detalles' => [
                'alumno' => [
                    'dni' => $alumno->dni,
                    'nombre' => $alumno->nombre_completo,
                ],
                'materia' => [
                    'id' => $materia->id,
                    'nombre' => $materia->nombre,
                ],
                'fuente_reglas' => $correlativas['fuente']
            ]
        ];
    }

    /**
     * Validar materias que deben estar regularizadas (cursadas)
     */
    protected function validarMateriasRegulares(array $materiasIds, $historial): array
    {
        if (empty($materiasIds)) {
            return ['cumple' => true, 'cumplidas' => [], 'faltantes' => []];
        }

        $cumplidas = [];
        $faltantes = [];

        foreach ($materiasIds as $materiaId) {
            $registro = $historial->firstWhere('materia', $materiaId);

            if ($registro && $registro->estaRegular()) {
                $cumplidas[] = [
                    'materia_id' => $materiaId,
                    'materia_nombre' => $registro->materiaRelacion->nombre ?? 'Desconocida',
                    'estado' => 'regular',
                    'nota' => $registro->nota,
                    'fecha' => $registro->fecha?->format('Y-m-d'),
                ];
            } else {
                $materia = Materia::find($materiaId);
                $faltantes[] = [
                    'materia_id' => $materiaId,
                    'materia_nombre' => $materia->nombre ?? 'Desconocida',
                    'estado_requerido' => 'regular',
                    'estado_actual' => $registro ? 'sin regularizar' : 'no cursada',
                ];
            }
        }

        return [
            'cumple' => empty($faltantes),
            'cumplidas' => $cumplidas,
            'faltantes' => $faltantes
        ];
    }

    /**
     * Validar materias que deben estar aprobadas (rendidas)
     */
    protected function validarMateriasAprobadas(array $materiasIds, $historial): array
    {
        if (empty($materiasIds)) {
            return ['cumple' => true, 'cumplidas' => [], 'faltantes' => []];
        }

        $cumplidas = [];
        $faltantes = [];

        foreach ($materiasIds as $materiaId) {
            $registro = $historial->firstWhere('materia', $materiaId);

            if ($registro && $registro->estaAprobada()) {
                $cumplidas[] = [
                    'materia_id' => $materiaId,
                    'materia_nombre' => $registro->materiaRelacion->nombre ?? 'Desconocida',
                    'estado' => 'aprobada',
                    'nota' => $registro->nota,
                    'fecha' => $registro->fecha?->format('Y-m-d'),
                ];
            } else {
                $materia = Materia::find($materiaId);
                $faltantes[] = [
                    'materia_id' => $materiaId,
                    'materia_nombre' => $materia->nombre ?? 'Desconocida',
                    'estado_requerido' => 'aprobada',
                    'estado_actual' => $registro ? ($registro->estaRegular() ? 'solo regular' : 'sin aprobar') : 'no cursada',
                ];
            }
        }

        return [
            'cumple' => empty($faltantes),
            'cumplidas' => $cumplidas,
            'faltantes' => $faltantes
        ];
    }

    /**
     * Generar mensaje legible para el usuario
     */
    protected function generarMensaje(Alumno $alumno, Materia $materia, bool $cumple, array $faltantes): string
    {
        if ($cumple) {
            return "Alumno {$alumno->dni} está en CONDICIONES para cursar {$materia->nombre}";
        }

        $mensajesFaltantes = [];
        foreach ($faltantes as $faltante) {
            $mensajesFaltantes[] = "{$faltante['materia_nombre']} ({$faltante['estado_requerido']})";
        }

        $faltantesTexto = implode(', ', $mensajesFaltantes);

        return "Alumno {$alumno->dni} NO está en CONDICIONES para cursar {$materia->nombre}. Falta: {$faltantesTexto}";
    }

    /**
     * Respuesta de error
     */
    protected function respuestaError(string $mensaje): array
    {
        return [
            'puede_cursar' => false,
            'mensaje' => $mensaje,
            'correlativas_cumplidas' => [],
            'correlativas_faltantes' => [],
            'detalles' => [],
            'error' => true
        ];
    }
}