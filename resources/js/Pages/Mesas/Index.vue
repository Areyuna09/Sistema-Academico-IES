<script setup>
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    mesas: Array,
    estudiante: Object,
    carrera: Object,
    periodoActivo: Object,
    anio: String,
});

// Usuarios especiales con estilo VIP (dorado)
const esUsuarioVIP = computed(() => {
    const dniVIPs = ['46180633', '44674217']; // Ramon y Alan
    return dniVIPs.includes(props.estudiante?.dni);
});

const busqueda = ref('');
const filtroEstado = ref('disponibles'); // Por defecto mostrar solo disponibles
const mostrarModalConfirmacion = ref(false);
const mesaSeleccionada = ref(null);

const mesasFiltradas = computed(() => {
    let resultado = props.mesas;

    // Filtro por búsqueda
    if (busqueda.value) {
        const termino = busqueda.value.toLowerCase();
        resultado = resultado.filter(m =>
            m.materia.nombre.toLowerCase().includes(termino)
        );
    }

    // Filtro por estado
    if (filtroEstado.value !== 'todas') {
        resultado = resultado.filter(m => {
            if (filtroEstado.value === 'disponibles') {
                return m.puede_inscribirse && !m.ya_inscripto && !m.ya_aprobada;
            }
            if (filtroEstado.value === 'inscripto') {
                return m.ya_inscripto;
            }
            if (filtroEstado.value === 'bloqueadas') {
                return !m.puede_inscribirse && !m.ya_inscripto && !m.ya_aprobada;
            }
            if (filtroEstado.value === 'aprobadas') {
                return m.ya_aprobada;
            }
            return true;
        });
    }

    return resultado;
});

const totalDisponibles = computed(() => {
    return props.mesas.filter(m => m.puede_inscribirse && !m.ya_inscripto && !m.ya_aprobada).length;
});

const totalInscriptas = computed(() => {
    return props.mesas.filter(m => m.ya_inscripto).length;
});

const totalAprobadas = computed(() => {
    return props.mesas.filter(m => m.ya_aprobada).length;
});

const abrirModalInscripcion = (mesa) => {
    mesaSeleccionada.value = mesa;
    mostrarModalConfirmacion.value = true;
};

const confirmarInscripcion = () => {
    if (mesaSeleccionada.value) {
        router.post(route('mesas.inscribir', mesaSeleccionada.value.id), {}, {
            preserveScroll: true,
            onSuccess: () => {
                mostrarModalConfirmacion.value = false;
                mesaSeleccionada.value = null;
            }
        });
    }
};

const cancelarInscripcion = (mesa) => {
    if (confirm('¿Estás seguro de cancelar tu inscripción a esta mesa?')) {
        router.delete(route('mesas.cancelar', mesa.id), {
            preserveScroll: true,
        });
    }
};

const getLlamadoTexto = (llamado) => {
    const textos = { 1: '1° Llamado', 2: '2° Llamado', 3: '3° Llamado' };
    return textos[llamado] || `${llamado}° Llamado`;
};

const getBadgeClasses = (mesa) => {
    if (mesa.ya_aprobada) {
        return 'bg-green-100 text-green-700 border-green-200';
    }
    if (mesa.ya_inscripto) {
        return 'bg-blue-100 text-blue-700 border-blue-200';
    }
    if (mesa.puede_inscribirse) {
        return 'bg-yellow-100 text-yellow-700 border-yellow-200';
    }
    return 'bg-gray-100 text-gray-600 border-gray-200';
};

const getBadgeText = (mesa) => {
    if (mesa.ya_aprobada) return 'Aprobada';
    if (mesa.ya_inscripto) return 'Inscripto';
    if (mesa.puede_inscribirse) return 'Disponible';
    return mesa.motivo_bloqueo || 'No disponible';
};
</script>

<template>
    <Head title="Mesas de Examen" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Mesas de Examen</h1>
                <p class="text-xs text-gray-400 mt-0.5">Inscripción a mesas de examen</p>
            </div>
        </template>

        <div class="p-4 md:p-8 max-w-7xl mx-auto space-y-4 md:space-y-6">
            <!-- Banner de Período Activo (igual que Inscripciones) -->
            <div v-if="periodoActivo" class="bg-blue-50 border border-blue-200 rounded-lg p-3 md:p-4">
                <div class="flex items-center">
                    <i class="bx bx-info-circle text-blue-600 text-xl md:text-2xl mr-2 md:mr-3"></i>
                    <div class="flex-1">
                        <h3 class="text-xs md:text-sm font-semibold text-blue-900">
                            {{ periodoActivo.llamado }}° Llamado - Inscripciones Abiertas
                        </h3>
                        <p class="text-[10px] md:text-xs text-blue-700 mt-0.5 md:mt-1">
                            Período: {{ periodoActivo.fecha_inicio }} al {{ periodoActivo.fecha_fin }}
                            <span v-if="periodoActivo.dias_restantes > 0" class="font-semibold">
                                ({{ periodoActivo.dias_restantes }} {{ periodoActivo.dias_restantes === 1 ? 'día' : 'días' }} restantes)
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div v-else class="bg-red-50 border border-red-200 rounded-lg p-3 md:p-4">
                <div class="flex items-center">
                    <i class="bx bx-error-circle text-red-600 text-xl md:text-2xl mr-2 md:mr-3"></i>
                    <div class="flex-1">
                        <h3 class="text-xs md:text-sm font-semibold text-red-900">Inscripciones Cerradas</h3>
                        <p class="text-[10px] md:text-xs text-red-700 mt-0.5 md:mt-1">
                            No hay un período de inscripción activo en este momento
                        </p>
                    </div>
                </div>
            </div>

            <!-- Información del estudiante -->
            <div
                class="rounded-lg p-3 md:p-5 transition-all duration-300"
                :class="esUsuarioVIP
                    ? 'bg-gradient-to-br from-yellow-50 via-amber-50 to-yellow-50 border-2 border-yellow-400 shadow-lg shadow-yellow-200'
                    : 'bg-white border border-gray-200'"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center flex-shrink-0"
                        :class="esUsuarioVIP
                            ? 'bg-gradient-to-br from-yellow-400 to-amber-500 shadow-md'
                            : 'bg-blue-100'"
                    >
                        <i
                            class="bx text-lg md:text-xl"
                            :class="[esUsuarioVIP ? 'bx-crown text-white' : 'bx-user text-blue-600']"
                        ></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h3
                                class="text-xs md:text-sm font-semibold"
                                :class="esUsuarioVIP ? 'text-yellow-900' : 'text-gray-900'"
                            >
                                {{ estudiante.nombre }}
                            </h3>
                            <span v-if="esUsuarioVIP" class="px-2 py-0.5 bg-yellow-400 text-yellow-900 text-[8px] md:text-[10px] font-bold rounded-full">
                                VIP
                            </span>
                        </div>
                        <p
                            class="text-[10px] md:text-xs"
                            :class="esUsuarioVIP ? 'text-yellow-700' : 'text-gray-500'"
                        >
                            {{ carrera.nombre }} - {{ anio }}
                        </p>
                        <p
                            v-if="estudiante.descripcion"
                            class="text-[10px] md:text-xs italic mt-0.5 md:mt-1"
                            :class="esUsuarioVIP ? 'text-yellow-600 font-medium' : 'text-blue-600'"
                        >
                            "{{ estudiante.descripcion }}"
                        </p>
                    </div>
                </div>
            </div>

            <!-- Estadísticas compactas -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4">
                <div class="bg-white border border-gray-200 rounded-lg p-2.5 md:p-4">
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <i class="bx bx-calendar text-blue-600 text-lg md:text-2xl"></i>
                        <div>
                            <p class="text-[10px] md:text-xs text-gray-500">Total</p>
                            <p class="text-base md:text-xl font-bold text-gray-900">{{ mesas.length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-2.5 md:p-4">
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <i class="bx bx-check-circle text-yellow-600 text-lg md:text-2xl"></i>
                        <div>
                            <p class="text-[10px] md:text-xs text-gray-500">Disponibles</p>
                            <p class="text-base md:text-xl font-bold text-yellow-700">{{ totalDisponibles }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-2.5 md:p-4">
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <i class="bx bx-user-check text-blue-600 text-lg md:text-2xl"></i>
                        <div>
                            <p class="text-[10px] md:text-xs text-gray-500">Inscripto</p>
                            <p class="text-base md:text-xl font-bold text-blue-700">{{ totalInscriptas }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-2.5 md:p-4">
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <i class="bx bx-badge-check text-green-600 text-lg md:text-2xl"></i>
                        <div>
                            <p class="text-[10px] md:text-xs text-gray-500">Aprobadas</p>
                            <p class="text-base md:text-xl font-bold text-green-700">{{ totalAprobadas }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barra de búsqueda y filtros -->
            <div class="bg-white border border-gray-200 rounded-lg p-3 md:p-4">
                <div class="flex flex-col md:flex-row gap-2 md:gap-3">
                    <!-- Búsqueda -->
                    <div class="flex-1 relative">
                        <i class="bx bx-search absolute left-2.5 md:left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-base md:text-lg"></i>
                        <input
                            v-model="busqueda"
                            type="text"
                            placeholder="Buscar por materia"
                            class="w-full pl-8 md:pl-10 pr-3 md:pr-4 py-1.5 md:py-2 text-xs md:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                    <!-- Filtro de estado -->
                    <select
                        v-model="filtroEstado"
                        class="px-3 md:px-4 py-1.5 md:py-2 text-xs md:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="disponibles">Disponibles</option>
                        <option value="inscripto">Inscripto</option>
                        <option value="aprobadas">Aprobadas</option>
                        <option value="bloqueadas">Bloqueadas</option>
                        <option value="todas">Todas las mesas</option>
                    </select>
                </div>
            </div>

            <!-- Lista de Mesas (Cards compactas) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                <div
                    v-for="mesa in mesasFiltradas"
                    :key="mesa.id"
                    class="bg-white border-2 rounded-lg hover:shadow-md transition-all duration-200"
                    :class="getBadgeClasses(mesa)"
                >
                    <!-- Header compacto -->
                    <div class="p-3 md:p-4 border-b">
                        <div class="flex justify-between items-start gap-2">
                            <h3 class="font-semibold text-gray-900 text-xs md:text-sm leading-tight flex-1">
                                {{ mesa.materia.nombre }}
                            </h3>
                            <span class="px-1.5 md:px-2 py-0.5 text-[10px] md:text-xs font-semibold rounded-full whitespace-nowrap" :class="getBadgeClasses(mesa)">
                                {{ getBadgeText(mesa) }}
                            </span>
                        </div>
                        <p class="text-[10px] md:text-xs text-gray-500 mt-0.5 md:mt-1">{{ mesa.materia.carrera }} - {{ mesa.materia.anno }}° Año</p>
                    </div>

                    <!-- Body compacto -->
                    <div class="p-3 md:p-4 space-y-1.5 md:space-y-2">
                        <!-- Fecha, Hora y Llamado -->
                        <div class="flex items-center text-[10px] md:text-xs text-gray-700">
                            <i class="bx bx-calendar mr-1 md:mr-1.5 text-gray-400 text-sm md:text-base"></i>
                            <span>{{ mesa.fecha_examen_formatted }}</span>
                            <span class="mx-1 md:mx-1.5">•</span>
                            <i class="bx bx-time mr-0.5 md:mr-1 text-gray-400 text-sm md:text-base"></i>
                            <span>{{ mesa.hora_examen }}</span>
                        </div>

                        <div class="flex items-center text-[10px] md:text-xs text-gray-700">
                            <i class="bx bx-medal mr-1 md:mr-1.5 text-gray-400 text-sm md:text-base"></i>
                            <span>{{ getLlamadoTexto(mesa.llamado) }}</span>
                            <span v-if="mesa.aula" class="mx-1 md:mx-1.5">•</span>
                            <span v-if="mesa.aula">{{ mesa.aula }}</span>
                        </div>

                        <!-- Correlativas faltantes (compacto) -->
                        <div v-if="mesa.correlativas_faltantes.length > 0" class="bg-red-50 border border-red-200 rounded p-1.5 md:p-2 text-[10px] md:text-xs">
                            <p class="font-medium text-red-800 mb-0.5 md:mb-1">Correlativas faltantes:</p>
                            <ul class="list-disc list-inside text-red-600 space-y-0.5">
                                <li v-for="correlativa in mesa.correlativas_faltantes" :key="correlativa.id" class="text-[10px] md:text-xs">
                                    {{ correlativa.nombre }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Footer / Acciones -->
                    <div class="p-2.5 md:p-3 bg-gray-50 border-t">
                        <button
                            v-if="mesa.puede_inscribirse && !mesa.ya_inscripto"
                            @click="abrirModalInscripcion(mesa)"
                            class="w-full px-2.5 md:px-3 py-1.5 md:py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs md:text-sm font-semibold rounded-lg transition-colors duration-200"
                        >
                            <i class="bx bx-calendar-plus mr-0.5 md:mr-1"></i>
                            Inscribirme
                        </button>
                        <button
                            v-else-if="mesa.ya_inscripto && mesa.estado_inscripcion === 'inscripto'"
                            @click="cancelarInscripcion(mesa)"
                            class="w-full px-2.5 md:px-3 py-1.5 md:py-2 bg-red-100 hover:bg-red-200 text-red-700 text-xs md:text-sm font-semibold rounded-lg transition-colors duration-200"
                        >
                            <i class="bx bx-x-circle mr-0.5 md:mr-1"></i>
                            Cancelar Inscripción
                        </button>
                        <div
                            v-else-if="mesa.ya_inscripto"
                            class="w-full px-2.5 md:px-3 py-1.5 md:py-2 bg-green-100 text-green-700 text-xs md:text-sm font-semibold rounded-lg text-center"
                        >
                            <i class="bx bx-check-double mr-0.5 md:mr-1"></i>
                            Inscripto
                        </div>
                        <div
                            v-else
                            class="w-full px-2.5 md:px-3 py-1.5 md:py-2 bg-gray-200 text-gray-600 text-xs md:text-sm font-semibold rounded-lg text-center cursor-not-allowed"
                        >
                            <i class="bx bx-lock mr-0.5 md:mr-1"></i>
                            {{ mesa.motivo_bloqueo || 'No disponible' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sin resultados -->
            <div v-if="mesasFiltradas.length === 0" class="bg-white border border-gray-200 rounded-lg p-8 md:p-12 text-center">
                <i class="bx bx-search-alt text-4xl md:text-6xl text-gray-300 mb-3 md:mb-4"></i>
                <p class="text-gray-500 text-base md:text-lg">No se encontraron mesas de examen</p>
                <p class="text-gray-400 text-sm">Intenta con otros filtros de búsqueda</p>
            </div>
        </div>

        <!-- Modal de Confirmación -->
        <div v-if="mostrarModalConfirmacion" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="mostrarModalConfirmacion = false"></div>

                <div class="relative bg-white rounded-lg max-w-md w-full p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirmar Inscripción</h3>

                    <div v-if="mesaSeleccionada" class="mb-6">
                        <p class="text-sm text-gray-600 mb-2">¿Deseas inscribirte a la siguiente mesa?</p>
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <p class="font-semibold text-gray-900">{{ mesaSeleccionada.materia.nombre }}</p>
                            <p class="text-sm text-gray-600">{{ getLlamadoTexto(mesaSeleccionada.llamado) }}</p>
                            <p class="text-sm text-gray-600">{{ mesaSeleccionada.fecha_examen_formatted }} - {{ mesaSeleccionada.hora_examen }}</p>
                            <p v-if="mesaSeleccionada.aula" class="text-sm text-gray-600">Aula: {{ mesaSeleccionada.aula }}</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button
                            @click="mostrarModalConfirmacion = false"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="confirmarInscripcion"
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                        >
                            Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
