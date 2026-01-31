<?php

namespace App\Services\Importacion;

use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ValidadorEstructuraExcel
{
    private const COLUMNAS_REQUERIDAS = [
        'alumnos' => ['dni', 'apellido', 'nombre', 'carrera'],
        'profesores' => ['dni', 'apellido', 'nombre'],
        'materias' => ['nombre', 'carrera'],
    ];

    private const COLUMNAS_OPCIONALES = [
        'alumnos' => ['email', 'telefono', 'celular', 'legajo', 'anno', 'division', 'turno'],
        'profesores' => ['email', 'carrera', 'division'],
        'materias' => ['anno', 'semestre', 'correlativas_regular', 'correlativas_rendido', 'correlativas_rendir', 'resolucion'],
    ];

    // Reglas de formato por columna: 'numerico', 'email', 'texto', 'anno', 'semestre', 'turno'
    private const REGLAS_COLUMNAS = [
        'dni' => 'numerico',
        'legajo' => 'numerico',
        'telefono' => 'telefono',
        'celular' => 'telefono',
        'email' => 'email',
        'nombre' => 'texto',
        'apellido' => 'texto',
        'anno' => 'anno',
        'semestre' => 'semestre',
        'turno' => 'turno',
    ];

    private const FILAS_MUESTRA = 10;

    private const COLUMNAS_PROHIBIDAS = [
        'alumnos' => ['correlativas_regular', 'correlativas_rendido', 'correlativas_rendir', 'resolucion', 'semestre'],
        'profesores' => ['correlativas_regular', 'correlativas_rendido', 'correlativas_rendir', 'resolucion', 'semestre', 'legajo', 'celular', 'turno'],
        'materias' => ['dni', 'apellido', 'legajo', 'celular', 'turno', 'telefono'],
    ];

    public function validar(string $tipo, UploadedFile $file): array
    {
        if (!isset(self::COLUMNAS_REQUERIDAS[$tipo])) {
            return $this->resultadoError("Tipo de importación '{$tipo}' no válido.");
        }

        try {
            $filas = Excel::toArray([], $file)[0] ?? [];
        } catch (\Exception $e) {
            return $this->resultadoError('No se pudo leer el archivo. Verifique que sea un Excel válido.');
        }

        if (empty($filas) || empty($filas[0])) {
            return $this->resultadoError('El archivo está vacío o no tiene encabezados.');
        }

        $encabezados = array_map(function ($h) {
            $h = strtolower(trim($h ?? ''));
            $h = preg_replace('/\s*\*\s*$/', '', $h);
            return trim($h);
        }, $filas[0]);

        $encabezados = array_values(array_filter($encabezados, fn($h) => $h !== ''));

        $requeridos = self::COLUMNAS_REQUERIDAS[$tipo];
        $opcionales = self::COLUMNAS_OPCIONALES[$tipo];
        $prohibidas = self::COLUMNAS_PROHIBIDAS[$tipo];
        $todasValidas = array_merge($requeridos, $opcionales);

        // Campos requeridos: detectados o no
        $camposRequeridos = array_map(fn($col) => [
            'nombre' => $col,
            'detectado' => in_array($col, $encabezados),
        ], $requeridos);

        // Campos opcionales: detectados o no
        $camposOpcionales = array_map(fn($col) => [
            'nombre' => $col,
            'detectado' => in_array($col, $encabezados),
        ], $opcionales);

        // Columnas prohibidas encontradas
        $columnasProhibidas = array_values(array_filter($prohibidas, fn($col) => in_array($col, $encabezados)));

        // Columnas que no pertenecen a ninguna definición de este tipo
        $columnasInvalidas = array_values(array_filter($encabezados, fn($col) => !in_array($col, $todasValidas)));

        // La plantilla se define por su header completo: TODAS las columnas deben estar presentes.
        // Los datos opcionales pueden venir vacíos, pero el encabezado debe ser el de la plantilla completa.
        $faltantes = array_values(array_filter($todasValidas, fn($col) => !in_array($col, $encabezados)));

        // Detectar qué plantilla parece ser
        $plantillaDetectada = $this->detectarPlantilla($encabezados);

        // Determinar validez
        $error = null;
        $nombreTipo = strtoupper($tipo);

        if (!empty($columnasProhibidas)) {
            // Primero chequeamos prohibidas: si tiene columnas de otra plantilla, es archivo equivocado
            if ($plantillaDetectada && $plantillaDetectada !== $tipo) {
                $nombreDetectada = strtoupper($plantillaDetectada);
                $error = "El archivo parece ser una plantilla de {$nombreDetectada}, no de {$nombreTipo}. "
                    . "Columnas no permitidas: " . implode(', ', $columnasProhibidas) . '. '
                    . 'Use la plantilla correcta para importar ' . $tipo . '.';
            } else {
                $error = "El archivo contiene columnas no permitidas para {$nombreTipo}: "
                    . implode(', ', $columnasProhibidas) . '.';
            }
        } elseif (!empty($faltantes)) {
            $error = "La plantilla de {$nombreTipo} debe contener todos los encabezados. "
                . "Faltan: " . implode(', ', $faltantes) . '.';

            if ($plantillaDetectada && $plantillaDetectada !== $tipo) {
                $nombreDetectada = strtoupper($plantillaDetectada);
                $error .= " El archivo parece ser una plantilla de {$nombreDetectada}.";
            }

            $error .= ' Descargue la plantilla correcta desde el botón "Descargar Plantilla".';
        }

        $valido = empty($error);

        // Si la estructura es válida, muestrear datos para detectar valores incoherentes
        $advertencias = [];
        if ($valido) {
            $advertencias = $this->validarDatosMuestra($filas, $encabezados);
        }

        return [
            'valido' => $valido,
            'encabezados_detectados' => $encabezados,
            'campos_requeridos' => $camposRequeridos,
            'campos_opcionales' => $camposOpcionales,
            'columnas_invalidas' => $columnasInvalidas,
            'columnas_prohibidas' => $columnasProhibidas,
            'advertencias' => $advertencias,
            'error' => $error,
            'plantilla_detectada' => $plantillaDetectada,
        ];
    }

    private function validarDatosMuestra(array $filas, array $encabezados): array
    {
        $advertencias = [];
        $filasData = array_slice($filas, 1); // quitar header
        $muestreadas = 0;

        foreach ($filasData as $index => $fila) {
            $filaLimpia = array_filter($fila, fn($v) => $v !== null && $v !== '');
            if (empty($filaLimpia)) {
                continue;
            }

            $numeroFila = $index + 2;

            foreach ($encabezados as $colIndex => $columna) {
                $valor = $fila[$colIndex] ?? null;
                if ($valor === null || $valor === '') {
                    continue;
                }

                $valor = is_string($valor) ? trim($valor) : $valor;
                $problema = $this->validarCelda($columna, $valor);

                if ($problema) {
                    $valorMostrar = mb_strlen((string) $valor) > 20
                        ? mb_substr((string) $valor, 0, 20) . '...'
                        : (string) $valor;
                    $advertencias[] = "Fila {$numeroFila}, columna '{$columna}': {$problema} (valor: \"{$valorMostrar}\")";
                }
            }

            $muestreadas++;
            if ($muestreadas >= self::FILAS_MUESTRA) {
                break;
            }
        }

        return $advertencias;
    }

    private function validarCelda(string $columna, mixed $valor): ?string
    {
        $regla = self::REGLAS_COLUMNAS[$columna] ?? null;
        if (!$regla) {
            return null;
        }

        $valor = (string) $valor;

        return match ($regla) {
            'numerico' => !preg_match('/^\d+$/', $valor)
                ? 'debe ser numérico'
                : null,

            'telefono' => !preg_match('/^[\d\s\-\+\(\)]+$/', $valor)
                ? 'debe contener solo números, espacios, guiones o paréntesis'
                : null,

            'email' => !filter_var($valor, FILTER_VALIDATE_EMAIL)
                ? 'no es un email válido'
                : null,

            'texto' => preg_match('/^\d+$/', $valor) || preg_match('/[<>{}\\\\]/', $valor)
                ? 'no parece un nombre válido'
                : null,

            'anno' => !preg_match('/^\d+$/', $valor) || (int) $valor < 1 || (int) $valor > (int) date('Y') + 1
                ? 'debe ser un año o número válido'
                : null,

            'semestre' => !in_array($valor, ['1', '2'])
                ? 'debe ser 1 o 2'
                : null,

            'turno' => !in_array(mb_strtolower($valor), ['mañana', 'manana', 'tarde', 'noche', 'vespertino'])
                ? 'debe ser Mañana, Tarde o Noche'
                : null,

            default => null,
        };
    }

    private function detectarPlantilla(array $encabezados): ?string
    {
        $tiene = fn(string $col) => in_array($col, $encabezados);

        // Columnas exclusivas de materias
        if ($tiene('correlativas_regular') || $tiene('correlativas_rendido') || $tiene('resolucion')) {
            return 'materias';
        }

        // Tiene DNI → es plantilla de personas
        if ($tiene('dni')) {
            // Columnas exclusivas de alumnos
            if ($tiene('legajo') || $tiene('celular') || $tiene('turno')) {
                return 'alumnos';
            }
            return 'profesores';
        }

        // Sin DNI pero con nombre + carrera → materias
        if ($tiene('nombre') && $tiene('carrera') && !$tiene('apellido')) {
            return 'materias';
        }

        return null;
    }

    private function resultadoError(string $mensaje): array
    {
        return [
            'valido' => false,
            'encabezados_detectados' => [],
            'campos_requeridos' => [],
            'campos_opcionales' => [],
            'columnas_invalidas' => [],
            'columnas_prohibidas' => [],
            'advertencias' => [],
            'error' => $mensaje,
            'plantilla_detectada' => null,
        ];
    }
}
