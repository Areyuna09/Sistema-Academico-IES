<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from 'vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: "",
    password_confirmation: "",
});

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const submit = () => {
    form.post(route("password.store"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};

// Obtener configuración institucional
const page = usePage();
const configuracion = computed(() => page.props.configuracion || {});
const nombreInstitucion = computed(() => configuracion.value.nombre_institucion || 'IES Gral. Manuel Belgrano');

// Selección inteligente de logo
const logoUrl = computed(() => {
    const logoClaroUrl = configuracion.value.logo_url || '/images/logo-ies-original.png';
    const logoOscuroUrl = configuracion.value.logo_dark_url;

    if (!logoOscuroUrl) {
        return logoClaroUrl;
    }

    const bgColor = { r: 15, g: 23, b: 42 }; // slate-900
    const luminosity = (0.2126 * bgColor.r + 0.7152 * bgColor.g + 0.0722 * bgColor.b) / 255;

    return luminosity < 0.5 ? logoClaroUrl : logoOscuroUrl;
});
</script>

<template>
    <Head title="Restablecer Contraseña" />

    <div class="min-h-screen flex items-center justify-center relative overflow-hidden px-4 sm:px-6 py-8 sm:py-12 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900">
        <!-- Fondo con círculos decorativos animados -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/30 rounded-full blur-3xl animate-float-slow"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-500/30 rounded-full blur-3xl animate-float-slow-delayed"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-600/20 rounded-full blur-3xl animate-pulse-very-slow"></div>
            <div class="absolute top-20 left-1/4 w-64 h-64 bg-purple-500/25 rounded-full blur-3xl animate-float-reverse"></div>
            <div class="absolute bottom-20 right-1/4 w-80 h-80 bg-cyan-500/25 rounded-full blur-3xl animate-float-slow"></div>
        </div>

        <div class="w-full max-w-md relative z-10">
            <!-- Card -->
            <div class="bg-gray-800/95 backdrop-blur-sm rounded-2xl shadow-2xl border border-gray-700/50 animate-slide-up overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 sm:px-8 pt-6 sm:pt-8 pb-5 sm:pb-6">
                    <!-- Logo -->
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
                        <h1 class="text-xl sm:text-2xl font-bold text-white mb-1.5">Restablecer Contraseña</h1>
                        <p class="text-xs sm:text-sm text-blue-100/90">{{ nombreInstitucion }}</p>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="px-6 sm:px-8 py-5 sm:py-6">
                    <!-- Mensaje informativo -->
                    <div class="mb-5 bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="bx bx-key text-blue-400 text-xl mr-3 mt-0.5"></i>
                            <p class="text-sm text-gray-300 leading-relaxed">
                                Por favor, ingresa tu nueva contraseña. Asegúrate de que sea segura y fácil de recordar.
                            </p>
                        </div>
                    </div>

                    <form @submit.prevent="submit" class="space-y-4">
                        <!-- Email (readonly) -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Correo Electrónico
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="bx bx-envelope text-gray-400 text-lg"></i>
                                </div>
                                <input
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                    readonly
                                    class="w-full pl-11 pr-4 py-2.5 text-sm bg-gray-700/30 border border-gray-600 text-gray-400 rounded-lg cursor-not-allowed"
                                />
                            </div>
                            <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-400 flex items-center">
                                <i class="bx bx-error-circle mr-1"></i>
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <!-- Nueva Contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Nueva Contraseña
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="bx bx-lock-alt text-gray-400 text-lg"></i>
                                </div>
                                <input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    v-model="form.password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Ingresa tu nueva contraseña"
                                    class="w-full pl-11 pr-11 py-2.5 text-sm bg-gray-700/50 border border-gray-600 text-white placeholder-gray-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-gray-700 transition-all duration-200"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-300 focus:outline-none transition-colors"
                                    tabindex="-1"
                                >
                                    <i :class="showPassword ? 'bx bx-hide' : 'bx bx-show'" class="text-lg"></i>
                                </button>
                            </div>
                            <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-400 flex items-center">
                                <i class="bx bx-error-circle mr-1"></i>
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Confirmar Nueva Contraseña
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="bx bx-lock-alt text-gray-400 text-lg"></i>
                                </div>
                                <input
                                    id="password_confirmation"
                                    :type="showPasswordConfirmation ? 'text' : 'password'"
                                    v-model="form.password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Confirma tu nueva contraseña"
                                    class="w-full pl-11 pr-11 py-2.5 text-sm bg-gray-700/50 border border-gray-600 text-white placeholder-gray-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-gray-700 transition-all duration-200"
                                />
                                <button
                                    type="button"
                                    @click="showPasswordConfirmation = !showPasswordConfirmation"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-300 focus:outline-none transition-colors"
                                    tabindex="-1"
                                >
                                    <i :class="showPasswordConfirmation ? 'bx bx-hide' : 'bx bx-show'" class="text-lg"></i>
                                </button>
                            </div>
                            <p v-if="form.errors.password_confirmation" class="mt-1.5 text-xs text-red-400 flex items-center">
                                <i class="bx bx-error-circle mr-1"></i>
                                {{ form.errors.password_confirmation }}
                            </p>
                        </div>

                        <!-- Requisitos de contraseña -->
                        <div class="bg-gray-700/30 border border-gray-600 rounded-lg p-3">
                            <p class="text-xs text-gray-400 mb-2 font-medium">Requisitos de la contraseña:</p>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li class="flex items-start">
                                    <i class="bx bx-check text-green-400 mr-1.5 mt-0.5"></i>
                                    <span>Mínimo 8 caracteres</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="bx bx-check text-green-400 mr-1.5 mt-0.5"></i>
                                    <span>Debe contener letras (mayúsculas y minúsculas)</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="bx bx-check text-green-400 mr-1.5 mt-0.5"></i>
                                    <span>Debe contener al menos un número</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="bx bx-check text-green-400 mr-1.5 mt-0.5"></i>
                                    <span>Debe coincidir con la confirmación</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-semibold text-base rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center"
                        >
                            <i v-if="!form.processing" class="bx bx-check-circle text-xl mr-2"></i>
                            <svg v-else class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ form.processing ? 'Restableciendo...' : 'Restablecer Contraseña' }}
                        </button>

                        <!-- Volver al Login -->
                        <div class="text-center pt-3">
                            <a 
                                :href="route('login')" 
                                class="inline-flex items-center text-sm text-blue-400 hover:text-blue-300 transition-colors font-medium"
                            >
                                <i class="bx bx-arrow-back mr-1.5"></i>
                                Volver al inicio de sesión
                            </a>
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