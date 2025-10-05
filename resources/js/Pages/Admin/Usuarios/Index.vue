<script setup>
import { ref, computed } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    usuarios: Object,
    filtros: Object,
});

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

const toggleActivo = (usuario) => {
    if (confirm(`¿Está seguro de ${usuario.activo ? 'desactivar' : 'activar'} este usuario?`)) {
        router.post(route('admin.usuarios.toggle', usuario.id), {}, {
            preserveScroll: true,
        });
    }
};

const eliminarUsuario = (usuario) => {
    if (confirm(`¿Está seguro de eliminar el usuario de ${usuario.nombre}? Esta acción no se puede deshacer.`)) {
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
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Gestión de Usuarios</h1>
                <p class="text-xs text-gray-400 mt-0.5">Administración de usuarios del sistema</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Acciones y Filtros -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Usuarios del Sistema</h2>
                    <Link
                        :href="route('admin.usuarios.create')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200"
                    >
                        <i class="bx bx-plus text-xl mr-2"></i>
                        Nuevo Usuario
                    </Link>
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
    </SidebarLayout>
</template>
