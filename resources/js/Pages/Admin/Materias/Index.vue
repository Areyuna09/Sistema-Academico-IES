<script setup>
import { ref, computed } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    materias: Object,
    carreras: Array,
    filtros: Object,
});

const form = useForm({
    carrera: props.filtros?.carrera || '',
    semestre: props.filtros?.semestre || '',
    anno: props.filtros?.anno || '',
    buscar: props.filtros?.buscar || '',
});

const buscar = () => {
    form.get(route('admin.materias.index'), {
        preserveState: true,
        preserveScroll: true,
    });
};

const limpiarFiltros = () => {
    form.carrera = '';
    form.semestre = '';
    form.anno = '';
    form.buscar = '';
    form.get(route('admin.materias.index'));
};

const eliminarMateria = (materia) => {
    if (confirm(`¿Está seguro de eliminar la materia "${materia.nombre}"?`)) {
        router.delete(route('admin.materias.destroy', materia.id));
    }
};

const getSemestreBadge = (semestre) => {
    return semestre === 1
        ? { text: '1° Sem', class: 'bg-blue-100 text-blue-800' }
        : { text: '2° Sem', class: 'bg-purple-100 text-purple-800' };
};
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Gestión de Materias</h1>
                <p class="text-xs text-gray-400 mt-0.5">Administrar materias del instituto</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Botón Nueva Materia -->
            <div class="flex justify-end mb-6">
                <Link
                    :href="route('admin.materias.create')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200"
                >
                    <i class="bx bx-plus text-xl mr-2"></i>
                    Nueva Materia
                </Link>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form @submit.prevent="buscar" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Carrera</label>
                        <select
                            v-model="form.carrera"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                            <option value="">Todas las carreras</option>
                            <option v-for="carrera in carreras" :key="carrera.Id" :value="carrera.Id">
                                {{ carrera.nombre }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                        <select
                            v-model="form.anno"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                            <option value="">Todos los años</option>
                            <option value="1">1° Año</option>
                            <option value="2">2° Año</option>
                            <option value="3">3° Año</option>
                            <option value="4">4° Año</option>
                            <option value="5">5° Año</option>
                            <option value="6">6° Año</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Semestre</label>
                        <select
                            v-model="form.semestre"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                            <option value="">Ambos semestres</option>
                            <option value="1">1° Semestre</option>
                            <option value="2">2° Semestre</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input
                            v-model="form.buscar"
                            type="text"
                            placeholder="Nombre de materia..."
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        />
                    </div>

                    <div class="flex items-end gap-2">
                        <button
                            type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200"
                        >
                            <i class="bx bx-search mr-1"></i> Buscar
                        </button>
                        <button
                            type="button"
                            @click="limpiarFiltros"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200"
                        >
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de materias -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Materia
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Carrera
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Año
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Semestre
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Resolución
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="materia in materias.data" :key="materia.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ materia.nombre }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">{{ materia.carrera?.nombre || '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ materia.anno }}° Año
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', getSemestreBadge(materia.semestre).class]">
                                        {{ getSemestreBadge(materia.semestre).text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">{{ materia.resolucion || '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="route('admin.materias.edit', materia.id)"
                                            class="text-blue-600 hover:text-blue-900"
                                            title="Editar"
                                        >
                                            <i class="bx bx-edit text-lg"></i>
                                        </Link>
                                        <button
                                            @click="eliminarMateria(materia)"
                                            class="text-red-600 hover:text-red-900"
                                            title="Eliminar"
                                        >
                                            <i class="bx bx-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="materias.data.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="bx bx-search-alt text-4xl mb-2"></i>
                                    <p>No se encontraron materias</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div v-if="materias.links.length > 3" class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-wrap gap-1">
                        <component
                            :is="link.url ? Link : 'span'"
                            v-for="(link, index) in materias.links"
                            :key="index"
                            :href="link.url"
                            :class="[
                                'px-3 py-2 text-sm rounded-lg',
                                link.active
                                    ? 'bg-blue-600 text-white'
                                    : link.url
                                    ? 'bg-white text-gray-700 hover:bg-gray-100'
                                    : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                            ]"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
