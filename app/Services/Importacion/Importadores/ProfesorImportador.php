<?php

namespace App\Services\Importacion\Importadores;

use App\Models\Profesor;
use App\Models\Carrera;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class ProfesorImportador implements ImportadorInterface
{
    private array $carrerasCache = [];

    public function analizar(UploadedFile $file): array
    {
        $this->cargarCacheCarreras();

        $filas = Excel::toArray([], $file)[0] ?? [];

        if (empty($filas)) {
            return [
                'nuevos' => [],
                'duplicados' => [],
                'errores' => [],
                'total' => 0,
            ];
        }

        // Primera fila es encabezado - limpiar asteriscos y espacios
        $encabezados = array_map(function($h) {
            $h = strtolower(trim($h));
            $h = preg_replace('/\s*\*\s*$/', '', $h);
            return trim($h);
        }, $filas[0]);
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

            // Buscar carrera por nombre (opcional para profesor)
            $carreraId = null;
            if (!empty($registro['carrera'])) {
                $carreraId = $this->buscarCarreraId($registro['carrera']);
                if (!$carreraId) {
                    $errores[] = [
                        'fila' => $numeroFila,
                        'datos' => $registro,
                        'errores' => ["Carrera '{$registro['carrera']}' no encontrada"],
                    ];
                    continue;
                }
            }
            $registro['carrera_id'] = $carreraId;

            // Verificar duplicados por DNI
            $profesorExistente = Profesor::where('dni', $registro['dni'])->first();

            if ($profesorExistente) {
                $duplicados[] = [
                    'fila' => $numeroFila,
                    'datos' => $registro,
                    'existente' => [
                        'id' => $profesorExistente->id,
                        'dni' => $profesorExistente->dni,
                        'nombre' => $profesorExistente->nombre_completo,
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
        ];
    }

    public function importar(array $datos, array $opciones = []): array
    {
        $actualizarDuplicados = $opciones['actualizar_duplicados'] ?? [];
        $crearUsuarios = $opciones['crear_usuarios'] ?? true;

        $importados = 0;
        $actualizados = 0;
        $usuariosCreados = 0;
        $erroresImportacion = [];

        DB::beginTransaction();

        try {
            // Importar nuevos
            foreach ($datos['nuevos'] as $item) {
                $registro = $item['datos'];

                $profesor = Profesor::create([
                    'dni' => $registro['dni'],
                    'apellido' => $registro['apellido'],
                    'nombre' => $registro['nombre'],
                    'email' => $registro['email'] ?? null,
                    'carrera' => $registro['carrera_id'],
                    'division' => $registro['division'] ?? '',
                ]);

                $importados++;

                if ($crearUsuarios) {
                    $usuarioCreado = $this->crearUsuarioProfesor($profesor);
                    if ($usuarioCreado) {
                        $usuariosCreados++;
                    }
                }
            }

            // Actualizar duplicados seleccionados
            foreach ($datos['duplicados'] as $item) {
                $dni = $item['datos']['dni'];

                if (!in_array($dni, $actualizarDuplicados)) {
                    continue;
                }

                $registro = $item['datos'];
                $profesor = Profesor::where('dni', $dni)->first();

                if ($profesor) {
                    $profesor->update([
                        'apellido' => $registro['apellido'],
                        'nombre' => $registro['nombre'],
                        'email' => $registro['email'] ?? $profesor->email,
                        'carrera' => $registro['carrera_id'] ?? $profesor->carrera,
                        'division' => $registro['division'] ?? $profesor->division,
                    ]);

                    $actualizados++;

                    if ($crearUsuarios && !$profesor->user) {
                        $usuarioCreado = $this->crearUsuarioProfesor($profesor);
                        if ($usuarioCreado) {
                            $usuariosCreados++;
                        }
                    }
                }
            }

            DB::commit();

            return [
                'success' => true,
                'importados' => $importados,
                'actualizados' => $actualizados,
                'usuarios_creados' => $usuariosCreados,
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
            'dni' => 'DNI del profesor (único)',
            'apellido' => 'Apellido del profesor',
            'nombre' => 'Nombre del profesor',
        ];
    }

    public function getCamposOpcionales(): array
    {
        return [
            'email' => 'Correo electrónico',
            'carrera' => 'Nombre de la carrera (opcional)',
            'division' => 'División',
        ];
    }

    public function getNombreEntidad(): string
    {
        return 'Profesor';
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

        if (!empty($registro['email']) && !filter_var($registro['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "Email no tiene formato válido";
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

    private function crearUsuarioProfesor(Profesor $profesor): bool
    {
        $existe = User::where('dni', $profesor->dni)
            ->orWhere('profesor_id', $profesor->id)
            ->exists();

        if ($existe) {
            return false;
        }

        User::create([
            'dni' => $profesor->dni,
            'nombre' => $profesor->nombre . ' ' . $profesor->apellido,
            'email' => $profesor->email,
            'clave' => Hash::make($profesor->dni),
            'tipo' => 3, // Profesor
            'activo' => true,
            'profesor_id' => $profesor->id,
        ]);

        return true;
    }
}
