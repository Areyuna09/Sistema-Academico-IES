<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    legajo: Object,
    historialCompleto: Array,
    historialRevisiones: Array,
});

const mostrarModalAprobar = ref(false);
const mostrarModalRechazar = ref(false);
const mostrarModalEditar = ref(false);

// Formulario de rechazo
const formRechazo = useForm({
    observaciones: '',
});

// Formulario de edición
const formEdicion = useForm({
    nota: props.legajo.nota,
    cursada: props.legajo.cursada ? '1' : '0',
    rendida: props.legajo.rendida ? '1' : '0',
    libre: props.legajo.libre ? '1' : '0',
    equivalencia: props.legajo.equivalencia ? '1' : '0',
    libro: props.legajo.libro,
    folio: props.legajo.folio,
    fecha: props.legajo.fecha,
    motivo_correccion: '',
});

const aprobar = () => {
    router.post(route('directivo.aprobar', { id: props.legajo.id }), {}, {
        onSuccess: () => {
            mostrarModalAprobar.value = false;
        },
    });
};

const rechazar = () => {
    formRechazo.post(route('directivo.rechazar', { id: props.legajo.id }), {
        onSuccess: () => {
            mostrarModalRechazar.value = false;
            formRechazo.reset();
        },
    });
};

const guardarEdicion = () => {
    formEdicion.put(route('directivo.actualizar', { id: props.legajo.id }), {
        onSuccess: () => {
            mostrarModalEditar.value = false;
            formEdicion.reset('motivo_correccion');
        },
    });
};

const getEstadoBadge = (estado) => {
    const badges = {
        'pendiente': 'bg-yellow-100 text-yellow-800',
        'observaciones_supervisor': 'bg-orange-100 text-orange-800',
        'aprobado_directivo': 'bg-green-100 text-green-800',
        'observaciones_directivo': 'bg-red-100 text-red-800',
    };
    return badges[estado] || 'bg-gray-100 text-gray-800';
};

const puedeAprobar = computed(() => {
    return ['pendiente', 'observaciones_supervisor'].includes(props.legajo.estado_revision);
});

const puedeRechazar = computed(() => {
    return ['pendiente', 'observaciones_supervisor'].includes(props.legajo.estado_revision);
});
</script>

<template>
    <Head title="Revisar Legajo" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <Link :href="route('directivo.index')" class="text-blue-600 hover:text-blue-800 mb-2 inline-block">
                            <i class="bx bx-arrow-back mr-1"></i> Volver al Panel
                        </Link>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                            Revisión de Legajo
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
                            <i class="bx bx-check-circle mr-2"></i>
                            Aprobar Legajo
                        </button>
                        <button
                            v-if="puedeRechazar"
                            @click="mostrarModalRechazar = true"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition flex items-center"
                        >
                            <i class="bx bx-x-circle mr-2"></i>
                            Rechazar
                        </button>
                        <button
                            @click="mostrarModalEditar = true"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center"
                        >
                            <i class="bx bx-edit mr-2"></i>
                            Editar
                        </button>
                    </div>
                </div>

                <!-- Información del Alumno -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        Información del Alumno
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                                <label class="text-sm text-gray-600 dark:text-gray-400">Revisado por Directivo</label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ legajo.revisor_directivo }}
                                </p>
                                <p class="text-sm text-gray-500">{{ legajo.fecha_revision_directivo }}</p>
                            </div>
                        </div>

                        <!-- Observaciones del Supervisor -->
                        <div v-if="legajo.observaciones_supervisor" class="mt-4 p-4 bg-orange-50 dark:bg-orange-900/20 border-l-4 border-orange-500 rounded">
                            <h4 class="font-semibold text-orange-800 dark:text-orange-300 mb-2">
                                <i class="bx bx-error-circle mr-1"></i>
                                Observaciones del Supervisor
                            </h4>
                            <p class="text-orange-700 dark:text-orange-400">
                                {{ legajo.observaciones_supervisor }}
                            </p>
                            <p class="text-sm text-orange-600 dark:text-orange-500 mt-2">
                                Revisado por: {{ legajo.revisor_supervisor }} - {{ legajo.fecha_revision_supervisor }}
                            </p>
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
                    </div>
                </div>

                <!-- Historial Académico del Alumno -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        Historial Académico Completo
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
                                        <span v-if="item.id === legajo.id" class="ml-2 text-blue-600 font-semibold">(Actual)</span>
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
                        Historial de Revisiones
                    </h2>
                    <div class="space-y-4">
                        <div v-for="revision in historialRevisiones" :key="revision.id"
                             class="border-l-4 border-blue-500 pl-4 py-2">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ revision.revisor }} ({{ revision.tipo_revisor }})
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Acción: <span class="font-medium">{{ revision.accion }}</span>
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-500">
                                        {{ revision.estado_anterior }} → {{ revision.estado_nuevo }}
                                    </p>
                                    <p v-if="revision.observaciones" class="text-sm text-gray-700 dark:text-gray-300 mt-2">
                                        {{ revision.observaciones }}
                                    </p>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ revision.fecha }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Aprobar -->
        <div v-if="mostrarModalAprobar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    Confirmar Aprobación
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    ¿Estás seguro de aprobar este legajo? Se enviará a revisión del Supervisor.
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
                        Sí, Aprobar
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Rechazar -->
        <div v-if="mostrarModalRechazar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    Rechazar Legajo
                </h3>
                <form @submit.prevent="rechazar">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Observaciones *
                        </label>
                        <textarea
                            v-model="formRechazo.observaciones"
                            rows="4"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Especifica las correcciones necesarias..."
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
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 disabled:opacity-50"
                        >
                            Rechazar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Editar -->
        <div v-if="mostrarModalEditar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-2xl w-full mx-4 my-8">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    Editar Legajo
                </h3>
                <form @submit.prevent="guardarEdicion">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nota
                            </label>
                            <input
                                v-model="formEdicion.nota"
                                type="number"
                                step="0.01"
                                min="1"
                                max="10"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Fecha
                            </label>
                            <input
                                v-model="formEdicion.fecha"
                                type="date"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Libro
                            </label>
                            <input
                                v-model="formEdicion.libro"
                                type="text"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Folio
                            </label>
                            <input
                                v-model="formEdicion.folio"
                                type="text"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            />
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-4 mb-4">
                        <label class="flex items-center">
                            <input
                                v-model="formEdicion.cursada"
                                type="checkbox"
                                true-value="1"
                                false-value="0"
                                class="rounded border-gray-300 text-blue-600"
                            />
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Cursada</span>
                        </label>
                        <label class="flex items-center">
                            <input
                                v-model="formEdicion.rendida"
                                type="checkbox"
                                true-value="1"
                                false-value="0"
                                class="rounded border-gray-300 text-blue-600"
                            />
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Rendida</span>
                        </label>
                        <label class="flex items-center">
                            <input
                                v-model="formEdicion.libre"
                                type="checkbox"
                                true-value="1"
                                false-value="0"
                                class="rounded border-gray-300 text-blue-600"
                            />
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Libre</span>
                        </label>
                        <label class="flex items-center">
                            <input
                                v-model="formEdicion.equivalencia"
                                type="checkbox"
                                true-value="1"
                                false-value="0"
                                class="rounded border-gray-300 text-blue-600"
                            />
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Equivalencia</span>
                        </label>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Motivo de la Corrección *
                        </label>
                        <textarea
                            v-model="formEdicion.motivo_correccion"
                            rows="3"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Describe por qué realizas esta corrección..."
                            required
                        ></textarea>
                        <p v-if="formEdicion.errors.motivo_correccion" class="text-red-600 text-sm mt-1">
                            {{ formEdicion.errors.motivo_correccion }}
                        </p>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            @click="mostrarModalEditar = false"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="formEdicion.processing"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
                        >
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
