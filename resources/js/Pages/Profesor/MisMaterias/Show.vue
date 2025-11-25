<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import axios from 'axios';

const props = defineProps({
    profesorMateria: {
        type: Object,
        required: true
    },
    alumnos: {
        type: Array,
        default: () => []
    },
    asistencias: {
        type: Array,
        default: () => []
    },
    notasTemporales: {
        type: Array,
        default: () => []
    },
    estadisticas: {
        type: Object,
        default: () => ({})
    }
});

// Estado para filtros de notas
const filtroNotas = ref('todas');

// Estado para modal de Asistencia Final
const modalAsistenciaFinal = ref(false);
const totalClasesCuatrimestre = ref(props.estadisticas.total_clases || 0);
const asistenciasFinal = ref({});
const guardandoAsistenciaFinal = ref(false);

// Estado para modal de Notas Finales
const modalNotasFinales = ref(false);
const fechaNotasFinales = ref(new Date().toISOString().split('T')[0]);
const notasFinales = ref({});
const guardandoNotasFinales = ref(false);

// Computed para filtrar notas
const notasFiltradas = () => {
    if (filtroNotas.value === 'todas') return props.notasTemporales;
    return props.notasTemporales.filter(nota => nota.estado === filtroNotas.value);
};

// Helper para obtener color según porcentaje de asistencia
const getColorAsistencia = (porcentaje) => {
    if (porcentaje >= 80) return 'text-green-600 bg-green-50';
    if (porcentaje >= 70) return 'text-yellow-600 bg-yellow-50';
    return 'text-red-600 bg-red-50';
};

// Helper para obtener badge de estado de promoción
const getBadgePromocion = (estado) => {
    const badges = {
        'Apto': 'bg-green-100 text-green-700 border-green-300',
        'Regular': 'bg-yellow-100 text-yellow-700 border-yellow-300',
        'Insuficiente': 'bg-red-100 text-red-700 border-red-300'
    };
    return badges[estado] || 'bg-gray-100 text-gray-700 border-gray-300';
};

// Helper para obtener badge de estado de nota
const getBadgeNota = (estado) => {
    const badges = {
        'pendiente': 'bg-yellow-100 text-yellow-700 border-yellow-300',
        'aprobada': 'bg-green-100 text-green-700 border-green-300',
        'rechazada': 'bg-red-100 text-red-700 border-red-300'
    };
    return badges[estado] || 'bg-gray-100 text-gray-700 border-gray-300';
};

// Funciones para modal de Asistencia Final
const abrirModalAsistenciaFinal = () => {
    // Inicializar valores con asistencias existentes si ya hay datos finales
    asistenciasFinal.value = {};
    props.asistencias.forEach(asist => {
        if (asist.tiene_asistencia_final) {
            asistenciasFinal.value[asist.alumno_id] = {
                presentes: asist.presentes,
                ausentes: asist.ausentes,
                tardes: asist.tardes,
                observaciones: ''
            };
        } else {
            // Valores por defecto
            asistenciasFinal.value[asist.alumno_id] = {
                presentes: asist.presentes || 0,
                ausentes: asist.ausentes || 0,
                tardes: asist.tardes || 0,
                observaciones: ''
            };
        }
    });

    // Si no hay total de clases, usar valor por defecto
    if (!totalClasesCuatrimestre.value) {
        totalClasesCuatrimestre.value = 40; // Valor típico
    }

    modalAsistenciaFinal.value = true;
};

const cerrarModalAsistenciaFinal = () => {
    modalAsistenciaFinal.value = false;
};

const guardarAsistenciaFinalMasiva = async () => {
    if (!totalClasesCuatrimestre.value || totalClasesCuatrimestre.value < 1) {
        alert('Por favor ingresa el total de clases del cuatrimestre');
        return;
    }

    guardandoAsistenciaFinal.value = true;

    try {
        // Preparar datos
        const asistenciasArray = Object.keys(asistenciasFinal.value).map(alumnoId => ({
            alumno_id: parseInt(alumnoId),
            presentes: parseInt(asistenciasFinal.value[alumnoId].presentes) || 0,
            ausentes: parseInt(asistenciasFinal.value[alumnoId].ausentes) || 0,
            tardes: parseInt(asistenciasFinal.value[alumnoId].tardes) || 0,
            observaciones: asistenciasFinal.value[alumnoId].observaciones || null
        }));

        const response = await axios.post(route('expediente.guardar-asistencia-final'), {
            profesor_materia_id: props.profesorMateria.id,
            total_clases: parseInt(totalClasesCuatrimestre.value),
            asistencias: asistenciasArray
        });

        alert(response.data.message + '\n\nRecarga la página para ver los cambios reflejados.');
        cerrarModalAsistenciaFinal();

        // Recargar la página para ver los cambios
        window.location.reload();
    } catch (error) {
        console.error(error);
        if (error.response && error.response.data && error.response.data.message) {
            alert('Error: ' + error.response.data.message);
        } else {
            alert('Error al guardar la asistencia final');
        }
    } finally {
        guardandoAsistenciaFinal.value = false;
    }
};

// Helper para auto-calcular ausentes
const autoCalcularAusentes = (alumnoId) => {
    const asist = asistenciasFinal.value[alumnoId];
    const total = parseInt(totalClasesCuatrimestre.value) || 0;
    const presentes = parseInt(asist.presentes) || 0;
    const tardes = parseInt(asist.tardes) || 0;

    const calculados = presentes + tardes;
    if (calculados <= total) {
        asist.ausentes = total - calculados;
    }
};

// Funciones para modal de Notas Finales
const abrirModalNotasFinales = () => {
    // Inicializar valores buscando si ya existen notas finales
    notasFinales.value = {};

    props.alumnos.forEach(alumno => {
        // Buscar si ya tiene nota final cargada
        const notaExistente = props.notasTemporales.find(
            n => n.alumno_id === alumno.id && n.tipo_evaluacion === 'Final'
        );

        notasFinales.value[alumno.id] = {
            nota: notaExistente ? notaExistente.nota : '',
            observaciones: notaExistente ? notaExistente.observaciones : ''
        };
    });

    fechaNotasFinales.value = new Date().toISOString().split('T')[0];
    modalNotasFinales.value = true;
};

const cerrarModalNotasFinales = () => {
    modalNotasFinales.value = false;
};

const guardarNotasFinalesMasivas = async () => {
    if (!fechaNotasFinales.value) {
        alert('Por favor selecciona una fecha');
        return;
    }

    // Filtrar solo las notas que fueron ingresadas
    const notasIngresadas = Object.keys(notasFinales.value)
        .filter(alumnoId => notasFinales.value[alumnoId].nota !== '')
        .map(alumnoId => ({
            alumno_id: parseInt(alumnoId),
            nota: parseFloat(notasFinales.value[alumnoId].nota),
            observaciones: notasFinales.value[alumnoId].observaciones || null
        }));

    if (notasIngresadas.length === 0) {
        alert('Debes ingresar al menos una nota');
        return;
    }

    guardandoNotasFinales.value = true;

    try {
        const response = await axios.post(route('expediente.guardar-notas-finales'), {
            profesor_materia_id: props.profesorMateria.id,
            fecha: fechaNotasFinales.value,
            notas: notasIngresadas
        });

        alert(response.data.message + '\n\nRecarga la página para ver los cambios reflejados.');
        cerrarModalNotasFinales();

        // Recargar la página para ver los cambios
        window.location.reload();
    } catch (error) {
        console.error(error);
        if (error.response && error.response.data && error.response.data.message) {
            alert('Error: ' + error.response.data.message);
        } else {
            alert('Error al guardar las notas finales');
        }
    } finally {
        guardandoNotasFinales.value = false;
    }
};
</script>

<template>
    <Head title="Materia" />
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">
                    {{ profesorMateria.materia_nombre }}
                </h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    <i class="bx bx-graduation text-base mr-1"></i>
                    {{ profesorMateria.carrera_nombre }} ·
                    Cursado: {{ profesorMateria.cursado }} ·
                    División: {{ profesorMateria.division }}
                </p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Botón volver -->
            <div class="mb-6">
                <Link
                    :href="route('expediente.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors"
                >
                    <i class="bx bx-arrow-back text-lg mr-2"></i>
                    Volver a Expediente
                </Link>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Columna izquierda: Info de la materia -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Información general -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="bx bx-info-circle text-xl text-blue-600 mr-2"></i>
                            Información de la Materia
                        </h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Carrera</label>
                                <p class="text-sm text-gray-900">{{ profesorMateria.carrera_nombre }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Año Cursado</label>
                                <p class="text-sm text-gray-900">{{ profesorMateria.cursado }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">División</label>
                                <p class="text-sm text-gray-900">{{ profesorMateria.division }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Horario</label>
                                <p class="text-sm text-gray-900">{{ profesorMateria.horario || 'No definido' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Aula</label>
                                <p class="text-sm text-gray-900">{{ profesorMateria.aula || 'No definida' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Modalidad</label>
                                <p class="text-sm text-gray-900">Presencial</p>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas básicas -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="bx bx-bar-chart text-xl text-green-600 mr-2"></i>
                            Estadísticas
                        </h2>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <div class="text-3xl font-bold text-blue-600">{{ estadisticas.total_alumnos || 0 }}</div>
                                <div class="text-xs text-gray-600 mt-1">Alumnos Inscriptos</div>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <div class="text-3xl font-bold text-green-600">{{ estadisticas.promedio_asistencia || 0 }}%</div>
                                <div class="text-xs text-gray-600 mt-1">% Asistencia Prom.</div>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <div class="text-3xl font-bold text-purple-600">{{ estadisticas.notas_cargadas || 0 }}</div>
                                <div class="text-xs text-gray-600 mt-1">Notas Cargadas</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: Accesos rápidos -->
                <div class="space-y-6">
                    <!-- Accesos rápidos -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="bx bx-layout text-xl text-orange-600 mr-2"></i>
                            Accesos Rápidos
                        </h2>
                        <div class="space-y-3">
                            <Link
                                :href="route('expediente.index')"
                                class="flex items-center p-3 bg-teal-50 border border-teal-200 rounded-lg hover:bg-teal-100 transition-colors group"
                            >
                                <i class="bx bx-group text-2xl text-teal-600 mr-3"></i>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">Ver Alumnos</div>
                                    <div class="text-xs text-gray-600">Lista completa de inscriptos</div>
                                </div>
                                <i class="bx bx-chevron-right text-gray-400 group-hover:text-teal-600"></i>
                            </Link>

                            <div class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-lg opacity-60">
                                <i class="bx bx-calendar-check text-2xl text-gray-400 mr-3"></i>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-600">Asistencia Diaria</div>
                                    <div class="text-xs text-gray-500">Desde el panel Alumnos</div>
                                </div>
                            </div>

                            <div class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-lg opacity-60">
                                <i class="bx bx-edit text-2xl text-gray-400 mr-3"></i>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-600">Cargar Notas</div>
                                    <div class="text-xs text-gray-500">Desde el panel Alumnos</div>
                                </div>
                            </div>

                            <button
                                @click="abrirModalAsistenciaFinal"
                                class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors group"
                            >
                                <i class="bx bx-check-circle text-2xl text-green-600 mr-3"></i>
                                <div class="flex-1 text-left">
                                    <div class="text-sm font-medium text-gray-900">Asist. Final</div>
                                    <div class="text-xs text-gray-600">Carga masiva del cuatrimestre</div>
                                </div>
                                <i class="bx bx-chevron-right text-gray-400 group-hover:text-green-600"></i>
                            </button>

                            <button
                                @click="abrirModalNotasFinales"
                                class="flex items-center p-3 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors group"
                            >
                                <i class="bx bx-star text-2xl text-purple-600 mr-3"></i>
                                <div class="flex-1 text-left">
                                    <div class="text-sm font-medium text-gray-900">Notas Finales</div>
                                    <div class="text-xs text-gray-600">Carga masiva de finales</div>
                                </div>
                                <i class="bx bx-chevron-right text-gray-400 group-hover:text-purple-600"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Info de clases -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="bx bx-calendar text-xl text-gray-600 mr-2"></i>
                            Información de Cursado
                        </h2>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <span class="text-sm text-gray-700">Total de clases</span>
                                <span class="text-lg font-bold text-blue-600">{{ estadisticas.total_clases || 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                                <span class="text-sm text-gray-700">Notas pendientes</span>
                                <span class="text-lg font-bold text-yellow-600">{{ estadisticas.notas_pendientes || 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <span class="text-sm text-gray-700">Notas aprobadas</span>
                                <span class="text-lg font-bold text-green-600">{{ estadisticas.notas_aprobadas || 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Gestión de Asistencias -->
            <div class="mt-8 bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="bx bx-calendar-check text-2xl text-blue-600 mr-3"></i>
                        Gestión de Asistencias
                    </h2>
                    <div class="text-sm text-gray-600">
                        Total de clases: <strong>{{ estadisticas.total_clases || 0 }}</strong>
                    </div>
                </div>

                <!-- Tabla de asistencias -->
                <div v-if="asistencias && asistencias.length > 0" class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">#</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Alumno</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Presentes</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Tardes</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Ausentes</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">% Asistencia</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(asist, index) in asistencias" :key="asist.alumno_id" class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">{{ index + 1 }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="font-medium text-gray-900">{{ asist.alumno }}</div>
                                    <div class="text-xs text-gray-500">DNI: {{ asist.dni }}</div>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">{{ asist.presentes }}</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">{{ asist.tardes }}</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">{{ asist.ausentes }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span :class="['px-3 py-1 rounded-full text-sm font-semibold', getColorAsistencia(asist.porcentaje)]">
                                        {{ asist.porcentaje }}%
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span :class="['px-3 py-1 rounded-full text-xs font-medium border', getBadgePromocion(asist.estado_promocion)]">
                                        {{ asist.estado_promocion }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Leyenda -->
                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start text-blue-800 text-sm">
                            <i class="bx bx-info-circle mr-2 text-xl flex-shrink-0"></i>
                            <div>
                                <strong>Criterios de promoción:</strong>
                                <ul class="mt-1 ml-4 list-disc">
                                    <li><strong>Apto:</strong> ≥ 80% de asistencia (puede promocionar)</li>
                                    <li><strong>Regular:</strong> 70-79% de asistencia (solo final)</li>
                                    <li><strong>Insuficiente:</strong> < 70% de asistencia (libre)</li>
                                </ul>
                                <p class="mt-2"><em>Nota: Las llegadas tarde cuentan como 0.5 asistencia.</em></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sin asistencias -->
                <div v-else class="text-center py-12 bg-gray-50 rounded-lg">
                    <i class="bx bx-calendar-x text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600">No hay registros de asistencia aún</p>
                    <p class="text-sm text-gray-500 mt-2">Ve al panel de Alumnos para registrar la asistencia diaria</p>
                </div>
            </div>

            <!-- Sección de Gestión de Notas Temporales -->
            <div class="mt-8 bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="bx bx-edit text-2xl text-purple-600 mr-3"></i>
                        Gestión de Notas Temporales
                    </h2>

                    <!-- Filtros -->
                    <div class="flex gap-2">
                        <button
                            @click="filtroNotas = 'todas'"
                            :class="['px-3 py-1 text-sm rounded-lg transition-colors',
                                filtroNotas === 'todas' ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200']"
                        >
                            Todas ({{ notasTemporales.length }})
                        </button>
                        <button
                            @click="filtroNotas = 'pendiente'"
                            :class="['px-3 py-1 text-sm rounded-lg transition-colors',
                                filtroNotas === 'pendiente' ? 'bg-yellow-600 text-white' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200']"
                        >
                            Pendientes ({{ estadisticas.notas_pendientes || 0 }})
                        </button>
                        <button
                            @click="filtroNotas = 'aprobada'"
                            :class="['px-3 py-1 text-sm rounded-lg transition-colors',
                                filtroNotas === 'aprobada' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200']"
                        >
                            Aprobadas ({{ estadisticas.notas_aprobadas || 0 }})
                        </button>
                    </div>
                </div>

                <!-- Tabla de notas -->
                <div v-if="notasFiltradas() && notasFiltradas().length > 0" class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Fecha</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Alumno</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Tipo Evaluación</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Nota</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Estado</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="nota in notasFiltradas()" :key="nota.id" class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">{{ nota.fecha }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="font-medium text-gray-900">{{ nota.alumno }}</div>
                                    <div class="text-xs text-gray-500">DNI: {{ nota.dni }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ nota.tipo_evaluacion }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                        {{ nota.nota }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span :class="['px-3 py-1 rounded-full text-xs font-medium border capitalize', getBadgeNota(nota.estado)]">
                                        {{ nota.estado }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ nota.observaciones || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Sin notas -->
                <div v-else class="text-center py-12 bg-gray-50 rounded-lg">
                    <i class="bx bx-file text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600">No hay notas {{ filtroNotas !== 'todas' ? filtroNotas + 's' : 'cargadas' }} aún</p>
                    <p class="text-sm text-gray-500 mt-2">Ve al panel de Alumnos para cargar notas de evaluaciones</p>
                </div>

                <!-- Info sobre notas temporales -->
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-start text-yellow-800 text-sm">
                        <i class="bx bx-info-circle mr-2 text-xl flex-shrink-0"></i>
                        <div>
                            <strong>Sobre las notas temporales:</strong>
                            <p class="mt-1">Las notas quedan en estado <strong>Pendiente</strong> hasta que sean aprobadas por el administrador o bedel. Una vez aprobadas, se incorporarán al legajo oficial del alumno.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Asistencia Final Masiva -->
        <div v-if="modalAsistenciaFinal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                <!-- Header del modal -->
                <div class="bg-green-600 text-white px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Asistencia Final - Carga Masiva</h3>
                        <p class="text-sm text-green-100 mt-1">
                            {{ profesorMateria.materia_nombre }} - {{ profesorMateria.carrera_nombre }}
                        </p>
                    </div>
                    <button @click="cerrarModalAsistenciaFinal" class="text-white hover:text-gray-200">
                        <i class="bx bx-x text-3xl"></i>
                    </button>
                </div>

                <!-- Contenido del modal -->
                <div class="flex-1 overflow-y-auto p-6">
                    <!-- Total de clases -->
                    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Total de clases del cuatrimestre *
                        </label>
                        <input
                            v-model="totalClasesCuatrimestre"
                            type="number"
                            min="1"
                            class="w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="Ej: 40"
                        />
                        <p class="text-xs text-gray-600 mt-2">
                            <i class="bx bx-info-circle mr-1"></i>
                            Ingresa el total de clases dictadas en el cuatrimestre
                        </p>
                    </div>

                    <!-- Información -->
                    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start text-yellow-800 text-sm">
                            <i class="bx bx-info-circle mr-2 text-xl flex-shrink-0"></i>
                            <div>
                                <strong>Instrucciones:</strong>
                                <ul class="mt-1 ml-4 list-disc">
                                    <li>Ingresa el total de clases del cuatrimestre arriba</li>
                                    <li>Para cada alumno, ingresa la cantidad de presentes y tardes</li>
                                    <li>Los ausentes se calcularán automáticamente</li>
                                    <li>Esta carga masiva reemplaza o complementa las asistencias diarias</li>
                                    <li>Puedes modificar estos datos las veces que necesites</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de alumnos -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse bg-white border border-gray-200">
                            <thead class="bg-gray-100 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">#</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Alumno</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Presentes</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Tardes</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Ausentes</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(alumno, index) in alumnos" :key="alumno.id" class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="font-medium text-gray-900">{{ alumno.apellido }}, {{ alumno.nombre }}</div>
                                        <div class="text-xs text-gray-500">DNI: {{ alumno.dni }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input
                                            v-model="asistenciasFinal[alumno.id].presentes"
                                            @input="autoCalcularAusentes(alumno.id)"
                                            type="number"
                                            min="0"
                                            :max="totalClasesCuatrimestre"
                                            class="w-20 px-2 py-1 border border-gray-300 rounded text-sm text-center focus:ring-2 focus:ring-green-500"
                                        />
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input
                                            v-model="asistenciasFinal[alumno.id].tardes"
                                            @input="autoCalcularAusentes(alumno.id)"
                                            type="number"
                                            min="0"
                                            :max="totalClasesCuatrimestre"
                                            class="w-20 px-2 py-1 border border-gray-300 rounded text-sm text-center focus:ring-2 focus:ring-green-500"
                                        />
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input
                                            v-model="asistenciasFinal[alumno.id].ausentes"
                                            type="number"
                                            min="0"
                                            :max="totalClasesCuatrimestre"
                                            class="w-20 px-2 py-1 border border-gray-300 rounded text-sm text-center bg-gray-50 focus:ring-2 focus:ring-green-500"
                                        />
                                    </td>
                                    <td class="px-4 py-3">
                                        <input
                                            v-model="asistenciasFinal[alumno.id].observaciones"
                                            type="text"
                                            maxlength="500"
                                            placeholder="Opcional"
                                            class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500"
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
                        @click="cerrarModalAsistenciaFinal"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="guardarAsistenciaFinalMasiva"
                        :disabled="guardandoAsistenciaFinal"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                    >
                        <i :class="['bx mr-2', guardandoAsistenciaFinal ? 'bx-loader-alt animate-spin' : 'bx-save']"></i>
                        {{ guardandoAsistenciaFinal ? 'Guardando...' : 'Guardar Asistencia Final' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de Notas Finales Masivas -->
        <div v-if="modalNotasFinales" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-5xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                <!-- Header del modal -->
                <div class="bg-purple-600 text-white px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Notas Finales - Carga Masiva</h3>
                        <p class="text-sm text-purple-100 mt-1">
                            {{ profesorMateria.materia_nombre }} - {{ profesorMateria.carrera_nombre }}
                        </p>
                    </div>
                    <button @click="cerrarModalNotasFinales" class="text-white hover:text-gray-200">
                        <i class="bx bx-x text-3xl"></i>
                    </button>
                </div>

                <!-- Contenido del modal -->
                <div class="flex-1 overflow-y-auto p-6">
                    <!-- Fecha de examen -->
                    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha del examen final *
                        </label>
                        <input
                            v-model="fechaNotasFinales"
                            type="date"
                            class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        />
                        <p class="text-xs text-gray-600 mt-2">
                            <i class="bx bx-info-circle mr-1"></i>
                            Fecha en la que se tomó el examen final
                        </p>
                    </div>

                    <!-- Información -->
                    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start text-yellow-800 text-sm">
                            <i class="bx bx-info-circle mr-2 text-xl flex-shrink-0"></i>
                            <div>
                                <strong>Instrucciones:</strong>
                                <ul class="mt-1 ml-4 list-disc">
                                    <li>Ingresa la fecha del examen final arriba</li>
                                    <li>Para cada alumno, ingresa la nota final (1-10)</li>
                                    <li>Solo carga las notas de los alumnos que rindieron</li>
                                    <li>Las notas quedarán en estado "Pendiente" hasta ser aprobadas</li>
                                    <li>Puedes modificar estas notas antes de la aprobación</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de alumnos -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse bg-white border border-gray-200">
                            <thead class="bg-gray-100 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">#</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Alumno</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Nota Final (1-10)</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(alumno, index) in alumnos" :key="alumno.id" class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="font-medium text-gray-900">{{ alumno.apellido }}, {{ alumno.nombre }}</div>
                                        <div class="text-xs text-gray-500">DNI: {{ alumno.dni }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input
                                            v-model="notasFinales[alumno.id].nota"
                                            type="number"
                                            min="1"
                                            max="10"
                                            step="0.01"
                                            placeholder="-"
                                            class="w-24 px-2 py-1 border border-gray-300 rounded text-sm text-center focus:ring-2 focus:ring-purple-500"
                                        />
                                    </td>
                                    <td class="px-4 py-3">
                                        <input
                                            v-model="notasFinales[alumno.id].observaciones"
                                            type="text"
                                            maxlength="500"
                                            placeholder="Opcional"
                                            class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-purple-500"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Nota sobre aprobación -->
                    <div class="mt-4 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                        <div class="flex items-start text-purple-800 text-sm">
                            <i class="bx bx-lock mr-2 text-xl flex-shrink-0"></i>
                            <div>
                                <strong>Aprobación requerida:</strong>
                                <p class="mt-1">Las notas quedarán en estado "Pendiente" hasta que sean aprobadas por el administrador o bedel. Una vez aprobadas, se incorporarán al legajo oficial del alumno.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer del modal -->
                <div class="bg-gray-100 px-6 py-4 flex justify-end gap-3">
                    <button
                        @click="cerrarModalNotasFinales"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="guardarNotasFinalesMasivas"
                        :disabled="guardandoNotasFinales"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                    >
                        <i :class="['bx mr-2', guardandoNotasFinales ? 'bx-loader-alt animate-spin' : 'bx-save']"></i>
                        {{ guardandoNotasFinales ? 'Guardando...' : 'Guardar Notas Finales' }}
                    </button>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
