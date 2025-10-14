<?php
namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificacionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $notificaciones = Notificacion::query()
            ->when($search, fn($q) =>
                $q->where('nombre_plantilla', 'like', "%$search%")
                  ->orWhere('disparador', 'like', "%$search%")
            )
            ->orderBy('created_at', 'asc')
            ->get();

        return Inertia::render('Notificaciones/Notificaciones', [
            'notificaciones' => $notificaciones,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_plantilla' => 'required|string|max:255',
            'disparador' => 'required|string|max:255',
            'publico_objetivo' => 'required|string|max:255',
        ]);

        Notificacion::create($request->all());

        return redirect()->back()->with('success', 'Notificación creada');
    }

    public function update(Request $request, $id)
    {
        $notificacion = Notificacion::findOrFail($id);
        $notificacion->update($request->all());

        return redirect()->back()->with('success', 'Notificación actualizada');
    }

    public function destroy($id)
    {
        Notificacion::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Notificación eliminada');
    }
}
