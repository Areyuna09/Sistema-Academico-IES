<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InscripcionesController;
use App\Http\Controllers\ExpedienteController;
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

    // Rutas de expediente
    Route::get('/expediente', [ExpedienteController::class, 'index'])->name('expediente.index');
    Route::get('/expediente/{id}', [ExpedienteController::class, 'show'])->name('expediente.show');
});

// Rutas de autenticación (login, register, logout, reset password, etc.)
require __DIR__.'/auth.php';