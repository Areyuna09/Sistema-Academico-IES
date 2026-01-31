<?php

namespace App\Services\Importacion\Importadores;

use App\Models\Materia;
use App\Models\Carrera;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MateriaImportador implements ImportadorInterface
{
    private array $carrerasCache = [];
    private array $materiasCache = [];

    /**
     * Columnas requeridas para validar que es una plantilla de materias
     */
    private const COLUMNAS_REQUERIDAS = ['nombre', 'carrera'];

    /**
     * Columnas que indican que es plantilla de personas (NO deben estar)
     */
    private const COLUMNAS_PERSONAS = ['dni', 'apellido', 'email', 'telefono', 'celular'];

    public function analizar(UploadedFile $file): array
    {
        $this->cargarCacheCarreras();
        $this->cargarCacheMaterias();

        $filas = Excel::toArray([], $file)[0] ?? [];

        if (empty($filas)) {
            return [
                'nuevos' => [],
                'duplicados' => [],
                'errores' => [],
                'total' => 0,
                'error_estructura' => null,
            ];
        }

        // Primera fila es encabezado - limpiar asteriscos y espacios
        $encabezados = array_map(function($h) {
            $h = strtolower(trim($h ?? ''));
            $h = preg_replace('/\s*\*\s*$/', '', $h);
            return trim($h);
        }, $filas[0]);

        // Validar estructura del Excel
        $errorEstructura = $this->validarEstructura($encabezados);
        if ($errorEstructura) {
            return [
                'nuevos' => [],
                'duplicados' => [],
                'errores' => [],
                'total' => 0,
                'error_estructura' => $errorEstructura,
            ];
        }

        $datos = array_slice($filas, 1);

        $nuevos = [];
        $duplicados = [];
        $errores = [];

        foreach ($datos as $index => $fila) {
            $numeroFila = $index + 2;

            // Ignorar filas completamente vacías
            $filaLimpia = array_filter($fila, fn($v) => $v !== null && $v !== '');
            if (empty($filaLimpia)) {
                continue;
            }

            $registro = $this->mapearFila($fila, $encabezados);

            $erroresValidacion = $this->validarRegistro($registro, $numeroFila);

            if (!empty($erroresValidacion)) {
                $errores[] = [
                    'fila' => $numeroFila,
                    'datos' => $registro,
                    'errores' => $erroresValidacion,
                ];
                continue;
            }

            // Buscar carrera por nombre
            $carreraId = $this->buscarCarreraId($registro['carrera']);
            if (!$carreraId) {
                $errores[] = [
                    'fila' => $numeroFila,
                    'datos' => $registro,
                    'errores' => ["Carrera '{$registro['carrera']}' no encontrada"],
                ];
                continue;
            }
            $registro['carrera_id'] = $carreraId;

            // Verificar duplicados por nombre + carrera
            $materiaExistente = Materia::where('nombre', $registro['nombre'])
                ->where('carrera', $carreraId)
                ->first();

            if ($materiaExistente) {
                $duplicados[] = [
                    'fila' => $numeroFila,
                    'datos' => $registro,
                    'existente' => [
                        'id' => $materiaExistente->id,
                        'nombre' => $materiaExistente->nombre,
                        'carrera' => $materiaExistente->carrera()?->nombre ?? 'N/A',
                        'anno' => $materiaExistente->anno,
                    ],
                ];
            } else {
                $nuevos[] = [
                    'fila' => $numeroFila,
                    'datos' => $registro,
                ];
            }
        }

        return [
            'nuevos' => $nuevos,
            'duplicados' => $duplicados,
            'errores' => $errores,
            'total' => count($datos),
            'error_estructura' => null,
            'encabezados_detectados' => array_filter($encabezados, fn($h) => $h !== ''),
        ];
    }

    /**
     * Validar que el Excel tenga la estructura esperada para materias
     */
    private function validarEstructura(array $encabezados): ?string
    {
        // Verificar columnas requeridas
        $columnasFaltantes = [];
        foreach (self::COLUMNAS_REQUERIDAS as $columna) {
            if (!in_array($columna, $encabezados)) {
                $columnasFaltantes[] = $columna;
            }
        }

        if (!empty($columnasFaltantes)) {
            return "El archivo no tiene la estructura correcta para importar MATERIAS. " .
                   "Columnas faltantes: " . implode(', ', $columnasFaltantes) . ". " .
                   "Por favor, descargue la plantilla correcta desde el botón 'Descargar Plantilla'.";
        }

        // Verificar que no sea una plantilla de personas (alumnos/profesores)
        // Si tiene 'dni' o 'apellido', es plantilla de personas
        if (in_array('dni', $encabezados)) {
            if (in_array('legajo', $encabezados) || in_array('celular', $encabezados)) {
                return "El archivo parece ser una plantilla de ALUMNOS, no de MATERIAS. " .
                       "Por favor, use la plantilla correcta para importar materias.";
            }
            return "El archivo parece ser una plantilla de PROFESORES, no de MATERIAS. " .
                   "Por favor, use la plantilla correcta para importar materias.";
        }

        return null;
    }

    public function importar(array $datos, array $opciones = []): array
    {
        $actualizarDuplicados = $opciones['actualizar_duplicados'] ?? [];

        $importados = 0;
        $actualizados = 0;
        $erroresImportacion = [];

        // Recargar cache de materias antes de procesar correlativas
        $this->cargarCacheMaterias();

        DB::beginTransaction();

        try {
            // Primera pasada: crear materias sin correlativas
            foreach ($datos['nuevos'] as $item) {
                $registro = $item['datos'];

                $materia = Materia::create([
                    'nombre' => $registro['nombre'],
                    'carrera' => $registro['carrera_id'],
                    'anno' => $registro['anno'] ?? null,
                    'semestre' => $registro['semestre'] ?? null,
                    'resolucion' => $registro['resolucion'] ?? null,
                ]);

                // Actualizar cache para que las correlativas funcionen
                $key = $this->generarClaveMateria($materia->nombre, $registro['carrera_id']);
                $this->materiasCache[$key] = $materia->id;

                $importados++;
            }

            // Segunda pasada: actualizar correlativas
            foreach ($datos['nuevos'] as $item) {
                $registro = $item['datos'];
                $this->actualizarCorrelativas($registro);
            }

            // Actualizar duplicados seleccionados
            foreach ($datos['duplicados'] as $item) {
                $nombre = $item['datos']['nombre'];
                $carreraId = $item['datos']['carrera_id'];
                $key = $this->generarClaveMateria($nombre, $carreraId);

                if (!in_array($key, $actualizarDuplicados)) {
                    continue;
                }

                $registro = $item['datos'];
                $materia = Materia::where('nombre', $nombre)
                    ->where('carrera', $carreraId)
                    ->first();

                if ($materia) {
                    $materia->update([
                        'anno' => $registro['anno'] ?? $materia->anno,
                        'semestre' => $registro['semestre'] ?? $materia->semestre,
                        'resolucion' => $registro['resolucion'] ?? $materia->resolucion,
                    ]);

                    $this->actualizarCorrelativas($registro);
                    $actualizados++;
                }
            }

            DB::commit();

            return [
                'success' => true,
                'importados' => $importados,
                'actualizados' => $actualizados,
                'usuarios_creados' => 0,
                'errores' => $erroresImportacion,
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'importados' => 0,
                'actualizados' => 0,
                'usuarios_creados' => 0,
                'errores' => [$e->getMessage()],
            ];
        }
    }

    public function getCamposRequeridos(): array
    {
        return [
            'nombre' => 'Nombre de la materia',
            'carrera' => 'Nombre exacto de la carrera',
        ];
    }

    public function getCamposOpcionales(): array
    {
        return [
            'anno' => 'Año (1, 2, 3, 4)',
            'semestre' => 'Semestre (1 o 2)',
            'correlativas_regular' => 'Materias separadas por : para cursar (regularizadas)',
            'correlativas_rendido' => 'Materias separadas por : para cursar (aprobadas)',
            'correlativas_rendir' => 'Materias separadas por : para rendir final',
            'resolucion' => 'Número de resolución',
        ];
    }

    public function getNombreEntidad(): string
    {
        return 'Materia';
    }

    private function mapearFila(array $fila, array $encabezados): array
    {
        $registro = [];

        foreach ($encabezados as $index => $campo) {
            $valor = $fila[$index] ?? null;
            $registro[$campo] = is_string($valor) ? trim($valor) : $valor;
        }

        return $registro;
    }

    private function validarRegistro(array $registro, int $fila): array
    {
        $errores = [];

        if (empty($registro['nombre'])) {
            $errores[] = "Nombre es requerido";
        }

        if (empty($registro['carrera'])) {
            $errores[] = "Carrera es requerida";
        }

        if (!empty($registro['anno']) && (!is_numeric($registro['anno']) || $registro['anno'] < 1 || $registro['anno'] > 6)) {
            $errores[] = "Año debe ser un número entre 1 y 6";
        }

        if (!empty($registro['semestre']) && !in_array($registro['semestre'], [1, 2, '1', '2'])) {
            $errores[] = "Semestre debe ser 1 o 2";
        }

        return $errores;
    }

    private function cargarCacheCarreras(): void
    {
        if (empty($this->carrerasCache)) {
            $carreras = Carrera::all();
            foreach ($carreras as $carrera) {
                $nombreNormalizado = mb_strtolower(trim($carrera->nombre));
                $this->carrerasCache[$nombreNormalizado] = $carrera->Id;
            }
        }
    }

    private function cargarCacheMaterias(): void
    {
        $this->materiasCache = [];
        $materias = Materia::all();
        foreach ($materias as $materia) {
            $key = $this->generarClaveMateria($materia->nombre, $materia->carrera);
            $this->materiasCache[$key] = $materia->id;
        }
    }

    private function generarClaveMateria(string $nombre, int $carreraId): string
    {
        return mb_strtolower(trim($nombre)) . '_' . $carreraId;
    }

    private function buscarCarreraId(string $nombreCarrera): ?int
    {
        $nombreNormalizado = mb_strtolower(trim($nombreCarrera));
        return $this->carrerasCache[$nombreNormalizado] ?? null;
    }

    private function buscarMateriaId(string $nombreMateria, int $carreraId): ?int
    {
        $key = $this->generarClaveMateria($nombreMateria, $carreraId);
        return $this->materiasCache[$key] ?? null;
    }

    private function actualizarCorrelativas(array $registro): void
    {
        $materia = Materia::where('nombre', $registro['nombre'])
            ->where('carrera', $registro['carrera_id'])
            ->first();

        if (!$materia) {
            return;
        }

        $updates = [];

        // Correlativas para cursar (regularizadas)
        if (!empty($registro['correlativas_regular'])) {
            $ids = $this->convertirNombresAIds($registro['correlativas_regular'], $registro['carrera_id']);
            $updates['paracursar_regular'] = $ids;
        }

        // Correlativas para cursar (aprobadas)
        if (!empty($registro['correlativas_rendido'])) {
            $ids = $this->convertirNombresAIds($registro['correlativas_rendido'], $registro['carrera_id']);
            $updates['paracursar_rendido'] = $ids;
        }

        // Correlativas para rendir
        if (!empty($registro['correlativas_rendir'])) {
            $ids = $this->convertirNombresAIds($registro['correlativas_rendir'], $registro['carrera_id']);
            $updates['pararendir'] = $ids;
        }

        if (!empty($updates)) {
            $materia->update($updates);
        }
    }

    private function convertirNombresAIds(string $nombres, int $carreraId): string
    {
        $nombresArray = array_map('trim', explode(':', $nombres));
        $ids = [];

        foreach ($nombresArray as $nombre) {
            if (empty($nombre)) {
                continue;
            }

            $id = $this->buscarMateriaId($nombre, $carreraId);
            if ($id) {
                $ids[] = $id;
            }
        }

        return implode(':', $ids);
    }
}
