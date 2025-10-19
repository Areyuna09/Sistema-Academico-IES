<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ExamenController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CorrelativasController;

// NUEVAS RUTAS PARA SELECCIÓN DE MESA
Route::get('/examenes/seleccionar-mesa/{idAlumno}', [ExamenController::class, 'seleccionarMesa'])->name('examenes.seleccionar-mesa');
Route::post('/examenes/procesar-mesa', [ExamenController::class, 'procesarMesa'])->name('examenes.procesar-mesa');
// Rutas de Exámenes
Route::get('/', [ExamenController::class, 'identificar'])->name('identificar');
Route::post('/examenes/buscar', [ExamenController::class, 'buscarAlumno'])->name('examenes.buscar');
Route::get('/examenes/materias/{idAlumno}', [ExamenController::class, 'materias'])->name('materias');
Route::get('/examenes/validar/{idAlumno}/{idMateria}', [ExamenController::class, 'validarInscripcion'])->name('examenes.validar');
Route::post('/examenes/inscribir', [ExamenController::class, 'inscribir'])->name('examenes.inscribir');
// Rutas para gestionar inscripciones
Route::get('/examenes/inscripciones/{idAlumno}', [ExamenController::class, 'verInscripciones'])->name('examenes.inscripciones');
Route::delete('/examenes/eliminar-inscripcion/{mesa}/{id}', [ExamenController::class, 'eliminarInscripcion'])->name('examenes.eliminar-inscripcion');

// Rutas de Correlativas
Route::get('/correlativas', [CorrelativasController::class, 'index'])->name('correlativas.index');
Route::post('/correlativas/seleccionar', [CorrelativasController::class, 'seleccionar'])->name('correlativas.seleccionar');
Route::get('/correlativas/gestionar/{carreraId}', [CorrelativasController::class, 'gestionar'])->name('correlativas.gestionar');
Route::get('/correlativas/get/{materiaId}', [CorrelativasController::class, 'getCorrelativas'])->name('correlativas.get');
Route::post('/correlativas/guardar', [CorrelativasController::class, 'guardar'])->name('correlativas.guardar');
Route::delete('/correlativas/eliminar/{materiaId}', [CorrelativasController::class, 'eliminar'])->name('correlativas.eliminar');
/*
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

// Rutas de autenticación (login, register, logout, reset password, etc.)
require __DIR__.'/auth.php';
*/