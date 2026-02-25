<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    profesorMateria: { type: Object, required: true },
    asistencias: { type: Object, required: true },
    filtros: { type: Object, required: true },
    periodoActivo: { type: Object, default: null },
});

const fechaDesde = ref(props.filtros.fecha_desde);
const fechaHasta = ref(props.filtros.fecha_hasta);
const fechaExpandida = ref(null);

const filtrar = () => {
    router.get(route('preceptor.asistencias.historial', props.profesorMateria.id), {
        fecha_desde: fechaDesde.value,
        fecha_hasta: fechaHasta.value,
    }, { preserveState: true });
};

const toggleFecha = (fecha) => {
    fechaExpandida.value = fechaExpandida.value === fecha ? null : fecha;
};

const asistenciasArray = Object.values(props.asistencias);

const colorEstado = (estado) => {
    const colores = {
        presente: 'bg-green-100 text-green-700',
        ausente: 'bg-red-100 text-red-700',
        tarde: 'bg-yellow-100 text-yellow-700',
        justificada: 'bg-blue-100 text-blue-700',
    };
    return colores[estado] || 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <Head title="Historial de Asistencias" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Historial de Asistencias</h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ profesorMateria.materia_nombre }} - {{ profesorMateria.carrera_nombre }}
                </p>
            </div>
        </template>

        <div class="p-4 md:p-6 max-w-5xl mx-auto">
            <!-- Navegacion -->
            <div class="mb-4 flex flex-wrap gap-2">
                <Link
                    :href="route('preceptor.asistencias.index')"
                    class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded transition-colors"
                >
                    <i class="bx bx-arrow-back mr-1"></i> Volver
                </Link>
                <Link
                    :href="route('preceptor.asistencias.tomar', profesorMateria.id)"
                    class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors"
                >
                    <i class="bx bx-edit mr-1"></i> Tomar Asistencia
                </Link>
            </div>

            <!-- Periodo activo -->
            <div v-if="periodoActivo" class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 mb-4 flex items-center gap-2">
                <i class="bx bx-calendar text-blue-600 text-lg"></i>
                <span class="text-sm text-blue-800">Periodo activo: <strong>{{ periodoActivo.nombre }}</strong></span>
            </div>

            <!-- Info materia -->
            <div class="bg-white rounded-lg shadow p-4 mb-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500">Materia:</span>
                        <span class="ml-1 font-medium">{{ profesorMateria.materia_nombre }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Carrera:</span>
                        <span class="ml-1 font-medium">{{ profesorMateria.carrera_nombre }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Profesor:</span>
                        <span class="ml-1 font-medium">{{ profesorMateria.profesor_nombre }}</span>
                    </div>
                </div>
            </div>

            <!-- Filtro de fechas -->
            <div class="bg-white rounded-lg shadow p-4 mb-4">
                <div class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Desde</label>
                        <input v-model="fechaDesde" type="date" class="border border-gray-300 rounded px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Hasta</label>
                        <input v-model="fechaHasta" type="date" class="border border-gray-300 rounded px-3 py-2 text-sm" />
                    </div>
                    <button
                        @click="filtrar"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors"
                    >
                        <i class="bx bx-filter mr-1"></i> Filtrar
                    </button>
                </div>
            </div>

            <!-- Sin registros -->
            <div v-if="asistenciasArray.length === 0" class="bg-white rounded-lg shadow p-8 text-center">
                <i class="bx bx-calendar-x text-4xl text-gray-300 mb-2"></i>
                <p class="text-gray-500">No hay registros de asistencia en el rango seleccionado.</p>
            </div>

            <!-- Listado por fecha -->
            <div v-for="dia in asistenciasArray" :key="dia.fecha" class="mb-3">
                <button
                    @click="toggleFecha(dia.fecha)"
                    class="w-full bg-white rounded-lg shadow p-4 flex items-center justify-between hover:bg-gray-50 transition-colors"
                >
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="bx bx-calendar text-blue-600"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-gray-900">{{ dia.fecha_formateada }}</p>
                            <p class="text-xs text-gray-500">{{ dia.total_alumnos }} alumnos</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex gap-2 text-xs">
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full">{{ dia.totales.presente }} P</span>
                            <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full">{{ dia.totales.ausente }} A</span>
                            <span v-if="dia.totales.tarde" class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full">{{ dia.totales.tarde }} T</span>
                            <span v-if="dia.totales.justificada" class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">{{ dia.totales.justificada }} J</span>
                        </div>
                        <i :class="['bx text-gray-400 text-xl', fechaExpandida === dia.fecha ? 'bx-chevron-up' : 'bx-chevron-down']"></i>
                    </div>
                </button>

                <!-- Detalle expandible -->
                <div v-if="fechaExpandida === dia.fecha" class="bg-white border border-t-0 rounded-b-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alumno</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">DNI</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="det in dia.detalles" :key="det.id" class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-sm text-gray-900">{{ det.alumno_nombre }}</td>
                                <td class="px-4 py-2 text-sm text-gray-500">{{ det.alumno_dni }}</td>
                                <td class="px-4 py-2 text-center">
                                    <span :class="['px-2 py-0.5 text-xs font-medium rounded-full', colorEstado(det.estado)]">
                                        {{ det.estado }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-500">{{ det.observaciones || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
