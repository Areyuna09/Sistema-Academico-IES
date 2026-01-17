<?php

namespace App\Services\Importacion;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Servicio para ejecutar Stored Procedures de importación
 *
 * Ventajas de usar SPs:
 * - Operaciones atómicas en la BD
 * - Menor tráfico de red (lógica en BD)
 * - Mejor performance para importaciones masivas
 */
class StoredProcedureService
{
    /**
     * Crea un usuario para un alumno usando SP
     */
    public function crearUsuarioAlumno(
        int $alumnoId,
        string $dni,
        string $nombre,
        string $apellido,
        ?string $email = null,
        ?string $password = null
    ): array {
        $passwordHash = Hash::make($password ?? $dni);

        DB::statement('CALL sp_crear_usuario_alumno(?, ?, ?, ?, ?, ?, @usuario_id, @resultado)', [
            $alumnoId,
            $dni,
            $nombre,
            $apellido,
            $email,
            $passwordHash,
        ]);

        $result = DB::select('SELECT @usuario_id as usuario_id, @resultado as resultado')[0];

        return [
            'usuario_id' => $result->usuario_id,
            'resultado' => $result->resultado,
            'creado' => $result->resultado === 'CREADO',
        ];
    }

    /**
     * Crea un usuario para un profesor usando SP
     */
    public function crearUsuarioProfesor(
        int $profesorId,
        string $dni,
        string $nombre,
        string $apellido,
        ?string $email = null,
        ?string $password = null
    ): array {
        $passwordHash = Hash::make($password ?? $dni);

        DB::statement('CALL sp_crear_usuario_profesor(?, ?, ?, ?, ?, ?, @usuario_id, @resultado)', [
            $profesorId,
            $dni,
            $nombre,
            $apellido,
            $email,
            $passwordHash,
        ]);

        $result = DB::select('SELECT @usuario_id as usuario_id, @resultado as resultado')[0];

        return [
            'usuario_id' => $result->usuario_id,
            'resultado' => $result->resultado,
            'creado' => $result->resultado === 'CREADO',
        ];
    }

    /**
     * Importa un alumno completo (alumno + usuario) usando SP
     *
     * Esta es la forma más eficiente de importar ya que todo
     * se ejecuta en una sola transacción en la BD
     */
    public function importarAlumnoCompleto(
        string $dni,
        string $apellido,
        string $nombre,
        ?string $email,
        ?string $telefono,
        ?string $celular,
        ?string $legajo,
        int $carreraId,
        ?int $anno,
        bool $crearUsuario = true,
        ?string $password = null
    ): array {
        $passwordHash = Hash::make($password ?? $dni);

        DB::statement(
            'CALL sp_importar_alumno_completo(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @alumno_id, @usuario_id, @resultado)',
            [
                $dni,
                $apellido,
                $nombre,
                $email,
                $telefono,
                $celular,
                $legajo,
                $carreraId,
                $anno,
                $passwordHash,
                $crearUsuario,
            ]
        );

        $result = DB::select('SELECT @alumno_id as alumno_id, @usuario_id as usuario_id, @resultado as resultado')[0];

        return [
            'alumno_id' => $result->alumno_id,
            'usuario_id' => $result->usuario_id,
            'resultado' => $result->resultado,
            'success' => in_array($result->resultado, ['CREADO_CON_USUARIO', 'CREADO_SIN_USUARIO']),
            'usuario_creado' => $result->resultado === 'CREADO_CON_USUARIO',
        ];
    }

    /**
     * Importa un profesor completo (profesor + usuario) usando SP
     */
    public function importarProfesorCompleto(
        string $dni,
        string $apellido,
        string $nombre,
        ?string $email,
        ?int $carreraId,
        bool $crearUsuario = true,
        ?string $password = null
    ): array {
        $passwordHash = Hash::make($password ?? $dni);

        DB::statement(
            'CALL sp_importar_profesor_completo(?, ?, ?, ?, ?, ?, ?, @profesor_id, @usuario_id, @resultado)',
            [
                $dni,
                $apellido,
                $nombre,
                $email,
                $carreraId,
                $passwordHash,
                $crearUsuario,
            ]
        );

        $result = DB::select('SELECT @profesor_id as profesor_id, @usuario_id as usuario_id, @resultado as resultado')[0];

        return [
            'profesor_id' => $result->profesor_id,
            'usuario_id' => $result->usuario_id,
            'resultado' => $result->resultado,
            'success' => in_array($result->resultado, ['CREADO_CON_USUARIO', 'CREADO_SIN_USUARIO']),
            'usuario_creado' => $result->resultado === 'CREADO_CON_USUARIO',
        ];
    }

    /**
     * Verifica si los SPs están disponibles en la BD
     */
    public function verificarDisponibilidad(): array
    {
        $sps = [
            'sp_crear_usuario_alumno',
            'sp_crear_usuario_profesor',
            'sp_importar_alumno_completo',
            'sp_importar_profesor_completo',
        ];

        $disponibles = [];
        $faltantes = [];

        foreach ($sps as $sp) {
            $existe = DB::select(
                "SELECT COUNT(*) as count FROM information_schema.ROUTINES
                 WHERE ROUTINE_TYPE = 'PROCEDURE'
                 AND ROUTINE_SCHEMA = DATABASE()
                 AND ROUTINE_NAME = ?",
                [$sp]
            )[0]->count > 0;

            if ($existe) {
                $disponibles[] = $sp;
            } else {
                $faltantes[] = $sp;
            }
        }

        return [
            'todos_disponibles' => empty($faltantes),
            'disponibles' => $disponibles,
            'faltantes' => $faltantes,
        ];
    }
}
