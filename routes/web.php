<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InscripcionesController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\Admin\CorrelativasController;
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

    // Rutas de inscripciones
    Route::get('/inscripciones', [InscripcionesController::class, 'index'])->name('inscripciones.index');
    Route::post('/inscripciones', [InscripcionesController::class, 'store'])->name('inscripciones.store');
    Route::get('/inscripciones/confirmacion', [InscripcionesController::class, 'confirmacion'])->name('inscripciones.confirmacion');
    Route::get('/inscripciones/comprobante/pdf', [InscripcionesController::class, 'descargarComprobante'])->name('inscripciones.comprobante.pdf');

    // Rutas de expediente
    Route::get('/expediente', [ExpedienteController::class, 'index'])->name('expediente.index');
    Route::get('/expediente/{id}', [ExpedienteController::class, 'show'])->name('expediente.show');
});

// Rutas de administración (solo para admin y profesores)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Gestión de Correlativas
    Route::get('/correlativas', [CorrelativasController::class, 'index'])->name('correlativas.index');
    Route::get('/correlativas/crear', [CorrelativasController::class, 'create'])->name('correlativas.create');
    Route::post('/correlativas', [CorrelativasController::class, 'store'])->name('correlativas.store');
    Route::get('/correlativas/{correlativa}/editar', [CorrelativasController::class, 'edit'])->name('correlativas.edit');
    Route::put('/correlativas/{correlativa}', [CorrelativasController::class, 'update'])->name('correlativas.update');
    Route::post('/correlativas/{correlativa}/toggle', [CorrelativasController::class, 'toggle'])->name('correlativas.toggle');
    Route::delete('/correlativas/{correlativa}', [CorrelativasController::class, 'destroy'])->name('correlativas.destroy');

    // API para obtener correlativas de una materia
    Route::get('/api/correlativas/materia/{materiaId}', [CorrelativasController::class, 'obtenerCorrelativas'])->name('correlativas.obtener');

    // Importación masiva
    Route::post('/correlativas/importar', [CorrelativasController::class, 'importar'])->name('correlativas.importar');
});

// Rutas de autenticación (login, register, logout, reset password, etc.)
require __DIR__.'/auth.php';