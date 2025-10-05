<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    alumno: Object,
    historialPorAnio: Object,
    estadisticas: Object,
});

// Usuarios especiales con estilo VIP (dorado)
const esUsuarioVIP = computed(() => {
    const dniVIPs = ['46180633', '44674217']; // Ramon y Alan
    return dniVIPs.includes(props.alumno?.dni);
});

const vistaActiva = ref('materias'); // 'materias' o 'estadisticas'

const getEstadoBadge = (materia) => {
    if (materia.rendida) {
        return { text: 'Aprobada', class: 'bg-green-100 text-green-700 border-green-200' };
    }
    if (materia.cursada) {
        return { text: 'Regular', class: 'bg-blue-100 text-blue-700 border-blue-200' };
    }
    if (materia.libre) {
        return { text: 'Libre', class: 'bg-orange-100 text-orange-700 border-orange-200' };
    }
    if (materia.equivalencia) {
        return { text: 'Equivalencia', class: 'bg-purple-100 text-purple-700 border-purple-200' };
    }
    return { text: 'Pendiente', class: 'bg-gray-100 text-gray-600 border-gray-200' };
};

const getNotaColor = (nota) => {
    if (!nota) return 'text-gray-400';
    if (nota >= 8) return 'text-green-600 font-bold';
    if (nota >= 6) return 'text-blue-600 font-semibold';
    if (nota >= 4) return 'text-yellow-600 font-medium';
    return 'text-red-600 font-medium';
};

const aniosOrdenados = computed(() => {
    return Object.keys(props.historialPorAnio).sort((a, b) => a - b);
});
</script>

<template>
    <Head title="Mi Expediente Académico" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Mi Expediente Académico</h1>
                <p class="text-xs text-gray-400 mt-0.5">Historial académico y rendimiento</p>
            </div>
        </template>

        <div class="p-4 md:p-8 max-w-7xl mx-auto space-y-4 md:space-y-6">
            <!-- Información del Alumno -->
            <div
                class="rounded-xl shadow-lg p-4 md:p-6 text-white transition-all duration-300"
                :class="esUsuarioVIP
                    ? 'bg-gradient-to-r from-yellow-600 via-amber-500 to-yellow-600 border-2 border-yellow-300'
                    : 'bg-gradient-to-r from-blue-600 to-blue-700'"
            >
                <div class="flex items-start gap-3 md:gap-4">
                    <div
                        class="w-12 h-12 md:w-16 md:h-16 rounded-full flex items-center justify-center flex-shrink-0"
                        :class="esUsuarioVIP
                            ? 'bg-white bg-opacity-30 shadow-lg'
                            : 'bg-white bg-opacity-20'"
                    >
                        <i
                            class="bx text-2xl md:text-4xl"
                            :class="[esUsuarioVIP ? 'bx-crown' : 'bx-user']"
                        ></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="text-base md:text-2xl font-bold">{{ alumno.nombre_completo }}</h2>
                            <span v-if="esUsuarioVIP" class="px-2 md:px-3 py-1 bg-white bg-opacity-90 text-yellow-900 text-[10px] md:text-xs font-bold rounded-full shadow-md">
                                ⭐ VIP DEVELOPER
                            </span>
                        </div>
                        <p
                            class="text-xs md:text-sm mt-1 md:mt-2"
                            :class="esUsuarioVIP ? 'text-yellow-50' : 'text-blue-100'"
                        >
                            DNI {{ alumno.dni }}
                        </p>
                        <p
                            class="text-xs md:text-sm mt-0.5 md:mt-1"
                            :class="esUsuarioVIP ? 'text-yellow-50' : 'text-blue-100'"
                        >
                            {{ alumno.carrera }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Generales -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-2 md:gap-4">
                <div class="bg-white border border-gray-200 rounded-lg p-3 md:p-4">
                    <div class="text-center">
                        <i class="bx bx-book text-blue-600 text-2xl md:text-3xl mb-1 md:mb-2"></i>
                        <p class="text-xs md:text-sm text-gray-500">Total Materias</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-900">{{ estadisticas.total_materias }}</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-3 md:p-4">
                    <div class="text-center">
                        <i class="bx bx-check-circle text-green-600 text-2xl md:text-3xl mb-1 md:mb-2"></i>
                        <p class="text-xs md:text-sm text-gray-500">Aprobadas</p>
                        <p class="text-xl md:text-2xl font-bold text-green-600">{{ estadisticas.aprobadas }}</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-3 md:p-4">
                    <div class="text-center">
                        <i class="bx bx-time-five text-blue-600 text-2xl md:text-3xl mb-1 md:mb-2"></i>
                        <p class="text-xs md:text-sm text-gray-500">Regulares</p>
                        <p class="text-xl md:text-2xl font-bold text-blue-600">{{ estadisticas.regulares }}</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-3 md:p-4">
                    <div class="text-center">
                        <i class="bx bx-trending-up text-purple-600 text-2xl md:text-3xl mb-1 md:mb-2"></i>
                        <p class="text-xs md:text-sm text-gray-500">Promedio</p>
                        <p class="text-xl md:text-2xl font-bold text-purple-600">{{ estadisticas.promedio }}</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-3 md:p-4">
                    <div class="text-center">
                        <i class="bx bx-bar-chart-alt-2 text-orange-600 text-2xl md:text-3xl mb-1 md:mb-2"></i>
                        <p class="text-xs md:text-sm text-gray-500">Avance</p>
                        <p class="text-xl md:text-2xl font-bold text-orange-600">{{ estadisticas.porcentaje_avance }}%</p>
                    </div>
                </div>
            </div>

            <!-- Barra de Progreso -->
            <div class="bg-white border border-gray-200 rounded-lg p-3 md:p-4">
                <h3 class="text-xs md:text-sm font-semibold text-gray-700 mb-2 md:mb-3">Progreso de la Carrera</h3>
                <div class="w-full bg-gray-200 rounded-full h-4 md:h-6">
                    <div
                        class="bg-gradient-to-r from-green-500 to-green-600 h-4 md:h-6 rounded-full transition-all duration-500 flex items-center justify-end pr-2"
                        :style="{ width: estadisticas.porcentaje_avance + '%' }"
                    >
                        <span class="text-[10px] md:text-xs font-bold text-white">{{ estadisticas.porcentaje_avance }}%</span>
                    </div>
                </div>
                <p class="text-[10px] md:text-xs text-gray-500 mt-1 md:mt-2">
                    {{ estadisticas.aprobadas }} de {{ estadisticas.total_materias }} materias aprobadas
                </p>
            </div>

            <!-- Historial Académico por Año -->
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 p-3 md:p-4">
                    <h2 class="text-base md:text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bx bx-history text-blue-600"></i>
                        Historial Académico
                    </h2>
                </div>

                <div class="p-3 md:p-6 space-y-4 md:space-y-6">
                    <div v-for="anno in aniosOrdenados" :key="anno" class="space-y-2 md:space-y-3">
                        <h3 class="text-sm md:text-base font-bold text-gray-900 flex items-center gap-2 bg-blue-50 px-3 py-2 rounded-lg">
                            <i class="bx bx-calendar text-blue-600"></i>
                            {{ anno }}° Año
                            <span class="ml-auto text-xs font-normal text-blue-600">
                                {{ historialPorAnio[anno].length }} {{ historialPorAnio[anno].length === 1 ? 'materia' : 'materias' }}
                            </span>
                        </h3>

                        <!-- Vista Desktop: Tabla -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Materia</th>
                                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600">Estado</th>
                                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600">Cursada</th>
                                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600">Final</th>
                                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600">Nota Final</th>
                                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="materia in historialPorAnio[anno]"
                                        :key="materia.id"
                                        class="border-b border-gray-100 hover:bg-gray-50 transition-colors"
                                    >
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ materia.materia_nombre }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full border"
                                                :class="getEstadoBadge(materia).class"
                                            >
                                                {{ getEstadoBadge(materia).text }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span v-if="materia.calificacion_cursada" class="font-medium text-gray-700">
                                                {{ materia.calificacion_cursada }}
                                            </span>
                                            <span v-else class="text-gray-400">-</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span v-if="materia.calificacion_rendida" class="font-medium text-gray-700">
                                                {{ materia.calificacion_rendida }}
                                            </span>
                                            <span v-else class="text-gray-400">-</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span v-if="materia.nota_final" :class="getNotaColor(materia.nota_final)">
                                                {{ materia.nota_final }}
                                            </span>
                                            <span v-else class="text-gray-400">-</span>
                                        </td>
                                        <td class="px-4 py-3 text-center text-xs text-gray-500">
                                            {{ materia.fecha || '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Vista Mobile: Cards -->
                        <div class="md:hidden space-y-2">
                            <div
                                v-for="materia in historialPorAnio[anno]"
                                :key="materia.id"
                                class="bg-white border border-gray-200 rounded-lg p-3"
                            >
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold text-gray-900 text-sm flex-1">{{ materia.materia_nombre }}</h4>
                                    <span
                                        class="px-2 py-0.5 text-[10px] font-semibold rounded-full border ml-2"
                                        :class="getEstadoBadge(materia).class"
                                    >
                                        {{ getEstadoBadge(materia).text }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <span class="text-gray-500">Cursada:</span>
                                        <span class="ml-1 font-medium text-gray-700">{{ materia.calificacion_cursada || '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Final:</span>
                                        <span class="ml-1 font-medium text-gray-700">{{ materia.calificacion_rendida || '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Nota:</span>
                                        <span v-if="materia.nota_final" class="ml-1" :class="getNotaColor(materia.nota_final)">
                                            {{ materia.nota_final }}
                                        </span>
                                        <span v-else class="ml-1 text-gray-400">-</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Fecha:</span>
                                        <span class="ml-1 text-gray-600">{{ materia.fecha || '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sin materias -->
                    <div v-if="aniosOrdenados.length === 0" class="text-center py-8 md:py-12">
                        <i class="bx bx-book-open text-4xl md:text-6xl text-gray-300 mb-3 md:mb-4"></i>
                        <p class="text-gray-500 text-sm md:text-base">No hay materias registradas en tu expediente</p>
                    </div>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
