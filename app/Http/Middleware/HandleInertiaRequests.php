<?php

namespace App\Http\Middleware;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        try {
            $configuracion = Configuracion::get();
        } catch (\Exception $e) {
            $configuracion = null;
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'configuracion' => $configuracion ? [
                'nombre_institucion' => $configuracion->nombre_institucion ?? 'IES Gral. Manuel Belgrano',
                'logo_url' => ($configuracion->logo_path && Storage::exists($configuracion->logo_path))
                    ? Storage::url($configuracion->logo_path)
                    : '/images/logo-ies-original.png',
                'logo_dark_url' => ($configuracion->logo_dark_path && Storage::exists($configuracion->logo_dark_path))
                    ? Storage::url($configuracion->logo_dark_path)
                    : null,
                'telefono' => $configuracion->telefono ?? null,
                'email' => $configuracion->email ?? null,
                'sitio_web' => $configuracion->sitio_web ?? null,
                'direccion' => $configuracion->direccion ?? null,
                'footer_documentos' => $configuracion->footer_documentos ?? null,
                'firma_digital_url' => ($configuracion->firma_digital_path && Storage::exists($configuracion->firma_digital_path))
                    ? Storage::url($configuracion->firma_digital_path)
                    : null,
                'cargo_firma' => $configuracion->cargo_firma ?? null,
            ] : [
                'nombre_institucion' => 'IES Gral. Manuel Belgrano',
                'logo_url' => '/images/logo-ies-original.png',
                'logo_dark_url' => null,
                'telefono' => null,
                'email' => null,
                'sitio_web' => null,
                'direccion' => null,
                'footer_documentos' => null,
                'firma_digital_url' => null,
                'cargo_firma' => null,
            ],
        ];
    }
}
