<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    legajosPendientes: Object,
    legajosCorregidos: Object,
    estadisticas: Object,
    carreras: Array,
    filtros: Object,
});

const filtroCarrera = ref(props.filtros?.carrera || '');
const busquedaDni = ref(props.filtros?.dni || '');

const filtrar = () => {
    router.get(route('supervisor.index'), {
        carrera: filtroCarrera.value,
        dni: busquedaDni.value,
    }, {
        preserveState: true,
        preserveScroll: true,
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
</script>

<template>
    <Head title="Panel de Supervisor" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Encabezado -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Panel de Supervisor
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Supervisión ministerial final de legajos académicos
                    </p>
                </div>

                <!-- Estadísticas -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Pendientes</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ estadisticas.pendientes_supervision }}
                                </p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="bx bx-time text-2xl text-blue-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Corregidos</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ estadisticas.legajos_corregidos }}
                                </p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full">
                                <i class="bx bx-refresh text-2xl text-yellow-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Aprobados Hoy</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ estadisticas.aprobados_hoy }}
                                </p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="bx bx-check-double text-2xl text-green-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Supervisados</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ estadisticas.total_supervisados }}
                                </p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="bx bx-file-find text-2xl text-purple-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Listos para Títulos</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ estadisticas.aprobados_final }}
                                </p>
                            </div>
                            <div class="bg-green-600 p-3 rounded-full">
                                <i class="bx bx-trophy text-2xl text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Buscar por DNI
                            </label>
                            <input
                                v-model="busquedaDni"
                                type="text"
                                placeholder="Ingrese DNI del alumno"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                @keyup.enter="filtrar"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Filtrar por Carrera
                            </label>
                            <select
                                v-model="filtroCarrera"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                @change="filtrar"
                            >
                                <option value="">Todas las carreras</option>
                                <option v-for="carrera in carreras" :key="carrera.Id" :value="carrera.Id">
                                    {{ carrera.nombre }}
                                </option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button
                                @click="filtrar"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
                            >
                                <i class="bx bx-search mr-2"></i>
                                Buscar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Legajos Corregidos por Directivo (re-revisión) -->
                <div v-if="legajosCorregidos.data.length > 0" class="mb-8">
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4 mb-4">
                        <div class="flex">
                            <i class="bx bx-refresh text-2xl text-yellow-500 mr-3"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-300">
                                    Legajos Corregidos por Directivo
                                </h3>
                                <p class="text-sm text-yellow-700 dark:text-yellow-400">
                                    Estos legajos fueron devueltos y corregidos, requieren nueva revisión
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-8">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Alumno
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Materia
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Directivo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Fecha Corrección
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="legajo in legajosCorregidos.data" :key="legajo.Id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ legajo.alumno.apellido }}, {{ legajo.alumno.nombre }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            DNI: {{ legajo.alumno.dni }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ legajo.materiaRelacion?.nombre || 'Sin nombre' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ legajo.directivoRevisor?.nombre || 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ legajo.fecha_revision_directivo }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link
                                            :href="route('supervisor.show', { id: legajo.Id })"
                                            class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                        >
                                            <i class="bx bx-show mr-1"></i>
                                            Re-revisar
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Legajos Pendientes de Supervisión -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        Legajos Aprobados por Directivo - Pendientes de Supervisión Final
                    </h2>

                    <div v-if="legajosPendientes.data.length === 0"
                         class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                        <i class="bx bx-check-circle text-6xl text-green-500 mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-400 text-lg">
                            No hay legajos pendientes de supervisión
                        </p>
                    </div>

                    <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Alumno
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Carrera
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Materia
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Nota
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Aprobado por
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Fecha Aprobación
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="legajo in legajosPendientes.data" :key="legajo.Id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ legajo.alumno.apellido }}, {{ legajo.alumno.nombre }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            DNI: {{ legajo.alumno.dni }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ legajo.carrera?.nombre || 'Sin carrera' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ legajo.materiaRelacion?.nombre || 'Sin nombre' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ legajo.nota || 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ legajo.directivoRevisor?.nombre || 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ legajo.fecha_revision_directivo }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link
                                            :href="route('supervisor.show', { id: legajo.Id })"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                        >
                                            <i class="bx bx-show mr-1"></i>
                                            Supervisar
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Paginación -->
                        <div v-if="legajosPendientes.links" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            <nav class="flex items-center justify-between">
                                <div class="flex-1 flex justify-between sm:hidden">
                                    <Link
                                        v-if="legajosPendientes.prev_page_url"
                                        :href="legajosPendientes.prev_page_url"
                                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                    >
                                        Anterior
                                    </Link>
                                    <Link
                                        v-if="legajosPendientes.next_page_url"
                                        :href="legajosPendientes.next_page_url"
                                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                    >
                                        Siguiente
                                    </Link>
                                </div>
                                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            Mostrando
                                            <span class="font-medium">{{ legajosPendientes.from }}</span>
                                            a
                                            <span class="font-medium">{{ legajosPendientes.to }}</span>
                                            de
                                            <span class="font-medium">{{ legajosPendientes.total }}</span>
                                            legajos
                                        </p>
                                    </div>
                                    <div>
                                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                            <Link
                                                v-for="(link, index) in legajosPendientes.links"
                                                :key="index"
                                                :href="link.url"
                                                :class="[
                                                    link.active
                                                        ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                                                ]"
                                                v-html="link.label"
                                            />
                                        </nav>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
