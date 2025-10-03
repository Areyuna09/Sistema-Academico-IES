<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const sidebarOpen = ref(false);
const hoveredItem = ref(null);
const showUserMenu = ref(false);

defineProps({
    user: {
        type: Object,
        required: false
    }
});

const page = usePage();

// Verificar si una ruta está activa
const isActive = (routeName) => {
    return page.url.startsWith('/' + routeName);
};

// Obtener el módulo actual según la URL
const moduloActual = computed(() => {
    const url = page.url;
    if (url.startsWith('/inscripciones')) return 'Inscripciones';
    if (url.startsWith('/expediente')) return 'Expediente';
    if (url.startsWith('/mesas')) return 'Mesas';
    if (url.startsWith('/profile')) return 'Mi Perfil';
    if (url.startsWith('/dashboard')) return 'Dashboard';
    return 'Dashboard';
});

// Animación de entrada al cargar
const sidebarLoaded = ref(false);
onMounted(() => {
    setTimeout(() => {
        sidebarLoaded.value = true;
    }, 100);
});

// Menú items con íconos de Boxicons
const menuItems = [
    {
        name: 'Inicio',
        route: 'dashboard',
        icon: 'bx-home-alt',
        path: '/dashboard'
    },
    {
        name: 'Inscripciones',
        route: 'inscripciones',
        icon: 'bx-clipboard',
        path: '/inscripciones'
    },
    {
        name: 'Expediente',
        route: 'expediente',
        icon: 'bx-folder-open',
        path: '/expediente'
    },
    {
        name: 'Mesas',
        route: 'mesas',
        icon: 'bx-calendar-event',
        path: '/mesas',
        external: true
    }
];
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
                @click="sidebarOpen = false"
                class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden"
            ></div>
        </transition>

        <!-- Sidebar -->
        <aside
            :class="[
                'bg-gray-800 shadow-2xl transition-all duration-300 ease-in-out flex flex-col z-50',
                // Desktop: relative, parte del flujo
                // Móvil: fixed, overlay sobre contenido
                'fixed lg:relative h-screen',
                // Ancho del sidebar
                sidebarOpen ? 'w-56' : 'w-16',
                // Animación de entrada inicial
                sidebarLoaded ? 'opacity-100' : 'opacity-0',
                // En móvil cuando está cerrado: ocultar completamente
                // En desktop: siempre visible
                !sidebarOpen ? '-translate-x-full lg:translate-x-0' : 'translate-x-0'
            ]"
        >
            <!-- Logo Section -->
            <div class="p-3 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="bx bx-book-open text-white text-xl"></i>
                    </div>
                    <transition
                        enter-active-class="transition-all duration-300 ease-out"
                        enter-from-class="opacity-0 -translate-x-4"
                        enter-to-class="opacity-100 translate-x-0"
                        leave-active-class="transition-all duration-200 ease-in"
                        leave-from-class="opacity-100 translate-x-0"
                        leave-to-class="opacity-0 -translate-x-4"
                    >
                        <div v-show="sidebarOpen" class="flex flex-col">
                            <span class="text-sm font-bold text-white">IES</span>
                            <span class="text-xs text-gray-400">{{ moduloActual }}</span>
                        </div>
                    </transition>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-3 space-y-1 overflow-y-auto">
                <component
                    v-for="(item, index) in menuItems"
                    :key="item.route"
                    :is="item.external ? 'a' : Link"
                    :href="item.path"
                    @mouseenter="hoveredItem = item.route"
                    @mouseleave="hoveredItem = null"
                    :class="[
                        'flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 group relative',
                        isActive(item.route)
                            ? 'bg-blue-600 text-white shadow-lg'
                            : 'text-gray-400 hover:bg-gray-700 hover:text-white',
                        {
                            'animate-fade-in-up': sidebarLoaded,
                            'justify-center': !sidebarOpen
                        }
                    ]"
                    :style="{ animationDelay: `${index * 50}ms` }"
                >
                    <!-- Icon -->
                    <i
                        :class="[
                            'bx text-lg transition-all duration-200',
                            item.icon,
                            isActive(item.route) ? 'text-white' : 'text-gray-400 group-hover:text-white'
                        ]"
                    ></i>

                    <!-- Text -->
                    <transition
                        enter-active-class="transition-all duration-300 ease-out"
                        enter-from-class="opacity-0 translate-x-2"
                        enter-to-class="opacity-100 translate-x-0"
                        leave-active-class="transition-all duration-200 ease-in"
                        leave-from-class="opacity-100 translate-x-0"
                        leave-to-class="opacity-0 translate-x-2"
                    >
                        <span
                            v-show="sidebarOpen"
                            class="text-sm font-medium"
                        >
                            {{ item.name }}
                        </span>
                    </transition>
                </component>
            </nav>

            <!-- User Section -->
            <div class="border-t border-gray-700 p-3">
                <div
                    @click="showUserMenu = !showUserMenu"
                    :class="[
                        'flex items-center space-x-3 p-2 rounded-lg cursor-pointer transition-all duration-200 group',
                        showUserMenu ? 'bg-gray-700' : 'hover:bg-gray-700',
                        !sidebarOpen && 'justify-center'
                    ]"
                >
                    <!-- Avatar -->
                    <div class="relative flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xs">
                            {{ user?.name?.charAt(0).toUpperCase() || 'U' }}
                        </div>
                    </div>

                    <transition
                        enter-active-class="transition-all duration-300 ease-out"
                        enter-from-class="opacity-0 translate-x-2"
                        enter-to-class="opacity-100 translate-x-0"
                        leave-active-class="transition-all duration-200 ease-in"
                        leave-from-class="opacity-100 translate-x-0"
                        leave-to-class="opacity-0 translate-x-2"
                    >
                        <div v-if="user && sidebarOpen" class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-white truncate">{{ user.name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ user.email }}</p>
                        </div>
                    </transition>

                    <i
                        v-show="sidebarOpen"
                        :class="[
                            'bx bx-chevron-up text-base text-gray-400 transition-transform duration-200',
                            showUserMenu ? 'rotate-180' : ''
                        ]"
                    ></i>
                </div>

                <!-- User Dropdown -->
                <transition
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="opacity-0 -translate-y-2 scale-95"
                    enter-to-class="opacity-100 translate-y-0 scale-100"
                    leave-active-class="transition-all duration-200 ease-in"
                    leave-from-class="opacity-100 translate-y-0 scale-100"
                    leave-to-class="opacity-0 -translate-y-2 scale-95"
                >
                    <div
                        v-show="showUserMenu"
                        :class="[
                            'space-y-1 bg-gray-700 rounded-lg p-1 shadow-xl',
                            sidebarOpen
                                ? 'mt-2'
                                : 'absolute bottom-14 left-16 min-w-48 z-50'
                        ]"
                    >
                        <Link
                            :href="route('profile.edit')"
                            class="flex items-center space-x-2 px-3 py-2 rounded-md text-xs font-medium text-gray-300 hover:bg-gray-600 hover:text-white transition-all duration-150 group"
                        >
                            <i class="bx bx-user text-sm text-blue-400"></i>
                            <span>Mi Perfil</span>
                        </Link>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="w-full flex items-center space-x-2 px-3 py-2 rounded-md text-xs font-medium text-gray-300 hover:bg-red-600 hover:text-white transition-all duration-150 group"
                        >
                            <i class="bx bx-log-out text-sm text-red-400"></i>
                            <span>Cerrar Sesión</span>
                        </Link>
                    </div>
                </transition>
            </div>

        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden w-full lg:w-auto">
            <!-- Top Bar -->
            <header class="bg-gray-800 shadow-lg border-b border-gray-700">
                <div class="flex items-center justify-between px-6 py-2">
                    <!-- Botón hamburguesa para todos los modos -->
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="p-2 text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition-all duration-200 mr-3"
                    >
                        <i class="bx bx-menu text-xl"></i>
                    </button>

                    <div class="flex-1">
                        <slot name="header" />
                    </div>

                    <!-- Notifications -->
                    <div class="flex items-center space-x-2">
                        <button class="relative p-2 text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition-all duration-200">
                            <i class="bx bx-bell text-lg"></i>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Custom animations */
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes wiggle {
    0%, 100% {
        transform: rotate(0deg);
    }
    25% {
        transform: rotate(-10deg);
    }
    75% {
        transform: rotate(10deg);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out forwards;
}

.animate-wiggle {
    animation: wiggle 0.5s ease-in-out;
}
</style>
