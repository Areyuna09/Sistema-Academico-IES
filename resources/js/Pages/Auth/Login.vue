<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { computed } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    dni: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};

// Obtener configuración institucional
const page = usePage();
const configuracion = computed(() => page.props.configuracion || {});
const nombreInstitucion = computed(() => configuracion.value.nombre_institucion || 'IES Gral. Manuel Belgrano');

/**
 * Selección inteligente de logo basada en el color de fondo
 * Detecta la luminosidad del fondo y elige el logo apropiado:
 * - Fondo oscuro (slate-900, blue-900, etc.) -> usa logo claro (letras blancas)
 * - Fondo claro -> usa logo oscuro (letras negras)
 */
const logoUrl = computed(() => {
    const logoClaroUrl = configuracion.value.logo_url || '/images/logo-ies-original.png';
    const logoOscuroUrl = configuracion.value.logo_dark_url;

    // Si no hay logo oscuro configurado, usar siempre el claro
    if (!logoOscuroUrl) {
        return logoClaroUrl;
    }

    // El fondo actual es un gradiente oscuro (from-slate-900 via-blue-900 to-indigo-900)
    // Para fondos oscuros, usar logo claro (con letras blancas)
    // Para fondos claros, usar logo oscuro (con letras negras)

    // Slate-900 (rgb(15, 23, 42)) tiene luminosidad muy baja
    // Calculamos luminosidad aproximada del fondo principal
    const bgColor = { r: 15, g: 23, b: 42 }; // slate-900

    // Fórmula de luminosidad relativa (ITU BT.709)
    const luminosity = (0.2126 * bgColor.r + 0.7152 * bgColor.g + 0.0722 * bgColor.b) / 255;

    // Si luminosidad < 0.5 = fondo oscuro -> usar logo claro
    // Si luminosidad >= 0.5 = fondo claro -> usar logo oscuro
    return luminosity < 0.5 ? logoClaroUrl : logoOscuroUrl;
});
</script>

<template>
    <Head title="Iniciar Sesión" />

    <div class="min-h-screen flex items-center justify-center relative overflow-hidden px-4 sm:px-6 py-8 sm:py-12 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900">
        <!-- Fondo con círculos decorativos animados -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/30 rounded-full blur-3xl animate-float-slow"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-500/30 rounded-full blur-3xl animate-float-slow-delayed"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-600/20 rounded-full blur-3xl animate-pulse-very-slow"></div>
            <div class="absolute top-20 left-1/4 w-64 h-64 bg-purple-500/25 rounded-full blur-3xl animate-float-reverse"></div>
            <div class="absolute bottom-20 right-1/4 w-80 h-80 bg-cyan-500/25 rounded-full blur-3xl animate-float-slow"></div>
        </div>

        <div class="w-full max-w-sm relative z-10">
            <!-- Login Card -->
            <div class="bg-gray-800/95 backdrop-blur-sm rounded-2xl shadow-2xl border border-gray-700/50 animate-slide-up overflow-hidden">
                <!-- Header con gradiente -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 sm:px-8 pt-6 sm:pt-8 pb-5 sm:pb-6">
                    <!-- Logo Institucional -->
                    <div class="flex justify-center mb-3 sm:mb-4">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 flex items-center justify-center">
                            <img
                                :src="logoUrl"
                                :alt="nombreInstitucion"
                                class="w-full h-full object-contain drop-shadow-2xl"
                            />
                        </div>
                    </div>

                    <!-- Título institucional -->
                    <div class="text-center">
                        <h1 class="text-xl sm:text-2xl font-bold text-white mb-1.5">Sistema Académico</h1>
                        <p class="text-xs sm:text-sm text-blue-100/90">{{ nombreInstitucion }}</p>
                    </div>
                </div>

                <!-- Contenido del formulario -->
                <div class="px-6 sm:px-8 py-5 sm:py-6">

                <!-- Status Message -->
                <div v-if="status" class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-500 bg-opacity-10 border border-green-500 rounded-lg">
                    <p class="text-xs sm:text-sm text-green-400 flex items-center justify-center">
                        <i class="bx bx-check-circle text-base sm:text-lg mr-2"></i>
                        {{ status }}
                    </p>
                </div>

                    <form @submit.prevent="submit" class="space-y-4">
                        <!-- DNI Input -->
                        <div>
                            <label for="dni" class="block text-sm font-medium text-gray-300 mb-1.5">Número de DNI</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="bx bx-user text-gray-400 text-lg"></i>
                                </div>
                                <input
                                    id="dni"
                                    type="text"
                                    v-model="form.dni"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    maxlength="10"
                                    pattern="[0-9]+"
                                    inputmode="numeric"
                                    placeholder="Ingresa tu DNI"
                                    class="w-full pl-11 pr-4 py-2.5 text-sm bg-gray-700/50 border border-gray-600 text-white placeholder-gray-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-gray-700 transition-all duration-200"
                                />
                            </div>
                            <p v-if="form.errors.dni" class="mt-1.5 text-xs text-red-400 flex items-center">
                                <i class="bx bx-error-circle mr-1"></i>
                                {{ form.errors.dni }}
                            </p>
                        </div>

                        <!-- Password Input -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Contraseña</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="bx bx-lock-alt text-gray-400 text-lg"></i>
                                </div>
                                <input
                                    id="password"
                                    type="password"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Ingresa tu contraseña"
                                    class="w-full pl-11 pr-4 py-2.5 text-sm bg-gray-700/50 border border-gray-600 text-white placeholder-gray-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-gray-700 transition-all duration-200"
                                />
                            </div>
                            <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-400 flex items-center">
                                <i class="bx bx-error-circle mr-1"></i>
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center cursor-pointer group">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    v-model="form.remember"
                                    class="w-4 h-4 text-blue-600 bg-gray-700/50 border-gray-600 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer"
                                />
                                <span class="ml-2.5 text-gray-300 group-hover:text-white transition-colors">
                                    Recuérdame
                                </span>
                            </label>
                            <a v-if="canResetPassword" href="#" class="text-blue-400 hover:text-blue-300 transition-colors font-medium">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-semibold text-base rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center"
                        >
                            <i v-if="!form.processing" class="bx bx-log-in-circle text-xl mr-2"></i>
                            <svg v-else class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ form.processing ? 'Accediendo...' : 'Iniciar Sesión' }}
                        </button>

                        <!-- Footer Info -->
                        <div class="pt-4 border-t border-gray-700/50">
                            <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-3 text-center">
                                <p class="text-xs text-gray-300 mb-0.5">
                                    <i class="bx bx-info-circle text-blue-400"></i>
                                    ¿Primera vez en el sistema?
                                </p>
                                <p class="text-xs text-blue-300 font-medium">
                                    Tu contraseña inicial es tu número de DNI
                                </p>
                            </div>
                        </div>
                    </form>
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

@keyframes float-reverse {
    0%, 100% {
        transform: translate(0, 0) scale(1);
    }
    50% {
        transform: translate(-15px, -15px) scale(1.08);
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

.animate-float-reverse {
    animation: float-reverse 18s ease-in-out infinite;
    animation-delay: -5s;
}

.animate-pulse-very-slow {
    animation: pulse-very-slow 12s ease-in-out infinite;
}

/* Mejoras de accesibilidad */
@media (prefers-reduced-motion: reduce) {
    .animate-slide-up,
    .animate-float-slow,
    .animate-float-slow-delayed,
    .animate-float-reverse,
    .animate-pulse-very-slow {
        animation: none;
    }
}
</style>
