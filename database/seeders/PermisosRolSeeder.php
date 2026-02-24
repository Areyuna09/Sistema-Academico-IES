<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisosRolSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Definiciones de permisos (nombre legible + categoría)
        $definiciones = [
            // Permisos de acción
            ['clave' => 'puedeCrear',                  'nombre_legible' => 'Crear registros',              'categoria' => 'accion'],
            ['clave' => 'puedeModificar',              'nombre_legible' => 'Modificar registros',          'categoria' => 'accion'],
            ['clave' => 'puedeEliminar',               'nombre_legible' => 'Eliminar registros',           'categoria' => 'accion'],
            ['clave' => 'puedeGestionarUsuarios',      'nombre_legible' => 'Gestionar usuarios',           'categoria' => 'accion'],
            ['clave' => 'puedeGestionarInscripciones', 'nombre_legible' => 'Gestionar inscripciones',      'categoria' => 'accion'],
            ['clave' => 'puedeGestionarMesas',         'nombre_legible' => 'Gestionar mesas de examen',    'categoria' => 'accion'],
            ['clave' => 'puedeTomarAsistencias',       'nombre_legible' => 'Tomar asistencias',            'categoria' => 'accion'],
            ['clave' => 'puedeCargarNotas',            'nombre_legible' => 'Cargar notas',                 'categoria' => 'accion'],
            ['clave' => 'puedeAprobarNotas',           'nombre_legible' => 'Aprobar notas finales',        'categoria' => 'accion'],
            ['clave' => 'puedeRevisarLegajos',         'nombre_legible' => 'Revisar legajos',              'categoria' => 'accion'],
            ['clave' => 'puedeSupervisar',             'nombre_legible' => 'Supervisar',                   'categoria' => 'accion'],

            // Acceso a módulos del panel admin
            ['clave' => 'acceso:admin_usuarios',         'nombre_legible' => 'Usuarios',              'categoria' => 'acceso'],
            ['clave' => 'acceso:admin_carreras',         'nombre_legible' => 'Carreras',              'categoria' => 'acceso'],
            ['clave' => 'acceso:admin_planes_estudio',   'nombre_legible' => 'Planes de Estudio',     'categoria' => 'acceso'],
            ['clave' => 'acceso:admin_periodos',         'nombre_legible' => 'Períodos Lectivos',     'categoria' => 'acceso'],
            ['clave' => 'acceso:admin_inscripciones',    'nombre_legible' => 'Inscripciones',         'categoria' => 'acceso'],
            ['clave' => 'acceso:admin_mesas',            'nombre_legible' => 'Mesas de Examen',       'categoria' => 'acceso'],
            ['clave' => 'acceso:admin_correlativas',     'nombre_legible' => 'Correlativas',          'categoria' => 'acceso'],
            ['clave' => 'acceso:admin_excepciones',      'nombre_legible' => 'Excepciones',           'categoria' => 'acceso'],
            ['clave' => 'acceso:admin_solicitudes_email', 'nombre_legible' => 'Solicitudes de Email', 'categoria' => 'acceso'],
            ['clave' => 'acceso:admin_modulos',          'nombre_legible' => 'Módulos del Sistema',   'categoria' => 'acceso'],
            ['clave' => 'acceso:admin_importacion',      'nombre_legible' => 'Importación Masiva',    'categoria' => 'acceso'],
        ];

        // Upsert definiciones
        foreach ($definiciones as &$def) {
            $def['created_at'] = $now;
            $def['updated_at'] = $now;
        }

        DB::table('permisos_definicion')->upsert(
            $definiciones,
            ['clave'],
            ['nombre_legible', 'categoria', 'updated_at']
        );

        // Permisos por rol: qué tipos de usuario tienen cada permiso activo
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

            // Acceso a módulos del panel admin
            'acceso:admin_usuarios'         => [1, 7],
            'acceso:admin_carreras'         => [1],
            'acceso:admin_planes_estudio'   => [1],
            'acceso:admin_periodos'         => [1, 7],
            'acceso:admin_inscripciones'    => [1, 7, 8],
            'acceso:admin_mesas'            => [1, 7, 8],
            'acceso:admin_correlativas'     => [1],
            'acceso:admin_excepciones'      => [1, 7],
            'acceso:admin_solicitudes_email' => [1],
            'acceso:admin_modulos'          => [1],
            'acceso:admin_importacion'      => [1],
        ];

        $tiposUsuario = [1, 2, 3, 4, 5, 6, 7, 8];
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
