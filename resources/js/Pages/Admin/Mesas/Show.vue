<script setup>
import { Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    mesa: Object,
});

const getLlamadoTexto = (llamado) => {
    const textos = {
        1: '1° Llamado',
        2: '2° Llamado',
        3: '3° Llamado',
    };
    return textos[llamado] || `${llamado}° Llamado`;
};

const getEstadoBadgeClass = (estado) => {
    const clases = {
        'inscripto': 'bg-blue-100 text-blue-700',
        'presente': 'bg-green-100 text-green-700',
        'ausente': 'bg-gray-100 text-gray-700',
        'aprobado': 'bg-green-100 text-green-700',
        'desaprobado': 'bg-red-100 text-red-700',
    };
    return clases[estado] || 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <Head title="Detalle de Mesa" />
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Detalle de Mesa de Examen</h1>
                <p class="text-xs text-gray-400 mt-0.5">Información completa de la mesa</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <Link :href="route('admin.mesas.index')" class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="bx bx-arrow-back mr-1"></i>
                    Volver a Mesas
                </Link>
            </div>

            <!-- Información de la Mesa -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ mesa.materia.nombre }}</h2>
                        <p class="text-gray-600">{{ mesa.materia.carrera.nombre }}</p>
                    </div>
                    <Link
                        :href="route('admin.mesas.edit', mesa.id)"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                    >
                        <i class="bx bx-edit mr-1"></i>
                        Editar Mesa
                    </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t pt-6">
                    <!-- Información General -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Información General</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-600">Fecha:</span>
                                <p class="text-sm font-medium">{{ mesa.fecha_examen }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Hora:</span>
                                <p class="text-sm font-medium">{{ mesa.hora_examen }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Llamado:</span>
                                <p class="text-sm font-medium">{{ getLlamadoTexto(mesa.llamado) }}</p>
                            </div>
                            <div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Estado:</span>
                                <p class="text-sm font-medium">
                                    <span :class="getEstadoBadgeClass(mesa.estado)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ mesa.estado.charAt(0).toUpperCase() + mesa.estado.slice(1) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tribunal -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Tribunal</h3>
                        <div class="space-y-2">
                            <div v-if="mesa.presidente">
                                <span class="text-sm text-gray-600">Presidente:</span>
                                <p class="text-sm font-medium">{{ mesa.presidente.nombre_completo }}</p>
                            </div>
                            <div v-if="mesa.vocal1">
                                <span class="text-sm text-gray-600">Vocal 1:</span>
                                <p class="text-sm font-medium">{{ mesa.vocal1.nombre_completo }}</p>
                            </div>
                            <div v-if="mesa.vocal2">
                                <span class="text-sm text-gray-600">Vocal 2:</span>
                                <p class="text-sm font-medium">{{ mesa.vocal2.nombre_completo }}</p>
                            </div>
                            <div v-if="!mesa.presidente && !mesa.vocal1 && !mesa.vocal2">
                                <p class="text-sm text-gray-400 italic">Sin tribunal asignado</p>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Estadísticas</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-600">Total Inscriptos:</span>
                                <p class="text-2xl font-bold text-blue-600">{{ mesa.inscripciones.length }}</p>
                            </div>
                            <div v-if="mesa.observaciones" class="mt-4">
                                <span class="text-sm text-gray-600">Observaciones:</span>
                                <p class="text-sm text-gray-800">{{ mesa.observaciones }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Inscriptos -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Alumnos Inscriptos</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    DNI
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Alumno
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Año
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha Inscripción
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-if="mesa.inscripciones.length === 0">
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i class="bx bx-user-x text-4xl mb-2"></i>
                                    <p>No hay alumnos inscriptos a esta mesa</p>
                                </td>
                            </tr>
                            <tr v-for="(inscripcion, index) in mesa.inscripciones" :key="inscripcion.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ index + 1 }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ inscripcion.alumno.dni }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ inscripcion.alumno.nombre_completo }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ inscripcion.alumno.curso || '-' }}°
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ inscripcion.alumno.email || '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ inscripcion.fecha_inscripcion }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
