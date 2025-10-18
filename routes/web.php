<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InscripcionesController;
use App\Http\Controllers\InscripcionesMesaController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\Admin\CorrelativasController;
use App\Http\Controllers\Admin\UsuariosController;
use App\Http\Controllers\Admin\CarrerasController;
use App\Http\Controllers\Admin\MateriasController;
use App\Http\Controllers\Admin\PeriodosController;
use App\Http\Controllers\Admin\ConfiguracionController;
use App\Http\Controllers\Admin\MesasExamenController;
use App\Http\Controllers\Admin\ExcepcionesController;
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
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
    Route::post('/expediente/buscar-dni', [ExpedienteController::class, 'buscarPorDni'])->name('expediente.buscar-dni');
    Route::put('/expediente/materia/{alumnoMateriaId}', [ExpedienteController::class, 'actualizarEstadoMateria'])->name('expediente.actualizar-estado-materia');
    Route::get('/expediente/alumnos/profesor', [ExpedienteController::class, 'obtenerAlumnosProfesor'])->name('expediente.alumnos-profesor');
    Route::post('/expediente/asistencia/guardar', [ExpedienteController::class, 'guardarAsistencia'])->name('expediente.guardar-asistencia');
    Route::post('/expediente/asistencia/guardar-final', [ExpedienteController::class, 'guardarAsistenciaFinal'])->name('expediente.guardar-asistencia-final');
    Route::post('/expediente/notas/guardar', [ExpedienteController::class, 'guardarNotas'])->name('expediente.guardar-notas');
    Route::post('/expediente/notas/guardar-finales', [ExpedienteController::class, 'guardarNotasFinales'])->name('expediente.guardar-notas-finales');
    Route::post('/expediente/aprobar-nota/{id}', [ExpedienteController::class, 'aprobarNota'])->name('expediente.aprobar-nota');
    Route::post('/expediente/rechazar-nota/{id}', [ExpedienteController::class, 'rechazarNota'])->name('expediente.rechazar-nota');
    Route::post('/expediente/configurar-parametros/{profesorMateriaId}', [ExpedienteController::class, 'configurarParametrosAcademicos'])->name('expediente.configurar-parametros');

    // Rutas de mesas de examen (alumnos)
    Route::get('/mesas', [InscripcionesMesaController::class, 'index'])->name('mesas.index');
    Route::post('/mesas/{mesa}/inscribir', [InscripcionesMesaController::class, 'inscribir'])->name('mesas.inscribir');
    Route::delete('/mesas/{mesa}/cancelar', [InscripcionesMesaController::class, 'cancelar'])->name('mesas.cancelar');
    Route::get('/mesas/confirmacion/{inscripcion}', [InscripcionesMesaController::class, 'confirmacion'])->name('mesas.confirmacion');
    Route::get('/mesas/comprobante/{inscripcion}/pdf', [InscripcionesMesaController::class, 'descargarComprobante'])->name('mesas.comprobante.pdf');
    Route::get('/mis-mesas', [InscripcionesMesaController::class, 'misInscripciones'])->name('mesas.mis-inscripciones');

    // Plan de Estudio
    Route::get('/plan-estudio', function () {
        return Inertia::render('PlanDeEstudio');
    })->name('plan-estudio');

    // Notificaciones
    Route::get('/notificaciones', [NotificacionesController::class, 'index'])->name('notificaciones.index');
    Route::get('/notificaciones/contador', [NotificacionesController::class, 'contador'])->name('notificaciones.contador');
    Route::post('/notificaciones/{notificacion}/marcar-leida', [NotificacionesController::class, 'marcarLeida'])->name('notificaciones.marcar-leida');
    Route::post('/notificaciones/marcar-todas-leidas', [NotificacionesController::class, 'marcarTodasLeidas'])->name('notificaciones.marcar-todas-leidas');
    Route::delete('/notificaciones/{notificacion}', [NotificacionesController::class, 'destroy'])->name('notificaciones.destroy');
    Route::delete('/notificaciones/limpiar-leidas', [NotificacionesController::class, 'limpiarLeidas'])->name('notificaciones.limpiar-leidas');
});

// Rutas de administración (solo para admin y profesores)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Gestión de Usuarios
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UsuariosController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuariosController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}/editar', [UsuariosController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UsuariosController::class, 'update'])->name('usuarios.update');
    Route::post('/usuarios/{usuario}/toggle', [UsuariosController::class, 'toggle'])->name('usuarios.toggle');
    Route::delete('/usuarios/{usuario}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');
    Route::post('/usuarios/{usuario}/reset-password', [UsuariosController::class, 'resetPassword'])->name('usuarios.reset-password');

    // Gestión de Carreras
    Route::get('/carreras', [CarrerasController::class, 'index'])->name('carreras.index');
    Route::get('/carreras/crear', [CarrerasController::class, 'create'])->name('carreras.create');
    Route::post('/carreras', [CarrerasController::class, 'store'])->name('carreras.store');
    Route::get('/carreras/{carrera}/editar', [CarrerasController::class, 'edit'])->name('carreras.edit');
    Route::put('/carreras/{carrera}', [CarrerasController::class, 'update'])->name('carreras.update');
    Route::delete('/carreras/{carrera}', [CarrerasController::class, 'destroy'])->name('carreras.destroy');

    // Gestión de Materias
    Route::get('/materias', [MateriasController::class, 'index'])->name('materias.index');
    Route::get('/materias/crear', [MateriasController::class, 'create'])->name('materias.create');
    Route::post('/materias', [MateriasController::class, 'store'])->name('materias.store');
    Route::get('/materias/{materia}/editar', [MateriasController::class, 'edit'])->name('materias.edit');
    Route::put('/materias/{materia}', [MateriasController::class, 'update'])->name('materias.update');
    Route::delete('/materias/{materia}', [MateriasController::class, 'destroy'])->name('materias.destroy');

    // Gestión de Períodos Lectivos
    Route::get('/periodos', [PeriodosController::class, 'index'])->name('periodos.index');
    Route::get('/periodos/crear', [PeriodosController::class, 'create'])->name('periodos.create');
    Route::post('/periodos', [PeriodosController::class, 'store'])->name('periodos.store');
    Route::get('/periodos/{periodo}/editar', [PeriodosController::class, 'edit'])->name('periodos.edit');
    Route::put('/periodos/{periodo}', [PeriodosController::class, 'update'])->name('periodos.update');
    Route::post('/periodos/{periodo}/toggle', [PeriodosController::class, 'toggle'])->name('periodos.toggle');
    Route::delete('/periodos/{periodo}', [PeriodosController::class, 'destroy'])->name('periodos.destroy');

    // Configuración General
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::get('/configuracion/editar', [ConfiguracionController::class, 'edit'])->name('configuracion.edit');
    Route::post('/configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');
    Route::delete('/configuracion/logo', [ConfiguracionController::class, 'deleteLogo'])->name('configuracion.delete-logo');
    Route::delete('/configuracion/logo-dark', [ConfiguracionController::class, 'deleteLogoDark'])->name('configuracion.delete-logo-dark');
    Route::delete('/configuracion/firma', [ConfiguracionController::class, 'deleteFirma'])->name('configuracion.delete-firma');

    // Gestión de Mesas de Examen
    Route::get('/mesas', [MesasExamenController::class, 'index'])->name('mesas.index');
    Route::get('/mesas/crear', [MesasExamenController::class, 'create'])->name('mesas.create');
    Route::post('/mesas', [MesasExamenController::class, 'store'])->name('mesas.store');
    Route::get('/mesas/{mesa}', [MesasExamenController::class, 'show'])->name('mesas.show');
    Route::get('/mesas/{mesa}/editar', [MesasExamenController::class, 'edit'])->name('mesas.edit');
    Route::put('/mesas/{mesa}', [MesasExamenController::class, 'update'])->name('mesas.update');
    Route::delete('/mesas/{mesa}', [MesasExamenController::class, 'destroy'])->name('mesas.destroy');

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

    // Gestión de Excepciones (Parámetros)
    Route::get('/excepciones', [ExcepcionesController::class, 'index'])->name('excepciones.index');
    Route::post('/excepciones', [ExcepcionesController::class, 'store'])->name('excepciones.store');
    Route::put('/excepciones/{excepcion}', [ExcepcionesController::class, 'update'])->name('excepciones.update');
    Route::delete('/excepciones/{excepcion}', [ExcepcionesController::class, 'destroy'])->name('excepciones.destroy');

    // Configuración de Módulos del Sistema
    Route::get('/configuracion-modulos', [\App\Http\Controllers\Admin\ConfiguracionModulosController::class, 'index'])->name('configuracion-modulos.index');
    Route::post('/configuracion-modulos/{modulo}/toggle', [\App\Http\Controllers\Admin\ConfiguracionModulosController::class, 'toggle'])->name('configuracion-modulos.toggle');
    Route::post('/configuracion-modulos/update-batch', [\App\Http\Controllers\Admin\ConfiguracionModulosController::class, 'updateBatch'])->name('configuracion-modulos.update-batch');
    Route::post('/configuracion-modulos/reset', [\App\Http\Controllers\Admin\ConfiguracionModulosController::class, 'resetDefaults'])->name('configuracion-modulos.reset');
});

// Rutas de Profesores (asistencias y materias)
Route::middleware(['auth'])->prefix('profesor')->name('profesor.')->group(function () {

    // Mis materias
    Route::get('/mis-materias', [\App\Http\Controllers\Profesor\MisMateriasController::class, 'index'])
        ->name('mis-materias.index');
    Route::get('/mis-materias/{profesorMateria}', [\App\Http\Controllers\Profesor\MisMateriasController::class, 'show'])
        ->name('mis-materias.show');

    // Historial de asistencias
    Route::get('/materia/{profesorMateria}/asistencias',
        [\App\Http\Controllers\Profesor\AsistenciasController::class, 'index'])
        ->name('asistencias.index');

    // Formulario para tomar asistencia
    Route::get('/materia/{profesorMateria}/asistencias/crear',
        [\App\Http\Controllers\Profesor\AsistenciasController::class, 'create'])
        ->name('asistencias.create');

    // Guardar asistencias
    Route::post('/materia/{profesorMateria}/asistencias',
        [\App\Http\Controllers\Profesor\AsistenciasController::class, 'store'])
        ->name('asistencias.store');
});

// Rutas de autenticación (login, register, logout, reset password, etc.)
require __DIR__.'/auth.php';