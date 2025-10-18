<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionModulo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConfiguracionModulosController extends Controller
{
    /**
     * Mostrar panel de configuración de módulos
     */
    public function index()
    {
        $modulos = ConfiguracionModulo::orderBy('categoria')
            ->orderBy('orden')
            ->get()
            ->groupBy('categoria');

        // Nombres legibles para las categorías
        $categorias = [
            'modulos' => 'Módulos Principales',
            'administracion' => 'Gestión Administrativa',
            'comunicacion' => 'Notificaciones y Comunicación',
            'validaciones' => 'Validaciones y Reglas',
            'permisos' => 'Permisos y Accesos',
            'reportes' => 'Reportes y Estadísticas',
            'avanzado' => 'Funcionalidades Avanzadas',
        ];

        return Inertia::render('Admin/ConfiguracionModulos/Index', [
            'modulos' => $modulos,
            'categorias' => $categorias,
            'estadisticas' => [
                'total' => ConfiguracionModulo::count(),
                'activos' => ConfiguracionModulo::where('activo', true)->count(),
                'inactivos' => ConfiguracionModulo::where('activo', false)->count(),
            ],
        ]);
    }

    /**
     * Actualizar estado de un módulo
     */
    public function toggle(Request $request, $id)
    {
        $modulo = ConfiguracionModulo::findOrFail($id);

        $modulo->activo = !$modulo->activo;
        $modulo->save();

        \Log::info('Módulo actualizado', [
            'modulo' => $modulo->clave,
            'nuevo_estado' => $modulo->activo ? 'activo' : 'inactivo',
            'usuario' => auth()->user()->nombre,
        ]);

        return back()->with('success', $modulo->activo
            ? "Módulo \"{$modulo->nombre}\" activado correctamente"
            : "Módulo \"{$modulo->nombre}\" desactivado correctamente"
        );
    }

    /**
     * Actualizar múltiples módulos a la vez
     */
    public function updateBatch(Request $request)
    {
        $validated = $request->validate([
            'modulos' => 'required|array',
            'modulos.*.id' => 'required|exists:configuracion_modulos,id',
            'modulos.*.activo' => 'required|boolean',
        ]);

        foreach ($validated['modulos'] as $moduloData) {
            $modulo = ConfiguracionModulo::find($moduloData['id']);
            $modulo->activo = $moduloData['activo'];
            $modulo->save();
        }

        \Log::info('Actualización masiva de módulos', [
            'cantidad' => count($validated['modulos']),
            'usuario' => auth()->user()->nombre,
        ]);

        return back()->with('success', 'Configuración de módulos actualizada correctamente');
    }

    /**
     * Restablecer configuración por defecto
     */
    public function resetDefaults()
    {
        \Artisan::call('db:seed', ['--class' => 'ConfiguracionModulosSeeder']);

        \Log::warning('Configuración de módulos restablecida a valores por defecto', [
            'usuario' => auth()->user()->nombre,
        ]);

        return back()->with('success', 'Configuración restablecida a valores por defecto');
    }
}
