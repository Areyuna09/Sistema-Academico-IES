<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ConfiguracionController extends Controller
{
    /**
     * Mostrar vista de configuración
     */
    public function index()
    {
        $configuracion = Configuracion::get();

        return Inertia::render('Admin/Configuracion/Index', [
            'configuracion' => [
                'id' => $configuracion->id,
                'nombre_institucion' => $configuracion->nombre_institucion,
                'logo_path' => $configuracion->logo_path,
                'logo_url' => $configuracion->logo_path ? Storage::url($configuracion->logo_path) : null,
                'logo_dark_path' => $configuracion->logo_dark_path,
                'logo_dark_url' => $configuracion->logo_dark_path ? Storage::url($configuracion->logo_dark_path) : null,
                'direccion' => $configuracion->direccion,
                'telefono' => $configuracion->telefono,
                'email' => $configuracion->email,
                'sitio_web' => $configuracion->sitio_web,
                'footer_documentos' => $configuracion->footer_documentos,
                'firma_digital_path' => $configuracion->firma_digital_path,
                'firma_digital_url' => $configuracion->firma_digital_path ? Storage::url($configuracion->firma_digital_path) : null,
                'cargo_firma' => $configuracion->cargo_firma,
                'horarios_atencion' => $configuracion->horarios_atencion,
            ],
        ]);
    }

    /**
     * Mostrar formulario de configuración
     */
    public function edit()
    {
        $configuracion = Configuracion::get();

        return Inertia::render('Admin/Configuracion/Edit', [
            'configuracion' => [
                'id' => $configuracion->id,
                'nombre_institucion' => $configuracion->nombre_institucion,
                'logo_path' => $configuracion->logo_path,
                'logo_url' => $configuracion->logo_path ? Storage::url($configuracion->logo_path) : null,
                'logo_dark_path' => $configuracion->logo_dark_path,
                'logo_dark_url' => $configuracion->logo_dark_path ? Storage::url($configuracion->logo_dark_path) : null,
                'direccion' => $configuracion->direccion,
                'telefono' => $configuracion->telefono,
                'email' => $configuracion->email,
                'sitio_web' => $configuracion->sitio_web,
                'footer_documentos' => $configuracion->footer_documentos,
                'firma_digital_path' => $configuracion->firma_digital_path,
                'firma_digital_url' => $configuracion->firma_digital_path ? Storage::url($configuracion->firma_digital_path) : null,
                'cargo_firma' => $configuracion->cargo_firma,
                'horarios_atencion' => $configuracion->horarios_atencion,
            ],
        ]);
    }

    /**
     * Actualizar configuración
     */
    public function update(Request $request)
    {
        $configuracion = Configuracion::get();

        $validated = $request->validate([
            'nombre_institucion' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:200',
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s\-\+\(\)]+$/'],
            'email' => 'nullable|email|max:100',
            'sitio_web' => 'nullable|url|max:100',
            'footer_documentos' => 'nullable|string|max:500',
            'cargo_firma' => 'nullable|string|max:100',
            'horarios_atencion' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'logo_dark' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'firma_digital' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'telefono.regex' => 'El teléfono solo puede contener números, espacios, guiones, paréntesis y signo +.',
        ]);

        // Subir logo si se proporcionó
        if ($request->hasFile('logo')) {
            // Eliminar logo anterior si existe
            if ($configuracion->logo_path) {
                Storage::disk('public')->delete($configuracion->logo_path);
            }

            $logoPath = $request->file('logo')->store('configuracion/logos', 'public');
            $validated['logo_path'] = $logoPath;
        }

        // Subir logo oscuro si se proporcionó
        if ($request->hasFile('logo_dark')) {
            // Eliminar logo oscuro anterior si existe
            if ($configuracion->logo_dark_path) {
                Storage::disk('public')->delete($configuracion->logo_dark_path);
            }

            $logoDarkPath = $request->file('logo_dark')->store('configuracion/logos', 'public');
            $validated['logo_dark_path'] = $logoDarkPath;
        }

        // Subir firma digital si se proporcionó
        if ($request->hasFile('firma_digital')) {
            // Eliminar firma anterior si existe
            if ($configuracion->firma_digital_path) {
                Storage::disk('public')->delete($configuracion->firma_digital_path);
            }

            $firmaPath = $request->file('firma_digital')->store('configuracion/firmas', 'public');
            $validated['firma_digital_path'] = $firmaPath;
        }

        $configuracion->update($validated);

        // Redirigir de vuelta a edit con mensaje de éxito y configuración actualizada
        return redirect()->route('admin.configuracion.edit')
            ->with('success', 'Configuración actualizada exitosamente.');
    }

    /**
     * Eliminar logo
     */
    public function deleteLogo()
    {
        $configuracion = Configuracion::get();

        if ($configuracion->logo_path) {
            Storage::disk('public')->delete($configuracion->logo_path);
            $configuracion->update(['logo_path' => null]);
        }

        return back()->with('success', 'Logo eliminado exitosamente.');
    }

    /**
     * Eliminar logo oscuro
     */
    public function deleteLogoDark()
    {
        $configuracion = Configuracion::get();

        if ($configuracion->logo_dark_path) {
            Storage::disk('public')->delete($configuracion->logo_dark_path);
            $configuracion->update(['logo_dark_path' => null]);
        }

        return back()->with('success', 'Logo oscuro eliminado exitosamente.');
    }

    /**
     * Eliminar firma digital
     */
    public function deleteFirma()
    {
        $configuracion = Configuracion::get();

        if ($configuracion->firma_digital_path) {
            Storage::disk('public')->delete($configuracion->firma_digital_path);
            $configuracion->update(['firma_digital_path' => null]);
        }

        return back()->with('success', 'Firma digital eliminada exitosamente.');
    }
}
