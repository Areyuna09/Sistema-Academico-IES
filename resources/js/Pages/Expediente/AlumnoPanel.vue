<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    alumno: Object,
    // carrera puede ser un objeto único (legacy) o null cuando se usan carreras[]
    carrera: Object,
    // carreras[] se usa cuando el alumno está en múltiples carreras
    // cada elemento: { id, nombre, estadisticas, historial, materias_actuales }
    carreras: {
        type: Array,
        default: () => []
    },
    estadisticas: Object,
    historial: Array,
    materias_actuales: Array,
});

// Índice de la carrera actualmente seleccionada (para el selector)
const carreraSeleccionadaIdx = ref(0);

// Si viene más de una carrera en el array, usamos el modo multi-carrera
const modoMultiCarrera = computed(() => props.carreras && props.carreras.length > 1);

// Carrera activa según el modo
const carreraActiva = computed(() => {
    if (modoMultiCarrera.value) {
        return props.carreras[carreraSeleccionadaIdx.value];
    }
    // Compatibilidad con el modo legacy (prop carrera única)
    if (props.carreras && props.carreras.length === 1) {
        return props.carreras[0];
    }
    return props.carrera ? { nombre: props.carrera.nombre, estadisticas: props.estadisticas, historial: props.historial, materias_actuales: props.materias_actuales } : null;
});

// Datos activos según la carrera seleccionada
const estadisticasActivas = computed(() => carreraActiva.value?.estadisticas ?? props.estadisticas ?? {});
const historialActivo = computed(() => carreraActiva.value?.historial ?? props.historial ?? []);
const materiasActualesActivas = computed(() => carreraActiva.value?.materias_actuales ?? props.materias_actuales ?? []);

const vistaActual = ref('resumen');

const obtenerColorEstado = (estado) => {
    const colores = {
        'aprobada': 'bg-green-100 text-green-700',
        'regular': 'bg-blue-100 text-blue-700',
        'cursando': 'bg-yellow-100 text-yellow-700',
    };
    return colores[estado] || 'bg-gray-100 text-gray-700';
};

const obtenerTextoEstado = (estado) => {
    const textos = {
        'aprobada': 'Aprobada',
        'regular': 'Regular',
        'cursando': 'Cursando',
    };
    return textos[estado] || estado;
};

const obtenerColorAsistencia = (porcentaje) => {
    if (porcentaje >= 80) return 'bg-green-500';
    if (porcentaje >= 60) return 'bg-yellow-500';
    return 'bg-red-500';
};

// Al cambiar de carrera, volver al resumen
const seleccionarCarrera = (idx) => {
    carreraSeleccionadaIdx.value = idx;
    vistaActual.value = 'resumen';
};
</script>

<template>
    <Head title="Mi Expediente" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Mi Expediente Académico</h1>
                <p class="text-xs text-gray-400 mt-0.5">{{ alumno.nombre_completo }}</p>
            </div>
        </template>

        <div class="p-4 md:p-8 max-w-7xl mx-auto overflow-hidden">

            <!-- Información del Alumno -->
            <div class="bg-white rounded-lg p-3 md:p-6 mb-4 md:mb-6 border border-gray-200">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg md:text-2xl font-bold text-gray-900 truncate">{{ alumno.nombre_completo }}</h2>
                        <div class="mt-1 md:mt-2 flex flex-wrap gap-2 md:gap-4 text-xs md:text-sm text-gray-600">
                            <div class="flex items-center gap-1">
                                <i class='bx bx-id-card text-sm md:text-base'></i>
                                <span>DNI: {{ alumno.dni }}</span>
                            </div>
                            <div v-if="alumno.legajo" class="flex items-center gap-1">
                                <i class='bx bx-file text-sm md:text-base'></i>
                                <span>Legajo: {{ alumno.legajo }}</span>
                            </div>
                            <!-- Carrera única (modo legacy) -->
                            <div v-if="!modoMultiCarrera && carreraActiva" class="flex items-center gap-1 w-full sm:w-auto">
                                <i class='bx bx-book text-sm md:text-base'></i>
                                <span class="truncate">{{ carreraActiva.nombre }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selector de carrera (solo cuando hay más de una) -->
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

            <!-- Métricas -->
            <div class="grid grid-cols-4 gap-2 md:gap-4 mb-6">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-2 md:p-5 text-white">
                    <div class="text-center md:text-left">
                        <i class="bx bx-book-open text-xl md:text-3xl opacity-80"></i>
                        <p class="text-lg md:text-3xl font-bold mt-1">{{ estadisticasActivas.total_materias }}</p>
                        <p class="text-[8px] md:text-xs opacity-90 truncate">Total</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-2 md:p-5 text-white">
                    <div class="text-center md:text-left">
                        <i class="bx bx-check-circle text-xl md:text-3xl opacity-80"></i>
                        <p class="text-lg md:text-3xl font-bold mt-1">{{ estadisticasActivas.aprobadas }}</p>
                        <p class="text-[8px] md:text-xs opacity-90 truncate">Aprob.</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-2 md:p-5 text-white">
                    <div class="text-center md:text-left">
                        <i class="bx bx-time-five text-xl md:text-3xl opacity-80"></i>
                        <p class="text-lg md:text-3xl font-bold mt-1">{{ estadisticasActivas.regulares }}</p>
                        <p class="text-[8px] md:text-xs opacity-90 truncate">Regul.</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-2 md:p-5 text-white">
                    <div class="text-center md:text-left">
                        <i class="bx bx-trophy text-xl md:text-3xl opacity-80"></i>
                        <p class="text-lg md:text-3xl font-bold mt-1">
                            {{ estadisticasActivas.promedio ? estadisticasActivas.promedio.toFixed(2) : 'N/A' }}
                        </p>
                        <p class="text-[8px] md:text-xs opacity-90 truncate">Prom.</p>
                    </div>
                </div>
            </div>

            <!-- Pestañas -->
            <div class="bg-white rounded-lg border border-gray-200 mb-4 md:mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-2 md:space-x-6 px-2 md:px-6" aria-label="Tabs">
                        <button
                            @click="vistaActual = 'resumen'"
                            :class="[
                                vistaActual === 'resumen'
                                    ? 'border-blue-500 text-blue-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700',
                                'py-3 md:py-4 px-1 md:px-2 border-b-2 font-medium text-xs md:text-sm flex items-center gap-1 md:gap-2'
                            ]"
                        >
                            <i class='bx bx-chart text-base md:text-lg'></i>
                            <span class="hidden sm:inline">Resumen</span>
                        </button>
                        <button
                            @click="vistaActual = 'historial'"
                            :class="[
                                vistaActual === 'historial'
                                    ? 'border-blue-500 text-blue-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700',
                                'py-3 md:py-4 px-1 md:px-2 border-b-2 font-medium text-xs md:text-sm flex items-center gap-1 md:gap-2'
                            ]"
                        >
                            <i class='bx bx-history text-base md:text-lg'></i>
                            <span class="hidden sm:inline">Historial</span>
                        </button>
                        <button
                            @click="vistaActual = 'actuales'"
                            :class="[
                                vistaActual === 'actuales'
                                    ? 'border-blue-500 text-blue-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700',
                                'py-3 md:py-4 px-1 md:px-2 border-b-2 font-medium text-xs md:text-sm flex items-center gap-1 md:gap-2'
                            ]"
                        >
                            <i class='bx bx-calendar text-base md:text-lg'></i>
                            <span class="hidden sm:inline">Actuales</span>
                            <span v-if="materiasActualesActivas.length > 0" class="bg-blue-100 text-blue-600 px-1.5 md:px-2 py-0.5 rounded-full text-[10px] md:text-xs font-bold">
                                {{ materiasActualesActivas.length }}
                            </span>
                        </button>
                    </nav>
                </div>

                <!-- Contenido de las Pestañas -->
                <div class="p-3 md:p-6">

                    <!-- Vista Resumen -->
                    <div v-if="vistaActual === 'resumen'">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4 flex items-center">
                            <i class='bx bx-info-circle text-lg md:text-xl mr-2 text-blue-600'></i>
                            Información General
                            <span v-if="modoMultiCarrera" class="ml-2 text-xs font-normal text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                                {{ carreraActiva?.nombre }}
                            </span>
                        </h3>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 md:p-4 mb-4 md:mb-6">
                            <p class="text-xs md:text-sm text-blue-800">
                                Has completado <strong>{{ estadisticasActivas.aprobadas }}</strong> de <strong>{{ estadisticasActivas.total_materias }}</strong> materias en tu historial académico.
                                {{ estadisticasActivas.promedio ? `Tu promedio actual es de ${estadisticasActivas.promedio.toFixed(2)}.` : '' }}
                            </p>
                            <p v-if="estadisticasActivas.regulares > 0" class="text-xs md:text-sm text-blue-800 mt-2">
                                Tienes <strong>{{ estadisticasActivas.regulares }}</strong> materia{{ estadisticasActivas.regulares > 1 ? 's' : '' }}
                                regularizada{{ estadisticasActivas.regulares > 1 ? 's' : '' }} pendiente{{ estadisticasActivas.regulares > 1 ? 's' : '' }} de rendir.
                            </p>
                        </div>

                        <h4 class="text-sm md:text-base font-semibold text-gray-800 mb-2 md:mb-3">Últimas Materias</h4>
                        <div class="space-y-2 md:space-y-3">
                            <div
                                v-for="materia in historialActivo.slice(0, 5)"
                                :key="materia.id"
                                class="border border-gray-200 rounded-lg p-3 md:p-4 hover:bg-gray-50 transition"
                            >
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3">
                                    <div class="min-w-0 flex-1">
                                        <h5 class="text-sm md:text-base font-semibold text-gray-800 truncate">{{ materia.materia.nombre }}</h5>
                                        <p class="text-xs md:text-sm text-gray-500 mt-0.5 md:mt-1">
                                            {{ materia.materia.anno }}° Año - {{ materia.materia.semestre === 1 ? '1er' : '2do' }} Cuatr.
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 md:gap-3 flex-shrink-0">
                                        <div v-if="materia.nota_final" class="text-center">
                                            <p class="text-xl md:text-2xl font-bold text-blue-600">{{ materia.nota_final }}</p>
                                            <p class="text-[10px] md:text-xs text-gray-500">Nota</p>
                                        </div>
                                        <span :class="['px-2 md:px-3 py-1 rounded-full text-[10px] md:text-xs font-semibold whitespace-nowrap', obtenerColorEstado(materia.estado)]">
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
                        <div class="flex items-center justify-between mb-3 md:mb-4">
                            <h3 class="text-base md:text-lg font-semibold text-gray-900 flex items-center">
                                <i class='bx bx-history text-lg md:text-xl mr-2 text-blue-600'></i>
                                Historial Completo
                                <span v-if="modoMultiCarrera" class="ml-2 text-xs font-normal text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                                    {{ carreraActiva?.nombre }}
                                </span>
                            </h3>
                            <span class="text-xs md:text-sm text-gray-500">{{ historialActivo.length }} materias</span>
                        </div>

                        <div v-if="historialActivo.length === 0" class="text-center py-12 text-gray-500">
                            <i class='bx bx-book-open text-6xl mb-4'></i>
                            <p class="text-sm md:text-base">No hay materias en el historial de esta carrera</p>
                        </div>

                        <!-- Vista móvil: Tarjetas -->
                        <div v-if="historialActivo.length > 0" class="md:hidden space-y-3">
                            <div
                                v-for="materia in historialActivo"
                                :key="materia.id"
                                class="border border-gray-200 rounded-lg p-3 bg-white"
                            >
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1 min-w-0 pr-2">
                                        <h4 class="font-semibold text-gray-900 text-sm truncate">{{ materia.materia.nombre }}</h4>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ materia.materia.anno }}° - {{ materia.materia.semestre === 1 ? '1er' : '2do' }} Cuatr.
                                        </p>
                                    </div>
                                    <span :class="['px-2 py-0.5 rounded-full text-[10px] font-semibold whitespace-nowrap', obtenerColorEstado(materia.estado)]">
                                        {{ obtenerTextoEstado(materia.estado) }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-3 gap-2 mt-2 pt-2 border-t border-gray-100">
                                    <div class="text-center">
                                        <p class="text-[10px] text-gray-500 mb-0.5">Cursada</p>
                                        <p class="text-sm font-semibold text-blue-600">{{ materia.calificacion_cursada || '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-[10px] text-gray-500 mb-0.5">Final</p>
                                        <p class="text-sm font-bold text-green-600">{{ materia.nota_final || '-' }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-[10px] text-gray-500 mb-0.5">Fecha</p>
                                        <p class="text-[10px] text-gray-600">{{ materia.fecha || '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vista desktop: Tabla -->
                        <div v-if="historialActivo.length > 0" class="hidden md:block overflow-x-auto">
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
                        <div class="flex items-center justify-between mb-3 md:mb-4">
                            <h3 class="text-base md:text-lg font-semibold text-gray-900 flex items-center">
                                <i class='bx bx-calendar text-lg md:text-xl mr-2 text-blue-600'></i>
                                <span class="hidden sm:inline">Materias del Cuatrimestre Actual</span>
                                <span class="sm:hidden">Actuales</span>
                                <span v-if="modoMultiCarrera" class="ml-2 text-xs font-normal text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                                    {{ carreraActiva?.nombre }}
                                </span>
                            </h3>
                        </div>

                        <div v-if="materiasActualesActivas.length === 0" class="text-center py-12 text-gray-500">
                            <i class='bx bx-calendar-x text-5xl md:text-6xl mb-4'></i>
                            <p class="text-sm md:text-base">No hay materias inscritas en el cuatrimestre actual para esta carrera</p>
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                            <div
                                v-for="item in materiasActualesActivas"
                                :key="item.materia.id"
                                class="border border-gray-200 rounded-lg p-3 md:p-4 hover:bg-gray-50 transition overflow-hidden"
                            >
                                <div class="flex items-start justify-between mb-2 md:mb-3 gap-2">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-semibold text-gray-800 text-sm md:text-base truncate">{{ item.materia.nombre }}</h4>
                                        <p class="text-xs md:text-sm text-gray-500 mt-1">
                                            {{ item.materia.anno }}° Año - {{ item.materia.semestre === 1 ? '1er' : '2do' }} Cuatr.
                                        </p>
                                    </div>
                                    <i class='bx bx-book-open text-xl md:text-2xl text-blue-600 flex-shrink-0'></i>
                                </div>

                                <div v-if="item.asistencia.total_clases > 0" class="bg-gray-50 rounded-lg p-2 md:p-3">
                                    <div class="flex items-center justify-between mb-1 md:mb-2">
                                        <span class="text-xs md:text-sm font-medium text-gray-600">Asistencias</span>
                                        <span class="text-sm md:text-lg font-bold" :class="item.asistencia.porcentaje >= 80 ? 'text-green-600' : item.asistencia.porcentaje >= 60 ? 'text-yellow-600' : 'text-red-600'">
                                            {{ item.asistencia.porcentaje }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 md:h-2 mb-1 md:mb-2">
                                        <div
                                            :class="['h-1.5 md:h-2 rounded-full', obtenerColorAsistencia(item.asistencia.porcentaje)]"
                                            :style="`width: ${item.asistencia.porcentaje}%`"
                                        ></div>
                                    </div>
                                    <p class="text-[10px] md:text-xs text-gray-600">
                                        {{ item.asistencia.presentes }} de {{ item.asistencia.total_clases }} clases
                                    </p>
                                </div>
                                <div v-else class="bg-gray-50 rounded-lg p-2 md:p-3 text-center text-xs md:text-sm text-gray-500">
                                    Sin registros de asistencia
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </SidebarLayout>
</template>