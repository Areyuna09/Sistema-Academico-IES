<script setup>
import { Head, Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    inscripcion: {
        type: Object,
        required: true
    }
});

const getLlamadoTexto = (llamado) => {
    const textos = { 1: '1° Llamado', 2: '2° Llamado', 3: '3° Llamado' };
    return textos[llamado] || `${llamado}° Llamado`;
};
</script>

<template>
    <Head title="Inscripción Confirmada" />

    <SidebarLayout :user="$page.props.auth.user">
        <div class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-white flex items-center justify-center p-4">
            <div class="max-w-2xl w-full">
                <!-- Animación de éxito -->
                <div class="text-center mb-8 animate-fade-in">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-6 animate-bounce-slow">
                        <i class="bx bx-check-circle text-green-600 text-6xl"></i>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">
                        ¡Inscripción Completada!
                    </h1>
                    <p class="text-lg text-gray-600">
                        Tu inscripción a la mesa de examen ha sido procesada exitosamente
                    </p>
                </div>

                <!-- Tarjeta principal -->
                <div class="bg-white rounded-xl shadow-2xl overflow-hidden mb-6 animate-slide-up">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <i class="bx bx-user text-3xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold">{{ inscripcion.alumno.nombre_completo }}</h2>
                                <p class="text-blue-100 text-sm">DNI: {{ inscripcion.alumno.dni }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información de la mesa -->
                    <div class="bg-blue-50 border-b border-blue-100 p-4">
                        <div class="flex items-center gap-2 text-blue-800">
                            <i class="bx bx-calendar-event text-xl"></i>
                            <span class="font-semibold">{{ getLlamadoTexto(inscripcion.mesa.llamado) }} - {{ inscripcion.mesa.fecha_examen }}</span>
                        </div>
                    </div>

                    <!-- Detalles de la mesa -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="bx bx-book-open text-blue-600 text-xl"></i>
                            Detalles de la Mesa
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="bx bx-book text-blue-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ inscripcion.mesa.materia }}</p>
                                    <p class="text-sm text-gray-500">{{ inscripcion.mesa.carrera }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Confirmada
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-xs text-gray-500 mb-1">Fecha del Examen</p>
                                    <p class="font-semibold text-gray-900">{{ inscripcion.mesa.fecha_examen }}</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-xs text-gray-500 mb-1">Hora</p>
                                    <p class="font-semibold text-gray-900">{{ inscripcion.mesa.hora_examen }}</p>
                                </div>
                            </div>

                            <!-- Tribunal -->
                            <div v-if="inscripcion.mesa.tribunal.presidente || inscripcion.mesa.tribunal.vocal1 || inscripcion.mesa.tribunal.vocal2"
                                 class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-xs text-blue-700 font-semibold mb-2">Tribunal Evaluador</p>
                                <div class="space-y-1 text-sm text-gray-700">
                                    <p v-if="inscripcion.mesa.tribunal.presidente">
                                        <span class="font-medium">Presidente:</span> {{ inscripcion.mesa.tribunal.presidente }}
                                    </p>
                                    <p v-if="inscripcion.mesa.tribunal.vocal1">
                                        <span class="font-medium">Vocal 1:</span> {{ inscripcion.mesa.tribunal.vocal1 }}
                                    </p>
                                    <p v-if="inscripcion.mesa.tribunal.vocal2">
                                        <span class="font-medium">Vocal 2:</span> {{ inscripcion.mesa.tribunal.vocal2 }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información del comprobante -->
                    <div class="bg-green-50 border-t border-green-100 p-6">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="bx bx-envelope text-green-600 text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-green-900 mb-1">
                                    Comprobante enviado
                                </h4>
                                <p class="text-sm text-green-700">
                                    Se ha enviado un comprobante de inscripción a tu correo electrónico.
                                    Por favor, revisa tu bandeja de entrada y carpeta de spam.
                                </p>
                            </div>
                        </div>

                        <!-- Botón de descarga -->
                        <a
                            :href="`/mesas/comprobante/${inscripcion.id}/pdf`"
                            target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-all duration-200 font-medium shadow-md hover:shadow-lg"
                        >
                            <i class="bx bx-download text-xl"></i>
                            <span>Descargar Comprobante PDF</span>
                        </a>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="bx bx-info-circle text-blue-600 text-xl"></i>
                        Información Importante
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start gap-2">
                            <i class="bx bx-check text-green-600 mt-0.5"></i>
                            <span>Debes presentarte 15 minutos antes del horario del examen</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="bx bx-check text-green-600 mt-0.5"></i>
                            <span>Es obligatorio traer el DNI original</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="bx bx-check text-green-600 mt-0.5"></i>
                            <span>Este comprobante debe conservarse como documento oficial</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="bx bx-check text-green-600 mt-0.5"></i>
                            <span>Verifica que el comprobante haya llegado a tu correo electrónico</span>
                        </li>
                    </ul>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <Link
                        :href="'/dashboard'"
                        class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg transition-all duration-200 font-medium"
                    >
                        <i class="bx bx-home text-xl"></i>
                        <span>Volver al Inicio</span>
                    </Link>
                    <Link
                        :href="'/mesas'"
                        class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 font-medium shadow-lg"
                    >
                        <i class="bx bx-calendar text-xl"></i>
                        <span>Ver Mesas de Examen</span>
                    </Link>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

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

@keyframes bounce-slow {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.8s ease-out;
}

.animate-bounce-slow {
    animation: bounce-slow 2s ease-in-out infinite;
}
</style>
