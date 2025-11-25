<script setup>
import { ref, onMounted, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

defineProps({
    sidebarOpen: {
        type: Boolean,
        default: false
    },
    showHelpButton: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['toggleSidebar', 'showHelp']);

// Estado de notificaciones
const notificaciones = ref([]);
const noLeidasCount = ref(0);
const dropdownOpen = ref(false);
const cargando = ref(false);

// Cargar notificaciones
const cargarNotificaciones = async () => {
    try {
        cargando.value = true;
        const response = await axios.get(route('notificaciones.index'));
        notificaciones.value = response.data.notificaciones;
        noLeidasCount.value = response.data.no_leidas_count;
    } catch (error) {
        console.error('Error al cargar notificaciones:', error);
    } finally {
        cargando.value = false;
    }
};

// Marcar como leída
const marcarLeida = async (notificacion) => {
    if (notificacion.leida) return;

    try {
        await axios.post(route('notificaciones.marcar-leida', notificacion.id));
        notificacion.leida = true;
        noLeidasCount.value = Math.max(0, noLeidasCount.value - 1);

        // Si tiene URL, redirigir
        if (notificacion.url) {
            router.visit(notificacion.url);
        }
    } catch (error) {
        console.error('Error al marcar notificación:', error);
    }
};

// Marcar todas como leídas
const marcarTodasLeidas = async () => {
    try {
        await axios.post(route('notificaciones.marcar-todas-leidas'));
        notificaciones.value.forEach(n => n.leida = true);
        noLeidasCount.value = 0;
    } catch (error) {
        console.error('Error al marcar todas:', error);
    }
};

// Toggle dropdown
const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
    if (dropdownOpen.value && notificaciones.value.length === 0) {
        cargarNotificaciones();
    }
};

// Cerrar al hacer click fuera
const cerrarDropdown = (event) => {
    if (!event.target.closest('.notifications-dropdown')) {
        dropdownOpen.value = false;
    }
};

// Polling cada 30 segundos
const iniciarPolling = () => {
    setInterval(async () => {
        try {
            const response = await axios.get(route('notificaciones.contador'));
            noLeidasCount.value = response.data.count;
        } catch (error) {
            console.error('Error en polling:', error);
        }
    }, 30000);
};

// Escuchar evento de nueva notificación
const escucharNuevasNotificaciones = () => {
    window.addEventListener('nueva-notificacion', () => {
        cargarNotificaciones();
    });
};

onMounted(() => {
    cargarNotificaciones();
    iniciarPolling();
    escucharNuevasNotificaciones();
    document.addEventListener('click', cerrarDropdown);
});

// Icono por tipo de notificación
const getIcono = (notificacion) => {
    return notificacion.icono || 'bx-bell';
};

// Color por tipo
const getColor = (notificacion) => {
    const colores = {
        'blue': 'text-blue-600',
        'green': 'text-green-600',
        'red': 'text-red-600',
        'yellow': 'text-yellow-600',
    };
    return colores[notificacion.color] || 'text-gray-600';
};

// Notificaciones recientes (últimas 5)
const notificacionesRecientes = computed(() => {
    return notificaciones.value.slice(0, 5);
});
</script>

<template>
    <header class="bg-gray-800 shadow-lg border-b border-gray-700">
        <div class="flex items-center justify-between px-3 sm:px-6 py-2">
            <!-- Botón hamburguesa -->
            <button
                @click="emit('toggleSidebar')"
                class="p-1.5 sm:p-2 text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition-all duration-200 mr-2 sm:mr-3"
            >
                <i class="bx bx-menu text-lg sm:text-xl"></i>
            </button>

            <div class="flex-1 min-w-0">
                <slot name="header" />
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-1 sm:space-x-2">
                <!-- Botón de ayuda/tutorial (solo visible si showHelpButton=true) -->
                <button
                    v-if="showHelpButton"
                    @click="emit('showHelp')"
                    class="onboarding-help-button p-1.5 sm:p-2 text-gray-400 hover:bg-blue-600 hover:text-white rounded-lg transition-all duration-200"
                    title="Ver tutorial del sistema"
                >
                    <i class="bx bx-help-circle text-base sm:text-lg"></i>
                </button>

                <!-- Notifications -->
                <div class="relative notifications-dropdown">
                    <button
                        @click="toggleDropdown"
                        class="relative p-1.5 sm:p-2 text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition-all duration-200"
                        title="Notificaciones"
                    >
                        <i class="bx bx-bell text-base sm:text-lg"></i>
                        <span
                            v-if="noLeidasCount > 0"
                            class="absolute top-0 right-0 sm:top-0.5 sm:right-0.5 flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 bg-red-500 text-white text-[10px] sm:text-xs font-bold rounded-full"
                        >
                            {{ noLeidasCount > 9 ? '9+' : noLeidasCount }}
                        </span>
                    </button>

                    <!-- Dropdown de notificaciones -->
                    <div
                        v-if="dropdownOpen"
                        class="fixed sm:absolute inset-x-2 sm:inset-x-auto sm:right-0 top-14 sm:top-auto sm:mt-2 w-auto sm:w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
                    >
                        <!-- Header -->
                        <div class="flex items-center justify-between px-3 sm:px-4 py-2 sm:py-3 border-b border-gray-200">
                            <h3 class="text-xs sm:text-sm font-semibold text-gray-900">Notificaciones</h3>
                            <button
                                v-if="noLeidasCount > 0"
                                @click="marcarTodasLeidas"
                                class="text-[10px] sm:text-xs text-blue-600 hover:text-blue-800 whitespace-nowrap"
                            >
                                Marcar leídas
                            </button>
                        </div>

                        <!-- Lista de notificaciones -->
                        <div class="max-h-[60vh] sm:max-h-96 overflow-y-auto">
                            <div v-if="cargando" class="p-6 sm:p-8 text-center text-gray-500">
                                <i class="bx bx-loader-alt bx-spin text-xl sm:text-2xl"></i>
                                <p class="mt-2 text-xs sm:text-sm">Cargando...</p>
                            </div>

                            <div v-else-if="notificacionesRecientes.length === 0" class="p-6 sm:p-8 text-center text-gray-500">
                                <i class="bx bx-bell-off text-3xl sm:text-4xl mb-2"></i>
                                <p class="text-xs sm:text-sm">No tienes notificaciones</p>
                            </div>

                            <div v-else>
                                <div
                                    v-for="notificacion in notificacionesRecientes"
                                    :key="notificacion.id"
                                    @click="marcarLeida(notificacion)"
                                    :class="[
                                        'px-3 sm:px-4 py-2 sm:py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer',
                                        !notificacion.leida && 'bg-blue-50'
                                    ]"
                                >
                                    <div class="flex items-start gap-2 sm:gap-0">
                                        <div :class="['mr-0 sm:mr-3 mt-0.5 flex-shrink-0', getColor(notificacion)]">
                                            <i :class="['bx text-base sm:text-xl', getIcono(notificacion)]"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs sm:text-sm font-medium text-gray-900 line-clamp-1 sm:truncate">
                                                {{ notificacion.titulo }}
                                            </p>
                                            <p class="text-[10px] sm:text-xs text-gray-600 mt-0.5 sm:mt-1 line-clamp-2">
                                                {{ notificacion.mensaje }}
                                            </p>
                                            <p class="text-[10px] sm:text-xs text-gray-400 mt-0.5 sm:mt-1">
                                                Hace {{ notificacion.tiempo_transcurrido }}
                                            </p>
                                        </div>
                                        <div v-if="!notificacion.leida" class="ml-1 sm:ml-2 flex-shrink-0">
                                            <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-blue-600 rounded-full block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer - Ver todas -->
                        <div v-if="notificaciones.length > 5" class="px-3 sm:px-4 py-2 sm:py-3 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                            <button
                                @click="dropdownOpen = false"
                                class="w-full text-xs sm:text-sm text-blue-600 hover:text-blue-800 font-medium"
                            >
                                Ver todas las notificaciones
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>
