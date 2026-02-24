<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Importacion\ImportacionService;
use App\Services\Importacion\ValidadorEstructuraExcel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ImportacionController extends Controller
{
    use \App\Traits\ChecksPermissions;

    public function __construct(
        private ImportacionService $importacionService
    ) {}

    /**
     * Muestra la página principal de importación
     */
    public function index()
    {
        return Inertia::render('Admin/Importacion/Index', [
            'tipos' => $this->importacionService->getTiposDisponibles(),
        ]);
    }

    /**
     * Muestra el formulario de carga para un tipo específico
     */
    public function create(string $tipo)
    {
        try {
            $campos = $this->importacionService->getCampos($tipo);
            $tiposDisponibles = $this->importacionService->getTiposDisponibles();

            return Inertia::render('Admin/Importacion/Create', [
                'tipo' => $tipo,
                'tipoInfo' => $tiposDisponibles[$tipo] ?? null,
                'campos' => $campos,
            ]);
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('admin.importacion.index')
                ->with('error', 'Tipo de importación no válido');
        }
    }

    /**
     * Analiza el archivo y muestra preview
     */
    public function analizar(Request $request, string $tipo)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $archivo = $request->file('archivo');

        // Validar archivo
        $erroresArchivo = $this->importacionService->validarArchivo($archivo);
        if (!empty($erroresArchivo)) {
            return back()->withErrors(['archivo' => $erroresArchivo]);
        }

        try {
            $preview = $this->importacionService->analizar($tipo, $archivo);

            // Verificar si hay error de estructura (plantilla incorrecta)
            if (!empty($preview['error_estructura'])) {
                return back()->with('error', $preview['error_estructura']);
            }

            // Guardar datos en sesión para la confirmación
            session(['importacion_preview' => $preview]);
            session(['importacion_tipo' => $tipo]);

            return Inertia::render('Admin/Importacion/Preview', [
                'preview' => $preview,
                'tipo' => $tipo,
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al analizar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Ejecuta la importación confirmada
     */
    public function importar(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string|in:alumnos,profesores,materias',
            'actualizar_duplicados' => 'array',
            'crear_usuarios' => 'boolean',
        ]);

        $tipo = $request->input('tipo');
        $preview = session('importacion_preview');

        if (!$preview || session('importacion_tipo') !== $tipo) {
            return redirect()->route('admin.importacion.index')
                ->with('error', 'Sesión de importación expirada. Por favor, suba el archivo nuevamente.');
        }

        try {
            $opciones = [
                'actualizar_duplicados' => $request->input('actualizar_duplicados', []),
                'crear_usuarios' => $request->input('crear_usuarios', true),
            ];

            $resultado = $this->importacionService->importar($tipo, $preview['datos'], $opciones);

            // Limpiar sesión
            session()->forget(['importacion_preview', 'importacion_tipo']);

            if ($resultado['success']) {
                $mensaje = sprintf(
                    'Importación completada: %d registros importados, %d actualizados, %d usuarios creados.',
                    $resultado['importados'],
                    $resultado['actualizados'],
                    $resultado['usuarios_creados']
                );

                return redirect()->route('admin.importacion.index')
                    ->with('success', $mensaje);
            } else {
                return redirect()->route('admin.importacion.index')
                    ->with('error', 'Error en la importación: ' . implode(', ', $resultado['errores']));
            }

        } catch (\Exception $e) {
            return redirect()->route('admin.importacion.index')
                ->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }

    /**
     * Valida la estructura del archivo Excel antes de analizar
     */
    public function validarEstructura(Request $request, string $tipo)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $validador = new ValidadorEstructuraExcel();
        $resultado = $validador->validar($tipo, $request->file('archivo'));

        return response()->json($resultado);
    }

    /**
     * Descarga la plantilla Excel para un tipo
     */
    public function descargarPlantilla(string $tipo)
    {
        try {
            $tiposDisponibles = $this->importacionService->getTiposDisponibles();

            if (!isset($tiposDisponibles[$tipo])) {
                return back()->with('error', 'Tipo de importación no válido');
            }

            $nombreArchivo = "plantilla_{$tipo}.xlsx";
            $exportClass = $this->getExportClass($tipo);

            return \Maatwebsite\Excel\Facades\Excel::download(
                new $exportClass(),
                $nombreArchivo
            );

        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar plantilla: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene la clase Export correspondiente al tipo
     */
    private function getExportClass(string $tipo): string
    {
        return match ($tipo) {
            'alumnos' => \App\Exports\PlantillaAlumnosExport::class,
            'profesores' => \App\Exports\PlantillaProfesoresExport::class,
            'materias' => \App\Exports\PlantillaMateriasExport::class,
            default => throw new \InvalidArgumentException("Tipo no válido: {$tipo}"),
        };
    }
}
