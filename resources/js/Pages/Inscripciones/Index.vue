<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import MateriaCard from '@/Components/MateriaCard.vue';

const props = defineProps({
    estudiante: {
        type: Object,
        required: true
    },
    carrera: {
        type: Object,
        required: true
    },
    periodo: {
        type: Object,
        required: true
    },
    materias: {
        type: Array,
        required: true
    },
    anio: {
        type: String,
        default: '3° 1°'
    }
});

const page = usePage();
const mostrarNotificacion = ref(false);
const tipoNotificacion = ref(''); // 'success', 'error', 'warning'
const mensajeNotificacion = ref('');

// Detectar mensajes flash de la sesión
onMounted(() => {
    if (page.props.flash?.success) {
        mostrarToast('success', page.props.flash.success);
    }
    if (page.props.flash?.error) {
        mostrarToast('error', page.props.flash.error);
    }
    if (page.props.flash?.warning) {
        mostrarToast('warning', page.props.flash.warning);
    }
});

const mostrarToast = (tipo, mensaje) => {
    tipoNotificacion.value = tipo;
    mensajeNotificacion.value = mensaje;
    mostrarNotificacion.value = true;

    // Auto-ocultar después de 5 segundos
    setTimeout(() => {
        mostrarNotificacion.value = false;
    }, 5000);
};

const busqueda = ref('');
const filtroEstado = ref('todas');
const materiasSeleccionadas = ref([]);
const mostrarModalConfirmacion = ref(false);
const mostrandoAdvertencia = ref(false);

// Usuarios especiales con estilo VIP (dorado)
const esUsuarioVIP = computed(() => {
    const dniVIPs = ['46180633', '44674217']; // Ramon y Alan
    return dniVIPs.includes(props.estudiante?.dni);
});

const anioFormateado = computed(() => {
    const anio = props.anio;
    if (anio === 1 || anio === '1') return '1er Año';
    if (anio === 2 || anio === '2') return '2do Año';
    if (anio === 3 || anio === '3') return '3er Año';
    return anio;
});

const materiasFiltradas = computed(() => {
    let resultado = props.materias;

    // Filtro por búsqueda (solo por nombre)
    if (busqueda.value) {
        const termino = busqueda.value.toLowerCase();
        resultado = resultado.filter(m =>
            m.nombre.toLowerCase().includes(termino)
        );
    }

    // Filtro por estado
    if (filtroEstado.value !== 'todas') {
        resultado = resultado.filter(m => {
            if (filtroEstado.value === 'disponibles') {
                return m.puede_cursar && !m.ya_inscrito;
            }
            if (filtroEstado.value === 'inscrito') {
                return m.ya_inscrito;
            }
            if (filtroEstado.value === 'bloqueadas') {
                return !m.puede_cursar && !m.ya_inscrito;
            }
            return true;
        });
    }

    return resultado;
});

// Contar materias disponibles para cursar
const materiasDisponibles = computed(() => {
    return props.materias.filter(m => m.puede_cursar).length;
});

const toggleSeleccion = (materia) => {
    const index = materiasSeleccionadas.value.findIndex(m => m.id === materia.id);
    if (index > -1) {
        materiasSeleccionadas.value.splice(index, 1);
    } else {
        if (materiasSeleccionadas.value.length < 5) {
            materiasSeleccionadas.value.push(materia);
        }
    }
};

const estaSeleccionada = (materia) => {
    return materiasSeleccionadas.value.some(m => m.id === materia.id);
};

const confirmarInscripcion = () => {
    if (materiasSeleccionadas.value.length === 0) {
        alert('Debes seleccionar al menos una materia');
        return;
    }

    // Mostrar modal de confirmación
    mostrandoAdvertencia.value = false;
    mostrarModalConfirmacion.value = true;
};

const procesarInscripcion = () => {
    console.log('🚀 procesarInscripcion ejecutándose');
    const materiasIds = materiasSeleccionadas.value.map(m => m.id);
    console.log('📝 Materias IDs:', materiasIds);

    router.post('/inscripciones', {
        materias: materiasIds
    }, {
        onStart: () => console.log('✅ Request iniciado'),
        onSuccess: () => console.log('✅ Request exitoso'),
        onError: (errors) => {
            console.error('❌ Error en inscripción:', errors);
            alert('Hubo un error al procesar la inscripción. Por favor, intenta nuevamente.');
            cerrarModal();
        },
        onFinish: () => console.log('🏁 Request finalizado')
    });

    console.log('📤 router.post() llamado');
};

const cerrarModal = () => {
    mostrarModalConfirmacion.value = false;
    mostrandoAdvertencia.value = false;
};
</script>

<template>
    <Head title="Inscripción a Materias" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Inscripciones</h1>
                <p class="text-xs text-gray-400 mt-0.5">Inscripción a materias del cuatrimestre</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto space-y-6">
            <!-- Alerta de período de inscripción -->
            <div v-if="periodo.inscripciones_abiertas" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="bx bx-info-circle text-blue-600 text-2xl mr-3"></i>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-blue-900">{{ periodo.nombre }} - Inscripciones Abiertas</h3>
                        <p class="text-xs text-blue-700 mt-1">
                            Período: {{ periodo.fecha_inicio }} al {{ periodo.fecha_fin }}
                            <span v-if="periodo.dias_restantes > 0" class="font-semibold">
                                ({{ periodo.dias_restantes }} {{ periodo.dias_restantes === 1 ? 'día' : 'días' }} restantes)
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div v-else class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="bx bx-error-circle text-red-600 text-2xl mr-3"></i>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-red-900">Inscripciones Cerradas</h3>
                        <p class="text-xs text-red-700 mt-1">
                            El período de inscripción finalizó el {{ periodo.fecha_fin }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Información del estudiante y resumen -->
            <div
                class="rounded-lg p-5 transition-all duration-300"
                :class="esUsuarioVIP
                    ? 'bg-gradient-to-br from-yellow-50 via-amber-50 to-yellow-50 border-2 border-yellow-400 shadow-lg shadow-yellow-200'
                    : 'bg-white border border-gray-200'"
            >
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <!-- Info del estudiante -->
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                            :class="esUsuarioVIP
                                ? 'bg-gradient-to-br from-yellow-400 to-amber-500 shadow-md'
                                : 'bg-blue-100'"
                        >
                            <i
                                class="bx text-xl"
                                :class="[esUsuarioVIP ? 'bx-crown text-white' : 'bx-user text-blue-600']"
                            ></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3
                                    class="text-sm font-semibold"
                                    :class="esUsuarioVIP ? 'text-yellow-900' : 'text-gray-900'"
                                >
                                    {{ estudiante.nombre }}
                                </h3>
                                <span v-if="esUsuarioVIP" class="px-2 py-0.5 bg-yellow-400 text-yellow-900 text-[10px] font-bold rounded-full">
                                    VIP
                                </span>
                            </div>
                            <p
                                class="text-xs"
                                :class="esUsuarioVIP ? 'text-yellow-700' : 'text-gray-500'"
                            >
                                {{ carrera.nombre }} - {{ anioFormateado }}
                            </p>
                            <p
                                v-if="estudiante.descripcion"
                                class="text-xs italic mt-1"
                                :class="esUsuarioVIP ? 'text-yellow-600 font-medium' : 'text-blue-600'"
                            >
                                "{{ estudiante.descripcion }}"
                            </p>
                        </div>
                    </div>

                    <!-- Contador de materias seleccionadas -->
                    <div
                        class="flex items-center gap-2 px-4 py-2 rounded-lg"
                        :class="esUsuarioVIP ? 'bg-yellow-100' : 'bg-blue-50'"
                    >
                        <i
                            class="bx bx-check-square text-xl"
                            :class="esUsuarioVIP ? 'text-yellow-600' : 'text-blue-600'"
                        ></i>
                        <div>
                            <p
                                class="text-xs font-medium"
                                :class="esUsuarioVIP ? 'text-yellow-600' : 'text-blue-600'"
                            >
                                Materias seleccionadas
                            </p>
                            <p
                                class="text-lg font-bold"
                                :class="esUsuarioVIP ? 'text-yellow-700' : 'text-blue-700'"
                            >
                                {{ materiasSeleccionadas.length }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Barra de búsqueda y filtros -->
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex flex-col md:flex-row gap-3">
                    <!-- Búsqueda -->
                    <div class="flex-1 relative">
                        <i class="bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input
                            v-model="busqueda"
                            type="text"
                            placeholder="Buscar materias por nombre o código"
                            class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                    <!-- Filtro por estado -->
                    <select
                        v-model="filtroEstado"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="todas">Todas las materias</option>
                        <option value="disponibles">✅ Disponibles para inscribirse</option>
                        <option value="inscrito">📝 Ya inscrito</option>
                        <option value="bloqueadas">🔒 Bloqueadas (sin correlativas)</option>
                    </select>
                </div>
            </div>

            <!-- Grid de materias -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <MateriaCard
                    v-for="materia in materiasFiltradas"
                    :key="materia.id"
                    :materia="materia"
                    :correlativas="materia.correlativas || {}"
                    :seleccionada="estaSeleccionada(materia)"
                    @toggle-seleccion="toggleSeleccion"
                />
            </div>

            <!-- Mensaje cuando no hay resultados -->
            <div
                v-if="materiasFiltradas.length === 0"
                class="bg-white rounded-lg shadow-md p-12 text-center"
            >
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No se encontraron materias</h3>
                <p class="text-gray-500">Intenta con otros filtros o términos de búsqueda</p>
            </div>
        </div>

        <!-- Botón flotante de confirmación -->
        <transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-4"
        >
            <div v-if="materiasSeleccionadas.length > 0" class="fixed bottom-6 right-6 z-50">
                <!-- Botón de confirmar cuando período está abierto -->
                <button
                    v-if="periodo.inscripciones_abiertas"
                    @click="confirmarInscripcion"
                    class="flex items-center gap-3 bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-full shadow-2xl hover:shadow-xl transition-all duration-200 group"
                >
                    <span class="font-semibold text-base">
                        Confirmar Inscripción ({{ materiasSeleccionadas.length }})
                    </span>
                    <i class="bx bx-check-circle text-2xl group-hover:scale-110 transition-transform"></i>
                </button>

                <!-- Mensaje cuando período está cerrado -->
                <div
                    v-else
                    class="flex items-center gap-3 bg-red-100 border-2 border-red-400 text-red-800 px-6 py-4 rounded-full shadow-2xl"
                >
                    <i class="bx bx-lock text-2xl"></i>
                    <span class="font-semibold text-base">
                        Período de inscripciones cerrado
                    </span>
                </div>
            </div>
        </transition>

        <!-- Modal de Confirmación -->
        <transition
            enter-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="mostrarModalConfirmacion"
                @click="cerrarModal"
                class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
            >
                <transition
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition-all duration-200 ease-in"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div
                        @click.stop
                        class="bg-white rounded-xl shadow-2xl max-w-lg w-full p-6"
                    >
                        <!-- Advertencia si no seleccionó todas -->
                        <div v-if="!mostrandoAdvertencia && materiasSeleccionadas.length < materiasDisponibles">
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded mb-6">
                                <div class="flex items-start">
                                    <i class="bx bx-error text-yellow-600 text-3xl mr-3"></i>
                                    <div>
                                        <h3 class="text-lg font-semibold text-yellow-900 mb-2">
                                            No seleccionaste todas las materias disponibles
                                        </h3>
                                        <p class="text-sm text-yellow-700">
                                            Hay <strong>{{ materiasDisponibles }}</strong> materias disponibles para cursar
                                            y solo seleccionaste <strong>{{ materiasSeleccionadas.length }}</strong>.
                                        </p>
                                        <p class="text-sm text-yellow-700 mt-2">
                                            ¿Estás seguro de que deseas continuar sin inscribirte a todas las materias?
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button
                                    @click="cerrarModal"
                                    class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors font-medium"
                                >
                                    Volver a revisar
                                </button>
                                <button
                                    @click="mostrandoAdvertencia = true"
                                    class="flex-1 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors font-medium"
                                >
                                    Continuar de todas formas
                                </button>
                            </div>
                        </div>

                        <!-- Confirmación final -->
                        <div v-else>
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="bx bx-check-circle text-blue-600 text-4xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                    Confirmar Inscripción
                                </h3>
                                <p class="text-gray-600">
                                    Estás a punto de inscribirte a las siguientes materias:
                                </p>
                            </div>

                            <!-- Lista de materias seleccionadas -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-6 max-h-60 overflow-y-auto">
                                <ul class="space-y-2">
                                    <li
                                        v-for="materia in materiasSeleccionadas"
                                        :key="materia.id"
                                        class="flex items-center gap-2 text-sm"
                                    >
                                        <i class="bx bx-check text-green-600 text-lg"></i>
                                        <span class="font-medium text-gray-900">{{ materia.nombre }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <p class="text-sm text-blue-800">
                                    <i class="bx bx-info-circle text-blue-600 mr-1"></i>
                                    Se enviará un comprobante de inscripción a tu correo electrónico.
                                </p>
                            </div>

                            <div class="flex gap-3">
                                <button
                                    @click="cerrarModal"
                                    class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors font-medium"
                                >
                                    Cancelar
                                </button>
                                <button
                                    @click="procesarInscripcion"
                                    class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium"
                                >
                                    Confirmar
                                </button>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>

        <!-- Notificación Toast -->
        <transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-2"
        >
            <div
                v-if="mostrarNotificacion"
                class="fixed top-6 right-6 z-50 max-w-md"
            >
                <div
                    :class="{
                        'bg-green-50 border-green-500': tipoNotificacion === 'success',
                        'bg-red-50 border-red-500': tipoNotificacion === 'error',
                        'bg-yellow-50 border-yellow-500': tipoNotificacion === 'warning'
                    }"
                    class="border-l-4 p-4 rounded-lg shadow-lg flex items-start gap-3"
                >
                    <i
                        :class="{
                            'bx bx-check-circle text-green-600': tipoNotificacion === 'success',
                            'bx bx-error-circle text-red-600': tipoNotificacion === 'error',
                            'bx bx-error text-yellow-600': tipoNotificacion === 'warning'
                        }"
                        class="text-2xl flex-shrink-0"
                    ></i>
                    <div class="flex-1">
                        <p
                            :class="{
                                'text-green-800': tipoNotificacion === 'success',
                                'text-red-800': tipoNotificacion === 'error',
                                'text-yellow-800': tipoNotificacion === 'warning'
                            }"
                            class="text-sm font-medium"
                        >
                            {{ mensajeNotificacion }}
                        </p>
                    </div>
                    <button
                        @click="mostrarNotificacion = false"
                        class="flex-shrink-0 text-gray-400 hover:text-gray-600"
                    >
                        <i class="bx bx-x text-xl"></i>
                    </button>
                </div>
            </div>
        </transition>
    </SidebarLayout>
</template>