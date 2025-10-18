<script setup>
import { ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import Dialog from '@/Components/Dialog.vue';
import { useDialog } from '@/Composables/useDialog';

const props = defineProps({
    excepciones: Object,
    alumnos: Array,
    materias: Array,
    filtros: Object,
});

const { confirm: showConfirm, alert: showAlert } = useDialog();

// Estados del modal
const modalNuevaExcepcion = ref(false);
const modalResolverExcepcion = ref(false);
const excepcionSeleccionada = ref(null);

// Filtros
const filtrosForm = ref({
    search: props.filtros.search || '',
    estado: props.filtros.estado || '',
    tipo: props.filtros.tipo || '',
});

// Formulario de nueva excepción
const formNueva = useForm({
    alumno_id: '',
    tipo: 'correlativa',
    descripcion: '',
    materia_id: '',
});

// Formulario de resolución
const formResolucion = useForm({
    estado: 'aprobada',
    justificacion_administrativa: '',
});

const aplicarFiltros = () => {
    router.get(route('admin.excepciones.index'), filtrosForm.value, {
        preserveState: true,
        preserveScroll: true,
    });
};

const limpiarFiltros = () => {
    filtrosForm.value = { search: '', estado: '', tipo: '' };
    aplicarFiltros();
};

const abrirModalNueva = () => {
    formNueva.reset();
    modalNuevaExcepcion.value = true;
};

const guardarExcepcion = () => {
    formNueva.post(route('admin.excepciones.store'), {
        preserveScroll: true,
        onSuccess: () => {
            modalNuevaExcepcion.value = false;
            formNueva.reset();
        },
    });
};

const abrirModalResolver = (excepcion) => {
    excepcionSeleccionada.value = excepcion;
    formResolucion.reset();
    formResolucion.estado = 'aprobada';
    modalResolverExcepcion.value = true;
};

const resolverExcepcion = () => {
    formResolucion.put(route('admin.excepciones.update', excepcionSeleccionada.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            modalResolverExcepcion.value = false;
            excepcionSeleccionada.value = null;
            formResolucion.reset();
        },
    });
};

const eliminarExcepcion = async (excepcion) => {
    const confirmed = await showConfirm(
        `¿Está seguro de eliminar esta excepción? Esta acción no se puede deshacer.`,
        'Confirmar eliminación'
    );

    if (confirmed) {
        router.delete(route('admin.excepciones.destroy', excepcion.id), {
            preserveScroll: true,
        });
    }
};

const getEstadoBadgeClass = (estado) => {
    const clases = {
        'pendiente': 'bg-yellow-100 text-yellow-700 border-yellow-300',
        'aprobada': 'bg-green-100 text-green-700 border-green-300',
        'rechazada': 'bg-red-100 text-red-700 border-red-300',
    };
    return clases[estado] || 'bg-gray-100 text-gray-700';
};

const alumnoNombre = computed(() => (alumnoId) => {
    const alumno = props.alumnos.find(a => a.id === alumnoId);
    return alumno ? `${alumno.apellido}, ${alumno.nombre}` : '';
});
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Gestión de Excepciones</h1>
                <p class="text-xs text-gray-400 mt-0.5">Gestionar excepciones administrativas</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Botón Nueva Excepción -->
            <div class="flex justify-end mb-6">
                <button
                    @click="abrirModalNueva"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200"
                >
                    <i class="bx bx-plus text-xl mr-2"></i>
                    Nueva Excepción
                </button>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Búsqueda -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input
                            v-model="filtrosForm.search"
                            type="text"
                            placeholder="Nombre, apellido o DNI del alumno..."
                            @input="aplicarFiltros"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        />
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
                            <option value="pendiente">Pendiente</option>
                            <option value="aprobada">Aprobada</option>
                            <option value="rechazada">Rechazada</option>
                        </select>
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                        <select
                            v-model="filtrosForm.tipo"
                            @change="aplicarFiltros"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                            <option value="">Todos los tipos</option>
                            <option value="correlativa">Excepción de Correlativa</option>
                            <option value="reingreso">Reingreso</option>
                            <option value="cambio_carrera">Cambio de Carrera</option>
                            <option value="cambio_plan">Cambio de Plan</option>
                            <option value="justificacion_inasistencia">Justificación de Inasistencia</option>
                            <option value="otra">Otra</option>
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

            <!-- Tabla de Excepciones -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Alumno
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Descripción
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha
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
                            <tr v-if="excepciones.data.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="bx bx-folder-open text-4xl mb-2"></i>
                                    <p>No hay excepciones registradas</p>
                                </td>
                            </tr>
                            <tr v-for="excepcion in excepciones.data" :key="excepcion.id" class="hover:bg-gray-50">
                                <!-- Alumno -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ excepcion.alumno ? `${excepcion.alumno.apellido}, ${excepcion.alumno.nombre}` : '-' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        DNI: {{ excepcion.alumno?.dni || '-' }}
                                    </div>
                                </td>

                                <!-- Tipo -->
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">{{ excepcion.tipo_texto }}</span>
                                    <div v-if="excepcion.materia" class="text-xs text-gray-500 mt-1">
                                        {{ excepcion.materia.nombre }}
                                    </div>
                                </td>

                                <!-- Descripción -->
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900 max-w-xs truncate" :title="excepcion.descripcion">
                                        {{ excepcion.descripcion }}
                                    </p>
                                </td>

                                <!-- Fecha -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ new Date(excepcion.fecha_solicitud).toLocaleDateString('es-AR') }}
                                    </div>
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-4">
                                    <span :class="getEstadoBadgeClass(excepcion.estado)" class="px-2 py-1 text-xs font-semibold rounded-full border">
                                        {{ excepcion.estado_texto }}
                                    </span>
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                    <button
                                        v-if="excepcion.estado === 'pendiente'"
                                        @click="abrirModalResolver(excepcion)"
                                        class="text-blue-600 hover:text-blue-900"
                                        title="Resolver excepción"
                                    >
                                        <i class="bx bx-check-circle text-lg"></i>
                                    </button>
                                    <button
                                        @click="eliminarExcepcion(excepcion)"
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
                <div v-if="excepciones.data.length > 0" class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Mostrando {{ excepciones.from }} a {{ excepciones.to }} de {{ excepciones.total }} excepciones
                        </div>
                        <div class="flex gap-2">
                            <template v-for="link in excepciones.links" :key="link.label">
                                <component
                                    :is="link.url ? 'a' : 'span'"
                                    :href="link.url"
                                    @click.prevent="link.url && router.visit(link.url)"
                                    :class="[
                                        'px-3 py-1 rounded text-sm',
                                        link.active
                                            ? 'bg-blue-600 text-white'
                                            : link.url
                                            ? 'bg-white text-gray-700 hover:bg-gray-50 border cursor-pointer'
                                            : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                    ]"
                                    v-html="link.label"
                                />
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Nueva Excepción -->
        <div v-if="modalNuevaExcepcion" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="modalNuevaExcepcion = false"></div>

                <div class="relative bg-white rounded-lg max-w-2xl w-full p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Nueva Excepción</h3>

                    <form @submit.prevent="guardarExcepcion" class="space-y-4">
                        <!-- Alumno -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Alumno <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="formNueva.alumno_id"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            >
                                <option value="">Seleccionar alumno</option>
                                <option v-for="alumno in alumnos" :key="alumno.id" :value="alumno.id">
                                    {{ alumno.apellido }}, {{ alumno.nombre }} - DNI: {{ alumno.dni }}
                                </option>
                            </select>
                            <p v-if="formNueva.errors.alumno_id" class="text-red-600 text-sm mt-1">{{ formNueva.errors.alumno_id }}</p>
                        </div>

                        <!-- Tipo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tipo de Excepción <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="formNueva.tipo"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            >
                                <option value="correlativa">Excepción de Correlativa</option>
                                <option value="reingreso">Reingreso</option>
                                <option value="cambio_carrera">Cambio de Carrera</option>
                                <option value="cambio_plan">Cambio de Plan de Estudios</option>
                                <option value="justificacion_inasistencia">Justificación de Inasistencia</option>
                                <option value="otra">Otra</option>
                            </select>
                            <p v-if="formNueva.errors.tipo" class="text-red-600 text-sm mt-1">{{ formNueva.errors.tipo }}</p>
                        </div>

                        <!-- Materia (opcional) -->
                        <div v-if="formNueva.tipo === 'correlativa'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Materia (opcional)
                            </label>
                            <select
                                v-model="formNueva.materia_id"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            >
                                <option value="">Sin materia específica</option>
                                <option v-for="materia in materias" :key="materia.id" :value="materia.id">
                                    {{ materia.nombre }} - {{ materia.carrera_relacion?.nombre }}
                                </option>
                            </select>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Descripción <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                v-model="formNueva.descripcion"
                                required
                                rows="4"
                                maxlength="500"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                placeholder="Describa el motivo de la excepción..."
                            ></textarea>
                            <p class="text-xs text-gray-500 mt-1">{{ formNueva.descripcion.length }}/500 caracteres</p>
                            <p v-if="formNueva.errors.descripcion" class="text-red-600 text-sm mt-1">{{ formNueva.errors.descripcion }}</p>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button
                                type="button"
                                @click="modalNuevaExcepcion = false"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="formNueva.processing"
                                class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors disabled:opacity-50"
                            >
                                {{ formNueva.processing ? 'Guardando...' : 'Guardar Excepción' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Resolver Excepción -->
        <div v-if="modalResolverExcepcion && excepcionSeleccionada" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="modalResolverExcepcion = false"></div>

                <div class="relative bg-white rounded-lg max-w-2xl w-full p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Resolver Excepción</h3>

                    <!-- Info de la excepción -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-4 border border-blue-200">
                        <p class="text-sm text-gray-700"><strong>Alumno:</strong> {{ excepcionSeleccionada.alumno ? `${excepcionSeleccionada.alumno.apellido}, ${excepcionSeleccionada.alumno.nombre}` : '-' }}</p>
                        <p class="text-sm text-gray-700 mt-1"><strong>Tipo:</strong> {{ excepcionSeleccionada.tipo_texto }}</p>
                        <p class="text-sm text-gray-700 mt-1"><strong>Descripción:</strong> {{ excepcionSeleccionada.descripcion }}</p>
                    </div>

                    <form @submit.prevent="resolverExcepcion" class="space-y-4">
                        <!-- Decisión -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Decisión <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input
                                        type="radio"
                                        v-model="formResolucion.estado"
                                        value="aprobada"
                                        class="mr-2"
                                    />
                                    <span class="text-sm">Aprobar</span>
                                </label>
                                <label class="flex items-center">
                                    <input
                                        type="radio"
                                        v-model="formResolucion.estado"
                                        value="rechazada"
                                        class="mr-2"
                                    />
                                    <span class="text-sm">Rechazar</span>
                                </label>
                            </div>
                        </div>

                        <!-- Justificación -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Justificación Administrativa <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                v-model="formResolucion.justificacion_administrativa"
                                required
                                rows="4"
                                maxlength="1000"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                placeholder="Justifique la decisión tomada..."
                            ></textarea>
                            <p class="text-xs text-gray-500 mt-1">{{ formResolucion.justificacion_administrativa.length }}/1000 caracteres</p>
                            <p v-if="formResolucion.errors.justificacion_administrativa" class="text-red-600 text-sm mt-1">{{ formResolucion.errors.justificacion_administrativa }}</p>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button
                                type="button"
                                @click="modalResolverExcepcion = false"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="formResolucion.processing"
                                :class="[
                                    'flex-1 px-4 py-2 text-white rounded-lg transition-colors disabled:opacity-50',
                                    formResolucion.estado === 'aprobada' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'
                                ]"
                            >
                                {{ formResolucion.processing ? 'Guardando...' : (formResolucion.estado === 'aprobada' ? 'Aprobar Excepción' : 'Rechazar Excepción') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Dialog component -->
        <Dialog />
    </SidebarLayout>
</template>
