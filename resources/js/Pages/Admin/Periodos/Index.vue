<script setup>
import { ref, computed, watch } from 'vue';
import { Head, useForm, Link, router, usePage } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import Dialog from '@/Components/Dialog.vue';
import { useDialog } from '@/Composables/useDialog';

const props = defineProps({
    periodos: Object,
    anios: Array,
    filtros: Object,
    periodosConAsignaciones: Array,
});

const form = useForm({
    anio: props.filtros?.anio || '',
    cuatrimestre: props.filtros?.cuatrimestre || '',
    activo: props.filtros?.activo || '',
});

const buscar = () => {
    form.get(route('admin.periodos.index'), {
        preserveState: true,
        preserveScroll: true,
    });
};

const limpiarFiltros = () => {
    form.anio = '';
    form.cuatrimestre = '';
    form.activo = '';
    form.get(route('admin.periodos.index'));
};

const { confirm: showConfirm, alert: showAlert } = useDialog();

// Variables para clonar asignaciones
const mostrarDialogoClonar = ref(false);
const periodoParaClonar = ref(null);
const periodoOrigenId = ref('');
const clonando = ref(false);

// Períodos disponibles para clonar (excluir el destino)
const periodosDisponibles = computed(() => {
    if (!periodoParaClonar.value) return [];
    return (props.periodosConAsignaciones || []).filter(p => p.id !== periodoParaClonar.value);
});

const toggleActivo = async (periodo) => {
    const action = periodo.activo ? 'desactivar' : 'activar';
    const confirmed = await showConfirm(
        `¿Está seguro de ${action} el período "${periodo.nombre}"?`,
        `Confirmar ${action}`
    );

    if (confirmed) {
        router.post(route('admin.periodos.toggle', periodo.id), {}, {
            preserveScroll: true,
            onSuccess: () => {
                // Verificar si el período activado no tiene asignaciones
                const page = usePage();
                const flash = page.props.flash || {};
                if (flash.sin_asignaciones && flash.periodo_id) {
                    periodoParaClonar.value = flash.periodo_id;
                    mostrarDialogoClonar.value = true;
                }
            }
        });
    }
};

const clonarAsignaciones = () => {
    if (!periodoParaClonar.value || !periodoOrigenId.value) return;
    clonando.value = true;
    router.post(route('admin.periodos.clonar-asignaciones', periodoParaClonar.value), {
        periodo_origen_id: periodoOrigenId.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            mostrarDialogoClonar.value = false;
            periodoParaClonar.value = null;
            periodoOrigenId.value = '';
        },
        onFinish: () => {
            clonando.value = false;
        }
    });
};

const cerrarDialogoClonar = () => {
    mostrarDialogoClonar.value = false;
    periodoParaClonar.value = null;
    periodoOrigenId.value = '';
};

const eliminarPeriodo = async (periodo) => {
    const confirmed = await showConfirm(
        `¿Está seguro de eliminar el período "${periodo.nombre}"? Esta acción no se puede deshacer y puede afectar a inscripciones y mesas de examen asociadas.`,
        'Confirmar eliminación'
    );

    if (confirmed) {
        router.delete(route('admin.periodos.destroy', periodo.id));
    }
};

const getCuatrimestreBadge = (cuatrimestre) => {
    return cuatrimestre === 1
        ? { text: '1° Cuatrimestre', class: 'bg-blue-100 text-blue-800' }
        : { text: '2° Cuatrimestre', class: 'bg-purple-100 text-purple-800' };
};

const formatearFecha = (fecha) => {
    return new Date(fecha).toLocaleDateString('es-AR');
};

const estaEnInscripcion = (periodo) => {
    const hoy = new Date();
    const inicio = new Date(periodo.fecha_inicio_inscripcion);
    const fin = new Date(periodo.fecha_fin_inscripcion);
    return periodo.activo && hoy >= inicio && hoy <= fin;
};
</script>

<template>
    <Head title="Períodos Lectivos" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Gestión de Períodos Lectivos</h1>
                <p class="text-xs text-gray-400 mt-0.5">Administrar períodos de inscripción y cursada</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Botón Nuevo Período -->
            <div class="flex justify-end mb-6">
                <Link
                    v-if="$page.props.permisos?.puedeCrear"
                    :href="route('admin.periodos.create')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200"
                >
                    <i class="bx bx-plus text-xl mr-2"></i>
                    Nuevo Período
                </Link>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form @submit.prevent="buscar" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                        <select
                            v-model="form.anio"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                            <option value="">Todos los años</option>
                            <option v-for="anio in anios" :key="anio" :value="anio">
                                {{ anio }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cuatrimestre</label>
                        <select
                            v-model.number="form.cuatrimestre"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                            <option value="">Todos</option>
                            <option :value="1">1° Cuatrimestre</option>
                            <option :value="2">2° Cuatrimestre</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select
                            v-model="form.activo"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                            <option value="">Todos</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
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

            <!-- Tabla de períodos -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Período
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cuatrimestre
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Inscripción
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cursada
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="periodo in periodos.data" :key="periodo.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ periodo.nombre }}</div>
                                    <div class="text-xs text-gray-500">{{ periodo.anio }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', getCuatrimestreBadge(periodo.cuatrimestre).class]">
                                        {{ getCuatrimestreBadge(periodo.cuatrimestre).text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ formatearFecha(periodo.fecha_inicio_inscripcion) }}</div>
                                    <div class="text-xs text-gray-500">al {{ formatearFecha(periodo.fecha_fin_inscripcion) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ formatearFecha(periodo.fecha_inicio_cursada) }}</div>
                                    <div class="text-xs text-gray-500">al {{ formatearFecha(periodo.fecha_fin_cursada) }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span v-if="periodo.activo" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="bx bx-check-circle mr-1"></i> Activo
                                    </span>
                                    <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Inactivo
                                    </span>
                                    <div v-if="estaEnInscripcion(periodo)" class="mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="bx bx-time-five mr-1"></i> Inscripción abierta
                                        </span>
                                    </div>
                                    <div v-if="periodo.asignaciones_count !== undefined" class="mt-1">
                                        <span :class="[
                                            'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium',
                                            periodo.asignaciones_count > 0 ? 'bg-indigo-100 text-indigo-800' : 'bg-orange-100 text-orange-800'
                                        ]">
                                            <i class="bx bx-user mr-1"></i> {{ periodo.asignaciones_count }} asignaciones
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            v-if="$page.props.permisos?.puedeModificar"
                                            @click="toggleActivo(periodo)"
                                            :class="[
                                                'transition-colors',
                                                periodo.activo ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900'
                                            ]"
                                            :title="periodo.activo ? 'Desactivar' : 'Activar'"
                                        >
                                            <i :class="['bx text-lg', periodo.activo ? 'bx-toggle-right' : 'bx-toggle-left']"></i>
                                        </button>
                                        <Link
                                            v-if="$page.props.permisos?.puedeModificar"
                                            :href="route('admin.periodos.edit', periodo.id)"
                                            class="text-blue-600 hover:text-blue-900"
                                            title="Editar"
                                        >
                                            <i class="bx bx-edit text-lg"></i>
                                        </Link>
                                        <button
                                            v-if="$page.props.permisos?.puedeEliminar"
                                            @click="eliminarPeriodo(periodo)"
                                            class="text-red-600 hover:text-red-900"
                                            title="Eliminar"
                                            :disabled="periodo.activo"
                                            :class="{ 'opacity-50 cursor-not-allowed': periodo.activo }"
                                        >
                                            <i class="bx bx-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="periodos.data.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="bx bx-search-alt text-4xl mb-2"></i>
                                    <p>No se encontraron períodos</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div v-if="periodos.links.length > 3" class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-wrap gap-1">
                        <component
                            :is="link.url ? Link : 'span'"
                            v-for="(link, index) in periodos.links"
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

        <!-- Modal de clonar asignaciones -->
        <div v-if="mostrarDialogoClonar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="bg-indigo-600 text-white px-6 py-4 rounded-t-lg">
                    <h3 class="text-lg font-semibold">Asignaciones de profesores</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start mb-4">
                        <i class="bx bx-info-circle text-indigo-600 text-2xl mr-3 flex-shrink-0"></i>
                        <p class="text-sm text-gray-700">
                            Este período no tiene asignaciones de profesores a materias. Seleccioná de qué período querés copiar las asignaciones.
                        </p>
                    </div>

                    <div v-if="periodosDisponibles.length > 0" class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Copiar asignaciones de:</label>
                        <select
                            v-model="periodoOrigenId"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-sm"
                        >
                            <option value="">-- Seleccionar período --</option>
                            <option v-for="p in periodosDisponibles" :key="p.id" :value="p.id">
                                {{ p.nombre }} ({{ p.asignaciones_count }} asignaciones)
                            </option>
                        </select>
                    </div>
                    <div v-else class="mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                        <p class="text-sm text-orange-700">No hay períodos con asignaciones disponibles para clonar.</p>
                    </div>

                    <p class="text-xs text-gray-500 mb-6">
                        Se copiarán todas las asignaciones profesor-materia del período seleccionado. Luego podrá editar individualmente si hay cambios.
                    </p>
                    <div class="flex justify-end gap-3">
                        <button
                            @click="cerrarDialogoClonar"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm"
                        >
                            No, asignar manualmente
                        </button>
                        <button
                            @click="clonarAsignaciones"
                            :disabled="clonando || !periodoOrigenId"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm disabled:opacity-50 flex items-center"
                        >
                            <i :class="['bx mr-1', clonando ? 'bx-loader-alt animate-spin' : 'bx-copy']"></i>
                            {{ clonando ? 'Clonando...' : 'Clonar asignaciones' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dialog component -->
        <Dialog />
    </SidebarLayout>
</template>
