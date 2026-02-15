<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    materias: {
        type: Array,
        default: () => []
    },
    mensaje: {
        type: String,
        default: null
    },
    periodosDisponibles: {
        type: Array,
        default: () => []
    },
    periodoActivo: {
        type: Object,
        default: null
    },
    periodoSeleccionado: {
        type: Number,
        default: null
    },
    esArchivo: {
        type: Boolean,
        default: false
    }
});

const periodoLabel = computed(() => {
    if (!props.periodoSeleccionado || !props.periodosDisponibles.length) return '';
    const p = props.periodosDisponibles.find(p => p.id === props.periodoSeleccionado);
    return p ? p.label : '';
});

const cambiarPeriodo = (periodoId) => {
    router.get(route('profesor.mis-materias.index'), { periodo_id: periodoId }, {
        preserveState: true,
        preserveScroll: true
    });
};
</script>

<template>
    <Head title="Mis Materias" />
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Mis Materias</h1>
                    <p class="mt-1 text-sm text-gray-600">Materias asignadas para el ciclo lectivo</p>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Selector de período -->
                    <div v-if="periodosDisponibles.length > 0" class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 gap-3">
                        <div class="flex items-center gap-2 text-gray-500">
                            <i class="bx bx-calendar text-lg"></i>
                            <span class="text-xs font-medium hidden sm:inline">Período</span>
                        </div>
                        <select
                            :value="periodoSeleccionado"
                            @change="cambiarPeriodo(Number($event.target.value))"
                            class="bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-sm font-medium text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer min-w-[220px] appearance-none"
                            :style="{ backgroundImage: 'url(\'data:image/svg+xml;charset=UTF-8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%236b7280%22 stroke-width=%222%22%3E%3Cpolyline points=%226 9 12 15 18 9%22/%3E%3C/svg%3E\')', backgroundRepeat: 'no-repeat', backgroundPosition: 'right 8px center', backgroundSize: '16px', paddingRight: '32px' }"
                        >
                            <option v-for="p in periodosDisponibles" :key="p.id" :value="p.id">
                                {{ p.label }}
                            </option>
                        </select>
                        <span v-if="!esArchivo" class="flex items-center gap-1 px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full whitespace-nowrap">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                            Activo
                        </span>
                        <span v-else class="flex items-center gap-1 px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full whitespace-nowrap">
                            <i class="bx bx-archive-in text-xs"></i>
                            Archivo
                        </span>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 px-4 py-2 rounded-lg">
                        <p class="text-sm font-medium text-blue-700">
                            Total: <span class="font-bold">{{ materias.length }}</span> materia{{ materias.length !== 1 ? 's' : '' }}
                        </p>
                    </div>
                </div>
            </div>
        </template>

        <div class="p-6">
            <!-- Banner de archivo -->
            <div v-if="esArchivo" class="mb-6 p-4 bg-amber-50 border border-amber-300 rounded-lg flex items-center">
                <i class="bx bx-archive text-amber-600 text-2xl mr-3"></i>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-amber-800">Estás viendo materias archivadas del período: {{ periodoLabel }}</p>
                    <p class="text-xs text-amber-600 mt-0.5">Los datos son de solo lectura.</p>
                </div>
                <span class="px-3 py-1 bg-amber-200 text-amber-800 text-xs font-semibold rounded-full">Archivo</span>
            </div>

            <!-- Mensaje de error o advertencia -->
            <div v-if="mensaje" class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <i class="bx bx-info-circle text-3xl text-yellow-600 mb-2"></i>
                <p class="text-gray-700 font-medium">{{ mensaje }}</p>
            </div>

            <!-- Sin materias asignadas -->
            <div v-else-if="materias.length === 0" class="bg-gray-50 border border-gray-200 rounded-lg p-12 text-center">
                <i class="bx bx-book-open text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No tiene materias asignadas</h3>
                <p class="text-sm text-gray-500">Contacte al administrador para que le asignen materias.</p>
            </div>

            <!-- Grid de materias -->
            <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div 
                    v-for="materia in materias" 
                    :key="materia.id"
                    class="bg-white shadow rounded-lg border border-gray-200 hover:shadow-lg transition-shadow duration-200 overflow-hidden"
                >
                    <!-- Header de la card -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
                        <h3 class="text-lg font-semibold text-white truncate" :title="materia.materia_nombre">
                            {{ materia.materia_nombre }}
                        </h3>
                    </div>
                    
                    <!-- Contenido -->
                    <div class="px-4 py-4 space-y-3">
                        <div class="flex items-start">
                            <i class="bx bx-graduation text-gray-400 text-lg mr-2 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <span class="text-xs font-medium text-gray-500 block">Carrera</span>
                                <span class="text-sm text-gray-900">{{ materia.carrera_nombre }}</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex items-start">
                                <i class="bx bx-calendar text-gray-400 text-lg mr-2 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <span class="text-xs font-medium text-gray-500 block">Cursado</span>
                                    <span class="text-sm text-gray-900">{{ materia.cursado }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <i class="bx bx-group text-gray-400 text-lg mr-2 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <span class="text-xs font-medium text-gray-500 block">División</span>
                                    <span class="text-sm text-gray-900">{{ materia.division }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Cantidad de alumnos -->
                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Alumnos inscriptos:</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-700">
                                    <i class="bx bx-user text-base mr-1"></i>
                                    {{ materia.cantidad_alumnos }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer con botón -->
                    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                        <Link 
                            :href="route('expediente.materia.show', materia.id)"
                            class="block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                        >
                            <i class="bx bx-search-alt text-base mr-1"></i>
                            Ver Alumnos
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>