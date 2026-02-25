<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    materias: Array,
    carreras: Array,
    filtros: Object,
    periodoActivo: Object,
});

const filtroCarrera = ref(props.filtros?.carrera_id || '');
const filtroBuscar = ref(props.filtros?.buscar || '');

const aplicarFiltros = () => {
    const params = {};
    if (filtroCarrera.value) params.carrera_id = filtroCarrera.value;
    if (filtroBuscar.value) params.buscar = filtroBuscar.value;
    router.get(route('preceptor.asistencias.index'), params, { preserveState: true });
};

const limpiarFiltros = () => {
    filtroCarrera.value = '';
    filtroBuscar.value = '';
    router.get(route('preceptor.asistencias.index'));
};

const materiasPorCarrera = computed(() => {
    const agrupado = {};
    props.materias.forEach(m => {
        const key = m.carrera_nombre;
        if (!agrupado[key]) agrupado[key] = [];
        agrupado[key].push(m);
    });
    return agrupado;
});

const totalMaterias = computed(() => props.materias.length);
const totalConAsistencia = computed(() => props.materias.filter(m => m.asistencia_hoy).length);
</script>

<template>
    <Head title="Asistencias - Preceptor" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Asistencias</h1>
                <p class="text-xs text-gray-400 mt-0.5" v-if="periodoActivo">{{ periodoActivo.nombre }}</p>
            </div>
        </template>

        <div class="p-4 md:p-8 max-w-7xl mx-auto">
            <!-- Sin periodo activo -->
            <div v-if="!periodoActivo" class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <i class="bx bx-calendar-x text-4xl text-yellow-500 mb-2"></i>
                <h3 class="text-lg font-semibold text-gray-900">No hay periodo activo</h3>
                <p class="text-sm text-gray-600 mt-1">No se puede tomar asistencia sin un periodo lectivo activo.</p>
            </div>

            <template v-else>
                <!-- Periodo activo -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 mb-4 flex items-center gap-2">
                    <i class="bx bx-calendar text-blue-600 text-lg"></i>
                    <span class="text-sm text-blue-800">Periodo activo: <strong>{{ periodoActivo.nombre }}</strong></span>
                </div>

                <!-- Resumen -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-6">
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="bx bx-book-open text-xl text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">{{ totalMaterias }}</p>
                                <p class="text-xs text-gray-500">Materias</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="bx bx-check-circle text-xl text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">{{ totalConAsistencia }}</p>
                                <p class="text-xs text-gray-500">Con asistencia hoy</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 col-span-2 md:col-span-1">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="bx bx-time text-xl text-orange-600"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">{{ totalMaterias - totalConAsistencia }}</p>
                                <p class="text-xs text-gray-500">Pendientes hoy</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <div class="flex flex-wrap items-end gap-3">
                        <div class="flex-1 min-w-[180px]">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Carrera</label>
                            <select
                                v-model="filtroCarrera"
                                @change="aplicarFiltros"
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                            >
                                <option value="">Todas las carreras</option>
                                <option v-for="c in carreras" :key="c.id" :value="c.id">{{ c.nombre }}</option>
                            </select>
                        </div>
                        <div class="flex-1 min-w-[180px]">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Buscar materia</label>
                            <input
                                v-model="filtroBuscar"
                                type="text"
                                placeholder="Nombre de materia..."
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                                @keyup.enter="aplicarFiltros"
                            />
                        </div>
                        <div class="flex gap-2">
                            <button
                                @click="aplicarFiltros"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors"
                            >
                                <i class="bx bx-search mr-1"></i> Buscar
                            </button>
                            <button
                                v-if="filtroCarrera || filtroBuscar"
                                @click="limpiarFiltros"
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded transition-colors"
                            >
                                Limpiar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Listado de materias agrupado por carrera -->
                <div v-if="materias.length === 0" class="bg-white rounded-lg shadow p-8 text-center">
                    <i class="bx bx-search-alt text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">No se encontraron materias con los filtros aplicados.</p>
                </div>

                <div v-for="(materiasList, carreraNombre) in materiasPorCarrera" :key="carreraNombre" class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <i class="bx bx-briefcase text-orange-500"></i>
                        {{ carreraNombre }}
                        <span class="text-xs font-normal text-gray-400">({{ materiasList.length }} materias)</span>
                    </h3>

                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materia</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profesor</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Division</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Inscriptos</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Hoy</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="mat in materiasList" :key="mat.id" class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-medium text-gray-900">{{ mat.materia_nombre }}</div>
                                            <div class="text-xs text-gray-500">{{ mat.cursado }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ mat.profesor_nombre }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 text-center">{{ mat.division || '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 text-center">{{ mat.inscriptos }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                v-if="mat.asistencia_hoy"
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700"
                                            >
                                                <i class="bx bx-check mr-1"></i> Cargada
                                            </span>
                                            <span
                                                v-else
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-500"
                                            >
                                                Pendiente
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <Link
                                                    :href="route('preceptor.asistencias.tomar', mat.id)"
                                                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors"
                                                    :title="mat.asistencia_hoy ? 'Editar asistencia de hoy' : 'Tomar asistencia'"
                                                >
                                                    <i class="bx bx-edit mr-1"></i>
                                                    {{ mat.asistencia_hoy ? 'Editar' : 'Tomar' }}
                                                </Link>
                                                <Link
                                                    :href="route('preceptor.asistencias.historial', mat.id)"
                                                    class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs font-medium rounded transition-colors"
                                                    title="Ver historial"
                                                >
                                                    <i class="bx bx-history mr-1"></i>
                                                    Historial
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </SidebarLayout>
</template>
