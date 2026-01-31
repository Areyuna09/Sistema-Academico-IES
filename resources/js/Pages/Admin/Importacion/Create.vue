<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import axios from 'axios';

const props = defineProps({
    tipo: String,
    tipoInfo: Object,
    campos: Object,
});

const page = usePage();
const flashError = computed(() => page.props.flash?.error);

const archivo = ref(null);
const arrastrando = ref(false);
const cargando = ref(false);
const error = ref('');

// Estado de validación de estructura
const validando = ref(false);
const validacion = ref(null); // null = sin validar, objeto = resultado

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

const puedeAnalizar = computed(() => {
    return archivo.value && !cargando.value && !validando.value && validacion.value?.valido === true;
});

const onFileChange = (event) => {
    const file = event.target.files[0];
    seleccionarArchivo(file);
};

const onDrop = (event) => {
    arrastrando.value = false;
    const file = event.dataTransfer.files[0];
    seleccionarArchivo(file);
};

const seleccionarArchivo = (file) => {
    error.value = '';
    validacion.value = null;

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
    validarEstructura(file);
};

const quitarArchivo = () => {
    archivo.value = null;
    error.value = '';
    validacion.value = null;
};

const validarEstructura = async (file) => {
    validando.value = true;
    validacion.value = null;
    error.value = '';

    const formData = new FormData();
    formData.append('archivo', file);

    try {
        const response = await axios.post(
            route('admin.importacion.validar-estructura', props.tipo),
            formData,
            { headers: { 'Content-Type': 'multipart/form-data' } }
        );
        validacion.value = response.data;
    } catch (err) {
        if (err.response?.status === 422) {
            const errors = err.response.data.errors;
            error.value = errors?.archivo?.[0] || 'Error de validación del archivo.';
        } else {
            error.value = 'Error al validar la estructura del archivo.';
        }
    } finally {
        validando.value = false;
    }
};

const analizarArchivo = () => {
    if (!puedeAnalizar.value) return;

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

            <!-- Campos requeridos y opcionales (solo si no hay archivo seleccionado) -->
            <div v-if="!archivo" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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

            <!-- Panel de validación de estructura (cuando hay archivo) -->
            <div v-if="archivo && (validando || validacion)" class="mb-6">
                <!-- Cargando validación -->
                <div v-if="validando" class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center space-x-3">
                        <i class="bx bx-loader-alt bx-spin text-2xl text-blue-500"></i>
                        <span class="text-gray-600">Validando estructura del archivo...</span>
                    </div>
                </div>

                <!-- Resultado: Válido -->
                <div v-else-if="validacion?.valido" class="bg-white rounded-lg shadow-md border-2 border-green-200 overflow-hidden">
                    <div class="bg-green-50 px-6 py-4 flex items-center space-x-3">
                        <i class="bx bx-check-circle text-2xl text-green-600"></i>
                        <div>
                            <h3 class="font-semibold text-green-800">Plantilla aceptada</h3>
                            <p class="text-sm text-green-600">La estructura del archivo es correcta para importar {{ tipoInfo?.nombre?.toLowerCase() }}.</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-xs text-gray-500 mb-4">Todos los encabezados de la plantilla fueron detectados. Los datos en las columnas opcionales pueden quedar vacíos.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Datos obligatorios -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="bx bx-check-circle text-green-500 mr-1"></i>
                                    Datos Obligatorios
                                </h4>
                                <ul class="space-y-1.5">
                                    <li v-for="campo in validacion.campos_requeridos" :key="campo.nombre" class="flex items-center text-sm">
                                        <i class="bx bx-check text-green-500 mr-2 text-lg"></i>
                                        <span class="font-mono bg-green-100 text-green-800 px-1.5 py-0.5 rounded text-xs">{{ campo.nombre }}</span>
                                    </li>
                                </ul>
                            </div>
                            <!-- Datos opcionales -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="bx bx-info-circle text-blue-500 mr-1"></i>
                                    Datos Opcionales
                                </h4>
                                <ul class="space-y-1.5">
                                    <li v-for="campo in validacion.campos_opcionales" :key="campo.nombre" class="flex items-center text-sm">
                                        <i class="bx bx-check text-blue-500 mr-2 text-lg"></i>
                                        <span class="font-mono bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded text-xs">{{ campo.nombre }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Columnas extra no reconocidas -->
                        <div v-if="validacion.columnas_invalidas?.length > 0" class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="bx bx-info-circle text-yellow-600 mr-2 mt-0.5"></i>
                                <div class="text-sm">
                                    <span class="font-medium text-yellow-800">Columnas no reconocidas (serán ignoradas):</span>
                                    <span class="text-yellow-700 ml-1">{{ validacion.columnas_invalidas.join(', ') }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- Advertencias de datos -->
                        <div v-if="validacion.advertencias?.length > 0" class="mt-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="bx bx-error text-orange-600 mr-2 mt-0.5"></i>
                                <div class="text-sm">
                                    <span class="font-medium text-orange-800">Problemas detectados en los datos (primeras filas):</span>
                                    <ul class="mt-1.5 space-y-1 text-orange-700">
                                        <li v-for="(adv, idx) in validacion.advertencias" :key="idx">{{ adv }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resultado: Inválido -->
                <div v-else-if="validacion && !validacion.valido" class="bg-white rounded-lg shadow-md border-2 border-red-200 overflow-hidden">
                    <div class="bg-red-50 px-6 py-4 flex items-center space-x-3">
                        <i class="bx bx-error-circle text-2xl text-red-600"></i>
                        <div>
                            <h3 class="font-semibold text-red-800">Plantilla rechazada</h3>
                            <p class="text-sm text-red-600">{{ validacion.error }}</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <!-- Mostrar qué se esperaba -->
                        <div v-if="validacion.campos_requeridos?.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Campos requeridos para {{ tipoInfo?.nombre }}</h4>
                                <ul class="space-y-1.5">
                                    <li v-for="campo in validacion.campos_requeridos" :key="campo.nombre" class="flex items-center text-sm">
                                        <i :class="campo.detectado ? 'bx bx-check text-green-500' : 'bx bx-x text-red-500'" class="mr-2 text-lg"></i>
                                        <span :class="campo.detectado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="font-mono px-1.5 py-0.5 rounded text-xs">{{ campo.nombre }}</span>
                                    </li>
                                </ul>
                            </div>
                            <!-- Columnas prohibidas encontradas -->
                            <div v-if="validacion.columnas_prohibidas?.length > 0">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Columnas no permitidas encontradas</h4>
                                <ul class="space-y-1.5">
                                    <li v-for="col in validacion.columnas_prohibidas" :key="col" class="flex items-center text-sm">
                                        <i class="bx bx-block text-red-500 mr-2 text-lg"></i>
                                        <span class="font-mono bg-red-100 text-red-800 px-1.5 py-0.5 rounded text-xs">{{ col }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Encabezados detectados en el archivo -->
                        <div v-if="validacion.encabezados_detectados?.length > 0" class="mt-4 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 mb-2">Encabezados detectados en el archivo:</p>
                            <div class="flex flex-wrap gap-1.5">
                                <span v-for="enc in validacion.encabezados_detectados" :key="enc" class="font-mono text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">{{ enc }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zona de carga -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Subir Archivo</h3>

                <!-- Error -->
                <div v-if="error || flashError" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center">
                    <i class="bx bx-error-circle text-red-500 text-xl mr-2"></i>
                    <span class="text-red-700">{{ error || flashError }}</span>
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
                            :disabled="cargando"
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
                        :disabled="!puedeAnalizar"
                        :class="[
                            'inline-flex items-center px-6 py-2 font-semibold rounded-lg transition-colors duration-200',
                            puedeAnalizar
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
