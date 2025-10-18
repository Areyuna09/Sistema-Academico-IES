<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionesController extends Controller
{
    /**
     * Obtener notificaciones del usuario autenticado
     */
    public function index(Request $request)
    {
        $query = Notificacion::where('user_id', auth()->id());

        // Filtro de leídas/no leídas
        if ($request->filled('leidas')) {
            if ($request->leidas === 'true') {
                $query->leidas();
            } else {
                $query->noLeidas();
            }
        }

        $notificaciones = $query->orderBy('created_at', 'desc')
                               ->limit(50)
                               ->get();

        return response()->json([
            'notificaciones' => $notificaciones,
            'no_leidas_count' => Notificacion::where('user_id', auth()->id())
                                           ->noLeidas()
                                           ->count(),
        ]);
    }

    /**
     * Obtener solo el contador de no leídas (para polling)
     */
    public function contador()
    {
        return response()->json([
            'count' => Notificacion::where('user_id', auth()->id())
                                  ->noLeidas()
                                  ->count(),
        ]);
    }

    /**
     * Marcar una notificación como leída
     */
    public function marcarLeida(Notificacion $notificacion)
    {
        // Verificar que la notificación pertenece al usuario
        if ($notificacion->user_id !== auth()->id()) {
            abort(403);
        }

        $notificacion->marcarComoLeida();

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída',
        ]);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function marcarTodasLeidas()
    {
        Notificacion::where('user_id', auth()->id())
                   ->noLeidas()
                   ->update([
                       'leida' => true,
                       'fecha_lectura' => now(),
                   ]);

        return response()->json([
            'success' => true,
            'message' => 'Todas las notificaciones marcadas como leídas',
        ]);
    }

    /**
     * Eliminar una notificación
     */
    public function destroy(Notificacion $notificacion)
    {
        // Verificar que la notificación pertenece al usuario
        if ($notificacion->user_id !== auth()->id()) {
            abort(403);
        }

        $notificacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notificación eliminada',
        ]);
    }

    /**
     * Eliminar todas las notificaciones leídas
     */
    public function limpiarLeidas()
    {
        Notificacion::where('user_id', auth()->id())
                   ->leidas()
                   ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notificaciones leídas eliminadas',
        ]);
    }
}
