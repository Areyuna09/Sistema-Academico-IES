<script setup>
import { ref, computed } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import Dialog from '@/Components/Dialog.vue';
import { useDialog } from '@/Composables/useDialog';

const props = defineProps({
    carreras: Object,
    filtros: Object,
});

const form = useForm({
    buscar: props.filtros?.buscar || '',
});

const buscar = () => {
    form.get(route('admin.carreras.index'), {
        preserveState: true,
        preserveScroll: true,
    });
};

const limpiarFiltros = () => {
    form.buscar = '';
    form.get(route('admin.carreras.index'));
};

const { confirm: showConfirm } = useDialog();

const eliminarCarrera = async (carrera) => {
    const confirmed = await showConfirm(
        `¿Está seguro de eliminar la carrera "${carrera.nombre}"? Esta acción no se puede deshacer y puede afectar a materias y alumnos asociados.`,
        'Confirmar eliminación'
    );

    if (confirmed) {
        router.delete(route('admin.carreras.destroy', carrera.Id));
    }
};
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Gestión de Carreras</h1>
                <p class="text-xs text-gray-400 mt-0.5">Administrar carreras del instituto</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Botón Nueva Carrera -->
            <div class="flex justify-end mb-6">
                <Link
                    :href="route('admin.carreras.create')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200"
                >
                    <i class="bx bx-plus text-xl mr-2"></i>
                    Nueva Carrera
                </Link>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form @submit.prevent="buscar" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[300px]">
                        <input
                            v-model="form.buscar"
                            type="text"
                            placeholder="Buscar por nombre o resolución..."
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        />
                    </div>
                    <div class="flex gap-2">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200"
                        >
                            <i class="bx bx-search mr-1"></i> Buscar
                        </button>
                        <button
                            type="button"
                            @click="limpiarFiltros"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200"
                        >
                            <i class="bx bx-x mr-1"></i> Limpiar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de carreras -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Resolución
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Materias
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Alumnos
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="carrera in carreras.data" :key="carrera.Id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ carrera.nombre }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ carrera.resolucion || '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ carrera.materias_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ carrera.alumnos_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="route('admin.carreras.edit', carrera.Id)"
                                            class="text-blue-600 hover:text-blue-900"
                                            title="Editar"
                                        >
                                            <i class="bx bx-edit text-lg"></i>
                                        </Link>
                                        <button
                                            @click="eliminarCarrera(carrera)"
                                            class="text-red-600 hover:text-red-900"
                                            title="Eliminar"
                                        >
                                            <i class="bx bx-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="carreras.data.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i class="bx bx-search-alt text-4xl mb-2"></i>
                                    <p>No se encontraron carreras</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div v-if="carreras.links.length > 3" class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-wrap gap-1">
                        <component
                            :is="link.url ? Link : 'span'"
                            v-for="(link, index) in carreras.links"
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

        <!-- Dialog component -->
        <Dialog />
    </SidebarLayout>
</template>
