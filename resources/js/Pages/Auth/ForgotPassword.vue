<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { computed } from 'vue';

const props = defineProps({
    status: {
        type: String,
    },
    showEmailMismatchWarning: {
        type: Boolean,
        default: false,
    },
    requestedEmail: {
        type: String,
        default: '',
    },
    requestedDni: {
        type: String,
        default: '',
    },
    solicitudEnviada: {
        type: Boolean,
        default: false,
    },
});

const form = useForm({
    dni: "",
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};

// Form para solicitar cambio de email
const solicitudForm = useForm({
    dni: "",
    email: "",
});

const solicitarCambioEmail = () => {
    // Actualizar los valores del formulario antes de enviar
    solicitudForm.dni = props.requestedDni || form.dni;
    solicitudForm.email = props.requestedEmail || form.email;

    solicitudForm.post(route("solicitud-cambio-email.store"), {
        preserveState: true,
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
    <Head title="Recuperar Contraseña" />

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
                        <h1 class="text-xl sm:text-2xl font-bold text-white mb-1.5">Recuperar Contraseña</h1>
                        <p class="text-xs sm:text-sm text-blue-100/90">{{ nombreInstitucion }}</p>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="px-6 sm:px-8 py-5 sm:py-6">
                    <!-- Mensaje informativo -->
                    <div class="mb-5 bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="bx bx-info-circle text-blue-400 text-xl mr-3 mt-0.5"></i>
                            <p class="text-sm text-gray-300 leading-relaxed">
                                Para recuperar tu contraseña, ingresa tu <strong>DNI</strong> y el <strong>correo electrónico</strong>
                                asociado a tu cuenta. Te enviaremos un enlace de recuperación.
                            </p>
                        </div>
                    </div>

                    <!-- Status Message -->
                    <div v-if="status" class="mb-5 p-4 bg-green-500 bg-opacity-10 border border-green-500 rounded-lg animate-fade-in">
                        <div class="flex items-center">
                            <i class="bx bx-check-circle text-green-400 text-xl mr-3"></i>
                            <p class="text-sm text-green-400">
                                {{ status }}
                            </p>
                        </div>
                    </div>

                    <!-- Email Mismatch Warning -->
                    <div v-if="showEmailMismatchWarning" class="mb-5 p-4 bg-yellow-500 bg-opacity-10 border border-yellow-500 rounded-lg animate-fade-in">
                        <div class="flex items-start">
                            <i class="bx bx-error text-yellow-400 text-xl mr-3 mt-0.5"></i>
                            <div class="text-sm text-yellow-400 flex-1">
                                <p class="font-semibold mb-2">⚠️ El correo no coincide con tu cuenta</p>
                                <p class="mb-3">
                                    El email <strong>{{ requestedEmail }}</strong> no coincide con el registrado en tu cuenta.
                                </p>

                                <div v-if="!solicitudEnviada">
                                    <p class="text-xs text-yellow-300 mb-3">
                                        Por seguridad, no podemos enviar el enlace de recuperación.
                                    </p>
                                    <p class="text-xs text-yellow-200 mb-3 font-medium">
                                        💡 Puedes solicitar que este sea tu nuevo correo electrónico. La solicitud será revisada por un administrador.
                                    </p>
                                    <button
                                        @click="solicitarCambioEmail"
                                        :disabled="solicitudForm.processing"
                                        class="mt-2 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg font-medium text-xs transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                                    >
                                        <i v-if="!solicitudForm.processing" class="bx bx-envelope-open"></i>
                                        <svg v-else class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ solicitudForm.processing ? 'Enviando...' : 'Solicitar cambio de email' }}
                                    </button>
                                </div>

                                <div v-else class="bg-green-500/20 border border-green-500/30 rounded-lg p-3 mt-2">
                                    <div class="flex items-start">
                                        <i class="bx bx-check-circle text-green-400 text-lg mr-2"></i>
                                        <div class="text-xs text-green-300">
                                            <p class="font-semibold mb-1">✓ Solicitud enviada</p>
                                            <p>Tu solicitud de cambio de email ha sido enviada a los administradores. Recibirás una notificación cuando sea revisada.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="submit" class="space-y-4">
                        <!-- DNI Input -->
                        <div>
                            <label for="dni" class="block text-sm font-medium text-gray-300 mb-1.5">
                                DNI <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="bx bx-id-card text-gray-400 text-lg"></i>
                                </div>
                                <input
                                    id="dni"
                                    type="text"
                                    v-model="form.dni"
                                    required
                                    maxlength="20"
                                    autofocus
                                    placeholder="12345678"
                                    class="w-full pl-11 pr-4 py-2.5 text-sm bg-gray-700/50 border border-gray-600 text-white placeholder-gray-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-gray-700 transition-all duration-200"
                                />
                            </div>
                            <p v-if="form.errors.dni" class="mt-1.5 text-xs text-red-400 flex items-center">
                                <i class="bx bx-error-circle mr-1"></i>
                                {{ form.errors.dni }}
                            </p>
                        </div>

                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Correo Electrónico <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="bx bx-envelope text-gray-400 text-lg"></i>
                                </div>
                                <input
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                    required
                                    maxlength="100"
                                    autocomplete="username"
                                    placeholder="ejemplo@correo.com"
                                    class="w-full pl-11 pr-4 py-2.5 text-sm bg-gray-700/50 border border-gray-600 text-white placeholder-gray-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-gray-700 transition-all duration-200"
                                />
                            </div>
                            <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-400 flex items-center">
                                <i class="bx bx-error-circle mr-1"></i>
                                {{ form.errors.email }}
                            </p>
                            <p class="mt-1.5 text-xs text-gray-400">
                                <i class="bx bx-shield text-blue-400 mr-1"></i>
                                El email debe coincidir con el registrado en tu cuenta
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-semibold text-base rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center"
                        >
                            <i v-if="!form.processing" class="bx bx-paper-plane text-xl mr-2"></i>
                            <svg v-else class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ form.processing ? 'Verificando...' : 'Enviar Enlace de Recuperación' }}
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

@keyframes fade-in {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
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

.animate-fade-in {
    animation: fade-in 0.5s ease-out;
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
    .animate-fade-in,
    .animate-float-slow,
    .animate-float-slow-delayed,
    .animate-float-reverse,
    .animate-pulse-very-slow {
        animation: none;
    }
}
</style>
