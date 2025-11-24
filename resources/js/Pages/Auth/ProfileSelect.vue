<script setup>
import { Head, router } from "@inertiajs/vue3";
import { ref, onMounted, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const configuracion = computed(() => page.props.configuracion || {});
const nombreInstitucion = computed(() => configuracion.value.nombre_institucion || 'IES Gral. Manuel Belgrano');
const modulosConfig = computed(() => page.props.modulosConfig || {});
const estaModuloActivo = (clave) => {
    // Admins (tipo 1, 2) y profesores (tipo 3) siempre tienen acceso
    const tipoUsuario = page.props.auth.user?.tipo;
    if ([1, 2, 3].includes(tipoUsuario)) {
        return true;
    }
    // Para alumnos (tipo 4), verificar si el módulo está activo
    return modulosConfig.value[clave] !== false;
};

const logoUrl = computed(() => {
    const logoClaroUrl = configuracion.value.logo_url || '/images/logo-ies-original.png';
    const logoOscuroUrl = configuracion.value.logo_dark_url;

    if (!logoOscuroUrl) {
        return logoClaroUrl;
    }

    const bgColor = { r: 15, g: 23, b: 42 };
    const luminosity = (0.2126 * bgColor.r + 0.7152 * bgColor.g + 0.0722 * bgColor.b) / 255;
    return luminosity < 0.5 ? logoClaroUrl : logoOscuroUrl;
});

const savedProfiles = ref([]);
const loggingIn = ref(false);
const errorMessage = ref(null);

// Cargar perfiles guardados del localStorage
onMounted(() => {
    const stored = localStorage.getItem('saved_profiles');
    if (stored) {
        try {
            savedProfiles.value = JSON.parse(stored);
            console.log('========== PERFILES CARGADOS ==========');
            console.log('Perfiles:', savedProfiles.value);
            savedProfiles.value.forEach((profile, index) => {
                console.log(`Perfil ${index + 1}:`, {
                    nombre: profile.name,
                    dni: profile.dni,
                    avatar: profile.avatar,
                    tieneAvatar: !!profile.avatar
                });
            });
            console.log('=======================================');
            // Si no hay perfiles guardados, redirigir al login
            if (savedProfiles.value.length === 0) {
                sessionStorage.setItem('from_profile_select', 'true');
                router.visit(route('login'));
            }
        } catch (e) {
            console.error('Error parsing saved profiles:', e);
            savedProfiles.value = [];
            sessionStorage.setItem('from_profile_select', 'true');
            router.visit(route('login'));
        }
    } else {
        // Si no hay perfiles guardados, redirigir al login
        sessionStorage.setItem('from_profile_select', 'true');
        router.visit(route('login'));
    }
});

// Seleccionar un perfil - hacer login automático
const selectProfile = (profile) => {
    if (loggingIn.value) return; // Evitar clicks múltiples

    console.log('Perfil seleccionado:', profile);
    console.log('Tiene contraseña?', !!profile.password);

    loggingIn.value = true;

    // Si el perfil tiene contraseña guardada, hacer login automático
    if (profile.password) {
        console.log('Intentando login automático con DNI:', profile.dni);
        router.post(route('login'), {
            dni: profile.dni,
            password: profile.password,
            remember: true,
        }, {
            onSuccess: () => {
                // Login exitoso, redirigir al dashboard
                console.log('Login exitoso, redirigiendo al dashboard');
            },
            onError: (errors) => {
                // Si hay error de autenticación, mostrar mensaje o redirigir al login
                console.error('Error en login automático:', errors);
                loggingIn.value = false;

                // Si la contraseña es incorrecta, eliminar el perfil guardado
                if (errors.dni || errors.password) {
                    errorMessage.value = 'La contraseña guardada ya no es válida. Por favor, inicia sesión nuevamente.';

                    // Ocultar mensaje después de 5 segundos y redirigir
                    setTimeout(() => {
                        errorMessage.value = null;
                        // Ir al login con DNI prellenado
                        sessionStorage.setItem('selected_profile_dni', profile.dni);
                        sessionStorage.setItem('from_profile_select', 'true');
                        router.visit(route('login'));
                    }, 5000);
                }
            },
            onFinish: () => {
                loggingIn.value = false;
            }
        });
    } else {
        // Si no tiene contraseña, redirigir al login con el DNI prellenado
        console.log('No tiene contraseña, redirigiendo al login');
        sessionStorage.setItem('selected_profile_dni', profile.dni);
        sessionStorage.setItem('from_profile_select', 'true');
        router.visit(route('login'));
    }
};

// Eliminar perfil guardado
const removeProfile = (index, event) => {
    event.stopPropagation();
    savedProfiles.value.splice(index, 1);
    localStorage.setItem('saved_profiles', JSON.stringify(savedProfiles.value));
};

// Ir a login normal para agregar otra cuenta
const goToLogin = () => {
    sessionStorage.setItem('from_profile_select', 'true');
    router.visit(route('login'));
};

// Obtener iniciales del nombre
const getInitials = (name) => {
    if (!name) return 'U';
    const parts = name.split(' ');
    if (parts.length >= 2) {
        return parts[0].charAt(0).toUpperCase() + parts[1].charAt(0).toUpperCase();
    }
    return name.charAt(0).toUpperCase();
};
</script>

<template>
    <Head title="Seleccionar Perfil" />

    <div class="min-h-screen flex items-center justify-center relative overflow-hidden px-4 sm:px-6 py-8 sm:py-12 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900">
        <!-- Fondo con círculos decorativos animados -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/30 rounded-full blur-3xl animate-float-slow"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-500/30 rounded-full blur-3xl animate-float-slow-delayed"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-600/20 rounded-full blur-3xl animate-pulse-very-slow"></div>
        </div>

        <div class="w-full max-w-md relative z-10">
            <!-- Error Message -->
            <Transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 -translate-y-4"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-4"
            >
                <div
                    v-if="errorMessage"
                    class="mb-4 bg-red-500/90 backdrop-blur-sm border border-red-400 text-white px-6 py-4 rounded-xl shadow-lg flex items-start gap-3"
                >
                    <i class="bx bx-error-circle text-2xl flex-shrink-0"></i>
                    <div class="flex-1">
                        <p class="font-medium text-sm">{{ errorMessage }}</p>
                        <p class="text-xs text-red-100 mt-1">Redirigiendo al login en 5 segundos...</p>
                    </div>
                </div>
            </Transition>

            <!-- Profile Selection Card -->
            <div class="bg-gray-800/95 backdrop-blur-sm rounded-2xl shadow-2xl border border-gray-700/50 animate-slide-up overflow-hidden">
                <!-- Header con gradiente -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 sm:px-8 pt-6 sm:pt-8 pb-5 sm:pb-6">
                    <!-- Logo Institucional -->
                    <div class="flex justify-center mb-3 sm:mb-4">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center">
                            <img
                                :src="logoUrl"
                                :alt="nombreInstitucion"
                                class="w-full h-full object-contain drop-shadow-2xl"
                            />
                        </div>
                    </div>

                    <!-- Título -->
                    <div class="text-center">
                        <h1 class="text-xl sm:text-2xl font-bold text-white mb-1.5">Seleccionar Perfil</h1>
                        <p class="text-xs sm:text-sm text-blue-100/90">{{ nombreInstitucion }}</p>
                    </div>
                </div>

                <!-- Lista de perfiles -->
                <div class="px-6 sm:px-8 py-5 sm:py-6">
                    <!-- Loading overlay -->
                    <div v-if="loggingIn" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 rounded-2xl">
                        <div class="text-center">
                            <svg class="animate-spin h-12 w-12 mx-auto text-blue-500 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-white font-medium">Iniciando sesión...</p>
                        </div>
                    </div>

                    <div v-if="savedProfiles.length > 0" class="space-y-3">
                        <!-- Perfil guardado -->
                        <div
                            v-for="(profile, index) in savedProfiles"
                            :key="index"
                            @click="selectProfile(profile)"
                            class="group relative flex items-center gap-4 p-4 bg-gray-700/50 hover:bg-gray-700 border border-gray-600/50 hover:border-blue-500/50 rounded-xl cursor-pointer transition-all duration-200 hover:scale-[1.02] hover:shadow-lg"
                        >
                            <!-- Avatar -->
                            <div class="relative flex-shrink-0">
                                <div
                                    class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center overflow-hidden ring-2 ring-gray-600 group-hover:ring-blue-500 transition-all"
                                >
                                    <img
                                        v-if="profile.avatar && estaModuloActivo('avatares')"
                                        :src="`/storage/${profile.avatar}`"
                                        :alt="profile.name"
                                        class="w-full h-full object-cover"
                                    />
                                    <span
                                        v-else
                                        class="text-white font-bold text-lg"
                                    >
                                        {{ getInitials(profile.name) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Información del perfil -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-white font-semibold text-base truncate group-hover:text-blue-300 transition-colors">
                                    {{ profile.name }}
                                </h3>
                                <p class="text-gray-400 text-sm truncate">
                                    DNI: {{ profile.dni }}
                                </p>
                            </div>

                            <!-- Botón eliminar -->
                            <button
                                @click="removeProfile(index, $event)"
                                class="opacity-0 group-hover:opacity-100 p-2 text-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all"
                                title="Eliminar perfil"
                            >
                                <i class="bx bx-x text-xl"></i>
                            </button>

                            <!-- Icono flecha -->
                            <div class="text-gray-400 group-hover:text-blue-400 transition-colors">
                                <i class="bx bx-chevron-right text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje si no hay perfiles -->
                    <div v-else class="text-center py-8">
                        <div class="w-20 h-20 mx-auto mb-4 bg-gray-700/50 rounded-full flex items-center justify-center">
                            <i class="bx bx-user-circle text-4xl text-gray-500"></i>
                        </div>
                        <p class="text-gray-400 text-sm mb-2">No hay perfiles guardados</p>
                        <p class="text-gray-500 text-xs">Inicia sesión con "Recuérdame" para guardar tu perfil</p>
                    </div>

                    <!-- Botón para agregar otra cuenta -->
                    <button
                        @click="goToLogin"
                        class="mt-4 w-full py-3 px-4 bg-gray-700/50 hover:bg-gray-700 border border-gray-600 hover:border-blue-500/50 text-white font-semibold text-base rounded-lg transition-all duration-200 flex items-center justify-center gap-2 group"
                    >
                        <i class="bx bx-plus-circle text-xl group-hover:text-blue-400 transition-colors"></i>
                        <span>Usar otra cuenta</span>
                    </button>
                </div>
            </div>

            <!-- Bottom Text -->
            <div class="mt-5 text-center">
                <p class="text-white/50 text-xs">
                    © {{ new Date().getFullYear() }} {{ nombreInstitucion }}
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float-slow {
    0%, 100% {
        transform: translate(0, 0) scale(1);
    }
    50% {
        transform: translate(20px, -20px) scale(1.05);
    }
}

@keyframes float-slow-delayed {
    0%, 100% {
        transform: translate(0, 0) scale(1);
    }
    50% {
        transform: translate(-20px, 20px) scale(1.05);
    }
}

@keyframes pulse-very-slow {
    0%, 100% {
        opacity: 0.15;
        transform: translate(-50%, -50%) scale(1);
    }
    50% {
        opacity: 0.25;
        transform: translate(-50%, -50%) scale(1.05);
    }
}

.animate-slide-up {
    animation: slide-up 0.6s ease-out;
}

.animate-float-slow {
    animation: float-slow 20s ease-in-out infinite;
}

.animate-float-slow-delayed {
    animation: float-slow-delayed 22s ease-in-out infinite;
    animation-delay: -8s;
}

.animate-pulse-very-slow {
    animation: pulse-very-slow 12s ease-in-out infinite;
}

@media (prefers-reduced-motion: reduce) {
    .animate-slide-up,
    .animate-float-slow,
    .animate-float-slow-delayed,
    .animate-pulse-very-slow {
        animation: none;
    }
}
</style>
