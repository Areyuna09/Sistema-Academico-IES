<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    estadisticas: {
        type: Object,
        default: () => ({
            pendientes_supervision: 0,
            legajos_corregidos: 0,
            aprobados_hoy: 0,
            total_supervisados: 0,
            aprobados_final: 0,
        })
    },
    carreras: {
        type: Array,
        default: () => []
    }
});

// Acciones rápidas disponibles para Supervisor
const accionesRapidas = [
    {
        titulo: 'Ver Expedientes',
        descripcion: 'Consultar expedientes de alumnos',
        icono: 'bx-folder-open',
        color: 'blue',
        ruta: '/expediente',
        disponible: true
    },
    {
        titulo: 'Dashboard Principal',
        descripcion: 'Volver al panel de inicio',
        icono: 'bx-home',
        color: 'green',
        ruta: '/dashboard',
        disponible: true
    },
    {
        titulo: 'Reportes de Supervisión',
        descripcion: 'Generar reportes de control',
        icono: 'bx-file-blank',
        color: 'purple',
        ruta: '#',
        disponible: false
    },
    {
        titulo: 'Auditoría Académica',
        descripcion: 'Revisar historial de cambios',
        icono: 'bx-history',
        color: 'orange',
        ruta: '#',
        disponible: false
    }
];

const irA = (ruta) => {
    if (ruta !== '#') {
        router.visit(ruta);
    }
};
</script>

<template>
    <SidebarLayout>
        <Head title="Panel de Supervisor" />

        <div class="py-6 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-indigo-600 p-3 rounded-lg">
                            <i class="bx bx-shield-quarter text-3xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Panel de Supervisor</h1>
                            <p class="text-sm text-gray-600 mt-1">
                                Control de calidad y supervisión final académica
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Mensaje informativo -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-l-4 border-indigo-500 p-6 mb-8 rounded-r-lg shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="bx bx-info-circle text-indigo-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-indigo-900 mb-1">Modo Solo Lectura</h3>
                            <p class="text-sm text-indigo-800 leading-relaxed">
                                Como <strong>Supervisor</strong>, eres responsable de la revisión final y control de calidad de todos los registros académicos.
                                Puedes consultar toda la información del sistema, validar datos y generar reportes de supervisión, pero no puedes realizar modificaciones directas.
                                Para solicitar correcciones, contacta con el equipo administrativo.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <div class="bg-blue-100 p-3 rounded-xl">
                                    <i class="bx bx-clipboard text-2xl text-blue-600"></i>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Pendientes</p>
                            <p class="text-3xl font-bold text-gray-900">{{ estadisticas.pendientes_supervision }}</p>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <div class="bg-orange-100 p-3 rounded-xl">
                                    <i class="bx bx-error text-2xl text-orange-600"></i>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Corregidos</p>
                            <p class="text-3xl font-bold text-gray-900">{{ estadisticas.legajos_corregidos }}</p>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <div class="bg-green-100 p-3 rounded-xl">
                                    <i class="bx bx-check-circle text-2xl text-green-600"></i>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Aprobados Hoy</p>
                            <p class="text-3xl font-bold text-gray-900">{{ estadisticas.aprobados_hoy }}</p>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <div class="bg-purple-100 p-3 rounded-xl">
                                    <i class="bx bx-user-check text-2xl text-purple-600"></i>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Supervisados</p>
                            <p class="text-3xl font-bold text-gray-900">{{ estadisticas.total_supervisados }}</p>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <div class="bg-indigo-100 p-3 rounded-xl">
                                    <i class="bx bx-check-double text-2xl text-indigo-600"></i>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Aprobados Final</p>
                            <p class="text-3xl font-bold text-gray-900">{{ estadisticas.aprobados_final }}</p>
                        </div>
                    </div>
                </div>

                <!-- Accesos Rápidos -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Accesos Rápidos</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <button
                            v-for="accion in accionesRapidas"
                            :key="accion.titulo"
                            @click="irA(accion.ruta)"
                            :disabled="!accion.disponible"
                            :class="[
                                'text-left p-6 rounded-xl shadow-sm transition-all duration-200',
                                accion.disponible
                                    ? 'bg-white hover:shadow-lg hover:-translate-y-1 cursor-pointer border border-gray-100'
                                    : 'bg-gray-50 cursor-not-allowed opacity-60 border border-gray-200'
                            ]"
                        >
                            <div class="flex items-start gap-4">
                                <div
                                    :class="[
                                        'p-3 rounded-lg',
                                        accion.color === 'blue' ? 'bg-blue-100' : '',
                                        accion.color === 'green' ? 'bg-green-100' : '',
                                        accion.color === 'purple' ? 'bg-purple-100' : '',
                                        accion.color === 'orange' ? 'bg-orange-100' : '',
                                        !accion.disponible ? 'bg-gray-200' : ''
                                    ]"
                                >
                                    <i
                                        :class="[
                                            'bx text-2xl',
                                            accion.icono,
                                            accion.color === 'blue' && accion.disponible ? 'text-blue-600' : '',
                                            accion.color === 'green' && accion.disponible ? 'text-green-600' : '',
                                            accion.color === 'purple' && accion.disponible ? 'text-purple-600' : '',
                                            accion.color === 'orange' && accion.disponible ? 'text-orange-600' : '',
                                            !accion.disponible ? 'text-gray-400' : ''
                                        ]"
                                    ></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ accion.titulo }}</h3>
                                    <p class="text-sm text-gray-600">{{ accion.descripcion }}</p>
                                    <p v-if="!accion.disponible" class="text-xs text-gray-500 mt-2">
                                        Próximamente disponible
                                    </p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Panel de Responsabilidades -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                                <i class="bx bx-task text-xl"></i>
                                Responsabilidades del Supervisor
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="bg-indigo-100 p-2 rounded-lg mt-1">
                                        <i class="bx bx-check text-indigo-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-1">Control de Calidad</h4>
                                        <p class="text-sm text-gray-600">
                                            Revisar la exactitud y completitud de todos los registros académicos.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="bg-indigo-100 p-2 rounded-lg mt-1">
                                        <i class="bx bx-search-alt text-indigo-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-1">Auditoría Académica</h4>
                                        <p class="text-sm text-gray-600">
                                            Verificar cumplimiento de normativas y procedimientos institucionales.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="bg-indigo-100 p-2 rounded-lg mt-1">
                                        <i class="bx bx-shield-quarter text-indigo-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-1">Validación Final</h4>
                                        <p class="text-sm text-gray-600">
                                            Aprobar o solicitar correcciones en legajos antes de su archivo definitivo.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel de Ayuda -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                                <i class="bx bx-help-circle text-xl"></i>
                                Guía de Uso
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="bg-green-100 p-2 rounded-lg mt-1">
                                        <i class="bx bx-file-find text-green-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-1">Consultar Información</h4>
                                        <p class="text-sm text-gray-600">
                                            Accede a "Expediente" para ver datos completos de alumnos, materias y notas.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="bg-blue-100 p-2 rounded-lg mt-1">
                                        <i class="bx bx-export text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-1">Generar Reportes</h4>
                                        <p class="text-sm text-gray-600">
                                            Exporta información en PDF para análisis y documentación oficial.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="bg-orange-100 p-2 rounded-lg mt-1">
                                        <i class="bx bx-message-square-error text-orange-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-1">Reportar Inconsistencias</h4>
                                        <p class="text-sm text-gray-600">
                                            Contacta al equipo administrativo para solicitar correcciones necesarias.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>

<style scoped>
/* Animaciones suaves */
button:not(:disabled):hover {
    transform: translateY(-2px);
}

button:not(:disabled):active {
    transform: translateY(0);
}
</style>