<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import Dialog from '@/Components/Dialog.vue';
import Modal from '@/Components/Modal.vue';
import { useDialog } from '@/Composables/useDialog';
import axios from 'axios';

const props = defineProps({
    usuarios: Object,
    filtros: Object,
});

// Manejo de mensajes flash
const page = usePage();
const flashMessage = ref('');
const flashType = ref('');
const showFlash = ref(false);

// Mostrar mensaje flash si existe
const checkFlashMessages = () => {
    const flash = page.props.flash;
    if (flash?.success) {
        flashMessage.value = flash.success;
        flashType.value = 'success';
        showFlash.value = true;
        setTimeout(() => showFlash.value = false, 5000);
    } else if (flash?.error) {
        flashMessage.value = flash.error;
        flashType.value = 'error';
        showFlash.value = true;
        setTimeout(() => showFlash.value = false, 8000);
    }
};

onMounted(() => {
    checkFlashMessages();
});

// Observar cambios en flash props
watch(
    () => page.props.flash,
    () => {
        checkFlashMessages();
    },
    { deep: true }
);

const form = useForm({
    tipo: props.filtros?.tipo || '',
    activo: props.filtros?.activo || '',
    buscar: props.filtros?.buscar || '',
});

const buscar = () => {
    router.get(route('admin.usuarios.index'), {
        tipo: form.tipo,
        activo: form.activo,
        buscar: form.buscar,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const limpiarFiltros = () => {
    form.tipo = '';
    form.activo = '';
    form.buscar = '';
    router.get(route('admin.usuarios.index'));
};

const { confirm: showConfirm } = useDialog();

const toggleActivo = async (usuario) => {
    const action = usuario.activo ? 'desactivar' : 'activar';
    const confirmed = await showConfirm(
        `¿Está seguro de ${action} el usuario de ${usuario.nombre}?`,
        `Confirmar ${action}`
    );

    if (confirmed) {
        router.post(route('admin.usuarios.toggle', usuario.id), {}, {
            preserveScroll: true,
        });
    }
};

const eliminarUsuario = async (usuario) => {
    const confirmed = await showConfirm(
        `¿Está seguro de eliminar el usuario de ${usuario.nombre}? Esta acción no se puede deshacer y eliminará permanentemente todos los datos asociados.`,
        'Confirmar eliminación'
    );

    if (confirmed) {
        router.delete(route('admin.usuarios.destroy', usuario.id), {
            preserveScroll: true,
        });
    }
};

const getTipoBadge = (tipo) => {
    const badges = {
        1: { text: 'Admin', class: 'bg-purple-100 text-purple-800' },
        3: { text: 'Profesor', class: 'bg-blue-100 text-blue-800' },
        4: { text: 'Alumno', class: 'bg-green-100 text-green-800' },
    };
    return badges[tipo] || { text: 'Usuario', class: 'bg-gray-100 text-gray-800' };
};

const showPreviewModal = ref(false);
const previewData = ref(null);
const cargandoPreview = ref(false);
const generandoUsuarios = ref(false);

const generarUsuariosAutomaticos = async () => {
    try {
        cargandoPreview.value = true;
        const response = await axios.get(route('admin.usuarios.generar-automaticos.preview'));
        previewData.value = response.data;

        if (previewData.value.total === 0) {
            await showConfirm(
                'No hay alumnos ni profesores sin usuario asignado.',
                'Sin usuarios para crear',
                { showCancel: false }
            );
            return;
        }

        showPreviewModal.value = true;
    } catch (error) {
        console.error('Error al obtener vista previa:', error);
        await showConfirm(
            'Error al obtener la lista de usuarios a crear. Por favor, intente nuevamente.',
            'Error',
            { showCancel: false }
        );
    } finally {
        cargandoPreview.value = false;
    }
};

const confirmarCreacion = () => {
    showPreviewModal.value = false;
    generandoUsuarios.value = true;
    router.post(route('admin.usuarios.generar-automaticos'), {}, {
        preserveScroll: true,
        onFinish: () => {
            generandoUsuarios.value = false;
            previewData.value = null;
        }
    });
};

const cerrarPreview = () => {
    showPreviewModal.value = false;
    previewData.value = null;
};
</script>

<template>
    <Head title="Gestión de Usuarios" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Gestión de Usuarios</h1>
                <p class="text-xs text-gray-400 mt-0.5">Administración de usuarios del sistema</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Mensaje Flash -->
            <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="transform opacity-0 -translate-y-2"
                enter-to-class="transform opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="transform opacity-100 translate-y-0"
                leave-to-class="transform opacity-0 -translate-y-2"
            >
                <div
                    v-if="showFlash"
                    :class="[
                        'mb-6 p-4 rounded-lg shadow-md flex items-center justify-between',
                        flashType === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'
                    ]"
                >
                    <div class="flex items-center">
                        <i
                            :class="[
                                'bx text-2xl mr-3',
                                flashType === 'success' ? 'bx-check-circle text-green-600' : 'bx-error-circle text-red-600'
                            ]"
                        ></i>
                        <span :class="flashType === 'success' ? 'text-green-800' : 'text-red-800'">
                            {{ flashMessage }}
                        </span>
                    </div>
                    <button
                        @click="showFlash = false"
                        :class="flashType === 'success' ? 'text-green-600 hover:text-green-800' : 'text-red-600 hover:text-red-800'"
                    >
                        <i class="bx bx-x text-xl"></i>
                    </button>
                </div>
            </transition>

            <!-- Acciones y Filtros -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Usuarios del Sistema</h2>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button
                            @click="generarUsuariosAutomaticos"
                            :disabled="cargandoPreview || generandoUsuarios"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-semibold rounded-lg transition-colors duration-200"
                        >
                            <i :class="cargandoPreview || generandoUsuarios ? 'bx bx-loader-alt bx-spin' : 'bx bx-user-plus'" class="text-xl mr-2"></i>
                            <span v-if="cargandoPreview">Cargando...</span>
                            <span v-else-if="generandoUsuarios">Generando...</span>
                            <span v-else>Generar Usuarios Automáticos</span>
                        </button>
                        <Link
                            :href="route('admin.usuarios.create')"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200"
                        >
                            <i class="bx bx-plus text-xl mr-2"></i>
                            Nuevo Usuario
                        </Link>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                        <select
                            v-model="form.tipo"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                            <option value="">Todos</option>
                            <option value="1">Admin</option>
                            <option value="3">Profesor</option>
                            <option value="4">Alumno</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select
                            v-model="form.activo"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                            <option value="">Todos</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input
                            v-model="form.buscar"
                            type="text"
                            placeholder="DNI, nombre o email..."
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            @keyup.enter="buscar"
                        />
                    </div>
                </div>

                <div class="flex gap-2 mt-4">
                    <button
                        @click="buscar"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200"
                    >
                        <i class="bx bx-search mr-1"></i> Buscar
                    </button>
                    <button
                        @click="limpiarFiltros"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200"
                    >
                        <i class="bx bx-x mr-1"></i> Limpiar
                    </button>
                </div>
            </div>

            <!-- Tabla de Usuarios -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vinculado a</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="usuario in usuarios.data" :key="usuario.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ usuario.dni }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ usuario.nombre }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ usuario.email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="[
                                            'px-2 py-1 text-xs font-semibold rounded-full',
                                            getTipoBadge(usuario.tipo).class
                                        ]"
                                    >
                                        {{ getTipoBadge(usuario.tipo).text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span v-if="usuario.alumno" class="flex items-center">
                                        <i class="bx bx-user text-green-600 mr-1"></i>
                                        {{ usuario.alumno.nombre_completo }}
                                    </span>
                                    <span v-else-if="usuario.profesor" class="flex items-center">
                                        <i class="bx bx-chalkboard text-blue-600 mr-1"></i>
                                        {{ usuario.profesor.nombre_completo }}
                                    </span>
                                    <span v-else class="text-gray-400">-</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="[
                                            'px-2 py-1 text-xs font-semibold rounded-full',
                                            usuario.activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                        ]"
                                    >
                                        {{ usuario.activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="route('admin.usuarios.edit', usuario.id)"
                                            class="text-blue-600 hover:text-blue-900"
                                            title="Editar"
                                        >
                                            <i class="bx bx-edit text-lg"></i>
                                        </Link>
                                        <button
                                            @click="toggleActivo(usuario)"
                                            :class="[
                                                'hover:opacity-75',
                                                usuario.activo ? 'text-yellow-600' : 'text-green-600'
                                            ]"
                                            :title="usuario.activo ? 'Desactivar' : 'Activar'"
                                        >
                                            <i :class="[
                                                'bx text-lg',
                                                usuario.activo ? 'bx-toggle-right' : 'bx-toggle-left'
                                            ]"></i>
                                        </button>
                                        <button
                                            @click="eliminarUsuario(usuario)"
                                            class="text-red-600 hover:text-red-900"
                                            title="Eliminar"
                                        >
                                            <i class="bx bx-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Mensaje si no hay resultados -->
                <div v-if="usuarios.data.length === 0" class="p-8 text-center text-gray-500">
                    <i class="bx bx-user-x text-5xl mb-2"></i>
                    <p>No se encontraron usuarios con los filtros aplicados.</p>
                </div>

                <!-- Paginación -->
                <div v-if="usuarios.data.length > 0" class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="text-sm text-gray-700">
                            Mostrando {{ usuarios.from }} a {{ usuarios.to }} de {{ usuarios.total }} usuarios
                        </div>
                        <div class="flex gap-1">
                            <component
                                v-for="link in usuarios.links"
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

        <!-- Modal de Vista Previa -->
        <Modal :show="showPreviewModal" @close="cerrarPreview" max-width="2xl">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Vista Previa - Usuarios a Crear</h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Revise la información antes de confirmar la creación
                        </p>
                    </div>
                    <button
                        @click="cerrarPreview"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <i class="bx bx-x text-3xl"></i>
                    </button>
                </div>

                <!-- Resumen -->
                <div v-if="previewData" class="mb-6 grid grid-cols-3 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ previewData.total }}</div>
                        <div class="text-sm text-gray-600 mt-1">Total Usuarios</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-green-600">{{ previewData.total_alumnos }}</div>
                        <div class="text-sm text-gray-600 mt-1">Alumnos</div>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ previewData.total_profesores }}</div>
                        <div class="text-sm text-gray-600 mt-1">Profesores</div>
                    </div>
                </div>

                <!-- Información importante -->
                <div class="mb-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <i class="bx bx-info-circle text-yellow-600 text-xl flex-shrink-0 mt-0.5"></i>
                        <div class="text-sm text-yellow-800">
                            <p class="font-semibold mb-1">Información importante:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>El <strong>usuario (DNI)</strong> y la <strong>contraseña</strong> serán el DNI de cada persona</li>
                                <li>Los emails marcados con <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800"><i class="bx bx-mail-send text-xs mr-1"></i>Generado</span> son creados automáticamente</li>
                                <li>Las entradas con <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800"><i class="bx bx-error text-xs mr-1"></i>DNI Duplicado</span> serán omitidas</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tabla de usuarios -->
                <div v-if="previewData" class="max-h-96 overflow-y-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Alumnos -->
                            <template v-if="previewData.alumnos.length > 0">
                                <tr v-for="alumno in previewData.alumnos" :key="'alumno-' + alumno.id" class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ alumno.nombre_completo }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ alumno.dni }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <span>{{ alumno.email }}</span>
                                            <span v-if="alumno.email_generado" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="bx bx-mail-send text-xs mr-1"></i>Generado
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ alumno.tipo_texto }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span v-if="alumno.dni_duplicado" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                            <i class="bx bx-error text-xs mr-1"></i>DNI Duplicado
                                        </span>
                                        <span v-else class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                            <i class="bx bx-check text-xs mr-1"></i>Listo
                                        </span>
                                    </td>
                                </tr>
                            </template>

                            <!-- Profesores -->
                            <template v-if="previewData.profesores.length > 0">
                                <tr v-for="profesor in previewData.profesores" :key="'profesor-' + profesor.id" class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ profesor.nombre_completo }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ profesor.dni }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <span>{{ profesor.email }}</span>
                                            <span v-if="profesor.email_generado" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="bx bx-mail-send text-xs mr-1"></i>Generado
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ profesor.tipo_texto }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span v-if="profesor.dni_duplicado" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                            <i class="bx bx-error text-xs mr-1"></i>DNI Duplicado
                                        </span>
                                        <span v-else class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                            <i class="bx bx-check text-xs mr-1"></i>Listo
                                        </span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Footer con botones -->
                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button
                        @click="cerrarPreview"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-colors duration-200"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="confirmarCreacion"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors duration-200 flex items-center gap-2"
                    >
                        <i class="bx bx-check-circle text-xl"></i>
                        Crear {{ previewData?.total }} Usuario(s)
                    </button>
                </div>
            </div>
        </Modal>

        <!-- Dialog component -->
        <Dialog />
    </SidebarLayout>
</template>
