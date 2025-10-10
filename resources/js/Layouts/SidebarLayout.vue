<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import Sidebar from '@/Components/Layout/Sidebar.vue';
import Navbar from '@/Components/Layout/Navbar.vue';

// Función para detectar si estamos en desktop (>= 1024px = lg breakpoint de Tailwind)
const isDesktop = () => window.innerWidth >= 1024;

// Inicializar sidebar expandido en desktop, colapsado en móvil
const sidebarOpen = ref(isDesktop());

defineProps({
    user: {
        type: Object,
        required: false
    }
});

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const closeSidebar = () => {
    sidebarOpen.value = false;
};

// Manejar resize de ventana para ajustar estado inicial
const handleResize = () => {
    // Solo auto-expandir en desktop si el sidebar está colapsado
    // Esto respeta la decisión del usuario si manualmente colapsó el sidebar en desktop
    if (isDesktop() && !sidebarOpen.value) {
        sidebarOpen.value = true;
    }
    // En móvil, siempre colapsar
    if (!isDesktop() && sidebarOpen.value) {
        sidebarOpen.value = false;
    }
};

onMounted(() => {
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
});
</script>

<template>
    <div class="flex h-screen bg-gray-50">
        <!-- Overlay con blur para móviles cuando sidebar está abierto -->
        <transition
            enter-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="sidebarOpen"
                @click="closeSidebar"
                class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden"
            ></div>
        </transition>

        <!-- Sidebar Component -->
        <Sidebar
            :sidebar-open="sidebarOpen"
            :user="user"
            @close-sidebar="closeSidebar"
        />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden w-full lg:w-auto">
            <!-- Navbar Component -->
            <Navbar
                :sidebar-open="sidebarOpen"
                @toggle-sidebar="toggleSidebar"
            >
                <template #header>
                    <slot name="header" />
                </template>
            </Navbar>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <slot />
            </main>
        </div>
    </div>
</template>
