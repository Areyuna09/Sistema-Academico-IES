<script setup>
import { ref, watch } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import Dialog from '@/Components/Dialog.vue';
import { useDialog } from '@/Composables/useDialog';

const props = defineProps({
    mesas: Object,
    carreras: Array,
    periodos: Array,
    filtros: Object,
});

const filtrosForm = ref({
    carrera_id: props.filtros.carrera_id || '',
    periodo_id: props.filtros.periodo_id || '',
    estado: props.filtros.estado || '',
    buscar: props.filtros.buscar || '',
});

const aplicarFiltros = () => {
    router.get(route('admin.mesas.index'), filtrosForm.value, {
        preserveState: true,
        preserveScroll: true,
    });
};

const limpiarFiltros = () => {
    filtrosForm.value = {
        carrera_id: '',
        periodo_id: '',
        estado: '',
        buscar: '',
    };
    aplicarFiltros();
};

const { confirm: showConfirm } = useDialog();

const eliminarMesa = async (mesa) => {
    const confirmed = await showConfirm(
        `¿Está seguro de eliminar la mesa de "${mesa.materia.nombre}"? Esta acción eliminará todos los datos asociados, incluyendo inscripciones y calificaciones.`,
        'Confirmar eliminación'
    );

    if (confirmed) {
        router.delete(route('admin.mesas.destroy', mesa.id), {
            preserveScroll: true,
        });
    }
};

const getEstadoBadgeClass = (estado) => {
    const clases = {
        'activa': 'bg-green-100 text-green-700',
        'cerrada': 'bg-gray-100 text-gray-700',
        'suspendida': 'bg-red-100 text-red-700',
    };
    return clases[estado] || 'bg-gray-100 text-gray-700';
};

const getLlamadoTexto = (llamado) => {
    const textos = {
        1: '1° Llamado',
        2: '2° Llamado',
        3: '3° Llamado',
    };
    return textos[llamado] || `${llamado}° Llamado`;
};
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Mesas de Examen</h1>
                <p class="text-xs text-gray-400 mt-0.5">Gestión de mesas de examen</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Botón Nueva Mesa -->
            <div class="flex justify-end mb-6">
                <Link
                    :href="route('admin.mesas.create')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200"
                >
                    <i class="bx bx-plus text-xl mr-2"></i>
                    Nueva Mesa
                </Link>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Búsqueda -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar Materia</label>
                        <input
                            v-model="filtrosForm.buscar"
                            type="text"
                            placeholder="Nombre de materia..."
                            @input="aplicarFiltros"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        />
                    </div>

                    <!-- Carrera -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Carrera</label>
                        <select
                            v-model="filtrosForm.carrera_id"
                            @change="aplicarFiltros"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                            <option value="">Todas las carreras</option>
                            <option v-for="carrera in carreras" :key="carrera.Id" :value="carrera.Id">
                                {{ carrera.nombre }}
                            </option>
                        </select>
                    </div>

                    <!-- Período -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Período</label>
                        <select
                            v-model="filtrosForm.periodo_id"
                            @change="aplicarFiltros"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                            <option value="">Todos los períodos</option>
                            <option v-for="periodo in periodos" :key="periodo.id" :value="periodo.id">
                                {{ periodo.nombre }}
                            </option>
                        </select>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select
                            v-model="filtrosForm.estado"
                            @change="aplicarFiltros"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                            <option value="">Todos los estados</option>
                            <option value="activa">Activa</option>
                            <option value="cerrada">Cerrada</option>
                            <option value="suspendida">Suspendida</option>
                        </select>
                    </div>
                </div>

                <!-- Botón limpiar filtros -->
                <div class="mt-4 flex justify-end">
                    <button
                        @click="limpiarFiltros"
                        class="text-sm text-gray-600 hover:text-gray-800"
                    >
                        <i class="bx bx-x-circle mr-1"></i>
                        Limpiar filtros
                    </button>
                </div>
            </div>

            <!-- Tabla de Mesas -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Materia
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha / Hora
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Llamado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aula
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tribunal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Inscriptos
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-if="mesas.data.length === 0">
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                    <i class="bx bx-folder-open text-4xl mb-2"></i>
                                    <p>No hay mesas de examen registradas</p>
                                </td>
                            </tr>
                            <tr v-for="mesa in mesas.data" :key="mesa.id" class="hover:bg-gray-50">
                                <!-- Materia -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ mesa.materia.nombre }}</div>
                                    <div class="text-sm text-gray-500">{{ mesa.materia.carrera.nombre }}</div>
                                </td>

                                <!-- Fecha / Hora -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ mesa.fecha_examen }}</div>
                                    <div class="text-sm text-gray-500">{{ mesa.hora_examen }}</div>
                                </td>

                                <!-- Llamado -->
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-900">{{ getLlamadoTexto(mesa.llamado) }}</span>
                                </td>

                                <!-- Aula -->
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">{{ mesa.aula || '-' }}</span>
                                </td>

                                <!-- Tribunal -->
                                <td class="px-6 py-4">
                                    <div class="text-xs space-y-1">
                                        <div v-if="mesa.presidente">
                                            <span class="font-medium">P:</span> {{ mesa.presidente.nombre_completo }}
                                        </div>
                                        <div v-if="mesa.vocal1">
                                            <span class="font-medium">V1:</span> {{ mesa.vocal1.nombre_completo }}
                                        </div>
                                        <div v-if="mesa.vocal2">
                                            <span class="font-medium">V2:</span> {{ mesa.vocal2.nombre_completo }}
                                        </div>
                                        <div v-if="!mesa.presidente && !mesa.vocal1 && !mesa.vocal2" class="text-gray-400">
                                            Sin asignar
                                        </div>
                                    </div>
                                </td>

                                <!-- Inscriptos -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <i class="bx bx-user text-gray-400 mr-1"></i>
                                        <span class="text-sm font-medium text-gray-900">{{ mesa.cantidad_inscriptos }}</span>
                                    </div>
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-4">
                                    <span :class="getEstadoBadgeClass(mesa.estado)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ mesa.estado.charAt(0).toUpperCase() + mesa.estado.slice(1) }}
                                    </span>
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                    <Link
                                        :href="route('admin.mesas.show', mesa.id)"
                                        class="text-blue-600 hover:text-blue-900"
                                        title="Ver detalles"
                                    >
                                        <i class="bx bx-show text-lg"></i>
                                    </Link>
                                    <Link
                                        :href="route('admin.mesas.edit', mesa.id)"
                                        class="text-yellow-600 hover:text-yellow-900"
                                        title="Editar"
                                    >
                                        <i class="bx bx-edit text-lg"></i>
                                    </Link>
                                    <button
                                        @click="eliminarMesa(mesa)"
                                        class="text-red-600 hover:text-red-900"
                                        title="Eliminar"
                                    >
                                        <i class="bx bx-trash text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div v-if="mesas.data.length > 0" class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Mostrando {{ mesas.from }} a {{ mesas.to }} de {{ mesas.total }} mesas
                        </div>
                        <div class="flex gap-2">
                            <template v-for="link in mesas.links" :key="link.label">
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    :class="[
                                        'px-3 py-1 rounded text-sm',
                                        link.active
                                            ? 'bg-blue-600 text-white'
                                            : 'bg-white text-gray-700 hover:bg-gray-50 border'
                                    ]"
                                    v-html="link.label"
                                />
                                <span
                                    v-else
                                    :class="'px-3 py-1 rounded text-sm bg-gray-100 text-gray-400 cursor-not-allowed'"
                                    v-html="link.label"
                                />
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dialog component -->
        <Dialog />
    </SidebarLayout>
</template>
