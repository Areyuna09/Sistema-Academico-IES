<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import Dialog from '@/Components/Dialog.vue';
import { useDialog } from '@/Composables/useDialog';

const props = defineProps({
    solicitudes: Object,
    pendientesCount: Number,
    filtros: Object,
});

const { confirm: showConfirm, prompt: showPrompt } = useDialog();

const filtrosLocales = ref({
    estado: props.filtros.estado || '',
    buscar: props.filtros.buscar || '',
});

const filtrar = () => {
    router.get(route('admin.solicitudes-email.index'), filtrosLocales.value, {
        preserveState: true,
        preserveScroll: true,
    });
};

const limpiarFiltros = () => {
    filtrosLocales.value = {
        estado: '',
        buscar: '',
    };
    filtrar();
};

const aprobarSolicitud = async (solicitud) => {
    const comentario = await showPrompt(
        `¿Deseas aprobar el cambio de email para ${solicitud.usuario?.nombre}?`,
        'Aprobar Solicitud',
        'Comentario opcional (ej: "Verificado documento")',
        false // No es required
    );

    if (comentario !== null) {
        router.post(route('admin.solicitudes-email.aprobar', solicitud.id), {
            comentario: comentario,
        }, {
            preserveScroll: true,
        });
    }
};

const rechazarSolicitud = async (solicitud) => {
    const motivo = await showPrompt(
        `¿Por qué rechazas el cambio de email para ${solicitud.usuario?.nombre}?`,
        'Rechazar Solicitud',
        'Motivo del rechazo (requerido)',
        true // Es required
    );

    if (motivo) {
        router.post(route('admin.solicitudes-email.rechazar', solicitud.id), {
            comentario: motivo,
        }, {
            preserveScroll: true,
        });
    }
};

const eliminarSolicitud = async (solicitud) => {
    const confirmed = await showConfirm(
        `¿Eliminar la solicitud de ${solicitud.usuario?.nombre}?`,
        'Confirmar eliminación'
    );

    if (confirmed) {
        router.delete(route('admin.solicitudes-email.destroy', solicitud.id), {
            preserveScroll: true,
        });
    }
};

const getEstadoBadgeClass = (estado) => {
    const classes = {
        'pendiente': 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'aprobada': 'bg-green-100 text-green-800 border-green-300',
        'rechazada': 'bg-red-100 text-red-800 border-red-300',
    };
    return classes[estado] || 'bg-gray-100 text-gray-800 border-gray-300';
};

const formatDate = (date) => {
    return new Date(date).toLocaleString('es-AR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-white">Solicitudes de Cambio de Email</h1>
                    <p class="text-xs text-gray-400 mt-0.5">Gestión de solicitudes de actualización de correo electrónico</p>
                </div>
                <div v-if="pendientesCount > 0" class="flex items-center gap-2 bg-yellow-500/20 px-4 py-2 rounded-lg border border-yellow-500/30">
                    <i class="bx bx-bell-ring text-yellow-400 text-xl"></i>
                    <span class="text-sm text-yellow-300 font-semibold">{{ pendientesCount }} pendiente{{ pendientesCount !== 1 ? 's' : '' }}</span>
                </div>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Buscar -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="bx bx-search mr-1"></i>
                            Buscar
                        </label>
                        <input
                            v-model="filtrosLocales.buscar"
                            @keyup.enter="filtrar"
                            type="text"
                            placeholder="DNI, email..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        />
                    </div>

                    <!-- Estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="bx bx-filter mr-1"></i>
                            Estado
                        </label>
                        <select
                            v-model="filtrosLocales.estado"
                            @change="filtrar"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        >
                            <option value="">Todos</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="aprobada">Aprobada</option>
                            <option value="rechazada">Rechazada</option>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-end gap-2">
                        <button
                            @click="filtrar"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm flex items-center justify-center gap-2"
                        >
                            <i class="bx bx-search"></i>
                            Buscar
                        </button>
                        <button
                            @click="limpiarFiltros"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium text-sm"
                        >
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla de Solicitudes -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Usuario
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email Actual
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email Solicitado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-if="solicitudes.data.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <i class="bx bx-inbox text-4xl mb-2 block text-gray-400"></i>
                                    No hay solicitudes {{ filtrosLocales.estado ? `en estado "${filtrosLocales.estado}"` : '' }}
                                </td>
                            </tr>
                            <tr v-for="solicitud in solicitudes.data" :key="solicitud.id" class="hover:bg-gray-50">
                                <!-- Usuario -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ solicitud.usuario?.nombre || 'N/A' }}
                                        </span>
                                        <span class="text-xs text-gray-500">DNI: {{ solicitud.dni }}</span>
                                    </div>
                                </td>

                                <!-- Email Actual -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <i class="bx bx-envelope text-gray-400"></i>
                                        <span class="text-sm text-gray-900">{{ solicitud.email_actual }}</span>
                                    </div>
                                </td>

                                <!-- Email Nuevo -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <i class="bx bx-right-arrow-alt text-blue-500"></i>
                                        <span class="text-sm font-medium text-blue-600">{{ solicitud.email_nuevo }}</span>
                                    </div>
                                    <div v-if="solicitud.ip_solicitud" class="text-xs text-gray-400 mt-1">
                                        IP: {{ solicitud.ip_solicitud }}
                                    </div>
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="getEstadoBadgeClass(solicitud.estado)"
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border"
                                    >
                                        {{ solicitud.estado }}
                                    </span>
                                    <div v-if="solicitud.comentario_admin" class="mt-2 text-xs text-gray-600 italic">
                                        "{{ solicitud.comentario_admin }}"
                                    </div>
                                </td>

                                <!-- Fecha -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ formatDate(solicitud.created_at) }}</div>
                                    <div v-if="solicitud.fecha_revision" class="text-xs text-gray-400 mt-1">
                                        Revisado: {{ formatDate(solicitud.fecha_revision) }}
                                    </div>
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <!-- Aprobar -->
                                        <button
                                            v-if="solicitud.estado === 'pendiente'"
                                            @click="aprobarSolicitud(solicitud)"
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors font-medium text-xs flex items-center gap-1"
                                            title="Aprobar"
                                        >
                                            <i class="bx bx-check"></i>
                                            Aprobar
                                        </button>

                                        <!-- Rechazar -->
                                        <button
                                            v-if="solicitud.estado === 'pendiente'"
                                            @click="rechazarSolicitud(solicitud)"
                                            class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors font-medium text-xs flex items-center gap-1"
                                            title="Rechazar"
                                        >
                                            <i class="bx bx-x"></i>
                                            Rechazar
                                        </button>

                                        <!-- Eliminar -->
                                        <button
                                            @click="eliminarSolicitud(solicitud)"
                                            class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors font-medium text-xs"
                                            title="Eliminar"
                                        >
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div v-if="solicitudes.data.length > 0" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Mostrando
                            <span class="font-medium">{{ solicitudes.from }}</span>
                            a
                            <span class="font-medium">{{ solicitudes.to }}</span>
                            de
                            <span class="font-medium">{{ solicitudes.total }}</span>
                            solicitudes
                        </div>
                        <div class="flex gap-2">
                            <component
                                v-for="link in solicitudes.links"
                                :key="link.label"
                                :is="link.url ? Link : 'span'"
                                :href="link.url"
                                v-html="link.label"
                                :class="{
                                    'bg-blue-600 text-white': link.active,
                                    'bg-white text-gray-700 hover:bg-gray-50': !link.active && link.url,
                                    'cursor-not-allowed opacity-50': !link.url,
                                }"
                                class="px-3 py-2 border border-gray-300 rounded text-sm font-medium transition-colors"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Dialog />
    </SidebarLayout>
</template>
