<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Redirección al login o al dashboard según corresponda
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Dashboard después de iniciar sesión
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas con autenticación
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- PANEL DEL PROFESOR (Inertia page) ---
Route::get('/panel-profesor', function () {
    return Inertia::render('Profesor/Panel');
})->name('panel.profesor');

// Endpoints para asistencias (usamos web routes)
Route::get('/profesor/materias', [\App\Http\Controllers\AsistenciaController::class, 'materiasDelProfesor']);
Route::get('/materias/{materiaId}/alumnos', [\App\Http\Controllers\AsistenciaController::class, 'alumnosPorMateria']);
Route::get('/asistencias', [\App\Http\Controllers\AsistenciaController::class, 'listarPorMateriaFecha']);
Route::post('/asistencias/guardar', [\App\Http\Controllers\AsistenciaController::class, 'guardarBatch']);
Route::delete('/asistencias/{id}', [\App\Http\Controllers\AsistenciaController::class, 'destroy']);


// --- NOTAS TEMPORALES (Panel del Profesor) ---
Route::get('/panel-profesor/notas', function () {
    return Inertia::render('Profesor/Notas');
})->middleware(['auth'])->name('panel.profesor.notas');

Route::get('/notas/materia', [\App\Http\Controllers\NotaController::class, 'listarPorMateria'])->middleware(['auth']);
Route::post('/notas/guardar', [\App\Http\Controllers\NotaController::class, 'guardarBatch'])->middleware(['auth']);
Route::post('/notas/aprobar/{id}', [\App\Http\Controllers\NotaController::class, 'aprobar'])->middleware(['auth']);

// Rutas de autenticación (login, register, logout, reset password, etc.)
require __DIR__.'/auth.php';