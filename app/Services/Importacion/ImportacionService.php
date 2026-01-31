<?php

namespace App\Services\Importacion;

use App\Services\Importacion\Importadores\ImportadorInterface;
use App\Services\Importacion\Importadores\AlumnoImportador;
use App\Services\Importacion\Importadores\ProfesorImportador;
use App\Services\Importacion\Importadores\MateriaImportador;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ImportacionService
{
    private array $importadores = [
        'alumnos' => AlumnoImportador::class,
        'profesores' => ProfesorImportador::class,
        'materias' => MateriaImportador::class,
    ];

    /**
     * Obtiene los tipos de importación disponibles
     */
    public function getTiposDisponibles(): array
    {
        return [
            'alumnos' => [
                'nombre' => 'Alumnos',
                'descripcion' => 'Importar alumnos con creación automática de usuarios',
                'icono' => 'users',
            ],
            'profesores' => [
                'nombre' => 'Profesores',
                'descripcion' => 'Importar profesores con creación automática de usuarios',
                'icono' => 'user-tie',
            ],
            'materias' => [
                'nombre' => 'Materias',
                'descripcion' => 'Importar materias con correlativas',
                'icono' => 'book',
            ],
        ];
    }

    /**
     * Obtiene el importador para un tipo específico
     */
    public function getImportador(string $tipo): ImportadorInterface
    {
        if (!isset($this->importadores[$tipo])) {
            throw new \InvalidArgumentException("Tipo de importación '{$tipo}' no válido");
        }

        $clase = $this->importadores[$tipo];
        return new $clase();
    }

    /**
     * Analiza un archivo y retorna preview
     */
    public function analizar(string $tipo, UploadedFile $file): array
    {
        $importador = $this->getImportador($tipo);

        Log::info('Iniciando análisis de importación', [
            'tipo' => $tipo,
            'archivo' => $file->getClientOriginalName(),
            'tamaño' => $file->getSize(),
        ]);

        $resultado = $importador->analizar($file);

        // Si hay error de estructura, retornar inmediatamente
        if (!empty($resultado['error_estructura'])) {
            Log::warning('Error de estructura en archivo de importación', [
                'tipo' => $tipo,
                'archivo' => $file->getClientOriginalName(),
                'error' => $resultado['error_estructura'],
            ]);

            return [
                'error_estructura' => $resultado['error_estructura'],
            ];
        }

        Log::info('Análisis completado', [
            'tipo' => $tipo,
            'nuevos' => count($resultado['nuevos']),
            'duplicados' => count($resultado['duplicados']),
            'errores' => count($resultado['errores']),
        ]);

        // Clasificar columnas detectadas
        $encabezadosDetectados = array_values($resultado['encabezados_detectados'] ?? []);
        $camposRequeridos = $importador->getCamposRequeridos();
        $camposOpcionales = $importador->getCamposOpcionales();

        $estructura = [
            'requeridos' => array_map(function($campo) use ($encabezadosDetectados) {
                return [
                    'nombre' => $campo,
                    'detectado' => in_array($campo, $encabezadosDetectados),
                ];
            }, $camposRequeridos),
            'opcionales' => array_map(function($campo) use ($encabezadosDetectados) {
                return [
                    'nombre' => $campo,
                    'detectado' => in_array($campo, $encabezadosDetectados),
                ];
            }, $camposOpcionales),
            'encabezados_archivo' => $encabezadosDetectados,
        ];

        return [
            'tipo' => $tipo,
            'entidad' => $importador->getNombreEntidad(),
            'archivo' => $file->getClientOriginalName(),
            'resumen' => [
                'total' => $resultado['total'],
                'nuevos' => count($resultado['nuevos']),
                'duplicados' => count($resultado['duplicados']),
                'errores' => count($resultado['errores']),
            ],
            'datos' => $resultado,
            'campos_requeridos' => $camposRequeridos,
            'campos_opcionales' => $camposOpcionales,
            'estructura' => $estructura,
            'error_estructura' => null,
        ];
    }

    /**
     * Ejecuta la importación con los datos confirmados
     */
    public function importar(string $tipo, array $datos, array $opciones = []): array
    {
        $importador = $this->getImportador($tipo);

        Log::info('Iniciando importación', [
            'tipo' => $tipo,
            'nuevos' => count($datos['nuevos'] ?? []),
            'duplicados_actualizar' => count($opciones['actualizar_duplicados'] ?? []),
        ]);

        $resultado = $importador->importar($datos, $opciones);

        Log::info('Importación completada', [
            'tipo' => $tipo,
            'success' => $resultado['success'],
            'importados' => $resultado['importados'],
            'actualizados' => $resultado['actualizados'],
            'usuarios_creados' => $resultado['usuarios_creados'],
        ]);

        return $resultado;
    }

    /**
     * Obtiene información de campos para un tipo
     */
    public function getCampos(string $tipo): array
    {
        $importador = $this->getImportador($tipo);

        return [
            'requeridos' => $importador->getCamposRequeridos(),
            'opcionales' => $importador->getCamposOpcionales(),
        ];
    }

    /**
     * Valida que el archivo sea un Excel válido
     */
    public function validarArchivo(UploadedFile $file): array
    {
        $errores = [];

        // Validar extensión
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
            $errores[] = 'El archivo debe ser Excel (.xlsx, .xls) o CSV (.csv)';
        }

        // Validar tamaño (max 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            $errores[] = 'El archivo no debe superar los 10MB';
        }

        // Validar que no esté vacío
        if ($file->getSize() === 0) {
            $errores[] = 'El archivo está vacío';
        }

        return $errores;
    }
}
