<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExcepcionController;
use App\Http\Controllers\NotificacionController;
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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/excepciones', [ExcepcionController::class, 'index'])->name('excepciones');
    Route::put('/excepciones/{excepcion}', [ExcepcionController::class, 'update'])->name('excepciones.update');
});

Route::post('/excepciones', [\App\Http\Controllers\ExcepcionController::class, 'store'])
    ->name('excepciones.store');

Route::delete('/excepciones/{id}', [ExcepcionController::class, 'destroy'])->name('excepciones.destroy');

Route::resource('notificaciones', NotificacionController::class);




// Rutas de autenticación (login, register, logout, reset password, etc.)
require __DIR__.'/auth.php';