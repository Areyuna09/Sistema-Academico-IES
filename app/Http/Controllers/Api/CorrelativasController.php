<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MotorCorrelativasService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Controlador API para gestión de correlativas
 * Expone el motor de correlativas como API REST
 */
class CorrelativasController extends Controller
{
    protected MotorCorrelativasService $motorCorrelativas;

    public function __construct(MotorCorrelativasService $motorCorrelativas)
    {
        $this->motorCorrelativas = $motorCorrelativas;
    }

    /**
     * Validar si un alumno puede cursar una materia (RF02)
     *
     * GET /api/correlativas/validar-cursado
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function validarCursado(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'dni' => ['required', 'string', 'max:10', 'regex:/^[0-9]+$/'],
            'materia_id' => 'required|integer',
            'carrera_id' => 'required|integer',
        ], [
            'dni.regex' => 'El DNI debe contener solo números.',
        ]);

        $resultado = $this->motorCorrelativas->validarParaCursar(
            $validated['dni'],
            $validated['materia_id'],
            $validated['carrera_id']
        );

        return response()->json($resultado);
    }

    /**
     * Validar si un alumno puede rendir una materia
     *
     * GET /api/correlativas/validar-examen
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function validarExamen(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'dni' => ['required', 'string', 'max:10', 'regex:/^[0-9]+$/'],
            'materia_id' => 'required|integer',
            'carrera_id' => 'required|integer',
        ], [
            'dni.regex' => 'El DNI debe contener solo números.',
        ]);

        $resultado = $this->motorCorrelativas->validarParaRendir(
            $validated['dni'],
            $validated['materia_id'],
            $validated['carrera_id']
        );

        return response()->json($resultado);
    }

    /**
     * Obtener reglas de correlativas de una materia (RF03)
     *
     * GET /api/correlativas/materia/{materiaId}
     *
     * @param Request $request
     * @param int $materiaId
     * @return JsonResponse
     */
    public function obtenerReglas(Request $request, int $materiaId): JsonResponse
    {
        $validated = $request->validate([
            'carrera_id' => 'required|integer',
        ]);

        $reglas = $this->motorCorrelativas->obtenerReglasDeMateria(
            $materiaId,
            $validated['carrera_id']
        );

        return response()->json([
            'materia_id' => $materiaId,
            'carrera_id' => $validated['carrera_id'],
            'reglas' => $reglas,
        ]);
    }

    /**
     * Crear nueva regla de correlativa (RF03)
     *
     * POST /api/correlativas/reglas
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function crearRegla(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'materia_id' => 'required|integer',
            'carrera_id' => 'required|integer',
            'tipo' => 'required|in:cursar,rendir',
            'correlativa_id' => 'required|integer',
            'estado_requerido' => 'required|in:regular,aprobada',
            'es_activa' => 'boolean',
            'excepciones' => 'nullable|array',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $regla = $this->motorCorrelativas->crearRegla($validated);

            return response()->json([
                'success' => true,
                'mensaje' => 'Regla de correlativa creada exitosamente',
                'regla' => $regla,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al crear regla de correlativa',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Actualizar regla de correlativa existente (RF03)
     *
     * PUT /api/correlativas/reglas/{id}
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function actualizarRegla(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'tipo' => 'sometimes|in:cursar,rendir',
            'estado_requerido' => 'sometimes|in:regular,aprobada',
            'es_activa' => 'sometimes|boolean',
            'excepciones' => 'nullable|array',
            'observaciones' => 'nullable|string',
        ]);

        $regla = $this->motorCorrelativas->actualizarRegla($id, $validated);

        if (!$regla) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Regla no encontrada',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'mensaje' => 'Regla actualizada exitosamente',
            'regla' => $regla,
        ]);
    }

    /**
     * Desactivar regla de correlativa (RF03)
     *
     * DELETE /api/correlativas/reglas/{id}
     *
     * @param int $id
     * @return JsonResponse
     */
    public function desactivarRegla(int $id): JsonResponse
    {
        $resultado = $this->motorCorrelativas->desactivarRegla($id);

        if (!$resultado) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Regla no encontrada',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'mensaje' => 'Regla desactivada exitosamente',
        ]);
    }

    /**
     * Sincronizar correlativas legacy a nuevas reglas (RF03)
     *
     * POST /api/correlativas/sincronizar-legacy
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sincronizarLegacy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'materia_id' => 'sometimes|integer',
            'carrera_id' => 'sometimes|integer',
        ]);

        try {
            if (isset($validated['carrera_id'])) {
                // Sincronizar toda la carrera
                $resultado = $this->motorCorrelativas->sincronizarCarreraCompleta(
                    $validated['carrera_id']
                );
            } elseif (isset($validated['materia_id'])) {
                // Sincronizar solo una materia
                $resultado = $this->motorCorrelativas->sincronizarDesdeLegacy(
                    $validated['materia_id']
                );
            } else {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'Debe proporcionar materia_id o carrera_id',
                ], 400);
            }

            return response()->json([
                'success' => true,
                'mensaje' => 'Sincronización completada',
                'resultado' => $resultado,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error en sincronización',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener resumen académico de un alumno
     *
     * GET /api/correlativas/alumno/{dni}
     *
     * @param Request $request
     * @param string $dni
     * @return JsonResponse
     */
    public function obtenerResumenAlumno(Request $request, string $dni): JsonResponse
    {
        $validated = $request->validate([
            'carrera_id' => 'required|integer',
        ]);

        $resumen = $this->motorCorrelativas->obtenerResumenAlumno(
            $dni,
            $validated['carrera_id']
        );

        if (isset($resumen['error'])) {
            return response()->json($resumen, 404);
        }

        return response()->json($resumen);
    }
}