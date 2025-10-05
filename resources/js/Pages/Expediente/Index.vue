<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    alumnos: Object,
    carreras: Array,
    filters: {
        type: Object,
        default: () => ({})
    }
});

// Estados para los filtros
const search = ref(props.filters?.search || '');
const selectedCarrera = ref(props.filters?.carrera || '');
const selectedDivision = ref(props.filters?.division || '');

// Aplicar filtros
const applyFilters = () => {
    router.get('/expediente', {
        search: search.value,
        carrera: selectedCarrera.value,
        division: selectedDivision.value
    }, {
        preserveState: true,
        preserveScroll: true
    });
};

// Limpiar filtros
const clearFilters = () => {
    search.value = '';
    selectedCarrera.value = '';
    selectedDivision.value = '';
    applyFilters();
};

// Obtener badge de división
const getDivisionBadge = (curso) => {
    if (curso === 1 || curso === '1') return { text: 'División 1', color: 'bg-blue-500' };
    if (curso === 2 || curso === '2') return { text: 'División 2', color: 'bg-green-500' };
    if (curso === 3 || curso === '3') return { text: 'División 3', color: 'bg-purple-500' };
    return { text: `División ${curso}`, color: 'bg-gray-500' };
};

// Formatear nombre completo
const nombreCompleto = (alumno) => {
    return `${alumno.nombre || ''} ${alumno.apellido || ''}`.trim();
};
</script>

<template>
    <Head title="Expedientes Académicos" />

    <SidebarLayout>
        <div class="min-h-screen bg-gray-50">
            <!-- Header Section -->
            <div class="bg-white border-b border-gray-200 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-100 p-3 rounded-xl">
                            <i class="bx bx-folder-open text-blue-600 text-4xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Expedientes Académicos</h1>
                            <p class="text-gray-600 mt-1">Gestiona y consulta los expedientes de todos los estudiantes</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="bg-white rounded-xl shadow p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search Input -->
                        <div class="md:col-span-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bx bx-search text-gray-400 text-xl"></i>
                                </div>
                                <input
                                    v-model="search"
                                    @input="applyFilters"
                                    type="text"
                                    placeholder="Buscar alumnos por nombre o DNI"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                />
                            </div>
                        </div>

                        <!-- Carrera Filter -->
                        <div>
                            <select
                                v-model="selectedCarrera"
                                @change="applyFilters"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            >
                                <option value="">Todas las carreras</option>
                                <option v-for="carrera in carreras" :key="carrera.Id" :value="carrera.Id">
                                    {{ carrera.nombre }}
                                </option>
                            </select>
                        </div>

                        <!-- Division Filter -->
                        <div>
                            <select
                                v-model="selectedDivision"
                                @change="applyFilters"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            >
                                <option value="">Todas las divisiones</option>
                                <option value="1">División 1</option>
                                <option value="2">División 2</option>
                                <option value="3">División 3</option>
                            </select>
                        </div>
                    </div>

                    <!-- Clear Filters Button -->
                    <div v-if="filters?.search || filters?.carrera || filters?.division" class="mt-4">
                        <button
                            @click="clearFilters"
                            class="text-sm text-blue-600 hover:text-blue-700 flex items-center space-x-1 transition-colors"
                        >
                            <i class="bx bx-x-circle"></i>
                            <span>Limpiar filtros</span>
                        </button>
                    </div>
                </div>

                <!-- Results Count -->
                <div class="mb-4 text-gray-600 text-sm">
                    Mostrando {{ alumnos?.data?.length || 0 }} de {{ alumnos?.total || 0 }} estudiantes
                </div>

                <!-- Alumnos List -->
                <div class="space-y-3">
                    <div
                        v-for="alumno in alumnos?.data || []"
                        :key="alumno.id"
                        class="bg-white rounded-xl shadow hover:shadow-lg transition-all duration-200 hover:-translate-y-1"
                    >
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <!-- Alumno Info -->
                                <div class="flex items-center space-x-4 flex-1">
                                    <div class="bg-blue-100 rounded-full p-3">
                                        <i class="bx bx-user text-blue-600 text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ nombreCompleto(alumno) }}
                                        </h3>
                                        <div class="flex items-center space-x-4 mt-1 text-sm text-gray-600">
                                            <span class="flex items-center space-x-1">
                                                <i class="bx bx-id-card"></i>
                                                <span>DNI: {{ alumno.dni }}</span>
                                            </span>
                                            <span class="flex items-center space-x-1">
                                                <i class="bx bx-book"></i>
                                                <span>{{ alumno.carrera?.nombre || 'Sin carrera' }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Division Badge & Actions -->
                                <div class="flex items-center space-x-4">
                                    <span
                                        :class="[
                                            'px-3 py-1 rounded-full text-white text-sm font-medium',
                                            getDivisionBadge(alumno.curso).color
                                        ]"
                                    >
                                        {{ getDivisionBadge(alumno.curso).text }}
                                    </span>

                                    <div class="flex space-x-2">
                                        <Link
                                            :href="`/expediente/${alumno.id}`"
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition-colors flex items-center space-x-2"
                                        >
                                            <i class="bx bx-show"></i>
                                            <span>Ver</span>
                                        </Link>
                                        <button
                                            class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white rounded-lg transition-colors flex items-center space-x-2"
                                        >
                                            <i class="bx bx-edit"></i>
                                            <span>Modificar</span>
                                        </button>
                                        <button
                                            class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg transition-colors flex items-center space-x-2"
                                        >
                                            <i class="bx bx-trash"></i>
                                            <span>Eliminar</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="!alumnos?.data || alumnos.data.length === 0" class="bg-white rounded-xl shadow p-12 text-center">
                    <i class="bx bx-search-alt text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No se encontraron resultados</h3>
                    <p class="text-gray-500">Intenta ajustar los filtros de búsqueda</p>
                </div>

                <!-- Pagination -->
                <div v-if="alumnos?.data && alumnos.data.length > 0" class="mt-6 flex justify-center">
                    <div class="flex space-x-2">
                        <template v-for="link in alumnos?.links || []" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                :class="[
                                    'px-4 py-2 rounded-lg transition-colors',
                                    link.active
                                        ? 'bg-blue-600 text-white'
                                        : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300'
                                ]"
                                v-html="link.label"
                            />
                            <span
                                v-else
                                :class="[
                                    'px-4 py-2 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200'
                                ]"
                                v-html="link.label"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
