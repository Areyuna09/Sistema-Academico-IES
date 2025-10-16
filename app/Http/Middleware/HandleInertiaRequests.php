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
        $configuracion = Configuracion::get();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'configuracion' => [
                'nombre_institucion' => $configuracion->nombre_institucion,
                'logo_url' => $configuracion->logo_path ? Storage::url($configuracion->logo_path) : null,
                'logo_dark_url' => $configuracion->logo_dark_path ? Storage::url($configuracion->logo_dark_path) : null,
                'telefono' => $configuracion->telefono,
                'email' => $configuracion->email,
                'sitio_web' => $configuracion->sitio_web,
                'direccion' => $configuracion->direccion,
                'footer_documentos' => $configuracion->footer_documentos,
                'firma_digital_url' => $configuracion->firma_digital_path ? Storage::url($configuracion->firma_digital_path) : null,
                'cargo_firma' => $configuracion->cargo_firma,
            ],
        ];
    }
}
