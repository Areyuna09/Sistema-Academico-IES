<script setup>
import { Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

defineProps({
    materias: { 
        type: Array, 
        default: () => [] 
    },
    mensaje: { 
        type: String, 
        default: null 
    }
});
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Mis Materias</h1>
                    <p class="mt-1 text-sm text-gray-600">Materias asignadas para el ciclo lectivo actual</p>
                </div>
                <div class="bg-blue-50 border border-blue-200 px-4 py-2 rounded-lg">
                    <p class="text-sm font-medium text-blue-700">
                        Total: <span class="font-bold">{{ materias.length }}</span> materia{{ materias.length !== 1 ? 's' : '' }}
                    </p>
                </div>
            </div>
        </template>

        <div class="p-6">
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