import { driver } from 'driver.js';
import 'driver.js/dist/driver.css';

/**
 * Composable para gestionar el onboarding de profesores
 * Tour guiado paso a paso con Driver.js
 */
export function useProfesorOnboarding() {
    const STORAGE_KEY = 'profesor_onboarding_completed';

    /**
     * Verifica si el usuario ya completó el onboarding
     */
    const hasCompletedOnboarding = () => {
        return localStorage.getItem(STORAGE_KEY) === 'true';
    };

    /**
     * Marca el onboarding como completado
     */
    const markAsCompleted = () => {
        localStorage.setItem(STORAGE_KEY, 'true');
    };

    /**
     * Reinicia el onboarding (útil para testing o si el usuario quiere verlo de nuevo)
     */
    const resetOnboarding = () => {
        localStorage.removeItem(STORAGE_KEY);
    };

    /**
     * Crea y configura el tour guiado para profesores
     */
    const createProfesorTour = () => {
        const driverObj = driver({
            showProgress: true,
            nextBtnText: 'Siguiente',
            prevBtnText: 'Anterior',
            doneBtnText: 'Finalizar',
            showButtons: ['next', 'previous', 'close'],

            // Configuración de animaciones y overlays
            animate: true,
            overlayOpacity: 0.8,
            smoothScroll: true,
            allowClose: true,
            overlayClickNext: false,

            // Popover normal (no fullscreen)
            popoverClass: 'onboarding-popover-custom',

            // Callbacks
            onDestroyed: () => {
                markAsCompleted();
            },

            // Steps del tour
            steps: [
                // PASO 1: Bienvenida - destacamos los tabs
                {
                    element: '.onboarding-tabs',
                    popover: {
                        title: '¡Bienvenido al Panel de Profesores!',
                        description: `
                            <div class="space-y-4">
                                <p>Te guiaremos paso a paso para que conozcas todas las funcionalidades del sistema.</p>

                                <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded">
                                    <p class="text-sm text-blue-800"><strong>💡 Consejo:</strong> Puedes cerrar este tour en cualquier momento y volver a verlo haciendo clic en el botón de ayuda <i class="bx bx-help-circle"></i> en la barra superior.</p>
                                </div>

                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div class="flex items-center gap-2 bg-gray-50 p-2 rounded">
                                        <i class="bx bx-book text-orange-600 text-xl"></i>
                                        <span>Gestionar materias</span>
                                    </div>
                                    <div class="flex items-center gap-2 bg-gray-50 p-2 rounded">
                                        <i class="bx bx-group text-teal-600 text-xl"></i>
                                        <span>Ver alumnos</span>
                                    </div>
                                    <div class="flex items-center gap-2 bg-gray-50 p-2 rounded">
                                        <i class="bx bx-calendar-check text-green-600 text-xl"></i>
                                        <span>Registrar asistencias</span>
                                    </div>
                                    <div class="flex items-center gap-2 bg-gray-50 p-2 rounded">
                                        <i class="bx bx-edit text-purple-600 text-xl"></i>
                                        <span>Cargar notas</span>
                                    </div>
                                </div>
                            </div>
                        `,
                        side: 'bottom',
                        align: 'center',
                    }
                },

                // PASO 2: Los tabs principales
                {
                    element: '.onboarding-tabs',
                    popover: {
                        title: 'Navegación Principal',
                        description: `
                            <div class="space-y-4">
                                <p>Estas son las tres secciones principales donde trabajarás:</p>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 text-center">
                                        <i class="bx bx-book text-orange-600 text-3xl mb-2"></i>
                                        <strong class="block text-orange-900">Materias</strong>
                                        <p class="text-xs text-orange-700 mt-1">Gestiona y configura tus materias</p>
                                    </div>
                                    <div class="bg-teal-50 border border-teal-200 rounded-lg p-3 text-center">
                                        <i class="bx bx-group text-teal-600 text-3xl mb-2"></i>
                                        <strong class="block text-teal-900">Alumnos</strong>
                                        <p class="text-xs text-teal-700 mt-1">Registra asistencias y notas</p>
                                    </div>
                                    <div class="bg-gray-50 border border-gray-300 rounded-lg p-3 text-center">
                                        <i class="bx bx-file text-gray-600 text-3xl mb-2"></i>
                                        <strong class="block text-gray-900">Legajos</strong>
                                        <p class="text-xs text-gray-700 mt-1">Consulta historiales académicos</p>
                                    </div>
                                </div>

                                <!-- Video demostrativo -->
                                <div class="onboarding-video-container">
                                    <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                                        <div class="text-center">
                                            <i class="bx bx-play-circle text-6xl text-gray-400 mb-2"></i>
                                            <p class="text-sm text-gray-600">Video: Navegación entre secciones</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `,
                        side: 'bottom',
                        align: 'center',
                    }
                },

                // PASO 3: Tarjeta de materia (solo si existe)
                {
                    element: '.onboarding-materia-card',
                    popover: {
                        title: 'Tarjeta de Materia - Acciones Rápidas',
                        onPopoverRender: (popover) => {
                            // Si no existe la tarjeta, saltamos este paso
                            if (!document.querySelector('.onboarding-materia-card')) {
                                return false;
                            }
                        },
                        description: `
                            <div class="space-y-4">
                                <p>Cada materia tiene acciones rápidas para gestionar tu trabajo diario:</p>

                                <div class="space-y-2">
                                    <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-lg p-3">
                                        <i class="bx bx-cog text-amber-600 text-2xl flex-shrink-0"></i>
                                        <div>
                                            <strong class="text-amber-900">Configurar</strong>
                                            <p class="text-xs text-amber-700 mt-1">Define nota de promoción, regularidad y % de asistencia</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                        <i class="bx bx-show text-blue-600 text-2xl flex-shrink-0"></i>
                                        <div>
                                            <strong class="text-blue-900">Ver detalle</strong>
                                            <p class="text-xs text-blue-700 mt-1">Accede a información completa, alumnos y estadísticas</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded">
                                    <p class="text-sm text-yellow-800">
                                        <strong>⚠️ Importante:</strong> Configura los parámetros académicos ANTES de cargar la primera nota.
                                    </p>
                                </div>

                                <!-- Video demostrativo -->
                                <div class="onboarding-video-container">
                                    <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                                        <div class="text-center">
                                            <i class="bx bx-play-circle text-6xl text-gray-400 mb-2"></i>
                                            <p class="text-sm text-gray-600">Video: Configurar materia</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `,
                        side: 'bottom',
                        align: 'start',
                    }
                },

                // PASO 4: Tab de Alumnos
                {
                    element: '.onboarding-tab-alumnos',
                    popover: {
                        title: 'Sección de Alumnos - Asistencias y Notas',
                        description: `
                            <div class="space-y-4">
                                <p>Aquí gestionas el día a día de tus materias:</p>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="bg-blue-50 border-2 border-blue-300 rounded-lg p-4 text-center">
                                        <i class="bx bx-calendar-check text-blue-600 text-4xl mb-2"></i>
                                        <strong class="block text-blue-900 mb-1">Asistencia Diaria</strong>
                                        <p class="text-xs text-blue-700">Registra presente, ausente o tarde para cada alumno</p>
                                    </div>
                                    <div class="bg-purple-50 border-2 border-purple-300 rounded-lg p-4 text-center">
                                        <i class="bx bx-edit text-purple-600 text-4xl mb-2"></i>
                                        <strong class="block text-purple-900 mb-1">Cargar Notas</strong>
                                        <p class="text-xs text-purple-700">Ingresa notas de parciales, prácticos o finales</p>
                                    </div>
                                </div>

                                <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded">
                                    <p class="text-sm text-blue-800">
                                        <strong>💡 Automático:</strong> El sistema calcula el % de asistencia y estado académico por ti.
                                    </p>
                                </div>

                                <!-- Video demostrativo -->
                                <div class="onboarding-video-container">
                                    <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                                        <div class="text-center">
                                            <i class="bx bx-play-circle text-6xl text-gray-400 mb-2"></i>
                                            <p class="text-sm text-gray-600">Video: Registrar asistencias y notas</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `,
                        side: 'bottom',
                        align: 'center',
                    }
                },

                // PASO 5: Sistema Inteligente de Notas (destacamos el contenedor principal)
                {
                    element: 'main',
                    popover: {
                        title: '🤖 Sistema Inteligente de Notas',
                        description: `
                            <div class="space-y-4">
                                <p class="text-center font-semibold">El sistema procesa las notas automáticamente según el tipo:</p>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-green-50 border-2 border-green-300 rounded-lg p-4">
                                        <div class="text-center mb-3">
                                            <i class="bx bx-check-double text-green-600 text-5xl"></i>
                                            <strong class="block text-green-900 text-lg mt-2">Parciales / Prácticos</strong>
                                        </div>
                                        <ul class="space-y-2 text-sm">
                                            <li class="flex items-start gap-2">
                                                <i class="bx bx-check text-green-600 text-lg flex-shrink-0"></i>
                                                <span>Se aprueban automáticamente</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <i class="bx bx-check text-green-600 text-lg flex-shrink-0"></i>
                                                <span>Van directo al legajo</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <i class="bx bx-check text-green-600 text-lg flex-shrink-0"></i>
                                                <span>Calculan estado académico</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="bg-amber-50 border-2 border-amber-300 rounded-lg p-4">
                                        <div class="text-center mb-3">
                                            <i class="bx bx-time text-amber-600 text-5xl"></i>
                                            <strong class="block text-amber-900 text-lg mt-2">Notas de Final</strong>
                                        </div>
                                        <ul class="space-y-2 text-sm">
                                            <li class="flex items-start gap-2">
                                                <i class="bx bx-time text-amber-600 text-lg flex-shrink-0"></i>
                                                <span>Quedan pendientes</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <i class="bx bx-time text-amber-600 text-lg flex-shrink-0"></i>
                                                <span>Requieren aprobación</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <i class="bx bx-time text-amber-600 text-lg flex-shrink-0"></i>
                                                <span>Bedel/Admin las transfiere</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Video demostrativo -->
                                <div class="onboarding-video-container">
                                    <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                                        <div class="text-center">
                                            <i class="bx bx-play-circle text-6xl text-gray-400 mb-2"></i>
                                            <p class="text-sm text-gray-600">Video: Cómo funciona el sistema de notas</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `,
                        side: 'bottom',
                        align: 'center',
                    }
                },

                // PASO 6: Tab de Legajos
                {
                    element: '.onboarding-tab-legajos',
                    popover: {
                        title: 'Consultar Legajos Académicos',
                        description: `
                            <div class="space-y-4">
                                <p>Accede al historial académico completo de cualquier estudiante:</p>

                                <div class="grid grid-cols-1 gap-3">
                                    <div class="bg-gray-50 border-2 border-gray-300 rounded-lg p-4">
                                        <div class="flex items-center gap-3 mb-2">
                                            <i class="bx bx-search-alt text-gray-600 text-3xl"></i>
                                            <strong class="text-gray-900">Búsqueda por DNI</strong>
                                        </div>
                                        <p class="text-sm text-gray-700 ml-12">Ingresa el DNI del alumno y consulta su expediente completo en segundos</p>
                                    </div>

                                    <div class="bg-gray-50 border-2 border-gray-300 rounded-lg p-4">
                                        <div class="flex items-center gap-3 mb-2">
                                            <i class="bx bx-history text-gray-600 text-3xl"></i>
                                            <strong class="text-gray-900">Historial Completo</strong>
                                        </div>
                                        <p class="text-sm text-gray-700 ml-12">Visualiza todas las materias cursadas, notas, estado académico y más, organizado por año</p>
                                    </div>
                                </div>

                                <!-- Video demostrativo -->
                                <div class="onboarding-video-container">
                                    <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                                        <div class="text-center">
                                            <i class="bx bx-play-circle text-6xl text-gray-400 mb-2"></i>
                                            <p class="text-sm text-gray-600">Video: Buscar y consultar legajos</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `,
                        side: 'bottom',
                        align: 'center',
                    }
                },

                // PASO 7: Finalización
                {
                    popover: {
                        title: '¡Tour Completado! 🎉',
                        description: `
                            <div class="space-y-4">
                                <p class="text-center">Ya conoces todas las funcionalidades principales del sistema.</p>

                                <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 p-4 rounded">
                                    <div class="flex items-start gap-3">
                                        <i class="bx bx-bulb text-blue-600 text-2xl flex-shrink-0"></i>
                                        <div>
                                            <strong class="text-blue-900">Recuerda:</strong>
                                            <p class="text-sm text-blue-800 mt-1">Configura los parámetros académicos de tu materia antes de cargar la primera nota para que el sistema funcione correctamente.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center text-sm text-gray-600 pt-2 border-t">
                                    <p>¿Necesitas ver el tutorial de nuevo?</p>
                                    <p class="mt-1">Haz clic en el botón de ayuda <i class="bx bx-help-circle text-blue-600"></i> en la barra superior</p>
                                </div>
                            </div>
                        `,
                        side: 'bottom',
                        align: 'center',
                    }
                }
            ]
        });

        return driverObj;
    };

    /**
     * Inicia el tour automáticamente si es la primera vez
     */
    const startTourIfFirstTime = () => {
        if (!hasCompletedOnboarding()) {
            // Pequeño delay para asegurar que el DOM está completamente renderizado
            setTimeout(() => {
                const tour = createProfesorTour();
                tour.drive();
            }, 500);
        }
    };

    /**
     * Fuerza el inicio del tour (útil para el botón de ayuda)
     */
    const startTour = () => {
        const tour = createProfesorTour();
        tour.drive();
    };

    return {
        hasCompletedOnboarding,
        markAsCompleted,
        resetOnboarding,
        startTourIfFirstTime,
        startTour,
    };
}
