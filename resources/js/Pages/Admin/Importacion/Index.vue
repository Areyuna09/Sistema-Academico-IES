<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    tipos: Object,
});

const page = usePage();
const dismissed = ref(false);

const flashMessage = computed(() => page.props.flash?.success || page.props.flash?.error || '');
const flashType = computed(() => page.props.flash?.success ? 'success' : 'error');
const showFlash = computed(() => !!flashMessage.value && !dismissed.value);

// Auto-hide después de unos segundos
watch(flashMessage, (newVal) => {
    if (newVal) {
        dismissed.value = false;
        setTimeout(() => dismissed.value = true, flashType.value === 'success' ? 5000 : 8000);
    }
}, { immediate: true });

const getIcono = (tipo) => {
    const iconos = {
        'alumnos': 'bx-group',
        'profesores': 'bx-user',
        'materias': 'bx-book',
    };
    return iconos[tipo] || 'bx-file';
};

const getColor = (tipo) => {
    const colores = {
        'alumnos': 'blue',
        'profesores': 'green',
        'materias': 'purple',
    };
    return colores[tipo] || 'gray';
};
</script>

<template>
    <Head title="Importacion Masiva" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Importacion Masiva</h1>
                <p class="text-xs text-gray-400 mt-0.5">Carga de datos desde archivos Excel</p>
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

            <!-- Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Seleccione el tipo de datos a importar</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Cada tipo tiene su propia plantilla con los campos requeridos
                        </p>
                    </div>
                    <i class="bx bx-import text-4xl text-gray-300"></i>
                </div>
            </div>

            <!-- Tarjetas de tipos de importacion -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="(info, tipo) in tipos"
                    :key="tipo"
                    class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden"
                >
                    <div :class="`bg-${getColor(tipo)}-500 h-2`"></div>
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div :class="`p-3 rounded-lg bg-${getColor(tipo)}-100`">
                                <i :class="`bx ${getIcono(tipo)} text-2xl text-${getColor(tipo)}-600`"></i>
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ info.nombre }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ info.descripcion }}</p>

                        <div class="flex flex-col gap-2">
                            <Link
                                :href="route('admin.importacion.create', tipo)"
                                :class="`inline-flex items-center justify-center px-4 py-2 bg-${getColor(tipo)}-600 hover:bg-${getColor(tipo)}-700 text-white font-semibold rounded-lg transition-colors duration-200`"
                            >
                                <i class="bx bx-upload text-xl mr-2"></i>
                                Importar {{ info.nombre }}
                            </Link>

                            <a
                                :href="route('admin.importacion.plantilla', tipo)"
                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200"
                            >
                                <i class="bx bx-download text-xl mr-2"></i>
                                Descargar Plantilla
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instrucciones -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">
                    <i class="bx bx-info-circle mr-2"></i>
                    Instrucciones de uso
                </h3>
                <ol class="list-decimal list-inside space-y-2 text-blue-700">
                    <li>Descargue la plantilla correspondiente al tipo de datos que desea importar</li>
                    <li>Complete los datos en el archivo Excel siguiendo el formato indicado</li>
                    <li>Los campos marcados con * son obligatorios</li>
                    <li>Para <strong>Alumnos</strong> y <strong>Profesores</strong>: se creara automaticamente un usuario con contrasena igual al DNI</li>
                    <li>Para las <strong>Carreras</strong>: escriba el nombre exacto tal como aparece en el sistema</li>
                    <li>Suba el archivo y revise la vista previa antes de confirmar la importacion</li>
                    <li>Los registros duplicados (mismo DNI) se pueden actualizar o ignorar</li>
                </ol>
            </div>
        </div>
    </SidebarLayout>
</template>
