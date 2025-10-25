<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CorrelativasController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Academic\PlanEstudioController;

// Rutas de Autenticación con Sanctum
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas con token
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas de Plan de Estudio para alumnos autenticados
    Route::get('/alumnos/{alumno}/plan-estudio', [PlanEstudioController::class, 'showAlumnoPlan']);
    Route::patch('/alumnos/{alumno}/materias/{materia}', [PlanEstudioController::class, 'updateAlumnoMateria']);
});

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
