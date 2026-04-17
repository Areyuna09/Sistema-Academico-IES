<script setup>
import { ref, computed } from 'vue';

defineOptions({ inheritAttrs: false });

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    alumno: {
        type: Object,
        default: null
    },
    // Props legacy (compatibilidad — se usan cuando carreras[] está vacío)
    carrera: {
        type: Object,
        default: null
    },
    estadisticas: {
        type: Object,
        default: () => ({})
    },
    historial: {
        type: Array,
        default: () => []
    },
    materias_actuales: {
        type: Array,
        default: () => []
    },
    // Array con todas las carreras del alumno.
    // Cada elemento: { nombre, carrera_id, alumno_id, carrera, estadisticas, historial, materias_actuales }
    carreras: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['close']);

const vistaActual = ref('resumen');
const carreraSeleccionadaIdx = ref(0);

// Hay múltiples carreras cuando el array tiene más de un elemento
const modoMultiCarrera = computed(() => props.carreras && props.carreras.length > 1);

// Carrera activa según el índice seleccionado
const carreraActiva = computed(() => {
    if (props.carreras && props.carreras.length > 0) {
        return props.carreras[carreraSeleccionadaIdx.value];
    }
    // Modo legacy: armar objeto compatible desde props individuales
    return props.carrera
        ? {
            nombre: props.carrera.nombre,
            carrera: props.carrera,
            estadisticas: props.estadisticas,
            historial: props.historial,
            materias_actuales: props.materias_actuales
          }
        : null;
});

// Datos reactivos según la carrera activa
const estadisticasActivas     = computed(() => carreraActiva.value?.estadisticas     ?? props.estadisticas     ?? {});
const historialActivo         = computed(() => carreraActiva.value?.historial         ?? props.historial         ?? []);
const materiasActualesActivas = computed(() => carreraActiva.value?.materias_actuales ?? props.materias_actuales ?? []);

const seleccionarCarrera = (idx) => {
    carreraSeleccionadaIdx.value = idx;
    vistaActual.value = 'resumen';
};

const obtenerColorEstado = (estado) => {
    const colores = {
        'aprobada': 'bg-green-100 text-green-700',
        'regular':  'bg-blue-100 text-blue-700',
        'cursando': 'bg-yellow-100 text-yellow-700',
    };
    return colores[estado] || 'bg-gray-100 text-gray-700';
};

const obtenerTextoEstado = (estado) => {
    const textos = {
        'aprobada': 'Aprobada',
        'regular':  'Regular',
        'cursando': 'Cursando',
    };
    return textos[estado] || estado;
};

const obtenerColorAsistencia = (porcentaje) => {
    if (porcentaje >= 80) return 'bg-green-500';
    if (porcentaje >= 60) return 'bg-yellow-500';
    return 'bg-red-500';
};
</script>

<template>
<div>
    <!-- Backdrop -->
    <Transition
        enter-active-class="transition-opacity duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="show"
            class="fixed inset-0 bg-black bg-opacity-50 z-40"
            @click="$emit('close')"
        ></div>
    </Transition>

    <!-- Modal -->
    <Transition
        enter-active-class="transition-all duration-200"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition-all duration-200"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div
            v-if="show"
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
        >
            <div
                class="bg-gray-50 rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto"
                @click.stop
            >
                <!-- Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10 rounded-t-lg">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Expediente Académico</h2>
                        <p class="text-sm text-gray-500">{{ alumno?.nombre_completo }}</p>
                    </div>
                    <button
                        @click="$emit('close')"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <i class="bx bx-x text-3xl"></i>
                    </button>
                </div>

                <!-- Contenido -->
                <div class="p-6">
                    <!-- Información del Alumno -->
                    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
                        <div class="flex items-start justify-between gap-4 flex-wrap">
                            <div class="min-w-0 flex-1">
                                <h3 class="text-2xl font-bold text-gray-900">{{ alumno?.nombre_completo }}</h3>
                                <div class="mt-2 flex flex-wrap gap-4 text-sm text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <i class='bx bx-id-card'></i>
                                        <span>DNI: {{ alumno?.dni }}</span>
                                    </div>
                                    <div v-if="alumno?.legajo" class="flex items-center gap-1">
                                        <i class='bx bx-file'></i>
                                        <span>Legajo: {{ alumno.legajo }}</span>
                                    </div>
                                    <!-- Carrera activa en modo una sola carrera -->
                                    <div v-if="!modoMultiCarrera && carreraActiva" class="flex items-center gap-1">
                                        <i class='bx bx-book'></i>
                                        <span>{{ carreraActiva.nombre }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selector de carrera: solo cuando hay más de una -->
                        <div v-if="modoMultiCarrera" class="mt-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                                <i class="bx bx-transfer-alt mr-1"></i>
                                Carreras del alumno
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    v-for="(c, idx) in carreras"
                                    :key="idx"
                                    @click="seleccionarCarrera(idx)"
                                    :class="[
                                        'px-3 py-1.5 rounded-lg text-sm font-medium border transition-all duration-150',
                                        carreraSeleccionadaIdx === idx
                                            ? 'bg-blue-600 text-white border-blue-600 shadow-sm'
                                            : 'bg-white text-gray-700 border-gray-300 hover:border-blue-400 hover:text-blue-600'
                                    ]"
                                >
                                    <i class="bx bx-book mr-1"></i>
                                    {{ c.nombre }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Métricas (reactivas a la carrera seleccionada) -->
                    <div class="grid grid-cols-4 gap-4 mb-6">
                        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-5 text-white">
                            <i class="bx bx-book-open text-3xl opacity-80"></i>
                            <p class="text-3xl font-bold mt-1">{{ estadisticasActivas?.total_materias || 0 }}</p>
                            <p class="text-xs opacity-90">Total</p>
                        </div>
                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-5 text-white">
                            <i class="bx bx-check-circle text-3xl opacity-80"></i>
                            <p class="text-3xl font-bold mt-1">{{ estadisticasActivas?.aprobadas || 0 }}</p>
                            <p class="text-xs opacity-90">Aprob.</p>
                        </div>
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-5 text-white">
                            <i class="bx bx-time-five text-3xl opacity-80"></i>
                            <p class="text-3xl font-bold mt-1">{{ estadisticasActivas?.regulares || 0 }}</p>
                            <p class="text-xs opacity-90">Regul.</p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-5 text-white">
                            <i class="bx bx-trophy text-3xl opacity-80"></i>
                            <p class="text-3xl font-bold mt-1">
                                {{ estadisticasActivas?.promedio ? estadisticasActivas.promedio.toFixed(2) : 'N/A' }}
                            </p>
                            <p class="text-xs opacity-90">Prom.</p>
                        </div>
                    </div>

                    <!-- Pestañas -->
                    <div class="bg-white rounded-lg border border-gray-200">
                        <div class="border-b border-gray-200">
                            <nav class="flex space-x-6 px-6" aria-label="Tabs">
                                <button
                                    @click="vistaActual = 'resumen'"
                                    :class="[
                                        vistaActual === 'resumen'
                                            ? 'border-blue-500 text-blue-600'
                                            : 'border-transparent text-gray-500 hover:text-gray-700',
                                        'py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2'
                                    ]"
                                >
                                    <i class='bx bx-chart'></i>
                                    Resumen
                                    <span v-if="modoMultiCarrera" class="text-xs font-normal text-indigo-500">
                                        · {{ carreraActiva?.nombre }}
                                    </span>
                                </button>
                                <button
                                    @click="vistaActual = 'historial'"
                                    :class="[
                                        vistaActual === 'historial'
                                            ? 'border-blue-500 text-blue-600'
                                            : 'border-transparent text-gray-500 hover:text-gray-700',
                                        'py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2'
                                    ]"
                                >
                                    <i class='bx bx-history'></i>
                                    Historial Completo
                                </button>
                                <button
                                    @click="vistaActual = 'actuales'"
                                    :class="[
                                        vistaActual === 'actuales'
                                            ? 'border-blue-500 text-blue-600'
                                            : 'border-transparent text-gray-500 hover:text-gray-700',
                                        'py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2'
                                    ]"
                                >
                                    <i class='bx bx-calendar'></i>
                                    Materias Actuales
                                    <span v-if="materiasActualesActivas.length > 0" class="bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full text-xs font-bold">
                                        {{ materiasActualesActivas.length }}
                                    </span>
                                </button>
                            </nav>
                        </div>

                        <!-- Contenido de las Pestañas -->
                        <div class="p-6">

                            <!-- Vista Resumen -->
                            <div v-if="vistaActual === 'resumen'">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class='bx bx-info-circle text-xl mr-2 text-blue-600'></i>
                                    Información General
                                </h4>

                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                    <p class="text-sm text-blue-800">
                                        Ha completado <strong>{{ estadisticasActivas?.aprobadas || 0 }}</strong> de
                                        <strong>{{ estadisticasActivas?.total_materias || 0 }}</strong> materias en el historial académico.
                                        {{ estadisticasActivas?.promedio ? `Promedio: ${estadisticasActivas.promedio.toFixed(2)}.` : '' }}
                                    </p>
                                    <p v-if="(estadisticasActivas?.regulares || 0) > 0" class="text-sm text-blue-800 mt-2">
                                        Tiene <strong>{{ estadisticasActivas.regulares }}</strong>
                                        materia{{ estadisticasActivas.regulares > 1 ? 's' : '' }}
                                        regularizada{{ estadisticasActivas.regulares > 1 ? 's' : '' }}
                                        pendiente{{ estadisticasActivas.regulares > 1 ? 's' : '' }} de rendir.
                                    </p>
                                </div>

                                <h5 class="font-semibold text-gray-800 mb-3">Últimas Materias</h5>
                                <div class="space-y-3">
                                    <div
                                        v-for="materia in historialActivo.slice(0, 5)"
                                        :key="materia.id"
                                        class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition"
                                    >
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h6 class="font-semibold text-gray-800">{{ materia.materia.nombre }}</h6>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    {{ materia.materia.anno }}° Año - {{ materia.materia.semestre === 1 ? '1er' : '2do' }} Cuatr.
                                                </p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <div v-if="materia.nota_final" class="text-center">
                                                    <p class="text-2xl font-bold text-blue-600">{{ materia.nota_final }}</p>
                                                    <p class="text-xs text-gray-500">Nota</p>
                                                </div>
                                                <span :class="['px-3 py-1 rounded-full text-xs font-semibold', obtenerColorEstado(materia.estado)]">
                                                    {{ obtenerTextoEstado(materia.estado) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="historialActivo.length === 0" class="text-center py-8 text-gray-500 text-sm">
                                        Sin materias en el historial de esta carrera
                                    </div>
                                </div>
                            </div>

                            <!-- Vista Historial Completo -->
                            <div v-if="vistaActual === 'historial'">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <i class='bx bx-history text-xl mr-2 text-blue-600'></i>
                                        Historial Completo
                                        <span v-if="modoMultiCarrera" class="ml-2 text-xs font-normal text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                                            {{ carreraActiva?.nombre }}
                                        </span>
                                    </h4>
                                    <span class="text-sm text-gray-500">{{ historialActivo.length }} materias</span>
                                </div>

                                <div v-if="historialActivo.length === 0" class="text-center py-12 text-gray-500">
                                    <i class='bx bx-book-open text-6xl mb-4'></i>
                                    <p>No hay materias en el historial de esta carrera</p>
                                </div>

                                <div v-else class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materia</th>
                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Año/Cuatr.</th>
                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Cursada</th>
                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Final</th>
                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="materia in historialActivo" :key="materia.id" class="hover:bg-gray-50">
                                                <td class="px-4 py-4">
                                                    <div class="font-medium text-gray-900">{{ materia.materia.nombre }}</div>
                                                </td>
                                                <td class="px-4 py-4 text-center text-sm text-gray-600">
                                                    {{ materia.materia.anno }}° - {{ materia.materia.semestre === 1 ? '1er' : '2do' }}
                                                </td>
                                                <td class="px-4 py-4 text-center">
                                                    <span v-if="materia.calificacion_cursada" class="text-lg font-semibold text-blue-600">
                                                        {{ materia.calificacion_cursada }}
                                                    </span>
                                                    <span v-else class="text-gray-400">-</span>
                                                </td>
                                                <td class="px-4 py-4 text-center">
                                                    <span v-if="materia.nota_final" class="text-lg font-bold text-green-600">
                                                        {{ materia.nota_final }}
                                                    </span>
                                                    <span v-else class="text-gray-400">-</span>
                                                </td>
                                                <td class="px-4 py-4 text-center">
                                                    <span :class="['inline-flex px-2 py-1 rounded-full text-xs font-semibold', obtenerColorEstado(materia.estado)]">
                                                        {{ obtenerTextoEstado(materia.estado) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 text-center text-sm text-gray-600">
                                                    {{ materia.fecha || '-' }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Vista Materias Actuales -->
                            <div v-if="vistaActual === 'actuales'">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <i class='bx bx-calendar text-xl mr-2 text-blue-600'></i>
                                        Materias del Cuatrimestre Actual
                                        <span v-if="modoMultiCarrera" class="ml-2 text-xs font-normal text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                                            {{ carreraActiva?.nombre }}
                                        </span>
                                    </h4>
                                </div>

                                <div v-if="materiasActualesActivas.length === 0" class="text-center py-12 text-gray-500">
                                    <i class='bx bx-calendar-x text-6xl mb-4'></i>
                                    <p>No hay materias inscritas en esta carrera</p>
                                </div>

                                <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div
                                        v-for="item in materiasActualesActivas"
                                        :key="item.materia.id"
                                        class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition"
                                    >
                                        <div class="flex items-start justify-between mb-3 gap-2">
                                            <div class="min-w-0 flex-1">
                                                <h5 class="font-semibold text-gray-800 text-base truncate">{{ item.materia.nombre }}</h5>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    {{ item.materia.anno }}° Año - {{ item.materia.semestre === 1 ? '1er' : '2do' }} Cuatr.
                                                </p>
                                            </div>
                                            <i class='bx bx-book-open text-2xl text-blue-600 flex-shrink-0'></i>
                                        </div>

                                        <div v-if="item.asistencia.total_clases > 0" class="bg-gray-50 rounded-lg p-3">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-medium text-gray-600">Asistencias</span>
                                                <span
                                                    class="text-lg font-bold"
                                                    :class="item.asistencia.porcentaje >= 80 ? 'text-green-600' : item.asistencia.porcentaje >= 60 ? 'text-yellow-600' : 'text-red-600'"
                                                >
                                                    {{ item.asistencia.porcentaje }}%
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                                <div
                                                    :class="['h-2 rounded-full', obtenerColorAsistencia(item.asistencia.porcentaje)]"
                                                    :style="`width: ${item.asistencia.porcentaje}%`"
                                                ></div>
                                            </div>
                                            <p class="text-xs text-gray-600">
                                                {{ item.asistencia.presentes }} de {{ item.asistencia.total_clases }} clases
                                            </p>
                                        </div>
                                        <div v-else class="bg-gray-50 rounded-lg p-3 text-center text-sm text-gray-500">
                                            Sin registros de asistencia
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</div>
</template>