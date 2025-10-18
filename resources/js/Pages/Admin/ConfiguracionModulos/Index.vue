<script setup>
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

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
</script>

<template>
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
    </SidebarLayout>
</template>
