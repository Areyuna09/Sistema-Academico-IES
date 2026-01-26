<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

trait ChecksPermissions
{
    /**
     * Verificar si el usuario puede crear
     */
    protected function autorizarCrear(Request $request): void
    {
        $user = $request->user();
        
        if (!$user || !$user->puedeCrear()) {
            abort(403, 'No tienes permisos para crear registros.');
        }
    }

    /**
     * Verificar si el usuario puede modificar
     */
    protected function autorizarModificar(Request $request): void
    {
        $user = $request->user();
        
        if (!$user || !$user->puedeModificar()) {
            abort(403, 'No tienes permisos para modificar registros.');
        }
    }

    /**
     * Verificar si el usuario puede eliminar
     */
    protected function autorizarEliminar(Request $request): void
    {
        $user = $request->user();
        
        if (!$user || !$user->puedeEliminar()) {
            abort(403, 'No tienes permisos para eliminar registros.');
        }
    }

    /**
     * Verificar si el usuario es solo lectura
     */
    protected function verificarNoSoloLectura(Request $request): void
    {
        $user = $request->user();
        
        if ($user && $user->esSoloLectura()) {
            abort(403, 'Tu rol solo tiene permisos de lectura. No puedes modificar información.');
        }
    }

    /**
     * Verificar permisos de gestión de usuarios
     */
    protected function autorizarGestionUsuarios(Request $request): void
    {
        $user = $request->user();
        
        if (!$user || !$user->puedeGestionarUsuarios()) {
            abort(403, 'No tienes permisos para gestionar usuarios.');
        }
    }

    /**
     * Verificar permisos de gestión de inscripciones
     */
    protected function autorizarGestionInscripciones(Request $request): void
    {
        $user = $request->user();
        
        if (!$user || !$user->puedeGestionarInscripciones()) {
            abort(403, 'No tienes permisos para gestionar inscripciones.');
        }
    }

    /**
     * Verificar permisos de gestión de mesas
     */
    protected function autorizarGestionMesas(Request $request): void
    {
        $user = $request->user();
        
        if (!$user || !$user->puedeGestionarMesas()) {
            abort(403, 'No tienes permisos para gestionar mesas de examen.');
        }
    }

    /**
     * Verificar permisos de tomar asistencias
     */
    protected function autorizarTomarAsistencias(Request $request): void
    {
        $user = $request->user();
        
        if (!$user || !$user->puedeTomarAsistencias()) {
            abort(403, 'No tienes permisos para tomar asistencias.');
        }
    }

    /**
     * Verificar permisos de cargar notas
     */
    protected function autorizarCargarNotas(Request $request): void
    {
        $user = $request->user();
        
        if (!$user || !$user->puedeCargarNotas()) {
            abort(403, 'No tienes permisos para cargar notas.');
        }
    }

    /**
     * Verificar permisos de aprobar notas
     */
    protected function autorizarAprobarNotas(Request $request): void
    {
        $user = $request->user();
        
        if (!$user || !$user->puedeAprobarNotas()) {
            abort(403, 'No tienes permisos para aprobar notas finales.');
        }
    }

    /**
     * Retornar respuesta JSON de error de permisos
     */
    protected function errorPermisos(string $mensaje = 'No tienes permisos para realizar esta acción.'): Response
    {
        return response()->json([
            'success' => false,
            'message' => $mensaje,
            'error' => 'FORBIDDEN',
        ], 403);
    }
}