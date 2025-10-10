<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import axios from 'axios';


// Tab activo
const tabActivo = ref('materias');

// Variables para búsqueda de legajos
const dniBusqueda = ref('');
const buscando = ref(false);
const alumnoEncontrado = ref(null);
const historialAcademico = ref(null);
const errorBusqueda = ref('');

// Variables para alumnos
const alumnosData = ref(null);
const cargandoAlumnos = ref(false);
const errorAlumnos = ref('');
const materiaSeleccionada = ref(null);

// Variables para materias
const materiasData = ref(null);
const cargandoMaterias = ref(false);
const errorMaterias = ref('');

// Variables para modales
const modalAsistenciaAbierto = ref(false);
const modalNotasAbierto = ref(false);
const materiaActual = ref(null);
const alumnosActual = ref([]);
const guardandoAsistencia = ref(false);
const guardandoNotas = ref(false);

// Variables para asistencias
const fechaAsistencia = ref(new Date().toISOString().split('T')[0]);
const asistencias = ref({});

// Variables para notas
const tipoEvaluacion = ref('');
const fechaNotas = ref(new Date().toISOString().split('T')[0]);
const notas = ref({});

const opciones = [
    {
        id: 'materias',
        titulo: 'Materias',
        descripcion: 'Asistencias y notas',
        icono: 'bx-book',
        bgColor: 'bg-orange-100',
        iconColor: 'text-orange-600',
        hoverBorder: 'hover:border-orange-400',
        activeBorder: 'border-orange-500',
        activeBg: 'bg-orange-50'
    },
    {
        id: 'alumnos',
        titulo: 'Alumnos',
        descripcion: 'Estudiantes activos',
        icono: 'bx-group',
        bgColor: 'bg-teal-100',
        iconColor: 'text-teal-600',
        hoverBorder: 'hover:border-teal-400',
        activeBorder: 'border-teal-500',
        activeBg: 'bg-teal-50'
    },
    {
        id: 'legajos',
        titulo: 'Legajos',
        descripcion: 'Expedientes académicos',
        icono: 'bx-file',
        bgColor: 'bg-gray-100',
        iconColor: 'text-gray-600',
        hoverBorder: 'hover:border-gray-400',
        activeBorder: 'border-gray-500',
        activeBg: 'bg-gray-50'
    }
];

const cambiarTab = (tabId) => {
    tabActivo.value = tabId;
    // Limpiar búsqueda al cambiar de tab
    if (tabId !== 'legajos') {
        limpiarBusqueda();
    }
    // Cargar materias si se selecciona el tab de materias
    if (tabId === 'materias' && !materiasData.value) {
        cargarMaterias();
    }
    // Cargar alumnos si se selecciona el tab de alumnos
    if (tabId === 'alumnos' && !alumnosData.value) {
        cargarAlumnos();
    }
};

const buscarAlumno = async () => {
    if (!dniBusqueda.value.trim()) {
        errorBusqueda.value = 'Por favor ingrese un DNI';
        return;
    }

    buscando.value = true;
    errorBusqueda.value = '';
    alumnoEncontrado.value = null;
    historialAcademico.value = null;

    try {
        const response = await axios.post(route('expediente.buscar-dni'), {
            dni: dniBusqueda.value
        });

        alumnoEncontrado.value = response.data.alumno;
        historialAcademico.value = response.data.historial;
    } catch (error) {
        if (error.response && error.response.status === 404) {
            errorBusqueda.value = 'No se encontró ningún alumno con ese DNI';
        } else {
            errorBusqueda.value = 'Ocurrió un error al buscar el alumno';
        }
    } finally {
        buscando.value = false;
    }
};

const limpiarBusqueda = () => {
    dniBusqueda.value = '';
    alumnoEncontrado.value = null;
    historialAcademico.value = null;
    errorBusqueda.value = '';
};

const cargarMaterias = async () => {
    cargandoMaterias.value = true;
    errorMaterias.value = '';

    try {
        const response = await axios.get(route('expediente.alumnos-profesor'));
        materiasData.value = response.data.materias;
    } catch (error) {
        errorMaterias.value = 'Error al cargar las materias';
        console.error(error);
    } finally {
        cargandoMaterias.value = false;
    }
};

const cargarAlumnos = async () => {
    cargandoAlumnos.value = true;
    errorAlumnos.value = '';

    try {
        const response = await axios.get(route('expediente.alumnos-profesor'));
        alumnosData.value = response.data.materias;

        // Seleccionar la primera materia por defecto
        if (alumnosData.value && alumnosData.value.length > 0) {
            materiaSeleccionada.value = alumnosData.value[0].materia_id;
        }
    } catch (error) {
        errorAlumnos.value = 'Error al cargar los alumnos';
        console.error(error);
    } finally {
        cargandoAlumnos.value = false;
    }
};

// Funciones para modales de asistencia
const abrirModalAsistencia = (materia) => {
    materiaActual.value = materia;
    alumnosActual.value = materia.alumnos;
    fechaAsistencia.value = new Date().toISOString().split('T')[0];

    // Inicializar asistencias con todos los alumnos presentes por defecto
    asistencias.value = {};
    materia.alumnos.forEach(alumno => {
        asistencias.value[alumno.id] = { estado: 'presente', observaciones: '' };
    });

    modalAsistenciaAbierto.value = true;
};

const cerrarModalAsistencia = () => {
    modalAsistenciaAbierto.value = false;
    materiaActual.value = null;
    alumnosActual.value = [];
    asistencias.value = {};
};

const guardarAsistenciaDiaria = async () => {
    if (!materiaActual.value || !fechaAsistencia.value) {
        alert('Por favor completa todos los campos requeridos');
        return;
    }

    guardandoAsistencia.value = true;

    try {
        // Preparar datos
        const asistenciasArray = Object.keys(asistencias.value).map(alumnoId => ({
            alumno_id: parseInt(alumnoId),
            estado: asistencias.value[alumnoId].estado,
            observaciones: asistencias.value[alumnoId].observaciones || null
        }));

        const response = await axios.post(route('expediente.guardar-asistencia'), {
            profesor_materia_id: materiaActual.value.materia_id,
            fecha: fechaAsistencia.value,
            asistencias: asistenciasArray
        });

        alert(response.data.message);
        cerrarModalAsistencia();
    } catch (error) {
        console.error(error);
        if (error.response && error.response.data && error.response.data.message) {
            alert('Error: ' + error.response.data.message);
        } else {
            alert('Error al guardar la asistencia');
        }
    } finally {
        guardandoAsistencia.value = false;
    }
};

// Funciones para modales de notas
const abrirModalNotas = (materia) => {
    materiaActual.value = materia;
    alumnosActual.value = materia.alumnos;
    fechaNotas.value = new Date().toISOString().split('T')[0];
    tipoEvaluacion.value = '';

    // Inicializar notas vacías
    notas.value = {};
    materia.alumnos.forEach(alumno => {
        notas.value[alumno.id] = { nota: '', observaciones: '' };
    });

    modalNotasAbierto.value = true;
};

const cerrarModalNotas = () => {
    modalNotasAbierto.value = false;
    materiaActual.value = null;
    alumnosActual.value = [];
    notas.value = {};
    tipoEvaluacion.value = '';
};

const guardarNotasTemporales = async () => {
    if (!materiaActual.value || !fechaNotas.value || !tipoEvaluacion.value) {
        alert('Por favor completa el tipo de evaluación y la fecha');
        return;
    }

    // Filtrar solo las notas que fueron ingresadas
    const notasIngresadas = Object.keys(notas.value)
        .filter(alumnoId => notas.value[alumnoId].nota !== '')
        .map(alumnoId => ({
            alumno_id: parseInt(alumnoId),
            nota: parseFloat(notas.value[alumnoId].nota),
            observaciones: notas.value[alumnoId].observaciones || null
        }));

    if (notasIngresadas.length === 0) {
        alert('Debes ingresar al menos una nota');
        return;
    }

    guardandoNotas.value = true;

    try {
        const response = await axios.post(route('expediente.guardar-notas'), {
            profesor_materia_id: materiaActual.value.materia_id,
            tipo_evaluacion: tipoEvaluacion.value,
            fecha: fechaNotas.value,
            notas: notasIngresadas
        });

        alert(response.data.message);
        cerrarModalNotas();
    } catch (error) {
        console.error(error);
        if (error.response && error.response.data && error.response.data.message) {
            alert('Error: ' + error.response.data.message);
        } else {
            alert('Error al guardar las notas');
        }
    } finally {
        guardandoNotas.value = false;
    }
};

// Cargar materias al montar el componente (ya que materias es el tab por defecto)
onMounted(() => {
    cargarMaterias();
});
</script>

<template>
    <Head title="Expediente Académico" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Expediente Académico</h1>
                <p class="text-xs text-gray-400 mt-0.5">Bienvenido, {{ $page.props.auth.user.name }}</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Grid de opciones / Tabs -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div
                    v-for="(opcion, index) in opciones"
                    :key="index"
                    @click="cambiarTab(opcion.id)"
                    class="group cursor-pointer"
                >
                    <div :class="[
                        'bg-white border-2 rounded-lg p-5 hover:shadow-md transition-all duration-200',
                        tabActivo === opcion.id ? [opcion.activeBorder, opcion.activeBg] : ['border-gray-200', opcion.hoverBorder]
                    ]">
                        <div class="flex items-center mb-3">
                            <div :class="[
                                'w-10 h-10 rounded-lg flex items-center justify-center mr-3',
                                opcion.bgColor
                            ]">
                                <i :class="['bx text-xl', opcion.icono, opcion.iconColor]"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-gray-900">{{ opcion.titulo }}</h3>
                                <p class="text-xs text-gray-500">{{ opcion.descripcion }}</p>
                            </div>
                            <i v-if="tabActivo === opcion.id" class="bx bx-check-circle text-2xl text-green-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido dinámico según el tab activo -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <!-- Tab Materias -->
                <div v-if="tabActivo === 'materias'">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <i class="bx bx-book text-3xl text-orange-600 mr-3"></i>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Mis Materias</h2>
                                <p class="text-sm text-gray-600">Gestiona tus materias, asistencias y notas</p>
                            </div>
                        </div>
                    </div>

                    <!-- Estado de carga -->
                    <div v-if="cargandoMaterias" class="text-center py-12">
                        <i class="bx bx-loader-alt animate-spin text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">Cargando materias...</p>
                    </div>

                    <!-- Error -->
                    <div v-else-if="errorMaterias" class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center text-red-700">
                            <i class="bx bx-error-circle mr-2 text-xl"></i>
                            {{ errorMaterias }}
                        </div>
                    </div>

                    <!-- Lista de materias -->
                    <div v-else-if="materiasData && materiasData.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-for="materia in materiasData" :key="materia.materia_id" class="bg-white border-2 border-gray-200 rounded-lg p-5 hover:border-orange-400 hover:shadow-md transition-all">
                            <!-- Header de la materia -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ materia.materia }}</h3>
                                    <p class="text-sm text-gray-600">{{ materia.carrera }}</p>
                                </div>
                                <span class="bg-orange-100 text-orange-700 text-xs font-medium px-3 py-1 rounded-full">
                                    {{ materia.total_alumnos }} alumnos
                                </span>
                            </div>

                            <!-- Detalles de la materia -->
                            <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <i class="bx bx-calendar text-orange-500 mr-2"></i>
                                    <span>Cursado: <strong>{{ materia.cursado }}</strong></span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="bx bx-group text-orange-500 mr-2"></i>
                                    <span>División: <strong>{{ materia.division }}</strong></span>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex gap-2 pt-3 border-t border-gray-200">
                                <a
                                    :href="route('profesor.mis-materias.show', materia.materia_id)"
                                    class="flex-1 px-3 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-center"
                                >
                                    <i class="bx bx-show mr-1"></i>
                                    Ver detalle
                                </a>
                                <button
                                    class="flex-1 px-3 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                                    title="Cargar asistencia final del cuatrimestre"
                                >
                                    <i class="bx bx-check-circle mr-1"></i>
                                    Asist. Final
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sin materias -->
                    <div v-else class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bx bx-book-open text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">No tienes materias asignadas</p>
                    </div>
                </div>

                <!-- Tab Alumnos -->
                <div v-if="tabActivo === 'alumnos'">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <i class="bx bx-group text-3xl text-teal-600 mr-3"></i>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Alumnos</h2>
                                <p class="text-sm text-gray-600">Estudiantes inscriptos en tus materias</p>
                            </div>
                        </div>
                    </div>

                    <!-- Estado de carga -->
                    <div v-if="cargandoAlumnos" class="text-center py-12">
                        <i class="bx bx-loader-alt animate-spin text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">Cargando alumnos...</p>
                    </div>

                    <!-- Error -->
                    <div v-else-if="errorAlumnos" class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center text-red-700">
                            <i class="bx bx-error-circle mr-2 text-xl"></i>
                            {{ errorAlumnos }}
                        </div>
                    </div>

                    <!-- Contenido de alumnos -->
                    <div v-else-if="alumnosData && alumnosData.length > 0">
                        <!-- Selector de materia -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar materia</label>
                            <select
                                v-model="materiaSeleccionada"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                            >
                                <option v-for="materia in alumnosData" :key="materia.materia_id" :value="materia.materia_id">
                                    {{ materia.materia }} - {{ materia.carrera }} ({{ materia.cursado }}) - División {{ materia.division }} ({{ materia.total_alumnos }} alumnos)
                                </option>
                            </select>
                        </div>

                        <!-- Lista de alumnos de la materia seleccionada -->
                        <div v-for="materia in alumnosData.filter(m => m.materia_id === materiaSeleccionada)" :key="materia.materia_id">
                            <!-- Información de la materia -->
                            <div class="bg-teal-50 border border-teal-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ materia.materia }}</h3>
                                        <p class="text-sm text-gray-600">{{ materia.carrera }} - Cursado {{ materia.cursado }} - División {{ materia.division }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button
                                            @click="abrirModalAsistencia(materia)"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center"
                                            title="Registrar asistencia del día"
                                        >
                                            <i class="bx bx-calendar-check mr-2"></i>
                                            Asistencia Diaria
                                        </button>
                                        <button
                                            @click="abrirModalNotas(materia)"
                                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center"
                                            title="Cargar notas temporales"
                                        >
                                            <i class="bx bx-edit mr-2"></i>
                                            Cargar Notas
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabla de alumnos -->
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse bg-white border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">#</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">DNI</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Apellido</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Nombre</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Email</th>
                                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(alumno, index) in materia.alumnos" :key="alumno.id" class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ index + 1 }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ alumno.dni }}</td>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ alumno.apellido }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ alumno.nombre }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ alumno.email }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <button
                                                    class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors"
                                                    title="Ver detalle del alumno"
                                                >
                                                    <i class="bx bx-show"></i>
                                                    Ver detalle
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Resumen -->
                            <div class="mt-4 text-sm text-gray-600">
                                Total de alumnos: <strong>{{ materia.total_alumnos }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Sin materias -->
                    <div v-else class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bx bx-user-plus text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">No tienes materias asignadas o no hay alumnos inscriptos</p>
                    </div>
                </div>

                <!-- Tab Legajos -->
                <div v-if="tabActivo === 'legajos'">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <i class="bx bx-file text-3xl text-gray-600 mr-3"></i>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Legajos de Alumnos</h2>
                                <p class="text-sm text-gray-600">Consulta los expedientes académicos</p>
                            </div>
                        </div>
                        <button
                            v-if="alumnoEncontrado"
                            @click="limpiarBusqueda"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
                        >
                            <i class="bx bx-x mr-2"></i>
                            Limpiar
                        </button>
                    </div>

                    <!-- Formulario de búsqueda por DNI -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar alumno por DNI</label>
                        <div class="flex gap-2">
                            <input
                                v-model="dniBusqueda"
                                type="text"
                                placeholder="Ingrese el DNI del alumno"
                                @keyup.enter="buscarAlumno"
                                pattern="[0-9]*"
                                inputmode="numeric"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                                :disabled="buscando"
                            />
                            <button
                                @click="buscarAlumno"
                                :disabled="buscando"
                                class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <i :class="['bx mr-2', buscando ? 'bx-loader-alt animate-spin' : 'bx-search']"></i>
                                {{ buscando ? 'Buscando...' : 'Buscar' }}
                            </button>
                        </div>

                        <!-- Mensaje de error -->
                        <div v-if="errorBusqueda" class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg flex items-center text-red-700">
                            <i class="bx bx-error-circle mr-2 text-xl"></i>
                            {{ errorBusqueda }}
                        </div>
                    </div>

                    <!-- Resultados de la búsqueda -->
                    <div v-if="alumnoEncontrado">
                        <!-- Datos del alumno -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">DNI</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ alumnoEncontrado.dni }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Apellido</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ alumnoEncontrado.apellido }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Nombre</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ alumnoEncontrado.nombre }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Año Ingr.</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ alumnoEncontrado.anio_ingreso }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Carrera</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ alumnoEncontrado.carrera }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Historial académico por año -->
                        <div v-for="(materias, anno) in historialAcademico" :key="anno" class="mb-6">
                            <div class="bg-gray-800 text-white px-4 py-2 rounded-t-lg">
                                <h3 class="font-semibold">{{ anno }}</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse bg-white">
                                    <thead class="bg-gray-100">
                                        <tr class="border-b border-gray-300">
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 border-r">Materia</th>
                                            <th class="px-2 py-2 text-center text-xs font-semibold text-gray-700 border-r">R</th>
                                            <th class="px-2 py-2 text-center text-xs font-semibold text-gray-700 border-r">P</th>
                                            <th class="px-2 py-2 text-center text-xs font-semibold text-gray-700 border-r">E</th>
                                            <th class="px-2 py-2 text-center text-xs font-semibold text-gray-700 border-r">L</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 border-r">Nota</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 border-r">Fecha</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 border-r">Libro</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700">Folio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(materia, index) in materias" :key="index" class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ materia.materia }}</td>
                                            <td class="px-2 py-2 text-center text-sm text-gray-900 border-r">{{ materia.regular }}</td>
                                            <td class="px-2 py-2 text-center text-sm text-gray-900 border-r">{{ materia.promocional }}</td>
                                            <td class="px-2 py-2 text-center text-sm text-gray-900 border-r">{{ materia.equivalencia }}</td>
                                            <td class="px-2 py-2 text-center text-sm text-gray-900 border-r">{{ materia.libre }}</td>
                                            <td class="px-3 py-2 text-center text-sm font-semibold text-gray-900 border-r">{{ materia.nota }}</td>
                                            <td class="px-3 py-2 text-center text-sm text-gray-900 border-r">{{ materia.fecha }}</td>
                                            <td class="px-3 py-2 text-center text-sm text-gray-900 border-r">{{ materia.libro }}</td>
                                            <td class="px-3 py-2 text-center text-sm text-gray-900">{{ materia.folio }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Mensaje si no hay historial -->
                        <div v-if="!historialAcademico || Object.keys(historialAcademico).length === 0" class="text-center py-8 bg-gray-50 rounded-lg">
                            <i class="bx bx-info-circle text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-600">Este alumno aún no tiene materias cursadas registradas</p>
                        </div>
                    </div>

                    <!-- Estado inicial -->
                    <div v-else-if="!buscando && !errorBusqueda" class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bx bx-search-alt text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">Ingresa un DNI para consultar el legajo del alumno</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Asistencia Diaria -->
        <div v-if="modalAsistenciaAbierto" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                <!-- Header del modal -->
                <div class="bg-blue-600 text-white px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Asistencia Diaria</h3>
                        <p class="text-sm text-blue-100 mt-1" v-if="materiaActual">
                            {{ materiaActual.materia }} - {{ materiaActual.carrera }}
                        </p>
                    </div>
                    <button @click="cerrarModalAsistencia" class="text-white hover:text-gray-200">
                        <i class="bx bx-x text-3xl"></i>
                    </button>
                </div>

                <!-- Contenido del modal -->
                <div class="flex-1 overflow-y-auto p-6">
                    <!-- Selector de fecha -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                        <input
                            v-model="fechaAsistencia"
                            type="date"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                    <!-- Tabla de alumnos -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse bg-white border border-gray-200">
                            <thead class="bg-gray-100 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">#</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Alumno</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Estado</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(alumno, index) in alumnosActual" :key="alumno.id" class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <div class="font-medium">{{ alumno.apellido }}, {{ alumno.nombre }}</div>
                                        <div class="text-xs text-gray-500">DNI: {{ alumno.dni }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <select
                                            v-model="asistencias[alumno.id].estado"
                                            class="px-3 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500"
                                        >
                                            <option value="presente">Presente</option>
                                            <option value="ausente">Ausente</option>
                                            <option value="tarde">Tarde</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input
                                            v-model="asistencias[alumno.id].observaciones"
                                            type="text"
                                            placeholder="Opcional"
                                            class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer del modal -->
                <div class="bg-gray-100 px-6 py-4 flex justify-end gap-3">
                    <button
                        @click="cerrarModalAsistencia"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="guardarAsistenciaDiaria"
                        :disabled="guardandoAsistencia"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                    >
                        <i :class="['bx mr-2', guardandoAsistencia ? 'bx-loader-alt animate-spin' : 'bx-save']"></i>
                        {{ guardandoAsistencia ? 'Guardando...' : 'Guardar Asistencia' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de Cargar Notas -->
        <div v-if="modalNotasAbierto" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                <!-- Header del modal -->
                <div class="bg-purple-600 text-white px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Cargar Notas</h3>
                        <p class="text-sm text-purple-100 mt-1" v-if="materiaActual">
                            {{ materiaActual.materia }} - {{ materiaActual.carrera }}
                        </p>
                    </div>
                    <button @click="cerrarModalNotas" class="text-white hover:text-gray-200">
                        <i class="bx bx-x text-3xl"></i>
                    </button>
                </div>

                <!-- Contenido del modal -->
                <div class="flex-1 overflow-y-auto p-6">
                    <!-- Controles superiores -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Evaluación *</label>
                            <select
                                v-model="tipoEvaluacion"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                                <option value="">Seleccionar...</option>
                                <option value="Parcial 1">Parcial 1</option>
                                <option value="Parcial 2">Parcial 2</option>
                                <option value="Recuperatorio 1">Recuperatorio 1</option>
                                <option value="Recuperatorio 2">Recuperatorio 2</option>
                                <option value="Trabajo Práctico">Trabajo Práctico</option>
                                <option value="Final">Final</option>
                                <option value="Otra">Otra</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha *</label>
                            <input
                                v-model="fechaNotas"
                                type="date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            />
                        </div>
                    </div>

                    <!-- Tabla de alumnos -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse bg-white border border-gray-200">
                            <thead class="bg-gray-100 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">#</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Alumno</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Nota (1-10)</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(alumno, index) in alumnosActual" :key="alumno.id" class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <div class="font-medium">{{ alumno.apellido }}, {{ alumno.nombre }}</div>
                                        <div class="text-xs text-gray-500">DNI: {{ alumno.dni }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input
                                            v-model="notas[alumno.id].nota"
                                            type="number"
                                            min="1"
                                            max="10"
                                            step="0.01"
                                            placeholder="-"
                                            class="w-20 px-2 py-1 border border-gray-300 rounded text-sm text-center focus:ring-2 focus:ring-purple-500"
                                        />
                                    </td>
                                    <td class="px-4 py-3">
                                        <input
                                            v-model="notas[alumno.id].observaciones"
                                            type="text"
                                            placeholder="Opcional"
                                            class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-purple-500"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Nota informativa -->
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start text-yellow-800">
                            <i class="bx bx-info-circle mr-2 text-xl"></i>
                            <div class="text-sm">
                                <strong>Importante:</strong> Las notas quedarán en estado "Pendiente" hasta que sean aprobadas por el administrador o bedel.
                                Solo necesitas cargar las notas de los alumnos que desees evaluar.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer del modal -->
                <div class="bg-gray-100 px-6 py-4 flex justify-end gap-3">
                    <button
                        @click="cerrarModalNotas"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="guardarNotasTemporales"
                        :disabled="guardandoNotas"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                    >
                        <i :class="['bx mr-2', guardandoNotas ? 'bx-loader-alt animate-spin' : 'bx-save']"></i>
                        {{ guardandoNotas ? 'Guardando...' : 'Guardar Notas' }}
                    </button>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
