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

            </div>
        </div>
    </header>
</template>
