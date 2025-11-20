<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InscripcionesController;
use App\Http\Controllers\InscripcionesMesaController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\Admin\CorrelativasController;
use App\Http\Controllers\Admin\UsuariosController;
use App\Http\Controllers\Admin\CarrerasController;
use App\Http\Controllers\Admin\MateriasController;
use App\Http\Controllers\Admin\PeriodosController;
use App\Http\Controllers\Admin\ConfiguracionController;
use App\Http\Controllers\Admin\MesasExamenController;
use App\Http\Controllers\Admin\ExcepcionesController;
use App\Http\Controllers\Admin\InscripcionesController as AdminInscripcionesController;
use App\Http\Controllers\Admin\SolicitudesCambioEmailController;
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
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de inscripciones
    Route::middleware('modulo:inscripciones')->group(function () {
        Route::get('/inscripciones', [InscripcionesController::class, 'index'])->name('inscripciones.index');
        Route::post('/inscripciones', [InscripcionesController::class, 'store'])->name('inscripciones.store');
        Route::get('/inscripciones/confirmacion', [InscripcionesController::class, 'confirmacion'])->name('inscripciones.confirmacion');
        Route::get('/inscripciones/comprobante/pdf', [InscripcionesController::class, 'descargarComprobante'])->name('inscripciones.comprobante.pdf');
    });

    // Rutas de expediente
    Route::middleware('modulo:expediente')->group(function () {
        Route::get('/expediente', [ExpedienteController::class, 'index'])->name('expediente.index');
        // Rutas específicas ANTES de la ruta genérica {id}
        Route::get('/expediente/materias-disponibles', [ExpedienteController::class, 'materiasDisponibles'])->name('expediente.materias-disponibles');
        Route::get('/expediente/alumnos/profesor', [ExpedienteController::class, 'obtenerAlumnosProfesor'])->name('expediente.alumnos-profesor');
        Route::post('/expediente/buscar-dni', [ExpedienteController::class, 'buscarPorDni'])->name('expediente.buscar-dni');
        Route::post('/expediente/agregar-materia', [ExpedienteController::class, 'agregarMateria'])->name('expediente.agregar-materia');
        Route::put('/expediente/materia/{alumnoMateriaId}', [ExpedienteController::class, 'actualizarEstadoMateria'])->name('expediente.actualizar-estado-materia');
        Route::post('/expediente/asistencia/guardar', [ExpedienteController::class, 'guardarAsistencia'])->name('expediente.guardar-asistencia');
        Route::post('/expediente/asistencia/guardar-final', [ExpedienteController::class, 'guardarAsistenciaFinal'])->name('expediente.guardar-asistencia-final');
        Route::post('/expediente/notas/guardar', [ExpedienteController::class, 'guardarNotas'])->name('expediente.guardar-notas');
        Route::post('/expediente/notas/guardar-finales', [ExpedienteController::class, 'guardarNotasFinales'])->name('expediente.guardar-notas-finales');
        Route::post('/expediente/aprobar-nota/{id}', [ExpedienteController::class, 'aprobarNota'])->name('expediente.aprobar-nota');
        Route::post('/expediente/rechazar-nota/{id}', [ExpedienteController::class, 'rechazarNota'])->name('expediente.rechazar-nota');
        Route::post('/expediente/configurar-parametros/{profesorMateriaId}', [ExpedienteController::class, 'configurarParametrosAcademicos'])->name('expediente.configurar-parametros');
        // Ruta genérica al final
        Route::get('/expediente/{id}', [ExpedienteController::class, 'show'])->name('expediente.show');
    });

    // Rutas de gestión de alumnos
    Route::resource('alumnos', AlumnoController::class)->parameters([
        'alumnos' => 'alumno'
    ]);

    // Rutas de gestión de profesores
    Route::post('profesores/{profesor}/limpiar-materias', [ProfesorController::class, 'limpiarMaterias'])->name('profesores.limpiar-materias');
    Route::resource('profesores', ProfesorController::class)->parameters([
        'profesores' => 'profesor'
    ]);

    // Rutas de mesas de examen (alumnos)
    Route::middleware('modulo:mesas_examen')->group(function () {
        Route::get('/mesas', [InscripcionesMesaController::class, 'index'])->name('mesas.index');
        Route::post('/mesas/{mesa}/inscribir', [InscripcionesMesaController::class, 'inscribir'])->name('mesas.inscribir');
        Route::delete('/mesas/{mesa}/cancelar', [InscripcionesMesaController::class, 'cancelar'])->name('mesas.cancelar');
        Route::get('/mesas/confirmacion/{inscripcion}', [InscripcionesMesaController::class, 'confirmacion'])->name('mesas.confirmacion');
        Route::get('/mesas/comprobante/{inscripcion}/pdf', [InscripcionesMesaController::class, 'descargarComprobante'])->name('mesas.comprobante.pdf');
        Route::get('/mis-mesas', [InscripcionesMesaController::class, 'misInscripciones'])->name('mesas.mis-inscripciones');
    });

    // Plan de Estudio
    Route::get('/plan-estudio', function () {
        return Inertia::render('PlanDeEstudio');
    })->name('plan-estudio');

    // API del Plan de Estudio con sesiones web
    Route::get('/alumnos/{alumno}/plan-estudio', [App\Http\Controllers\Academic\PlanEstudioController::class, 'showAlumnoPlan'])->name('plan-estudio.alumno');
    Route::get('/carreras', [App\Http\Controllers\Academic\PlanEstudioController::class, 'listCarreras'])->name('plan-estudio.carreras');
    Route::get('/carreras/{carrera}/planes', [App\Http\Controllers\Academic\PlanEstudioController::class, 'listPlanesCarrera'])->name('plan-estudio.planes');
    Route::get('/carreras/{carrera}/plan', [App\Http\Controllers\Academic\PlanEstudioController::class, 'showCarreraPlan'])->name('plan-estudio.carrera');

    // Gestión de planes de estudio (múltiples planes por carrera)
    Route::post('/admin/carreras/{carrera}/planes', [App\Http\Controllers\Academic\PlanEstudioController::class, 'crearPlan'])->name('plan-estudio.crear')->middleware('can:manage-plans');
    Route::get('/carreras/{carrera}/planes/{plan}', [App\Http\Controllers\Academic\PlanEstudioController::class, 'showPlan'])->name('plan-estudio.show');
    Route::post('/carreras/{carrera}/planes/{plan}/materias', [App\Http\Controllers\Academic\PlanEstudioController::class, 'asignarMateria'])->name('plan-estudio.asignar-materia');
    Route::delete('/carreras/{carrera}/planes/{plan}/materias/{materia}', [App\Http\Controllers\Academic\PlanEstudioController::class, 'quitarMateria'])->name('plan-estudio.quitar-materia');

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
    Route::get('/usuarios/generar-automaticos/preview', [UsuariosController::class, 'previewUsuariosAutomaticos'])->name('usuarios.generar-automaticos.preview');
    Route::post('/usuarios/generar-automaticos', [UsuariosController::class, 'generarUsuariosAutomaticos'])->name('usuarios.generar-automaticos');

    // Gestión de Carreras
    Route::get('/carreras', [CarrerasController::class, 'index'])->name('carreras.index');
    Route::get('/carreras/crear', [CarrerasController::class, 'create'])->name('carreras.create');
    Route::post('/carreras', [CarrerasController::class, 'store'])->name('carreras.store');
    Route::get('/carreras/{carrera}/editar', [CarrerasController::class, 'edit'])->name('carreras.edit');
    Route::put('/carreras/{carrera}', [CarrerasController::class, 'update'])->name('carreras.update');
    Route::delete('/carreras/{carrera}', [CarrerasController::class, 'destroy'])->name('carreras.destroy');

    // Gestión de Planes de Estudio
    Route::prefix('planes-estudio')->name('planes-estudio.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\PlanesEstudioController::class, 'index'])->name('index');
        Route::post('/carreras/{carrera}/planes', [App\Http\Controllers\Admin\PlanesEstudioController::class, 'store'])->name('store');
        Route::get('/planes/{plan}', [App\Http\Controllers\Admin\PlanesEstudioController::class, 'show'])->name('show');
        Route::put('/planes/{plan}', [App\Http\Controllers\Admin\PlanesEstudioController::class, 'update'])->name('update');
        Route::delete('/planes/{plan}', [App\Http\Controllers\Admin\PlanesEstudioController::class, 'destroy'])->name('destroy');
        Route::post('/planes/{plan}/clonar', [App\Http\Controllers\Admin\PlanesEstudioController::class, 'clonar'])->name('clonar');
        Route::patch('/planes/{plan}/toggle-activo', [App\Http\Controllers\Admin\PlanesEstudioController::class, 'toggleActivo'])->name('toggle-activo');
        Route::patch('/planes/{plan}/toggle-vigente', [App\Http\Controllers\Admin\PlanesEstudioController::class, 'toggleVigente'])->name('toggle-vigente');

        // Gestión de materias del plan
        Route::get('/planes/{plan}/materias', [App\Http\Controllers\Admin\PlanesEstudioController::class, 'getMaterias'])->name('materias');
        Route::post('/planes/{plan}/materias', [App\Http\Controllers\Admin\PlanesEstudioController::class, 'agregarMateria'])->name('agregar-materia');
        Route::delete('/planes/{plan}/materias/{materia}', [App\Http\Controllers\Admin\PlanesEstudioController::class, 'quitarMateria'])->name('quitar-materia');
        Route::post('/planes/{plan}/reemplazar-materia', [App\Http\Controllers\Admin\PlanesEstudioController::class, 'reemplazarMateria'])->name('reemplazar-materia');
    });

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
    Route::post('/mesas/asignar-fechas-masivo', [MesasExamenController::class, 'asignarFechasMasivo'])->name('mesas.asignar-fechas-masivo');
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

    // Gestión de Inscripciones (Admin)
    Route::get('/inscripciones', [AdminInscripcionesController::class, 'index'])->name('inscripciones.index');
    Route::post('/inscripciones', [AdminInscripcionesController::class, 'store'])->name('inscripciones.store');
    Route::put('/inscripciones/{inscripcion}', [AdminInscripcionesController::class, 'update'])->name('inscripciones.update');
    Route::delete('/inscripciones/{inscripcion}', [AdminInscripcionesController::class, 'destroy'])->name('inscripciones.destroy');

    // Exportaciones de inscripciones
    Route::get('/inscripciones/mesa/{mesa}/pdf', [AdminInscripcionesController::class, 'exportarMesaPdf'])->name('inscripciones.mesa.pdf');
    Route::get('/inscripciones/mesa/{mesa}/csv', [AdminInscripcionesController::class, 'exportarMesaCsv'])->name('inscripciones.mesa.csv');
    Route::get('/inscripciones/cursado/pdf', [AdminInscripcionesController::class, 'exportarCursadoPdf'])->name('inscripciones.cursado.pdf');
    Route::get('/inscripciones/cursado/csv', [AdminInscripcionesController::class, 'exportarCursadoCsv'])->name('inscripciones.cursado.csv');

    // Configuración de Módulos del Sistema
    Route::get('/configuracion-modulos', [\App\Http\Controllers\Admin\ConfiguracionModulosController::class, 'index'])->name('configuracion-modulos.index');
    Route::post('/configuracion-modulos/{modulo}/toggle', [\App\Http\Controllers\Admin\ConfiguracionModulosController::class, 'toggle'])->name('configuracion-modulos.toggle');
    Route::post('/configuracion-modulos/update-batch', [\App\Http\Controllers\Admin\ConfiguracionModulosController::class, 'updateBatch'])->name('configuracion-modulos.update-batch');
    Route::post('/configuracion-modulos/reset', [\App\Http\Controllers\Admin\ConfiguracionModulosController::class, 'resetDefaults'])->name('configuracion-modulos.reset');

    // Gestión de Solicitudes de Cambio de Email
    Route::get('/solicitudes-email', [SolicitudesCambioEmailController::class, 'index'])->name('solicitudes-email.index');
    Route::post('/solicitudes-email/{solicitud}/aprobar', [SolicitudesCambioEmailController::class, 'aprobar'])->name('solicitudes-email.aprobar');
    Route::post('/solicitudes-email/{solicitud}/rechazar', [SolicitudesCambioEmailController::class, 'rechazar'])->name('solicitudes-email.rechazar');
    Route::delete('/solicitudes-email/{solicitud}', [SolicitudesCambioEmailController::class, 'destroy'])->name('solicitudes-email.destroy');
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

// Rutas de Directivo (Tipo 5) - Revisión de legajos
Route::middleware(['auth', 'directivo'])->prefix('directivo')->name('directivo.')->group(function () {
    // Panel principal
    Route::get('/', [\App\Http\Controllers\DirectivoController::class, 'index'])->name('index');

    // Ver detalle de legajo
    Route::get('/legajo/{id}', [\App\Http\Controllers\DirectivoController::class, 'show'])->name('show');

    // Aprobar legajo
    Route::post('/legajo/{id}/aprobar', [\App\Http\Controllers\DirectivoController::class, 'aprobar'])->name('aprobar');

    // Rechazar legajo con observaciones
    Route::post('/legajo/{id}/rechazar', [\App\Http\Controllers\DirectivoController::class, 'rechazar'])->name('rechazar');

    // Editar/Corregir legajo
    Route::put('/legajo/{id}', [\App\Http\Controllers\DirectivoController::class, 'actualizar'])->name('actualizar');
});

// Rutas de Supervisor (Tipo 6) - Supervisión ministerial
Route::middleware(['auth', 'supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
    // Panel principal
    Route::get('/', [\App\Http\Controllers\SupervisorController::class, 'index'])->name('index');

    // Ver detalle de legajo
    Route::get('/legajo/{id}', [\App\Http\Controllers\SupervisorController::class, 'show'])->name('show');

    // Aprobar legajo (aprobación final)
    Route::post('/legajo/{id}/aprobar', [\App\Http\Controllers\SupervisorController::class, 'aprobar'])->name('aprobar');

    // Rechazar legajo con observaciones (devolver a Directivo)
    Route::post('/legajo/{id}/rechazar', [\App\Http\Controllers\SupervisorController::class, 'rechazar'])->name('rechazar');

    // Ver historial completo de un alumno
    Route::get('/alumno/{alumnoId}/historial', [\App\Http\Controllers\SupervisorController::class, 'historialAlumno'])->name('historial-alumno');
});

// Ruta de selección de perfil (antes de auth.php para que esté disponible sin autenticación)
Route::get('/profile-select', function () {
    return Inertia::render('Auth/ProfileSelect');
})->name('profile.select')->middleware('guest');

// Rutas de autenticación (login, register, logout, reset password, etc.)
require __DIR__.'/auth.php';