<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CorrelativasController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas de Correlativas - Motor de Correlativas (RF02, RF03)
Route::prefix('correlativas')->group(function () {

    // Validaciones (RF02)
    Route::get('/validar-cursado', [CorrelativasController::class, 'validarCursado']);
    Route::get('/validar-examen', [CorrelativasController::class, 'validarExamen']);

    // Gestión de reglas (RF03 - Motor Configurable)
    Route::get('/materia/{materiaId}', [CorrelativasController::class, 'obtenerReglas']);
    Route::post('/reglas', [CorrelativasController::class, 'crearRegla']);
    Route::put('/reglas/{id}', [CorrelativasController::class, 'actualizarRegla']);
    Route::delete('/reglas/{id}', [CorrelativasController::class, 'desactivarRegla']);

    // Sincronización legacy
    Route::post('/sincronizar-legacy', [CorrelativasController::class, 'sincronizarLegacy']);

    // Resumen de alumno
    Route::get('/alumno/{dni}', [CorrelativasController::class, 'obtenerResumenAlumno']);
});
