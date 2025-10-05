<script setup>
import { Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    inscripciones: Array,
});

const getEstadoBadgeClass = (estado) => {
    const clases = {
        'inscripto': 'bg-blue-100 text-blue-700',
        'presente': 'bg-green-100 text-green-700',
        'ausente': 'bg-gray-100 text-gray-700',
        'aprobado': 'bg-green-500 text-white',
        'desaprobado': 'bg-red-500 text-white',
    };
    return clases[estado] || 'bg-gray-100 text-gray-700';
};

const getLlamadoTexto = (llamado) => {
    const textos = { 1: '1° Llamado', 2: '2° Llamado', 3: '3° Llamado' };
    return textos[llamado] || `${llamado}° Llamado`;
};
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Mis Inscripciones a Mesas</h1>
                <p class="text-xs text-gray-400 mt-0.5">Historial de inscripciones a mesas de examen</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <Link :href="route('mesas.index')" class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="bx bx-arrow-back mr-1"></i>
                    Volver a Mesas Disponibles
                </Link>
            </div>

            <!-- Tabla de Inscripciones -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Materia
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha Examen
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Llamado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aula
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha Inscripción
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nota
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-if="inscripciones.length === 0">
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <i class="bx bx-calendar-x text-6xl mb-4"></i>
                                    <p class="text-lg">No tienes inscripciones a mesas de examen</p>
                                    <Link
                                        :href="route('mesas.index')"
                                        class="mt-4 inline-block text-blue-600 hover:text-blue-800"
                                    >
                                        Ver mesas disponibles →
                                    </Link>
                                </td>
                            </tr>
                            <tr v-for="inscripcion in inscripciones" :key="inscripcion.id" class="hover:bg-gray-50">
                                <!-- Materia -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ inscripcion.mesa.materia }}</div>
                                    <div class="text-xs text-gray-500">{{ inscripcion.mesa.carrera }}</div>
                                </td>

                                <!-- Fecha Examen -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ inscripcion.mesa.fecha_examen }}</div>
                                    <div class="text-xs text-gray-500">{{ inscripcion.mesa.hora_examen }}</div>
                                </td>

                                <!-- Llamado -->
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-900">{{ getLlamadoTexto(inscripcion.mesa.llamado) }}</span>
                                </td>

                                <!-- Aula -->
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ inscripcion.mesa.aula || '-' }}
                                </td>

                                <!-- Fecha Inscripción -->
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ inscripcion.fecha_inscripcion }}
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-4">
                                    <span :class="getEstadoBadgeClass(inscripcion.estado)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ inscripcion.estado.charAt(0).toUpperCase() + inscripcion.estado.slice(1) }}
                                    </span>
                                </td>

                                <!-- Nota -->
                                <td class="px-6 py-4">
                                    <span v-if="inscripcion.nota" class="text-sm font-bold" :class="inscripcion.nota >= 4 ? 'text-green-600' : 'text-red-600'">
                                        {{ inscripcion.nota }}
                                    </span>
                                    <span v-else class="text-sm text-gray-400">-</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
