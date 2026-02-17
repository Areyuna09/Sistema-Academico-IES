<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    estadisticas: {
        type: Object,
        default: () => ({
            pendientes_revision: 0,
            con_observaciones_supervisor: 0,
            aprobados_hoy: 0,
            total_revisados: 0,
        })
    },
    carreras: {
        type: Array,
        default: () => []
    }
});

// Acciones rápidas disponibles para Directivo
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
        titulo: 'Reportes Académicos',
        descripcion: 'Generar reportes e informes',
        icono: 'bx-bar-chart-alt-2',
        color: 'purple',
        ruta: '#',
        disponible: false
    },
    {
        titulo: 'Estadísticas',
        descripcion: 'Ver estadísticas del instituto',
        icono: 'bx-line-chart',
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
        <Head title="Panel de Directivo" />

        <div class="py-6 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-blue-600 p-3 rounded-lg">
                            <i class="bx bx-user-circle text-3xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Panel de Directivo</h1>
                            <p class="text-sm text-gray-600 mt-1">
                                Supervisión y consulta de información académica
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Mensaje informativo -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 p-6 mb-8 rounded-r-lg shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="bx bx-info-circle text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-blue-900 mb-1">Modo Solo Lectura</h3>
                            <p class="text-sm text-blue-800 leading-relaxed">
                                Como <strong>Directivo</strong>, tienes acceso completo a toda la información académica del instituto.
                                Puedes consultar expedientes, ver notas, asistencias y reportes, pero no puedes realizar modificaciones directas.
                                Para solicitar cambios o correcciones, contacta con el personal administrativo.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Legajos Pendientes</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ estadisticas.pendientes_revision }}</p>
                                </div>
                                <div class="bg-blue-100 p-4 rounded-xl">
                                    <i class="bx bx-clipboard text-3xl text-blue-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Con Observaciones</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ estadisticas.con_observaciones_supervisor }}</p>
                                </div>
                                <div class="bg-orange-100 p-4 rounded-xl">
                                    <i class="bx bx-error text-3xl text-orange-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Aprobados Hoy</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ estadisticas.aprobados_hoy }}</p>
                                </div>
                                <div class="bg-green-100 p-4 rounded-xl">
                                    <i class="bx bx-check-circle text-3xl text-green-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Total Revisados</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ estadisticas.total_revisados }}</p>
                                </div>
                                <div class="bg-purple-100 p-4 rounded-xl">
                                    <i class="bx bx-user-check text-3xl text-purple-600"></i>
                                </div>
                            </div>
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
                    <!-- Panel de Carreras -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                                <i class="bx bx-book-alt text-xl"></i>
                                Carreras del Instituto
                            </h3>
                        </div>
                        <div class="p-6">
                            <div v-if="carreras.length > 0" class="space-y-3">
                                <div
                                    v-for="carrera in carreras"
                                    :key="carrera.Id"
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="bg-blue-100 p-2 rounded-lg">
                                            <i class="bx bx-book text-blue-600"></i>
                                        </div>
                                        <span class="font-medium text-gray-900">{{ carrera.nombre }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ carrera.duracion || 3 }} años</span>
                                </div>
                            </div>
                            <div v-else class="text-center py-8">
                                <i class="bx bx-book-open text-4xl text-gray-300 mb-2"></i>
                                <p class="text-gray-500">No hay carreras registradas</p>
                            </div>
                        </div>
                    </div>

                    <!-- Panel de Ayuda -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                                <i class="bx bx-help-circle text-xl"></i>
                                Ayuda y Soporte
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="bg-green-100 p-2 rounded-lg mt-1">
                                        <i class="bx bx-file-find text-green-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-1">Consultar Expedientes</h4>
                                        <p class="text-sm text-gray-600">
                                            Accede a la sección "Expediente" para ver información completa de alumnos, profesores y materias.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="bg-blue-100 p-2 rounded-lg mt-1">
                                        <i class="bx bx-export text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-1">Exportar Datos</h4>
                                        <p class="text-sm text-gray-600">
                                            Puedes exportar listas y reportes en formato PDF desde las diferentes secciones.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="bg-orange-100 p-2 rounded-lg mt-1">
                                        <i class="bx bx-edit text-orange-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-1">Solicitar Cambios</h4>
                                        <p class="text-sm text-gray-600">
                                            Para modificaciones, contacta con el personal administrativo o bedeles.
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