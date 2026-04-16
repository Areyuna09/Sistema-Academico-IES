<?php

namespace App\Services\Importacion\Importadores;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Materia;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class AlumnoImportador implements ImportadorInterface
{
    private array $carrerasCache = [];

    private const COLUMNAS_REQUERIDAS = ['dni', 'apellido', 'nombre', 'carrera'];
    private const COLUMNAS_PROHIBIDAS = ['correlativas_regular', 'correlativas_rendido', 'resolucion'];

    public function analizar(UploadedFile $file): array
    {
        $this->cargarCacheCarreras();

        $filas = Excel::toArray([], $file)[0] ?? [];

        if (empty($filas)) {
            return [
                'nuevos'           => [],
                'duplicados'       => [],
                'errores'          => [],
                'total'            => 0,
                'error_estructura' => null,
            ];
        }

        $encabezados = array_map(function ($h) {
            $h = strtolower(trim($h ?? ''));
            $h = preg_replace('/\s*\*\s*$/', '', $h);
            return trim($h);
        }, $filas[0]);

        $errorEstructura = $this->validarEstructura($encabezados);
        if ($errorEstructura) {
            return [
                'nuevos'           => [],
                'duplicados'       => [],
                'errores'          => [],
                'total'            => 0,
                'error_estructura' => $errorEstructura,
            ];
        }

        $datos    = array_slice($filas, 1);
        $nuevos   = [];
        $duplicados = [];
        $errores  = [];

        foreach ($datos as $index => $fila) {
            $numeroFila = $index + 2;

            $filaLimpia = array_filter($fila, fn($v) => $v !== null && $v !== '');
            if (empty($filaLimpia)) {
                continue;
            }

            $registro = $this->mapearFila($fila, $encabezados);

            $erroresValidacion = $this->validarRegistro($registro, $numeroFila);
            if (!empty($erroresValidacion)) {
                $errores[] = [
                    'fila'    => $numeroFila,
                    'datos'   => $registro,
                    'errores' => $erroresValidacion,
                ];
                continue;
            }

            $carreraId = $this->buscarCarreraId($registro['carrera']);
            if (!$carreraId) {
                $errores[] = [
                    'fila'    => $numeroFila,
                    'datos'   => $registro,
                    'errores' => ["Carrera '{$registro['carrera']}' no encontrada"],
                ];
                continue;
            }
            $registro['carrera_id'] = $carreraId;

            // Buscar si ya existe un registro con este DNI Y esta carrera específica
            $registroExacto = Alumno::where('dni', $registro['dni'])
                ->where('carrera', $carreraId)
                ->first();

            // Buscar si existe en cualquier otra carrera (para informar al admin)
            $registroOtraCarrera = Alumno::where('dni', $registro['dni'])
                ->where('carrera', '!=', $carreraId)
                ->first();

            if ($registroExacto) {
                // Ya existe en esta misma carrera — es un duplicado real
                $duplicados[] = [
                    'fila'   => $numeroFila,
                    'datos'  => $registro,
                    'existente' => [
                        'id'     => $registroExacto->id,
                        'dni'    => $registroExacto->dni,
                        'nombre' => $registroExacto->nombre_completo,
                        'carrera' => $registroExacto->carreraRelacion?->nombre ?? 'N/A',
                    ],
                    'tipo_duplicado' => 'misma_carrera',
                ];
            } else {
                // No existe en esta carrera — puede ser alumno nuevo o segunda carrera
                $nuevos[] = [
                    'fila'  => $numeroFila,
                    'datos' => $registro,
                    // Flag para que el importador no cree usuario si ya tiene uno
                    'tiene_registro_otra_carrera' => $registroOtraCarrera !== null,
                    'id_registro_existente'       => $registroOtraCarrera?->id,
                ];
            }
        }

        return [
            'nuevos'               => $nuevos,
            'duplicados'           => $duplicados,
            'errores'              => $errores,
            'total'                => count($datos),
            'error_estructura'     => null,
            'encabezados_detectados' => array_filter($encabezados, fn($h) => $h !== ''),
        ];
    }

    private function validarEstructura(array $encabezados): ?string
    {
        $columnasFaltantes = [];
        foreach (self::COLUMNAS_REQUERIDAS as $columna) {
            if (!in_array($columna, $encabezados)) {
                $columnasFaltantes[] = $columna;
            }
        }

        if (!empty($columnasFaltantes)) {
            return "El archivo no tiene la estructura correcta para importar ALUMNOS. " .
                   "Columnas faltantes: " . implode(', ', $columnasFaltantes) . ". " .
                   "Por favor, descargue la plantilla correcta desde el botón 'Descargar Plantilla'.";
        }

        foreach (self::COLUMNAS_PROHIBIDAS as $columna) {
            if (in_array($columna, $encabezados)) {
                return "El archivo parece ser una plantilla de MATERIAS, no de ALUMNOS. " .
                       "Por favor, use la plantilla correcta para importar alumnos.";
            }
        }

        return null;
    }

    public function importar(array $datos, array $opciones = []): array
    {
        $actualizarDuplicados = $opciones['actualizar_duplicados'] ?? [];
        $crearUsuarios        = $opciones['crear_usuarios'] ?? true;

        $importados      = 0;
        $actualizados    = 0;
        $usuariosCreados = 0;
        $erroresImportacion = [];

        DB::beginTransaction();

        try {
            // ── Importar nuevos ────────────────────────────────────────────────
            foreach ($datos['nuevos'] as $item) {
                $registro    = $item['datos'];
                $annoIngreso = $registro['anno'] ?? date('Y');
                $cursoCalculado = $this->calcularCurso($annoIngreso, $registro['carrera_id']);

                $alumno = Alumno::create([
                    'dni'      => $registro['dni'],
                    'apellido' => $registro['apellido'],
                    'nombre'   => $registro['nombre'],
                    'email'    => $registro['email'] ?? null,
                    'telefono' => $registro['telefono'] ?? null,
                    'celular'  => $registro['celular'] ?? null,
                    'legajo'   => $registro['legajo'] ?? null,
                    'carrera'  => $registro['carrera_id'],
                    'anno'     => $annoIngreso,
                    'curso'    => $cursoCalculado,
                    'division' => $registro['division'] ?? '',
                    'turno'    => $registro['turno'] ?? '',
                    'fecha'    => $registro['fecha'] ?? now()->toDateString(),
                ]);

                $importados++;

                if ($crearUsuarios) {
                    // Si el alumno ya tiene un registro en otra carrera, NO crear
                    // un nuevo usuario — vincular el existente al nuevo registro.
                    $tieneRegistroOtraCarrera = $item['tiene_registro_otra_carrera'] ?? false;

                    if ($tieneRegistroOtraCarrera) {
                        // El usuario ya existe vinculado al primer registro (otra carrera).
                        // No hacemos nada: el login del alumno sigue funcionando con el
                        // user.alumno_id original. El nuevo registro queda sin usuario
                        // propio, lo cual es correcto — un alumno = un usuario.
                        \Log::info('Alumno con segunda carrera importado sin crear usuario duplicado', [
                            'alumno_id'   => $alumno->id,
                            'carrera_id'  => $registro['carrera_id'],
                            'dni'         => $registro['dni'],
                        ]);
                    } else {
                        $usuarioCreado = $this->crearUsuarioAlumno($alumno);
                        if ($usuarioCreado) {
                            $usuariosCreados++;
                        }
                    }
                }
            }

            // ── Actualizar duplicados seleccionados ────────────────────────────
            foreach ($datos['duplicados'] as $item) {
                $dni = $item['datos']['dni'];

                if (!in_array($dni, $actualizarDuplicados)) {
                    continue;
                }

                $registro = $item['datos'];

                // Actualizar el registro exacto de esta carrera
                $alumno = Alumno::where('dni', $dni)
                    ->where('carrera', $registro['carrera_id'])
                    ->first();

                if ($alumno) {
                    $annoIngreso    = $registro['anno'] ?? $alumno->anno;
                    $cursoCalculado = $this->calcularCurso($annoIngreso, $registro['carrera_id']);

                    $alumno->update([
                        'apellido' => $registro['apellido'],
                        'nombre'   => $registro['nombre'],
                        'email'    => $registro['email']    ?? $alumno->email,
                        'telefono' => $registro['telefono'] ?? $alumno->telefono,
                        'celular'  => $registro['celular']  ?? $alumno->celular,
                        'legajo'   => $registro['legajo']   ?? $alumno->legajo,
                        'anno'     => $annoIngreso,
                        'curso'    => $cursoCalculado,
                        'division' => $registro['division'] ?? $alumno->division,
                        'turno'    => $registro['turno']    ?? $alumno->turno,
                    ]);

                    $actualizados++;

                    if ($crearUsuarios && !$alumno->user) {
                        $usuarioCreado = $this->crearUsuarioAlumno($alumno);
                        if ($usuarioCreado) {
                            $usuariosCreados++;
                        }
                    }
                }
            }

            DB::commit();

            return [
                'success'         => true,
                'importados'      => $importados,
                'actualizados'    => $actualizados,
                'usuarios_creados' => $usuariosCreados,
                'errores'         => $erroresImportacion,
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success'         => false,
                'importados'      => 0,
                'actualizados'    => 0,
                'usuarios_creados' => 0,
                'errores'         => [$e->getMessage()],
            ];
        }
    }

    public function getCamposRequeridos(): array
    {
        return [
            'dni'      => 'DNI del alumno (único por persona)',
            'apellido' => 'Apellido del alumno',
            'nombre'   => 'Nombre del alumno',
            'carrera'  => 'Nombre exacto de la carrera',
        ];
    }

    public function getCamposOpcionales(): array
    {
        return [
            'email'    => 'Correo electrónico',
            'telefono' => 'Teléfono fijo',
            'celular'  => 'Teléfono celular',
            'legajo'   => 'Número de legajo',
            'anno'     => 'Año de ingreso (ej: 2024, 2025)',
            'division' => 'División',
            'turno'    => 'Turno (Mañana, Tarde, Noche)',
        ];
    }

    public function getNombreEntidad(): string
    {
        return 'Alumno';
    }

    private function mapearFila(array $fila, array $encabezados): array
    {
        $registro = [];
        foreach ($encabezados as $index => $campo) {
            $valor            = $fila[$index] ?? null;
            $registro[$campo] = is_string($valor) ? trim($valor) : $valor;
        }
        return $registro;
    }

    private function validarRegistro(array $registro, int $fila): array
    {
        $errores = [];

        if (empty($registro['dni'])) {
            $errores[] = "DNI es requerido";
        } elseif (!preg_match('/^[0-9]+$/', $registro['dni'])) {
            $errores[] = "DNI debe contener solo números";
        }

        if (empty($registro['apellido'])) {
            $errores[] = "Apellido es requerido";
        }

        if (empty($registro['nombre'])) {
            $errores[] = "Nombre es requerido";
        }

        if (empty($registro['carrera'])) {
            $errores[] = "Carrera es requerida";
        }

        if (!empty($registro['email']) && !filter_var($registro['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "Email no tiene formato válido";
        }

        if (!empty($registro['anno']) && (
            !is_numeric($registro['anno']) ||
            $registro['anno'] < 1900 ||
            $registro['anno'] > date('Y') + 1
        )) {
            $errores[] = "Año de ingreso debe ser un año válido (ej: 2024, 2025)";
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

    private function buscarCarreraId(string $nombreCarrera): ?int
    {
        $nombreNormalizado = mb_strtolower(trim($nombreCarrera));
        return $this->carrerasCache[$nombreNormalizado] ?? null;
    }

    private function crearUsuarioAlumno(Alumno $alumno): bool
    {
        // No crear usuario si ya existe uno con este DNI
        $existe = User::where('dni', $alumno->dni)
            ->orWhere('alumno_id', $alumno->id)
            ->exists();

        if ($existe) {
            return false;
        }

        User::create([
            'dni'      => $alumno->dni,
            'nombre'   => $alumno->nombre . ' ' . $alumno->apellido,
            'email'    => $alumno->email,
            'clave'    => Hash::make($alumno->dni),
            'tipo'     => 4,
            'activo'   => true,
            'alumno_id' => $alumno->id,
        ]);

        return true;
    }

    private function calcularCurso(int $annoIngreso, int $carreraId): int
    {
        $annoActual     = (int) date('Y');
        $cursoCalculado = $annoActual - $annoIngreso + 1;

        $maxAnno          = Materia::where('carrera', $carreraId)->max('anno');
        $duracionCarrera  = $maxAnno ? (int) $maxAnno : 3;

        if ($cursoCalculado < 1) return 1;
        if ($cursoCalculado > $duracionCarrera) return $duracionCarrera;

        return $cursoCalculado;
    }
}