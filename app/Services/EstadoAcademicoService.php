<?php

namespace App\Services;

/**
 * Servicio para calcular el estado académico de un alumno
 * según las notas obtenidas y la configuración de la materia
 */
class EstadoAcademicoService
{
    /**
     * Estados posibles
     */
    const ESTADO_PROMOCIONADO = 'promocionado';
    const ESTADO_REGULAR = 'regular';
    const ESTADO_LIBRE = 'libre';

    /**
     * Calcular el estado académico de un alumno según su nota
     *
     * @param float $nota Nota obtenida por el alumno
     * @param float $notaMinimaPromocion Nota mínima para promocionar (default: 7.00)
     * @param float $notaMinimaRegularidad Nota mínima para regularizar (default: 4.00)
     * @param bool $permitePromocion Si la materia permite promoción directa (default: true)
     * @return array ['estado' => string, 'cursada' => string|null, 'rendida' => string|null]
     */
    public static function calcularEstado(
        float $nota,
        float $notaMinimaPromocion = 7.00,
        float $notaMinimaRegularidad = 4.00,
        bool $permitePromocion = true
    ): array {
        $estado = self::ESTADO_LIBRE;
        $cursada = null;
        $rendida = null;

        // Si la materia permite promoción Y la nota es suficiente
        if ($permitePromocion && $nota >= $notaMinimaPromocion) {
            // Promociona directamente (cursada + rendida)
            $estado = self::ESTADO_PROMOCIONADO;
            $cursada = '1';
            $rendida = '1';
        }
        // Si la nota es suficiente para regularizar (pero no promociona)
        elseif ($nota >= $notaMinimaRegularidad) {
            // Queda regular (solo cursada, debe rendir final)
            $estado = self::ESTADO_REGULAR;
            $cursada = '1';
            $rendida = null;
        }
        // Si la nota es insuficiente
        else {
            // Queda libre
            $estado = self::ESTADO_LIBRE;
            $cursada = null;
            $rendida = null;
        }

        return [
            'estado' => $estado,
            'cursada' => $cursada,
            'rendida' => $rendida,
        ];
    }

    /**
     * Verificar si una nota es aprobatoria
     *
     * @param float|null $nota
     * @return bool
     */
    public static function esNotaAprobatoria(?float $nota): bool
    {
        if ($nota === null) {
            return false;
        }

        return $nota >= 4.00;
    }

    /**
     * Verificar si una nota alcanza para promoción
     *
     * @param float|null $nota
     * @param float $notaMinimaPromocion
     * @return bool
     */
    public static function alcanzaPromocion(?float $nota, float $notaMinimaPromocion = 7.00): bool
    {
        if ($nota === null) {
            return false;
        }

        return $nota >= $notaMinimaPromocion;
    }

    /**
     * Verificar si una nota alcanza para regularidad
     *
     * @param float|null $nota
     * @param float $notaMinimaRegularidad
     * @return bool
     */
    public static function alcanzaRegularidad(?float $nota, float $notaMinimaRegularidad = 4.00): bool
    {
        if ($nota === null) {
            return false;
        }

        return $nota >= $notaMinimaRegularidad;
    }

    /**
     * Obtener una descripción legible del estado académico
     *
     * @param string $estado
     * @return string
     */
    public static function obtenerDescripcionEstado(string $estado): string
    {
        return match($estado) {
            self::ESTADO_PROMOCIONADO => 'Promocionado (aprobó sin rendir final)',
            self::ESTADO_REGULAR => 'Regular (debe rendir final)',
            self::ESTADO_LIBRE => 'Libre (no alcanzó la nota mínima)',
            default => 'Estado desconocido'
        };
    }

    /**
     * Calcular el porcentaje de asistencia
     *
     * @param int $presentes
     * @param int $totalClases
     * @return float
     */
    public static function calcularPorcentajeAsistencia(int $presentes, int $totalClases): float
    {
        if ($totalClases === 0) {
            return 0.0;
        }

        return round(($presentes / $totalClases) * 100, 2);
    }

    /**
     * Verificar si cumple con el porcentaje mínimo de asistencia
     *
     * @param int $presentes
     * @param int $totalClases
     * @param float $porcentajeMinimo
     * @return bool
     */
    public static function cumpleAsistenciaMinima(
        int $presentes,
        int $totalClases,
        float $porcentajeMinimo = 75.0
    ): bool {
        $porcentaje = self::calcularPorcentajeAsistencia($presentes, $totalClases);
        return $porcentaje >= $porcentajeMinimo;
    }
}
