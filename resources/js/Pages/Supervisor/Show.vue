<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    legajo: Object,
    historialCompleto: Array,
    historialRevisiones: Array,
    estadisticasAlumno: Object,
});

const mostrarModalAprobar = ref(false);
const mostrarModalRechazar = ref(false);

// Formulario de rechazo
const formRechazo = useForm({
    observaciones: '',
});

const aprobar = () => {
    router.post(route('supervisor.aprobar', { id: props.legajo.id }), {}, {
        onSuccess: () => {
            mostrarModalAprobar.value = false;
        },
    });
};

const rechazar = () => {
    formRechazo.post(route('supervisor.rechazar', { id: props.legajo.id }), {
        onSuccess: () => {
            mostrarModalRechazar.value = false;
            formRechazo.reset();
        },
    });
};

const getEstadoBadge = (estado) => {
    const badges = {
        'aprobado_directivo': 'bg-blue-100 text-blue-800',
        'observaciones_directivo': 'bg-yellow-100 text-yellow-800',
        'aprobado_supervisor': 'bg-green-100 text-green-800',
        'aprobado_final': 'bg-green-600 text-white',
    };
    return badges[estado] || 'bg-gray-100 text-gray-800';
};

const puedeAprobar = computed(() => {
    return ['aprobado_directivo', 'observaciones_directivo'].includes(props.legajo.estado_revision);
});

const puedeRechazar = computed(() => {
    return ['aprobado_directivo', 'observaciones_directivo'].includes(props.legajo.estado_revision);
});
</script>

<template>
    <Head title="Supervisar Legajo" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <Link :href="route('supervisor.index')" class="text-blue-600 hover:text-blue-800 mb-2 inline-block">
                            <i class="bx bx-arrow-back mr-1"></i> Volver al Panel
                        </Link>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                            Supervisión Ministerial de Legajo
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ legajo.alumno.nombre_completo }} - {{ legajo.materia.nombre }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button
                            v-if="puedeAprobar"
                            @click="mostrarModalAprobar = true"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition flex items-center"
                        >
                            <i class="bx bx-check-double mr-2"></i>
                            Aprobación Final
                        </button>
                        <button
                            v-if="puedeRechazar"
                            @click="mostrarModalRechazar = true"
                            class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition flex items-center"
                        >
                            <i class="bx bx-revision mr-2"></i>
                            Devolver a Directivo
                        </button>
                    </div>
                </div>

                <!-- Información del Alumno -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        Información del Alumno
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Nombre Completo</label>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ legajo.alumno.nombre_completo }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">DNI</label>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ legajo.alumno.dni }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Email</label>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ legajo.alumno.email || 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Carrera</label>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ legajo.alumno.carrera }}
                            </p>
                        </div>
                    </div>

                    <!-- Estadísticas del Alumno -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                            Resumen Académico
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Materias Aprobadas</p>
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ estadisticasAlumno.materias_aprobadas }}
                                </p>
                            </div>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Materias Regulares</p>
                                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                    {{ estadisticasAlumno.materias_regulares }}
                                </p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Promedio General</p>
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                    {{ estadisticasAlumno.promedio ? estadisticasAlumno.promedio.toFixed(2) : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalles del Legajo -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        Detalles del Legajo
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Materia</label>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ legajo.materia.nombre }}
                            </p>
                            <p class="text-sm text-gray-500">Año: {{ legajo.materia.anno }}°</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Nota Final</label>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ legajo.nota || 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Fecha</label>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ legajo.fecha || 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Libro</label>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ legajo.libro || 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Folio</label>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ legajo.folio || 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400">Estado</label>
                            <div class="flex gap-2 mt-1">
                                <span v-if="legajo.cursada" class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                    Cursada
                                </span>
                                <span v-if="legajo.rendida" class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">
                                    Rendida
                                </span>
                                <span v-if="legajo.libre" class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded">
                                    Libre
                                </span>
                                <span v-if="legajo.equivalencia" class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded">
                                    Equivalencia
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Estado de Revisión -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Estado de Revisión</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400">Estado Actual</label>
                                <p>
                                    <span :class="[getEstadoBadge(legajo.estado_revision), 'px-3 py-1 text-sm font-semibold rounded-full inline-block mt-1']">
                                        {{ legajo.estado_revision }}
                                    </span>
                                </p>
                            </div>
                            <div v-if="legajo.revisor_directivo">
                                <label class="text-sm text-gray-600 dark:text-gray-400">Aprobado por Directivo</label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ legajo.revisor_directivo }}
                                </p>
                                <p class="text-sm text-gray-500">{{ legajo.fecha_revision_directivo }}</p>
                            </div>
                        </div>

                        <!-- Observaciones del Directivo -->
                        <div v-if="legajo.observaciones_directivo" class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded">
                            <h4 class="font-semibold text-blue-800 dark:text-blue-300 mb-2">
                                Observaciones del Directivo
                            </h4>
                            <p class="text-blue-700 dark:text-blue-400">
                                {{ legajo.observaciones_directivo }}
                            </p>
                        </div>

                        <!-- Observaciones previas del Supervisor -->
                        <div v-if="legajo.observaciones_supervisor" class="mt-4 p-4 bg-orange-50 dark:bg-orange-900/20 border-l-4 border-orange-500 rounded">
                            <h4 class="font-semibold text-orange-800 dark:text-orange-300 mb-2">
                                <i class="bx bx-history mr-1"></i>
                                Observaciones Anteriores del Supervisor
                            </h4>
                            <p class="text-orange-700 dark:text-orange-400">
                                {{ legajo.observaciones_supervisor }}
                            </p>
                            <p class="text-sm text-orange-600 dark:text-orange-500 mt-2">
                                {{ legajo.fecha_revision_supervisor }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Historial Académico Completo -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        Historial Académico Completo del Alumno
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Materia
                                    </th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Nota
                                    </th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Estado
                                    </th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Fecha
                                    </th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Revisión
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="item in historialCompleto" :key="item.id"
                                    :class="item.id === legajo.id ? 'bg-blue-50 dark:bg-blue-900/20' : ''">
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">
                                        {{ item.materia }}
                                        <span v-if="item.id === legajo.id" class="ml-2 text-blue-600 font-semibold">(En revisión)</span>
                                    </td>
                                    <td class="px-4 py-2 text-center text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ item.nota || '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-center text-sm">
                                        <span v-if="item.cursada" class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded mr-1">C</span>
                                        <span v-if="item.rendida" class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">R</span>
                                    </td>
                                    <td class="px-4 py-2 text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ item.fecha }}
                                    </td>
                                    <td class="px-4 py-2 text-center text-sm">
                                        <span :class="[getEstadoBadge(item.estado_revision), 'px-2 py-1 text-xs rounded']">
                                            {{ item.estado_revision }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Historial de Revisiones -->
                <div v-if="historialRevisiones.length > 0" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        Historial de Revisiones y Auditoría
                    </h2>
                    <div class="space-y-4">
                        <div v-for="revision in historialRevisiones" :key="revision.id"
                             class="border-l-4 pl-4 py-2"
                             :class="revision.tipo_revisor === 'supervisor' ? 'border-green-500' : 'border-blue-500'">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ revision.revisor }}
                                        <span class="ml-2 px-2 py-1 text-xs rounded"
                                              :class="revision.tipo_revisor === 'supervisor' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'">
                                            {{ revision.tipo_revisor === 'supervisor' ? 'Supervisor' : 'Directivo' }}
                                        </span>
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Acción: <span class="font-medium">{{ revision.accion }}</span>
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-500">
                                        {{ revision.estado_anterior }} → {{ revision.estado_nuevo }}
                                    </p>
                                    <p v-if="revision.observaciones" class="text-sm text-gray-700 dark:text-gray-300 mt-2 bg-gray-50 dark:bg-gray-900 p-2 rounded">
                                        {{ revision.observaciones }}
                                    </p>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ revision.fecha }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Aprobar Final -->
        <div v-if="mostrarModalAprobar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="bx bx-check-double text-green-600 mr-2"></i>
                    Aprobación Final
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    ¿Estás seguro de aprobar finalmente este legajo? Quedará <strong>listo para la Oficina de Títulos</strong>.
                </p>
                <div class="flex justify-end gap-3">
                    <button
                        @click="mostrarModalAprobar = false"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="aprobar"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                    >
                        Sí, Aprobar Finalmente
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Rechazar -->
        <div v-if="mostrarModalRechazar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    Devolver a Directivo con Observaciones
                </h3>
                <form @submit.prevent="rechazar">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Observaciones para el Directivo *
                        </label>
                        <textarea
                            v-model="formRechazo.observaciones"
                            rows="4"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Especifica qué correcciones debe realizar el Directivo..."
                            required
                        ></textarea>
                        <p v-if="formRechazo.errors.observaciones" class="text-red-600 text-sm mt-1">
                            {{ formRechazo.errors.observaciones }}
                        </p>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            @click="mostrarModalRechazar = false"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="formRechazo.processing"
                            class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 disabled:opacity-50"
                        >
                            Devolver con Observaciones
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
