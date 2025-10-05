<script setup>
import { Head, useForm } from "@inertiajs/vue3";

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
</script>

<template>
    <Head title="Iniciar Sesión" />

    <div class="min-h-screen flex items-center justify-center relative overflow-hidden px-4 py-12 bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900">
        <!-- Fondo con círculos decorativos animados -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500 rounded-full opacity-20 blur-3xl animate-float-slow"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-500 rounded-full opacity-20 blur-3xl animate-float-slow-delayed"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-600 rounded-full opacity-10 blur-3xl animate-pulse-very-slow"></div>
            <div class="absolute top-20 left-1/4 w-64 h-64 bg-purple-500 rounded-full opacity-15 blur-3xl animate-float-reverse"></div>
            <div class="absolute bottom-20 right-1/4 w-80 h-80 bg-cyan-500 rounded-full opacity-15 blur-3xl animate-float-slow"></div>
        </div>

        <div class="w-full max-w-md relative z-10">
            <!-- Login Card - Dark mode como el dashboard -->
            <div class="bg-gray-800 rounded-3xl shadow-2xl p-8 border border-gray-700 animate-slide-up">
                <!-- Avatar Icon -->
                <div class="flex justify-center mb-8 -mt-20">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-2xl border-4 border-gray-700">
                        <i class="bx bx-user text-white text-6xl"></i>
                    </div>
                </div>

                <!-- Status Message -->
                <div v-if="status" class="mb-6 p-4 bg-green-500 bg-opacity-10 border border-green-500 rounded-lg">
                    <p class="text-sm text-green-400 flex items-center justify-center">
                        <i class="bx bx-check-circle text-lg mr-2"></i>
                        {{ status }}
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- DNI Input -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bx bx-user text-gray-400 text-xl"></i>
                            </div>
                            <input
                                id="dni"
                                type="text"
                                v-model="form.dni"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="DNI"
                                class="w-full pl-12 pr-4 py-3.5 bg-gray-700 border border-gray-600 text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            />
                        </div>
                        <p v-if="form.errors.dni" class="mt-2 text-sm text-red-400 flex items-center">
                            <i class="bx bx-error-circle mr-1"></i>
                            {{ form.errors.dni }}
                        </p>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bx bx-lock-alt text-gray-400 text-xl"></i>
                            </div>
                            <input
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••••••"
                                class="w-full pl-12 pr-4 py-3.5 bg-gray-700 border border-gray-600 text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            />
                        </div>
                        <p v-if="form.errors.password" class="mt-2 text-sm text-red-400 flex items-center">
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
                                class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer"
                            />
                            <span class="ml-2 text-gray-300 group-hover:text-white transition-colors">
                                Recuérdame
                            </span>
                        </label>
                        <a v-if="canResetPassword" href="#" class="text-gray-400 hover:text-white transition-colors italic">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-400 text-white font-bold text-lg rounded-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center uppercase tracking-wide"
                    >
                        <i v-if="!form.processing" class="bx bx-log-in text-2xl mr-2"></i>
                        <svg v-else class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ form.processing ? 'Accediendo...' : 'Acceder' }}
                    </button>
                </form>

                <!-- Footer Info -->
                <div class="mt-6 pt-6 border-t border-gray-700">
                    <p class="text-center text-sm text-gray-400">
                        ¿Primera vez en el sistema?
                    </p>
                    <p class="text-center text-sm text-gray-200 font-medium mt-1">
                        Tu contraseña es tu DNI
                    </p>
                </div>
            </div>

            <!-- Bottom Text -->
            <div class="mt-6 text-center">
                <p class="text-white/60 text-sm mb-2">
                    Sistema Académico
                </p>
                <p class="text-white/80 text-sm font-medium">
                    IES Gral. Manuel Belgrano
                </p>
                <p class="text-white/40 text-xs mt-4">
                    © 2025 - Todos los derechos reservados
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Animación de flotación lenta para círculos */
@keyframes float-slow {
    0%, 100% {
        transform: translate(0, 0) scale(1);
    }
    33% {
        transform: translate(30px, -30px) scale(1.05);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.95);
    }
}

/* Flotación con delay */
@keyframes float-slow-delayed {
    0%, 100% {
        transform: translate(0, 0) scale(1);
    }
    33% {
        transform: translate(-25px, 25px) scale(1.05);
    }
    66% {
        transform: translate(25px, -20px) scale(0.95);
    }
}

/* Flotación en reversa */
@keyframes float-reverse {
    0%, 100% {
        transform: translate(0, 0) scale(1);
    }
    50% {
        transform: translate(-15px, -15px) scale(1.1);
    }
}

/* Pulso muy lento para el círculo central */
@keyframes pulse-very-slow {
    0%, 100% {
        opacity: 0.08;
        transform: translate(-50%, -50%) scale(1);
    }
    50% {
        opacity: 0.15;
        transform: translate(-50%, -50%) scale(1.1);
    }
}

.animate-slide-up {
    animation: slide-up 0.8s ease-out;
}

.animate-float-slow {
    animation: float-slow 20s ease-in-out infinite;
}

.animate-float-slow-delayed {
    animation: float-slow-delayed 25s ease-in-out infinite;
    animation-delay: -10s;
}

.animate-float-reverse {
    animation: float-reverse 18s ease-in-out infinite;
    animation-delay: -5s;
}

.animate-pulse-very-slow {
    animation: pulse-very-slow 15s ease-in-out infinite;
}
</style>
