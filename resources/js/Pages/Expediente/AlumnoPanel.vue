<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    alumno: Object,
    carrera: Object,
    estadisticas: Object,
    historial: Array,
    materias_actuales: Array,
});

const vistaActual = ref('resumen'); // resumen, historial, actuales

// Obtener color según el estado de la materia
const obtenerColorEstado = (estado) => {
    const colores = {
        'aprobada': 'bg-green-100 text-green-700',
        'regular': 'bg-blue-100 text-blue-700',
        'cursando': 'bg-yellow-100 text-yellow-700',
    };
    return colores[estado] || 'bg-gray-100 text-gray-700';
};

// Obtener texto legible del estado
const obtenerTextoEstado = (estado) => {
    const textos = {
        'aprobada': 'Aprobada',
        'regular': 'Regular',
        'cursando': 'Cursando',
    };
    return textos[estado] || estado;
};

// Obtener color del porcentaje de asistencia
const obtenerColorAsistencia = (porcentaje) => {
    if (porcentaje >= 80) return 'bg-green-500';
    if (porcentaje >= 60) return 'bg-yellow-500';
    return 'bg-red-500';
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
            <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ alumno.nombre_completo }}</h2>
                        <div class="mt-2 flex flex-wrap gap-4 text-sm text-gray-600">
                            <div class="flex items-center gap-1">
                                <i class='bx bx-id-card'></i>
                                <span>DNI: {{ alumno.dni }}</span>
                            </div>
                            <div v-if="alumno.legajo" class="flex items-center gap-1">
                                <i class='bx bx-file'></i>
                                <span>Legajo: {{ alumno.legajo }}</span>
                            </div>
                            <div v-if="carrera" class="flex items-center gap-1">
                                <i class='bx bx-book'></i>
                                <span>{{ carrera.nombre }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Métricas - Compacto en móvil -->
            <div class="grid grid-cols-4 md:grid-cols-4 gap-2 md:gap-4 mb-6">
                <!-- Total Materias -->
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-2 md:p-5 text-white">
                    <div class="text-center md:text-left">
                        <i class="bx bx-book-open text-xl md:text-3xl opacity-80"></i>
                        <p class="text-lg md:text-3xl font-bold mt-1">{{ estadisticas.total_materias }}</p>
                        <p class="text-[8px] md:text-xs opacity-90 truncate">Total</p>
                    </div>
                </div>

                <!-- Aprobadas -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-2 md:p-5 text-white">
                    <div class="text-center md:text-left">
                        <i class="bx bx-check-circle text-xl md:text-3xl opacity-80"></i>
                        <p class="text-lg md:text-3xl font-bold mt-1">{{ estadisticas.aprobadas }}</p>
                        <p class="text-[8px] md:text-xs opacity-90 truncate">Aprob.</p>
                    </div>
                </div>

                <!-- Regulares -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-2 md:p-5 text-white">
                    <div class="text-center md:text-left">
                        <i class="bx bx-time-five text-xl md:text-3xl opacity-80"></i>
                        <p class="text-lg md:text-3xl font-bold mt-1">{{ estadisticas.regulares }}</p>
                        <p class="text-[8px] md:text-xs opacity-90 truncate">Regul.</p>
                    </div>
                </div>

                <!-- Promedio -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-2 md:p-5 text-white">
                    <div class="text-center md:text-left">
                        <i class="bx bx-trophy text-xl md:text-3xl opacity-80"></i>
                        <p class="text-lg md:text-3xl font-bold mt-1">
                            {{ estadisticas.promedio ? estadisticas.promedio.toFixed(2) : 'N/A' }}
                        </p>
                        <p class="text-[8px] md:text-xs opacity-90 truncate">Prom.</p>
                    </div>
                </div>
            </div>

            <!-- Pestañas -->
            <div class="bg-white rounded-lg border border-gray-200 mb-6">
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
                            <span v-if="materias_actuales.length > 0" class="bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full text-xs font-bold">
                                {{ materias_actuales.length }}
                            </span>
                        </button>
                    </nav>
                </div>

                <!-- Contenido de las Pestañas -->
                <div class="p-6">
                    <!-- Vista Resumen -->
                    <div v-if="vistaActual === 'resumen'">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class='bx bx-info-circle text-xl mr-2 text-blue-600'></i>
                            Información General
                        </h3>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <p class="text-sm text-blue-800">
                                Has completado <strong>{{ estadisticas.aprobadas }}</strong> de <strong>{{ estadisticas.total_materias }}</strong> materias en tu historial académico.
                                {{ estadisticas.promedio ? `Tu promedio actual es de ${estadisticas.promedio.toFixed(2)}.` : '' }}
                            </p>
                            <p v-if="estadisticas.regulares > 0" class="text-sm text-blue-800 mt-2">
                                Tienes <strong>{{ estadisticas.regulares }}</strong> materia{{ estadisticas.regulares > 1 ? 's' : '' }}
                                regularizada{{ estadisticas.regulares > 1 ? 's' : '' }} pendiente{{ estadisticas.regulares > 1 ? 's' : '' }} de rendir.
                            </p>
                        </div>

                        <!-- Últimas 5 materias -->
                        <h4 class="font-semibold text-gray-800 mb-3">Últimas Materias</h4>
                        <div class="space-y-3">
                            <div
                                v-for="materia in historial.slice(0, 5)"
                                :key="materia.id"
                                class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition"
                            >
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h5 class="font-semibold text-gray-800">{{ materia.materia.nombre }}</h5>
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
                        </div>
                    </div>

                    <!-- Vista Historial Completo -->
                    <div v-if="vistaActual === 'historial'">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class='bx bx-history text-xl mr-2 text-blue-600'></i>
                                Historial Completo
                            </h3>
                            <span class="text-sm text-gray-500">{{ historial.length }} materias</span>
                        </div>

                        <div v-if="historial.length === 0" class="text-center py-12 text-gray-500">
                            <i class='bx bx-book-open text-6xl mb-4'></i>
                            <p>No tienes materias en tu historial académico</p>
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
                                    <tr v-for="materia in historial" :key="materia.id" class="hover:bg-gray-50">
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
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class='bx bx-calendar text-xl mr-2 text-blue-600'></i>
                                Materias del Cuatrimestre Actual
                            </h3>
                        </div>

                        <div v-if="materias_actuales.length === 0" class="text-center py-12 text-gray-500">
                            <i class='bx bx-calendar-x text-6xl mb-4'></i>
                            <p>No tienes materias inscritas en el cuatrimestre actual</p>
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                            <div
                                v-for="item in materias_actuales"
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

                                <!-- Asistencias -->
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
