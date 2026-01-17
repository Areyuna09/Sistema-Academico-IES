<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    tipo: String,
    tipoInfo: Object,
    campos: Object,
});

const archivo = ref(null);
const arrastrando = ref(false);
const cargando = ref(false);
const error = ref('');

const getColor = (tipo) => {
    const colores = {
        'alumnos': 'blue',
        'profesores': 'green',
        'materias': 'purple',
    };
    return colores[tipo] || 'gray';
};

const getIcono = (tipo) => {
    const iconos = {
        'alumnos': 'bx-group',
        'profesores': 'bx-user',
        'materias': 'bx-book',
    };
    return iconos[tipo] || 'bx-file';
};

const onFileChange = (event) => {
    const file = event.target.files[0];
    validarArchivo(file);
};

const onDrop = (event) => {
    arrastrando.value = false;
    const file = event.dataTransfer.files[0];
    validarArchivo(file);
};

const validarArchivo = (file) => {
    error.value = '';

    if (!file) return;

    const extensionesValidas = ['xlsx', 'xls', 'csv'];
    const extension = file.name.split('.').pop().toLowerCase();

    if (!extensionesValidas.includes(extension)) {
        error.value = 'El archivo debe ser Excel (.xlsx, .xls) o CSV (.csv)';
        return;
    }

    if (file.size > 10 * 1024 * 1024) {
        error.value = 'El archivo no debe superar los 10MB';
        return;
    }

    archivo.value = file;
};

const quitarArchivo = () => {
    archivo.value = null;
    error.value = '';
};

const analizarArchivo = () => {
    if (!archivo.value) return;

    cargando.value = true;
    error.value = '';

    const formData = new FormData();
    formData.append('archivo', archivo.value);

    router.post(route('admin.importacion.analizar', props.tipo), formData, {
        forceFormData: true,
        onError: (errors) => {
            error.value = errors.archivo || 'Error al procesar el archivo';
            cargando.value = false;
        },
        onFinish: () => {
            cargando.value = false;
        },
    });
};

const formatearTamano = (bytes) => {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
};
</script>

<template>
    <Head :title="`Importar ${tipoInfo?.nombre || tipo}`" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex items-center space-x-2">
                <Link :href="route('admin.importacion.index')" class="text-gray-400 hover:text-white">
                    <i class="bx bx-arrow-back text-xl"></i>
                </Link>
                <div>
                    <h1 class="text-xl font-semibold text-white">Importar {{ tipoInfo?.nombre || tipo }}</h1>
                    <p class="text-xs text-gray-400 mt-0.5">Carga masiva desde archivo Excel</p>
                </div>
            </div>
        </template>

        <div class="p-8 max-w-4xl mx-auto">
            <!-- Informacion del tipo -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center space-x-4">
                    <div :class="`p-4 rounded-lg bg-${getColor(tipo)}-100`">
                        <i :class="`bx ${getIcono(tipo)} text-3xl text-${getColor(tipo)}-600`"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ tipoInfo?.nombre }}</h2>
                        <p class="text-gray-500">{{ tipoInfo?.descripcion }}</p>
                    </div>
                </div>
            </div>

            <!-- Campos requeridos y opcionales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bx bx-check-circle text-green-500 mr-2"></i>
                        Campos Requeridos
                    </h3>
                    <ul class="space-y-2">
                        <li v-for="(descripcion, campo) in campos.requeridos" :key="campo" class="flex items-start">
                            <span class="font-mono text-sm bg-green-100 text-green-800 px-2 py-1 rounded mr-2">{{ campo }}</span>
                            <span class="text-sm text-gray-600">{{ descripcion }}</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bx bx-info-circle text-blue-500 mr-2"></i>
                        Campos Opcionales
                    </h3>
                    <ul class="space-y-2">
                        <li v-for="(descripcion, campo) in campos.opcionales" :key="campo" class="flex items-start">
                            <span class="font-mono text-sm bg-gray-100 text-gray-700 px-2 py-1 rounded mr-2">{{ campo }}</span>
                            <span class="text-sm text-gray-600">{{ descripcion }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Zona de carga -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Subir Archivo</h3>

                <!-- Error -->
                <div v-if="error" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center">
                    <i class="bx bx-error-circle text-red-500 text-xl mr-2"></i>
                    <span class="text-red-700">{{ error }}</span>
                </div>

                <!-- Dropzone -->
                <div
                    v-if="!archivo"
                    @dragover.prevent="arrastrando = true"
                    @dragleave.prevent="arrastrando = false"
                    @drop.prevent="onDrop"
                    :class="[
                        'border-2 border-dashed rounded-lg p-12 text-center transition-colors duration-200',
                        arrastrando ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400'
                    ]"
                >
                    <i class="bx bx-cloud-upload text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 mb-2">Arrastre su archivo aqui o</p>
                    <label class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg cursor-pointer transition-colors duration-200">
                        <i class="bx bx-upload mr-2"></i>
                        Seleccionar Archivo
                        <input
                            type="file"
                            accept=".xlsx,.xls,.csv"
                            @change="onFileChange"
                            class="hidden"
                        />
                    </label>
                    <p class="text-sm text-gray-400 mt-4">Formatos aceptados: .xlsx, .xls, .csv (max 10MB)</p>
                </div>

                <!-- Archivo seleccionado -->
                <div v-else class="border rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg mr-4">
                                <i class="bx bx-file text-2xl text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ archivo.name }}</p>
                                <p class="text-sm text-gray-500">{{ formatearTamano(archivo.size) }}</p>
                            </div>
                        </div>
                        <button
                            @click="quitarArchivo"
                            class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                        >
                            <i class="bx bx-trash text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Botones de accion -->
            <div class="flex justify-between">
                <Link
                    :href="route('admin.importacion.index')"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200"
                >
                    <i class="bx bx-arrow-back mr-2"></i>
                    Volver
                </Link>

                <div class="flex space-x-3">
                    <a
                        :href="route('admin.importacion.plantilla', tipo)"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200"
                    >
                        <i class="bx bx-download mr-2"></i>
                        Descargar Plantilla
                    </a>

                    <button
                        @click="analizarArchivo"
                        :disabled="!archivo || cargando"
                        :class="[
                            'inline-flex items-center px-6 py-2 font-semibold rounded-lg transition-colors duration-200',
                            archivo && !cargando
                                ? `bg-${getColor(tipo)}-600 hover:bg-${getColor(tipo)}-700 text-white`
                                : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                        ]"
                    >
                        <i :class="cargando ? 'bx bx-loader-alt bx-spin' : 'bx bx-search-alt'" class="mr-2"></i>
                        {{ cargando ? 'Analizando...' : 'Analizar Archivo' }}
                    </button>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
