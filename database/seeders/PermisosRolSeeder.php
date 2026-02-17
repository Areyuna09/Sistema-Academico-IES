<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisosRolSeeder extends Seeder
{
    public function run(): void
    {
        // Permisos y los tipos de usuario que los tienen activos
        $permisosActivos = [
            'puedeCrear'                  => [1, 7, 8],
            'puedeModificar'              => [1, 7, 8],
            'puedeEliminar'               => [1, 7],
            'puedeGestionarUsuarios'      => [1, 7],
            'puedeGestionarInscripciones' => [1, 7, 8],
            'puedeGestionarMesas'         => [1, 7, 8],
            'puedeTomarAsistencias'       => [1, 3, 8],
            'puedeCargarNotas'            => [1, 3],
            'puedeAprobarNotas'           => [1, 7],
            'puedeRevisarLegajos'         => [1, 5],
            'puedeSupervisar'             => [1, 6],
        ];

        $tiposUsuario = [1, 2, 3, 4, 5, 6, 7, 8];
        $now = now();
        $rows = [];

        foreach ($permisosActivos as $permiso => $rolesActivos) {
            foreach ($tiposUsuario as $tipo) {
                $rows[] = [
                    'permiso'      => $permiso,
                    'tipo_usuario' => $tipo,
                    'activo'       => in_array($tipo, $rolesActivos),
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
        }

        // Upsert para poder re-ejecutar sin duplicados
        DB::table('permisos_rol')->upsert(
            $rows,
            ['permiso', 'tipo_usuario'],
            ['activo', 'updated_at']
        );
    }
}
