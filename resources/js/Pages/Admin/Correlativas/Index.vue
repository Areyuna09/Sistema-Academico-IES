<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import Dialog from '@/Components/Dialog.vue';
import { useDialog } from '@/Composables/useDialog';

const props = defineProps({
    reglas: Object,
    materias: Array,
    carreras: Array,
    filtros: Object,
});

// Filtros locales
const filtroCarrera = ref(props.filtros.carrera_id || '');
const filtroMateria = ref(props.filtros.materia_id || '');
const filtroTipo = ref(props.filtros.tipo || '');
const filtroActiva = ref(props.filtros.activa !== undefined ? props.filtros.activa : '');

// Aplicar filtros
const aplicarFiltros = () => {
    router.get('/admin/correlativas', {
        carrera_id: filtroCarrera.value || undefined,
        materia_id: filtroMateria.value || undefined,
        tipo: filtroTipo.value || undefined,
        activa: filtroActiva.value !== '' ? filtroActiva.value : undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Limpiar filtros
const limpiarFiltros = () => {
    filtroCarrera.value = '';
    filtroMateria.value = '';
    filtroTipo.value = '';
    filtroActiva.value = '';
    router.get('/admin/correlativas');
};

const { confirm: showConfirm } = useDialog();

// Toggle activación de regla
const toggleRegla = async (reglaId) => {
    const confirmed = await showConfirm(
        '¿Está seguro de cambiar el estado de esta regla de correlativa?',
        'Confirmar cambio'
    );

    if (confirmed) {
        router.post(`/admin/correlativas/${reglaId}/toggle`, {}, {
            preserveState: true,
            preserveScroll: true,
        });
    }
};

// Eliminar regla
const eliminarRegla = async (reglaId) => {
    const confirmed = await showConfirm(
        '¿Está seguro de eliminar esta regla de correlativa? Esta acción no se puede deshacer y puede afectar la inscripción de alumnos a materias.',
        'Confirmar eliminación'
    );

    if (confirmed) {
        router.delete(route('admin.correlativas.destroy', reglaId), {
            preserveScroll: true,
            onSuccess: () => {
                // La página se recargará automáticamente con los datos actualizados
            },
            onError: (errors) => {
                console.error('Error al eliminar correlativa:', errors);
            }
        });
    }
};

// Badge color según tipo
const getTipoBadgeClass = (tipo) => {
    return tipo === 'cursar'
        ? 'bg-blue-100 text-blue-800'
        : 'bg-purple-100 text-purple-800';
};

// Badge color según estado
const getEstadoBadgeClass = (estado) => {
    return estado === 'regular'
        ? 'bg-yellow-100 text-yellow-800'
        : 'bg-green-100 text-green-800';
};
</script>

<template>
    <Head title="Gestión de Correlativas" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Gestión de Correlativas</h1>
                <p class="text-xs text-gray-400 mt-0.5">Configura las reglas de correlativas para cursado y examen</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Botón Nueva Regla -->
            <div class="flex justify-end mb-6">
                <Link :href="route('admin.correlativas.create')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200">
                    <i class="bx bx-plus text-xl mr-2"></i>
                    Nueva Regla
                </Link>
            </div>

                <!-- Filtros -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="bx bx-filter text-blue-600 mr-2"></i>
                        Filtros
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Filtro Carrera -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Carrera</label>
                            <select v-model="filtroCarrera" @change="aplicarFiltros"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todas las carreras</option>
                                <option v-for="carrera in carreras" :key="carrera.id" :value="carrera.id">
                                    {{ carrera.nombre }}
                                </option>
                            </select>
                        </div>

                        <!-- Filtro Materia -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Materia</label>
                            <select v-model="filtroMateria" @change="aplicarFiltros"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todas las materias</option>
                                <option v-for="materia in materias" :key="materia.id" :value="materia.id">
                                    {{ materia.nombre }} ({{ materia.anno }}° año)
                                </option>
                            </select>
                        </div>

                        <!-- Filtro Tipo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                            <select v-model="filtroTipo" @change="aplicarFiltros"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos los tipos</option>
                                <option value="cursar">Para Cursar</option>
                                <option value="rendir">Para Rendir</option>
                            </select>
                        </div>

                        <!-- Filtro Estado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <select v-model="filtroActiva" @change="aplicarFiltros"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todas</option>
                                <option :value="1">Activas</option>
                                <option :value="0">Inactivas</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button @click="limpiarFiltros"
                            class="text-sm text-gray-600 hover:text-gray-900 font-medium flex items-center">
                            <i class="bx bx-x-circle mr-1"></i>
                            Limpiar filtros
                        </button>
                    </div>
                </div>

                <!-- Tabla de Reglas -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div v-if="reglas.data.length === 0" class="text-center py-12">
                        <i class="bx bx-data text-6xl text-gray-300"></i>
                        <p class="mt-4 text-gray-500">No hay reglas de correlativas registradas</p>
                        <Link :href="route('admin.correlativas.create')"
                            class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                            <i class="bx bx-plus-circle mr-1"></i>
                            Crear primera regla
                        </Link>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Materia
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Correlativa
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado Requerido
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Activa
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="regla in reglas.data" :key="regla.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ regla.materia?.nombre || 'Materia eliminada' }}</div>
                                        <div v-if="regla.materia" class="text-xs text-gray-500">{{ regla.materia.anno }}° año - {{ regla.materia.semestre === 1 ? '1er' : '2do' }} Cuatr.</div>
                                        <div v-else class="text-xs text-red-500">ID: {{ regla.materia_id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ regla.correlativa?.nombre || 'Materia eliminada' }}</div>
                                        <div v-if="regla.correlativa" class="text-xs text-gray-500">{{ regla.correlativa.anno }}° año</div>
                                        <div v-else class="text-xs text-red-500">ID: {{ regla.correlativa_id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="['px-2 py-1 text-xs font-semibold rounded-full', getTipoBadgeClass(regla.tipo)]">
                                            {{ regla.tipo === 'cursar' ? 'Para Cursar' : 'Para Rendir' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="['px-2 py-1 text-xs font-semibold rounded-full', getEstadoBadgeClass(regla.estado_requerido)]">
                                            {{ regla.estado_requerido === 'regular' ? 'Regular' : 'Aprobada' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button @click="toggleRegla(regla.id)"
                                            :class="[
                                                'relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
                                                regla.es_activa ? 'bg-blue-600' : 'bg-gray-200'
                                            ]">
                                            <span :class="[
                                                'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                                                regla.es_activa ? 'translate-x-6' : 'translate-x-1'
                                            ]" />
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="route('admin.correlativas.edit', regla.id)"
                                            class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="bx bx-edit text-lg"></i>
                                        </Link>
                                        <button @click="eliminarRegla(regla.id)"
                                            class="text-red-600 hover:text-red-900">
                                            <i class="bx bx-trash text-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div v-if="reglas.data.length > 0" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Mostrando <span class="font-medium">{{ reglas.from }}</span> a
                                <span class="font-medium">{{ reglas.to }}</span> de
                                <span class="font-medium">{{ reglas.total }}</span> reglas
                            </div>
                            <div class="flex gap-2">
                                <component
                                    v-for="link in reglas.links"
                                    :key="link.label"
                                    :is="link.url ? Link : 'span'"
                                    :href="link.url || undefined"
                                    :class="[
                                        'px-3 py-1 rounded border text-sm',
                                        link.active ? 'bg-blue-600 text-white border-blue-600' : '',
                                        !link.url ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 cursor-pointer'
                                    ]"
                                    v-html="link.label"
                                />
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <!-- Dialog component -->
        <Dialog />
    </SidebarLayout>
</template>
