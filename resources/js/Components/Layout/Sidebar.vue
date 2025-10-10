<script setup>
import { ref, computed, onMounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const props = defineProps({
    sidebarOpen: {
        type: Boolean,
        default: false,
    },
    user: {
        type: Object,
        required: false,
    },
});

const emit = defineEmits(["update:sidebarOpen", "closeSidebar"]);

const hoveredItem = ref(null);
const showUserMenu = ref(false);
const showParametrosMenu = ref(false);
const page = usePage();

// Verificar si una ruta está activa
const isActive = (routeName) => {
    return page.url.startsWith("/" + routeName);
};

// Obtener el módulo actual según la URL
const moduloActual = computed(() => {
    const url = page.url;
    if (url.startsWith("/inscripciones")) return "Inscripciones";
    if (url.startsWith("/expediente")) return "Expediente";
    if (url.startsWith("/profesor/mis-materias")) return "Mis Materias";
    if (url.startsWith("/profesor/")) return "Profesor";
    if (url.startsWith("/mesas")) return "Mesas";
    if (url.startsWith("/admin")) return "Administración";
    if (url.startsWith("/profile")) return "Mi Perfil";
    if (url.startsWith("/dashboard")) return "Dashboard";
    return "Dashboard";
});

// Animación de entrada al cargar
const sidebarLoaded = ref(false);
onMounted(() => {
    setTimeout(() => {
        sidebarLoaded.value = true;
    }, 100);
});

// Verificar si el usuario es admin o profesor
const isAdminOrProfesor = computed(() => {
    return [1, 3].includes(page.props.auth.user?.tipo);
});

// Verificar si es profesor
const isProfesor = computed(() => {
    return page.props.auth.user?.tipo === 3;
});

// Menú items con íconos de Boxicons
const menuItems = computed(() => {
    const baseItems = [
        {
            name: "Inicio",
            route: "dashboard",
            icon: "bx-home-alt",
            path: "/dashboard",
        },
        {
            name: "Inscripciones",
            route: "inscripciones",
            icon: "bx-clipboard",
            path: "/inscripciones",
            onlyAlumno: true,
        },
        {
            name: "Expediente",
            route: "expediente",
            icon: "bx-folder-open",
            path: "/expediente",
        },
        {
            name: "Mesas",
            route: "mesas",
            icon: "bx-calendar-event",
            path: "/mesas",
            external: true,
            onlyAlumno: true,
        },
    ];

    // Agregar opciones para admin o profesor si se necesita en el futuro
    // if (isAdminOrProfesor.value) {
    //     baseItems.push({
    //         name: "Expedientes",
    //         route: "admin/expedientes",
    //         icon: "bx-folder-open",
    //         path: "/admin/expedientes",
    //     });
    // }

    // Filtrar items según tipo de usuario
    if (page.props.auth.user?.tipo === 4) {
        // Alumno: mostrar items de alumno y comunes
        return baseItems.filter(
            (item) => item.onlyAlumno || !item.hasOwnProperty("onlyAlumno")
        );
    } else if (page.props.auth.user?.tipo === 3) {
        // Profesor: mostrar inicio, expediente y parámetros
        return baseItems.filter((item) => !item.onlyAlumno);
    } else {
        // Admin: mostrar solo inicio, expediente y parámetros
        return baseItems.filter((item) => !item.onlyAlumno);
    }
});

// Subitems de Parámetros (solo para admin/profesor)
const parametrosItems = computed(() => {
    if (!isAdminOrProfesor.value) return [];

    return [
        {
            name: "Usuarios",
            route: "admin.usuarios.index",
            icon: "bx-user-circle",
            path: "/admin/usuarios",
        },
        {
            name: "Carreras",
            route: "admin.carreras.index",
            icon: "bx-briefcase",
            path: "/admin/carreras",
        },
        {
            name: "Materias",
            route: "admin.materias.index",
            icon: "bx-book",
            path: "/admin/materias",
        },
        {
            name: "Períodos Lectivos",
            route: "admin.periodos.index",
            icon: "bx-calendar",
            path: "/admin/periodos",
        },
        {
            name: "Mesas de Examen",
            route: "admin.mesas.index",
            icon: "bx-calendar-event",
            path: "/admin/mesas",
        },
        {
            name: "Correlativas",
            route: "admin.correlativas.index",
            icon: "bx-link",
            path: "/admin/correlativas",
        },
        {
            name: "Configuración",
            route: "admin.configuracion.index",
            icon: "bx-cog",
            path: "/admin/configuracion",
        },
    ];
});
</script>

<template>
    <aside
        :class="[
            'bg-gray-800 shadow-2xl transition-all duration-300 ease-in-out flex flex-col z-50',
            'fixed lg:relative h-screen',
            sidebarOpen ? 'w-56' : 'w-16',
            sidebarLoaded ? 'opacity-100' : 'opacity-0',
            !sidebarOpen
                ? '-translate-x-full lg:translate-x-0'
                : 'translate-x-0',
        ]"
    >
        <!-- Logo Section -->
        <div class="p-3 border-b border-gray-700">
            <div class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 flex items-center justify-center flex-shrink-0"
                >
                    <img
                        :src="
                            $page.props.configuracion.logo_url ||
                            '/images/logo-ies-original.png'
                        "
                        :alt="$page.props.configuracion.nombre_institucion"
                        class="w-full h-full object-contain"
                    />
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
                        <span class="text-sm font-bold text-white"
                            >IES G.M.Belgrano</span
                        >
                        <span class="text-xs text-gray-400">{{
                            moduloActual
                        }}</span>
                    </div>
                </transition>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-3 space-y-1 overflow-y-auto">
            <!-- Menu Items Normales -->
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
                        'justify-center': !sidebarOpen,
                    },
                ]"
                :style="{ animationDelay: `${index * 50}ms` }"
            >
                <i
                    :class="[
                        'bx text-lg transition-all duration-200',
                        item.icon,
                        isActive(item.route)
                            ? 'text-white'
                            : 'text-gray-400 group-hover:text-white',
                    ]"
                ></i>

                <transition
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="opacity-0 translate-x-2"
                    enter-to-class="opacity-100 translate-x-0"
                    leave-active-class="transition-all duration-200 ease-in"
                    leave-from-class="opacity-100 translate-x-0"
                    leave-to-class="opacity-0 translate-x-2"
                >
                    <span v-show="sidebarOpen" class="text-sm font-medium">
                        {{ item.name }}
                    </span>
                </transition>
            </component>

            <!-- Dropdown de Parámetros (solo para admin/profesor) -->
            <div v-if="isAdminOrProfesor" class="space-y-1">
                <!-- Botón principal de Parámetros -->
                <button
                    @click="showParametrosMenu = !showParametrosMenu"
                    :class="[
                        'w-full flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 group relative',
                        isActive('admin')
                            ? 'bg-blue-600 text-white shadow-lg'
                            : 'text-gray-400 hover:bg-gray-700 hover:text-white',
                        { 'justify-center': !sidebarOpen },
                    ]"
                >
                    <i
                        :class="[
                            'bx bx-cog text-lg transition-all duration-200',
                            isActive('admin')
                                ? 'text-white'
                                : 'text-gray-400 group-hover:text-white',
                        ]"
                    ></i>

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
                            class="text-sm font-medium flex-1 text-left"
                        >
                            Parámetros
                        </span>
                    </transition>

                    <i
                        v-show="sidebarOpen"
                        :class="[
                            'bx bx-chevron-down text-sm transition-transform duration-200',
                            showParametrosMenu ? 'rotate-180' : '',
                        ]"
                    ></i>
                </button>

                <!-- Subitems de Parámetros -->
                <transition
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition-all duration-200 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-2"
                >
                    <div
                        v-show="showParametrosMenu && sidebarOpen"
                        class="pl-3 space-y-1"
                    >
                        <Link
                            v-for="subitem in parametrosItems"
                            :key="subitem.route"
                            :href="subitem.path"
                            :class="[
                                'flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 group',
                                isActive(subitem.path.substring(1))
                                    ? 'bg-blue-500 text-white'
                                    : 'text-gray-400 hover:bg-gray-700 hover:text-white',
                            ]"
                        >
                            <i
                                :class="[
                                    'bx text-base transition-all duration-200',
                                    subitem.icon,
                                    isActive(subitem.path.substring(1))
                                        ? 'text-white'
                                        : 'text-gray-400 group-hover:text-white',
                                ]"
                            ></i>
                            <span class="text-sm font-medium">
                                {{ subitem.name }}
                            </span>
                        </Link>
                    </div>
                </transition>
            </div>
        </nav>

        <!-- User Section -->
        <div class="border-t border-gray-700 p-3">
            <div
                @click="showUserMenu = !showUserMenu"
                :class="[
                    'flex items-center space-x-3 p-2 rounded-lg cursor-pointer transition-all duration-200 group',
                    showUserMenu ? 'bg-gray-700' : 'hover:bg-gray-700',
                    !sidebarOpen && 'justify-center',
                ]"
            >
                <div class="relative flex-shrink-0">
                    <div
                        class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xs"
                    >
                        {{ user?.name?.charAt(0).toUpperCase() || "U" }}
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
                        <p class="text-xs font-semibold text-white truncate">
                            {{ user.name }}
                        </p>
                        <p class="text-xs text-gray-400 truncate">
                            {{ user.email }}
                        </p>
                    </div>
                </transition>

                <i
                    v-show="sidebarOpen"
                    :class="[
                        'bx bx-chevron-up text-base text-gray-400 transition-transform duration-200',
                        showUserMenu ? 'rotate-180' : '',
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
                            : 'absolute bottom-14 left-16 min-w-48 z-50',
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
</template>

<style scoped>
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

.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out forwards;
}
</style>
