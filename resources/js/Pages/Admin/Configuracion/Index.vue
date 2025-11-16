<script setup>
import { Head, Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    configuracion: Object,
});
</script>

<template>
    <Head title="Configuración del Sistema" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Configuración General</h1>
                <p class="text-xs text-gray-400 mt-0.5">Información de la institución</p>
            </div>
        </template>

        <div class="p-8 max-w-5xl mx-auto">
            <!-- Botón Editar -->
            <div class="flex justify-end mb-6">
                <Link
                    :href="route('admin.configuracion.edit')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200"
                >
                    <i class="bx bx-edit text-xl mr-2"></i>
                    Editar Configuración
                </Link>
            </div>

            <!-- Información de la Institución -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
                    <i class="bx bx-buildings text-xl mr-2 text-blue-600"></i>
                    Información de la Institución
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <div class="flex items-start gap-4">
                            <div v-if="configuracion.logo_url" class="flex-shrink-0">
                                <img :src="configuracion.logo_url" alt="Logo" class="h-24 w-24 object-contain border rounded-lg p-2">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nombre de la Institución</label>
                                <p class="text-lg font-semibold text-gray-900">{{ configuracion.nombre_institucion || '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Datos de Contacto -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
                    <i class="bx bx-phone text-xl mr-2 text-green-600"></i>
                    Datos de Contacto
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Dirección</label>
                        <p class="text-gray-900">{{ configuracion.direccion || 'No especificada' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Teléfono</label>
                        <p class="text-gray-900 flex items-center">
                            <i class="bx bx-phone text-lg mr-2 text-gray-400"></i>
                            {{ configuracion.telefono || 'No especificado' }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                        <p class="text-gray-900 flex items-center">
                            <i class="bx bx-envelope text-lg mr-2 text-gray-400"></i>
                            <a v-if="configuracion.email" :href="`mailto:${configuracion.email}`" class="text-blue-600 hover:underline">
                                {{ configuracion.email }}
                            </a>
                            <span v-else>No especificado</span>
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Sitio Web</label>
                        <p class="text-gray-900 flex items-center">
                            <i class="bx bx-globe text-lg mr-2 text-gray-400"></i>
                            <a v-if="configuracion.sitio_web" :href="configuracion.sitio_web" target="_blank" class="text-blue-600 hover:underline">
                                {{ configuracion.sitio_web }}
                            </a>
                            <span v-else>No especificado</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Información para Documentos -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
                    <i class="bx bx-file text-xl mr-2 text-purple-600"></i>
                    Información para Documentos
                </h3>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Footer para Certificados/Comprobantes</label>
                        <p class="text-gray-900 whitespace-pre-line">{{ configuracion.footer_documentos || 'No especificado' }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Firma Digital</label>
                            <div v-if="configuracion.firma_digital_url" class="mt-2">
                                <img :src="configuracion.firma_digital_url" alt="Firma" class="h-20 w-auto object-contain border rounded-lg p-2 bg-white">
                            </div>
                            <p v-else class="text-gray-500 text-sm">No cargada</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Cargo de quien firma</label>
                            <p class="text-gray-900">{{ configuracion.cargo_firma || 'No especificado' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horarios de Atención -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
                    <i class="bx bx-time text-xl mr-2 text-orange-600"></i>
                    Horarios de Atención
                </h3>

                <div>
                    <p class="text-gray-900 whitespace-pre-line">{{ configuracion.horarios_atencion || 'No especificados' }}</p>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
