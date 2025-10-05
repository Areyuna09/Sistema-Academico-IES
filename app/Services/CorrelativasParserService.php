<?php

namespace App\Services;

use App\Models\Materia;
use App\Models\ReglaCorrelativa;

/**
 * Servicio para parsear y transformar correlativas
 * Maneja tanto el formato legacy (strings con ':') como las nuevas reglas
 */
class CorrelativasParserService
{
    /**
     * Parsear correlativas desde formato legacy (string separado por ':')
     *
     * @param string|null $correlativasString Ej: "1:2:3" o "5"
     * @return array Array de IDs de materias
     */
    public function parsearCorrelativasLegacy(?string $correlativasString): array
    {
        if (empty($correlativasString)) {
            return [];
        }

        // Dividir por ':' y convertir a enteros
        return array_map('intval', explode(':', trim($correlativasString)));
    }

    /**
     * Obtener todas las correlativas de una materia desde formato legacy
     *
     * @param Materia $materia
     * @param string $tipo 'cursar' o 'rendir'
     * @return array [
     *   'regulares' => [ids],
     *   'aprobadas' => [ids]
     * ]
     */
    public function obtenerCorrelativasLegacy(Materia $materia, string $tipo = 'cursar'): array
    {
        if ($tipo === 'rendir') {
            return [
                'regulares' => [],
                'aprobadas' => $this->parsearCorrelativasLegacy($materia->pararendir)
            ];
        }

        // Para cursar
        return [
            'regulares' => $this->parsearCorrelativasLegacy($materia->paracursar_regular),
            'aprobadas' => $this->parsearCorrelativasLegacy($materia->paracursar_rendido)
        ];
    }

    /**
     * Obtener correlativas desde la nueva tabla de reglas
     *
     * @param int $materiaId
     * @param int $carreraId
     * @param string $tipo 'cursar' o 'rendir'
     * @return array [
     *   'regulares' => [ids],
     *   'aprobadas' => [ids]
     * ]
     */
    public function obtenerCorrelativasNuevas(int $materiaId, int $carreraId, string $tipo = 'cursar'): array
    {
        $reglas = ReglaCorrelativa::activas()
            ->paraMateria($materiaId, $carreraId)
            ->tipo($tipo)
            ->get();

        $regulares = [];
        $aprobadas = [];

        foreach ($reglas as $regla) {
            if ($regla->estado_requerido === 'regular') {
                $regulares[] = $regla->correlativa_id;
            } else {
                $aprobadas[] = $regla->correlativa_id;
            }
        }

        return [
            'regulares' => $regulares,
            'aprobadas' => $aprobadas
        ];
    }

    /**
     * Obtener correlativas combinando legacy y nuevas reglas
     * Prioridad: nuevas reglas > legacy
     *
     * @param Materia $materia
     * @param string $tipo 'cursar' o 'rendir'
     * @return array [
     *   'regulares' => [ids],
     *   'aprobadas' => [ids],
     *   'fuente' => 'legacy' | 'nueva' | 'mixta'
     * ]
     */
    public function obtenerCorrelativasCombinadas(Materia $materia, string $tipo = 'cursar'): array
    {
        // Obtener de nuevas reglas
        $nuevas = $this->obtenerCorrelativasNuevas($materia->id, $materia->carrera, $tipo);

        // Si hay reglas nuevas, usarlas
        if (!empty($nuevas['regulares']) || !empty($nuevas['aprobadas'])) {
            return [
                'regulares' => $nuevas['regulares'],
                'aprobadas' => $nuevas['aprobadas'],
                'fuente' => 'nueva'
            ];
        }

        // Si no hay reglas nuevas, usar legacy
        $legacy = $this->obtenerCorrelativasLegacy($materia, $tipo);

        return [
            'regulares' => $legacy['regulares'],
            'aprobadas' => $legacy['aprobadas'],
            'fuente' => 'legacy'
        ];
    }

    /**
     * Obtener información detallada de materias correlativas
     *
     * @param array $materiaIds Array de IDs
     * @return \Illuminate\Support\Collection Colección de Materias
     */
    public function obtenerDetallesMaterias(array $materiaIds): \Illuminate\Support\Collection
    {
        if (empty($materiaIds)) {
            return collect([]);
        }

        return Materia::whereIn('id', $materiaIds)
            ->with('carrera')
            ->get();
    }

    /**
     * Formatear correlativas para mostrar al usuario
     *
     * @param array $correlativas ['regulares' => [...], 'aprobadas' => [...]]
     * @return string Texto legible
     */
    public function formatearParaMostrar(array $correlativas): string
    {
        $mensajes = [];

        if (!empty($correlativas['regulares'])) {
            $materias = $this->obtenerDetallesMaterias($correlativas['regulares']);
            $nombres = $materias->pluck('nombre')->implode(', ');
            $mensajes[] = "Regularizadas: {$nombres}";
        }

        if (!empty($correlativas['aprobadas'])) {
            $materias = $this->obtenerDetallesMaterias($correlativas['aprobadas']);
            $nombres = $materias->pluck('nombre')->implode(', ');
            $mensajes[] = "Aprobadas: {$nombres}";
        }

        return empty($mensajes) ? 'Sin correlativas' : implode(' | ', $mensajes);
    }
}