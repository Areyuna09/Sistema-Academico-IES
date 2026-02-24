<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import axios from 'axios';

const props = defineProps({
    modulos: Object,
    categorias: Object,
    estadisticas: Object,
});

// Estado local de módulos
const modulosLocal = ref({});

// Inicializar estado local
Object.keys(props.modulos).forEach(categoria => {
    modulosLocal.value[categoria] = props.modulos[categoria].map(m => ({
        ...m,
        activo: m.activo
    }));
});

// Controlar acordeones abiertos
const acordeonesAbiertos = ref(Object.keys(props.categorias).reduce((acc, key) => {
    acc[key] = true; // Todos abiertos por defecto
    return acc;
}, {}));

// Verificar si hay cambios pendientes
const hayCambios = computed(() => {
    for (const categoria in modulosLocal.value) {
        for (const modulo of modulosLocal.value[categoria]) {
            const original = props.modulos[categoria].find(m => m.id === modulo.id);
            if (original && original.activo !== modulo.activo) {
                return true;
            }
        }
    }
    return false;
});

const toggleModulo = (categoriaKey, moduloId) => {
    const modulo = modulosLocal.value[categoriaKey].find(m => m.id === moduloId);
    if (modulo) {
        modulo.activo = !modulo.activo;
    }
};

const guardarCambios = () => {
    const cambios = [];

    for (const categoria in modulosLocal.value) {
        for (const modulo of modulosLocal.value[categoria]) {
            const original = props.modulos[categoria].find(m => m.id === modulo.id);
            if (original && original.activo !== modulo.activo) {
                cambios.push({
                    id: modulo.id,
                    activo: modulo.activo
                });
            }
        }
    }

    if (cambios.length === 0) {
        return;
    }

    router.post(route('admin.configuracion-modulos.update-batch'), {
        modulos: cambios
    }, {
        preserveScroll: true,
        onSuccess: () => {
            // Actualizar props
            Object.keys(modulosLocal.value).forEach(categoria => {
                modulosLocal.value[categoria] = props.modulos[categoria].map(m => ({
                    ...m,
                    activo: m.activo
                }));
            });
        }
    });
};

const restablecerDefecto = () => {
    if (confirm('¿Estás seguro de restablecer la configuración a los valores por defecto? Se perderán todos los cambios personalizados.')) {
        router.post(route('admin.configuracion-modulos.reset'), {}, {
            preserveScroll: true,
        });
    }
};

const toggleAcordeon = (categoria) => {
    acordeonesAbiertos.value[categoria] = !acordeonesAbiertos.value[categoria];
};

const contarActivosPorCategoria = (categoria) => {
    return modulosLocal.value[categoria]?.filter(m => m.activo).length || 0;
};

const contarTotalPorCategoria = (categoria) => {
    return modulosLocal.value[categoria]?.length || 0;
};

// ========================================
// PERMISOS POR ROL
// ========================================
const mostrarModalPermisos = ref(false);
const cargandoPermisos = ref(false);
const guardandoPermisos = ref(false);
const permisosMatriz = ref({});
const permisosNombres = ref({});
const tiposUsuario = ref({});
const permisosOriginal = ref({});

// Solo mostrar permisos de acceso a módulos (prefijo acceso:)
const nombresAcceso = computed(() => {
    const result = {};
    for (const [clave, nombre] of Object.entries(permisosNombres.value)) {
        if (clave.startsWith('acceso:')) {
            result[clave] = nombre;
        }
    }
    return result;
});

// Tipos que se muestran en la grilla (excluir Admin=1 y Alumno=4)
const tiposMostrados = computed(() => {
    const excluir = [1, 4]; // Admin siempre tiene todo, Alumno no gestiona
    const resultado = {};
    for (const [tipo, nombre] of Object.entries(tiposUsuario.value)) {
        if (!excluir.includes(Number(tipo))) {
            resultado[tipo] = nombre;
        }
    }
    return resultado;
});

const abrirModalPermisos = async () => {
    cargandoPermisos.value = true;
    mostrarModalPermisos.value = true;
    try {
        const { data } = await axios.get(route('admin.configuracion-modulos.permisos'));
        permisosMatriz.value = JSON.parse(JSON.stringify(data.matriz));
        permisosOriginal.value = JSON.parse(JSON.stringify(data.matriz));
        permisosNombres.value = data.nombres;
        tiposUsuario.value = data.tiposUsuario;
    } catch (e) {
        console.error('Error cargando permisos:', e);
        mostrarModalPermisos.value = false;
    } finally {
        cargandoPermisos.value = false;
    }
};

const togglePermiso = (permiso, tipo) => {
    if (!permisosMatriz.value[permiso]) {
        permisosMatriz.value[permiso] = {};
    }
    permisosMatriz.value[permiso][tipo] = !permisosMatriz.value[permiso][tipo];
};

const hayCambiosPermisos = computed(() => {
    for (const permiso in permisosMatriz.value) {
        for (const tipo in permisosMatriz.value[permiso]) {
            if (Number(tipo) === 1 || Number(tipo) === 4) continue;
            const actual = !!permisosMatriz.value[permiso][tipo];
            const original = !!permisosOriginal.value[permiso]?.[tipo];
            if (actual !== original) return true;
        }
    }
    return false;
});

const guardarPermisos = () => {
    const cambios = [];

    for (const permiso in permisosMatriz.value) {
        for (const tipo in permisosMatriz.value[permiso]) {
            const tipoNum = Number(tipo);
            if (tipoNum === 1) continue; // Admin no se toca
            const actual = !!permisosMatriz.value[permiso][tipo];
            const original = !!permisosOriginal.value[permiso]?.[tipo];
            if (actual !== original) {
                cambios.push({
                    permiso,
                    tipo_usuario: tipoNum,
                    activo: actual,
                });
            }
        }
    }

    if (cambios.length === 0) return;

    guardandoPermisos.value = true;
    router.post(route('admin.configuracion-modulos.update-permisos'), {
        cambios
    }, {
        preserveScroll: true,
        onSuccess: () => {
            permisosOriginal.value = JSON.parse(JSON.stringify(permisosMatriz.value));
            mostrarModalPermisos.value = false;
        },
        onFinish: () => {
            guardandoPermisos.value = false;
        }
    });
};
</script>

<template>
    <Head title="Configuración de Módulos" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Configuración de Módulos</h1>
                <p class="text-xs text-gray-400 mt-0.5">Activar o desactivar funcionalidades del sistema</p>
            </div>
        </template>

        <div class="p-8">
            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total de Módulos</p>
                            <p class="text-2xl font-bold text-gray-900">{{ estadisticas.total }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="bx bx-package text-2xl text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Módulos Activos</p>
                            <p class="text-2xl font-bold text-green-600">{{ estadisticas.activos }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="bx bx-check-circle text-2xl text-green-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Módulos Inactivos</p>
                            <p class="text-2xl font-bold text-gray-600">{{ estadisticas.inactivos }}</p>
                        </div>
                        <div class="bg-gray-100 p-3 rounded-lg">
                            <i class="bx bx-x-circle text-2xl text-gray-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alerta de cambios pendientes -->
            <div v-if="hayCambios" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="bx bx-info-circle text-yellow-400 text-xl mr-2"></i>
                        <p class="text-sm text-yellow-700">
                            Hay cambios pendientes de guardar
                        </p>
                    </div>
                    <button
                        @click="guardarCambios"
                        class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-lg transition-colors"
                    >
                        <i class="bx bx-save mr-1"></i>
                        Guardar Cambios
                    </button>
                </div>
            </div>

            <!-- Panel de configuración -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Módulos del Sistema</h2>
                            <p class="text-sm text-gray-600 mt-1">Gestiona qué funcionalidades están disponibles en la aplicación</p>
                        </div>
                        <button
                            @click="restablecerDefecto"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-lg transition-colors"
                        >
                            <i class="bx bx-reset mr-1"></i>
                            Restablecer por Defecto
                        </button>
                    </div>
                </div>

                <!-- Acordeones por categoría -->
                <div class="divide-y divide-gray-200">
                    <div
                        v-for="(nombreCategoria, categoriaKey) in categorias"
                        :key="categoriaKey"
                        class="border-b border-gray-200 last:border-b-0"
                    >
                        <!-- Header del acordeón -->
                        <button
                            @click="toggleAcordeon(categoriaKey)"
                            class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors"
                        >
                            <div class="flex items-center space-x-3">
                                <i
                                    :class="acordeonesAbiertos[categoriaKey] ? 'bx-chevron-down' : 'bx-chevron-right'"
                                    class="bx text-2xl text-gray-600 transition-transform"
                                ></i>
                                <div class="text-left">
                                    <h3 class="text-base font-semibold text-gray-900">{{ nombreCategoria }}</h3>
                                    <p class="text-xs text-gray-500">
                                        {{ contarActivosPorCategoria(categoriaKey) }} de {{ contarTotalPorCategoria(categoriaKey) }} activos
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span
                                    class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded"
                                >
                                    {{ contarActivosPorCategoria(categoriaKey) }} activos
                                </span>
                                <span
                                    class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded"
                                >
                                    {{ contarTotalPorCategoria(categoriaKey) - contarActivosPorCategoria(categoriaKey) }} inactivos
                                </span>
                            </div>
                        </button>

                        <!-- Contenido del acordeón -->
                        <div
                            v-show="acordeonesAbiertos[categoriaKey]"
                            class="px-6 pb-4"
                        >
                            <div class="space-y-3">
                                <div
                                    v-for="modulo in modulosLocal[categoriaKey]"
                                    :key="modulo.id"
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                                >
                                    <div class="flex items-center space-x-4 flex-1">
                                        <div class="flex-shrink-0">
                                            <div
                                                :class="modulo.activo ? 'bg-green-100' : 'bg-gray-100'"
                                                class="w-12 h-12 rounded-lg flex items-center justify-center"
                                            >
                                                <i
                                                    :class="[modulo.icono || 'bx-package', modulo.activo ? 'text-green-600' : 'text-gray-400']"
                                                    class="bx text-2xl"
                                                ></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ modulo.nombre }}</h4>
                                            <p class="text-xs text-gray-600 mt-1">{{ modulo.descripcion }}</p>
                                        </div>
                                    </div>

                                    <!-- Switch Toggle -->
                                    <label class="relative inline-flex items-center cursor-pointer ml-4">
                                        <input
                                            type="checkbox"
                                            :checked="modulo.activo"
                                            @change="toggleModulo(categoriaKey, modulo.id)"
                                            class="sr-only peer"
                                        />
                                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección Acceso a Módulos por Rol -->
            <div class="bg-white rounded-lg shadow mt-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="bg-indigo-100 p-3 rounded-lg">
                                <i class="bx bx-layout text-2xl text-indigo-600"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Acceso a Módulos por Rol</h2>
                                <p class="text-sm text-gray-600 mt-1">Configura qué secciones del panel de Parámetros puede ver cada rol</p>
                            </div>
                        </div>
                        <button
                            @click="abrirModalPermisos"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg transition-colors"
                        >
                            <i class="bx bx-cog mr-1"></i>
                            Configurar Accesos
                        </button>
                    </div>
                </div>
            </div>

            <!-- Botón fijo para guardar -->
            <div
                v-if="hayCambios"
                class="fixed bottom-8 right-8 z-50"
            >
                <button
                    @click="guardarCambios"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-lg transition-all transform hover:scale-105"
                >
                    <i class="bx bx-save mr-2"></i>
                    Guardar Cambios
                </button>
            </div>
        </div>

        <!-- Modal de Permisos por Rol -->
        <Teleport to="body">
            <div
                v-if="mostrarModalPermisos"
                class="fixed inset-0 z-[60] flex items-center justify-center"
            >
                <!-- Overlay -->
                <div
                    class="absolute inset-0 bg-black bg-opacity-50"
                    @click="mostrarModalPermisos = false"
                ></div>

                <!-- Modal -->
                <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] flex flex-col">
                    <!-- Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Acceso a Módulos por Rol</h3>
                            <p class="text-sm text-gray-500 mt-1">El Administrador siempre tiene acceso a todos los módulos</p>
                        </div>
                        <button
                            @click="mostrarModalPermisos = false"
                            class="text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <i class="bx bx-x text-2xl"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 overflow-auto flex-1">
                        <div v-if="cargandoPermisos" class="flex items-center justify-center py-12">
                            <i class="bx bx-loader-alt bx-spin text-3xl text-indigo-600 mr-3"></i>
                            <span class="text-gray-600">Cargando permisos...</span>
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700 bg-gray-50 rounded-tl-lg">
                                            Módulo
                                        </th>
                                        <th
                                            v-for="(nombre, tipo) in tiposMostrados"
                                            :key="tipo"
                                            class="text-center py-3 px-3 font-semibold text-gray-700 bg-gray-50 whitespace-nowrap"
                                        >
                                            {{ nombre }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(nombreLegible, permiso) in nombresAcceso"
                                        :key="permiso"
                                        class="border-b border-gray-100 hover:bg-gray-50 transition-colors"
                                    >
                                        <td class="py-3 px-4 text-gray-800 font-medium">
                                            {{ nombreLegible }}
                                        </td>
                                        <td
                                            v-for="(nombre, tipo) in tiposMostrados"
                                            :key="tipo"
                                            class="text-center py-3 px-3"
                                        >
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    :checked="!!permisosMatriz[permiso]?.[tipo]"
                                                    @change="togglePermiso(permiso, tipo)"
                                                    class="sr-only peer"
                                                />
                                                <div class="w-9 h-5 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-500"></div>
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200">
                        <button
                            @click="mostrarModalPermisos = false"
                            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="guardarPermisos"
                            :disabled="!hayCambiosPermisos || guardandoPermisos"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-300 disabled:cursor-not-allowed text-white rounded-lg text-sm transition-colors"
                        >
                            <i v-if="guardandoPermisos" class="bx bx-loader-alt bx-spin mr-1"></i>
                            <i v-else class="bx bx-save mr-1"></i>
                            Guardar Permisos
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </SidebarLayout>
</template>
