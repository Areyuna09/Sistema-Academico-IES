<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\SolicitudCambioEmail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class SolicitudesCambioEmailController extends Controller
{
    /**
     * Mostrar listado de solicitudes
     */
    public function index(Request $request): Response
    {
        $query = SolicitudCambioEmail::with(['usuario', 'revisor'])
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function($q) use ($search) {
                $q->where('dni', 'like', "%{$search}%")
                  ->orWhere('email_actual', 'like', "%{$search}%")
                  ->orWhere('email_nuevo', 'like', "%{$search}%");
            });
        }

        $solicitudes = $query->paginate(15)->withQueryString();

        // Contar pendientes para el badge
        $pendientesCount = SolicitudCambioEmail::where('estado', 'pendiente')->count();

        return Inertia::render('Admin/SolicitudesCambioEmail/Index', [
            'solicitudes' => $solicitudes,
            'pendientesCount' => $pendientesCount,
            'filtros' => $request->only(['estado', 'buscar']),
        ]);
    }

    /**
     * Aprobar solicitud y actualizar email del usuario
     */
    public function aprobar(Request $request, SolicitudCambioEmail $solicitud): RedirectResponse
    {
        $request->validate([
            'comentario' => 'nullable|string|max:500',
        ]);

        $solicitud->load('usuario');

        if ($solicitud->estado !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        DB::beginTransaction();

        try {
            $user = $request->user();

            // Actualizar email del usuario
            $solicitud->usuario->update([
                'email' => $solicitud->email_nuevo,
            ]);

            // Actualizar solicitud
            $solicitud->update([
                'estado' => 'aprobada',
                'revisado_por' => $user->id,
                'fecha_revision' => now(),
                'comentario_admin' => $request->comentario,
            ]);

            // Notificar al usuario solicitante
            try {
                Notificacion::crear(
                    $solicitud->user_id,
                    'email_actualizado',
                    'Email Actualizado',
                    "Tu solicitud de cambio de email ha sido aprobada. Tu nuevo email es: {$solicitud->email_nuevo}",
                    [
                        'icono' => 'bx-check-circle',
                        'color' => 'green',
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Error al notificar usuario sobre aprobación de cambio de email: ' . $e->getMessage());
            }

            DB::commit();

            Log::info('Solicitud de cambio de email aprobada', [
                'solicitud_id' => $solicitud->id,
                'user_id' => $solicitud->user_id,
                'email_anterior' => $solicitud->email_actual,
                'email_nuevo' => $solicitud->email_nuevo,
                'aprobado_por' => $user->id,
            ]);

            return back()->with('success', 'Solicitud aprobada. El email del usuario ha sido actualizado.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al aprobar solicitud de cambio de email: ' . $e->getMessage());
            return back()->with('error', 'Error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar solicitud
     */
    public function rechazar(Request $request, SolicitudCambioEmail $solicitud): RedirectResponse
    {
        $request->validate([
            'comentario' => 'required|string|max:500',
        ], [
            'comentario.required' => 'Debes proporcionar un motivo para rechazar la solicitud.',
        ]);

        $solicitud->load('usuario');

        if ($solicitud->estado !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        try {
            $user = $request->user();

            // Actualizar solicitud
            $solicitud->update([
                'estado' => 'rechazada',
                'revisado_por' => $user->id,
                'fecha_revision' => now(),
                'comentario_admin' => $request->comentario,
            ]);

            // Notificar al usuario solicitante
            try {
                Notificacion::crear(
                    $solicitud->user_id,
                    'email_rechazado',
                    'Solicitud de Cambio de Email Rechazada',
                    "Tu solicitud de cambio de email ha sido rechazada. Motivo: {$request->comentario}",
                    [
                        'icono' => 'bx-x-circle',
                        'color' => 'red',
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Error al notificar usuario sobre rechazo de cambio de email: ' . $e->getMessage());
            }

            Log::info('Solicitud de cambio de email rechazada', [
                'solicitud_id' => $solicitud->id,
                'user_id' => $solicitud->user_id,
                'rechazado_por' => $user->id,
                'motivo' => $request->comentario,
            ]);

            return back()->with('success', 'Solicitud rechazada.');

        } catch (\Exception $e) {
            Log::error('Error al rechazar solicitud de cambio de email: ' . $e->getMessage());
            return back()->with('error', 'Error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar solicitud
     */
    public function destroy(SolicitudCambioEmail $solicitud): RedirectResponse
    {
        try {
            $solicitudId = $solicitud->id;
            $solicitud->delete();

            Log::info('Solicitud de cambio de email eliminada', [
                'solicitud_id' => $solicitudId,
                'eliminado_por' => auth()->id(),
            ]);

            return back()->with('success', 'Solicitud eliminada.');

        } catch (\Exception $e) {
            Log::error('Error al eliminar solicitud de cambio de email: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar la solicitud.');
        }
    }
}
