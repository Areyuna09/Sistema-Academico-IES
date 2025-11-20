<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import Dialog from '@/Components/Dialog.vue';
import { useDialog } from '@/Composables/useDialog';

const props = defineProps({
    tipo: String,
    inscripciones: Object,
    periodos: Array,
    carreras: Array,
    materias: Array,
    mesas: Array,
    filtros: Object,
});

const { confirm: showConfirm } = useDialog();

// Filtros locales
const filtrosLocales = ref({
    tipo: props.tipo || 'mesas',
    mesa_id: props.filtros?.mesa_id || '',
    periodo_id: props.filtros?.periodo_id || '',
    carrera_id: props.filtros?.carrera_id || '',
    materia_id: props.filtros?.materia_id || '',
    anio_cursado: props.filtros?.anio_cursado || '',
});

// Filtros para mesas (carrera y año)
const filtroMesaCarrera = ref('');
const filtroMesaAnio = ref('');

// Cambiar tipo de inscripción
const cambiarTipo = (nuevoTipo) => {
    filtrosLocales.value.tipo = nuevoTipo;
    // Limpiar filtros al cambiar
    filtrosLocales.value.mesa_id = '';
    filtrosLocales.value.periodo_id = '';
    filtrosLocales.value.carrera_id = '';
    filtrosLocales.value.materia_id = '';
    filtrosLocales.value.anio_cursado = '';
    filtrar();
};

// Filtrar
const filtrar = () => {
    router.get(route('admin.inscripciones.index'), filtrosLocales.value, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Exportar a PDF
const exportarPdf = () => {
    if (props.tipo === 'mesas' && filtrosLocales.value.mesa_id) {
        window.open(route('admin.inscripciones.mesa.pdf', filtrosLocales.value.mesa_id), '_blank');
    } else if (props.tipo === 'cursado') {
        const params = new URLSearchParams();
        if (filtrosLocales.value.periodo_id) params.append('periodo_id', filtrosLocales.value.periodo_id);
        if (filtrosLocales.value.carrera_id) params.append('carrera_id', filtrosLocales.value.carrera_id);
        if (filtrosLocales.value.materia_id) params.append('materia_id', filtrosLocales.value.materia_id);
        if (filtrosLocales.value.anio_cursado) params.append('anio_cursado', filtrosLocales.value.anio_cursado);
        window.open(route('admin.inscripciones.cursado.pdf') + '?' + params.toString(), '_blank');
    }
};

// Exportar a CSV
const exportarCsv = () => {
    if (props.tipo === 'mesas' && filtrosLocales.value.mesa_id) {
        window.open(route('admin.inscripciones.mesa.csv', filtrosLocales.value.mesa_id), '_blank');
    } else if (props.tipo === 'cursado') {
        const params = new URLSearchParams();
        if (filtrosLocales.value.periodo_id) params.append('periodo_id', filtrosLocales.value.periodo_id);
        if (filtrosLocales.value.carrera_id) params.append('carrera_id', filtrosLocales.value.carrera_id);
        if (filtrosLocales.value.materia_id) params.append('materia_id', filtrosLocales.value.materia_id);
        if (filtrosLocales.value.anio_cursado) params.append('anio_cursado', filtrosLocales.value.anio_cursado);
        window.open(route('admin.inscripciones.cursado.csv') + '?' + params.toString(), '_blank');
    }
};

// Verificar si se puede exportar
const puedeExportar = computed(() => {
    if (props.tipo === 'mesas') {
        return !!filtrosLocales.value.mesa_id;
    }
    return props.inscripciones?.data?.length > 0;
});

// Colores de estado
const getEstadoColor = (estado) => {
    const colores = {
        // Mesas
        inscripto: 'bg-blue-100 text-blue-800',
        presente: 'bg-cyan-100 text-cyan-800',
        ausente: 'bg-orange-100 text-orange-800',
        aprobado: 'bg-green-100 text-green-800',
        desaprobado: 'bg-red-100 text-red-800',
        // Cursado
        confirmada: 'bg-green-100 text-green-800',
        pendiente: 'bg-yellow-100 text-yellow-800',
        cancelada: 'bg-red-100 text-red-800',
    };
    return colores[estado] || 'bg-gray-100 text-gray-800';
};

// Eliminar inscripción
const eliminarInscripcion = async (id, alumnoNombre, materiaNombre) => {
    const tipoTexto = props.tipo === 'mesas' ? 'mesa de' : 'cursado de';
    const confirmed = await showConfirm(
        `¿Está seguro de eliminar la inscripción de ${alumnoNombre} a ${tipoTexto} ${materiaNombre}?`,
        'Confirmar eliminación'
    );

    if (confirmed) {
        router.delete(route('admin.inscripciones.destroy', id), {
            data: { tipo: props.tipo },
            preserveScroll: true,
        });
    }
};

// Cambiar estado
const cambiarEstado = (id, nuevoEstado) => {
    router.put(route('admin.inscripciones.update', id), {
        estado: nuevoEstado,
        tipo: props.tipo,
    }, {
        preserveScroll: true,
    });
};

// Mesa seleccionada info
const mesaSeleccionada = computed(() => {
    if (!filtrosLocales.value.mesa_id || !props.mesas) return null;
    return props.mesas.find(m => m.id == filtrosLocales.value.mesa_id);
});

// Años de cursado disponibles (1°, 2°, 3°, 4°)
const aniosDisponibles = computed(() => {
    return [1, 2, 3, 4];
});

// Obtener carreras disponibles de las mesas
const carrerasDisponibles = computed(() => {
    if (!props.mesas) return [];
    const carreras = new Map();
    props.mesas.forEach(mesa => {
        if (mesa.carrera_id && mesa.carrera_nombre) {
            carreras.set(mesa.carrera_id, mesa.carrera_nombre);
        }
    });
    return Array.from(carreras, ([id, nombre]) => ({ id, nombre })).sort((a, b) => a.nombre.localeCompare(b.nombre));
});

// Filtrar mesas según filtros seleccionados
const mesasFiltradas = computed(() => {
    if (!props.mesas) return [];

    return props.mesas.filter(mesa => {
        // Filtrar por carrera
        if (filtroMesaCarrera.value && mesa.carrera_id != filtroMesaCarrera.value) {
            return false;
        }

        // Filtrar por año de la materia (1°, 2°, 3°, 4°)
        if (filtroMesaAnio.value && mesa.materia_anio != filtroMesaAnio.value) {
            return false;
        }

        return true;
    });
});

// Seleccionar mesa
const seleccionarMesa = (mesaId) => {
    filtrosLocales.value.mesa_id = mesaId;
    filtrar();
};

// Filtrar materias por carrera seleccionada
const materiasFiltradas = computed(() => {
    if (!props.materias) return [];
    if (!filtrosLocales.value.carrera_id) return props.materias;
    return props.materias.filter(m => m.carrera_id == filtrosLocales.value.carrera_id);
});

// Limpiar materia cuando cambia la carrera
watch(() => filtrosLocales.value.carrera_id, () => {
    if (filtrosLocales.value.materia_id) {
        const materiaExiste = materiasFiltradas.value.find(m => m.id == filtrosLocales.value.materia_id);
        if (!materiaExiste) {
            filtrosLocales.value.materia_id = '';
        }
    }
});
</script>

<template>
    <Head title="Listados de Inscripciones" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Listados de Inscripciones</h1>
                <p class="text-xs text-gray-400 mt-0.5">Consulta y exportación de listados de inscriptos</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Tabs -->
            <div class="mb-6 flex gap-2">
                <button
                    @click="cambiarTipo('mesas')"
                    :class="[
                        'px-6 py-2.5 rounded-lg font-medium transition-all duration-200',
                        tipo === 'mesas'
                            ? 'bg-blue-600 text-white shadow-md'
                            : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'
                    ]"
                >
                    <i class="bx bx-calendar-event mr-2"></i>
                    Mesas de Examen
                </button>
                <button
                    @click="cambiarTipo('cursado')"
                    :class="[
                        'px-6 py-2.5 rounded-lg font-medium transition-all duration-200',
                        tipo === 'cursado'
                            ? 'bg-blue-600 text-white shadow-md'
                            : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'
                    ]"
                >
                    <i class="bx bx-book-open mr-2"></i>
                    Inscripciones a Cursado
                </button>
            </div>

            <!-- Panel de Mesas -->
            <div v-if="tipo === 'mesas'" class="space-y-6">
                <!-- Filtros -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="bx bx-filter-alt mr-2 text-blue-600"></i>
                        Filtrar Mesas de Examen
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Filtro por Carrera -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Carrera</label>
                            <select
                                v-model="filtroMesaCarrera"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            >
                                <option value="">Todas las carreras</option>
                                <option v-for="carrera in carrerasDisponibles" :key="carrera.id" :value="carrera.id">
                                    {{ carrera.nombre }}
                                </option>
                            </select>
                        </div>

                        <!-- Filtro por Año de la materia -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Año de cursado</label>
                            <select
                                v-model="filtroMesaAnio"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            >
                                <option value="">Todos los años</option>
                                <option v-for="anio in aniosDisponibles" :key="anio" :value="anio">
                                    {{ anio }}° año
                                </option>
                            </select>
                        </div>

                        <!-- Contador de resultados -->
                        <div class="flex items-end">
                            <div class="bg-gray-100 rounded-lg p-3 w-full text-center">
                                <span class="text-sm text-gray-600">
                                    <strong class="text-blue-600">{{ mesasFiltradas.length }}</strong> mesas encontradas
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cards de Mesas -->
                <div v-if="mesasFiltradas.length > 0 && !filtrosLocales.mesa_id" class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="bx bx-calendar-event mr-2 text-blue-600"></i>
                        Seleccionar Mesa
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div
                            v-for="mesa in mesasFiltradas"
                            :key="mesa.id"
                            @click="seleccionarMesa(mesa.id)"
                            class="border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all duration-200"
                        >
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-medium text-gray-900 text-sm">
                                    {{ mesa.nombre.split(' - ')[0] }}
                                </h3>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">
                                    {{ mesa.inscriptos_count }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 space-y-1">
                                <p><i class="bx bx-calendar mr-1"></i>{{ mesa.nombre.split(' - ')[1] }}</p>
                                <p><i class="bx bx-book mr-1"></i>{{ mesa.carrera_nombre }}</p>
                            </div>
                        </div>
                    </div>

                    <div v-if="mesasFiltradas.length === 0" class="text-center py-8 text-gray-500">
                        <i class="bx bx-search text-4xl mb-2"></i>
                        <p>No se encontraron mesas con los filtros seleccionados</p>
                    </div>
                </div>

                <!-- Mesa Seleccionada - Detalle -->
                <div v-if="mesaSeleccionada" class="bg-white rounded-lg shadow-md p-6">
                    <!-- Botón volver -->
                    <button
                        @click="filtrosLocales.mesa_id = ''; filtrar()"
                        class="mb-4 text-blue-600 hover:text-blue-800 text-sm flex items-center"
                    >
                        <i class="bx bx-arrow-back mr-1"></i>
                        Volver a la lista de mesas
                    </button>

                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">
                                <i class="bx bx-list-check mr-2 text-blue-600"></i>
                                {{ mesaSeleccionada.nombre.split(' - ')[0] }}
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="bx bx-calendar mr-1"></i>{{ mesaSeleccionada.nombre.split(' - ')[1] }}
                                <span class="mx-2">|</span>
                                <i class="bx bx-book mr-1"></i>{{ mesaSeleccionada.carrera_nombre }}
                            </p>
                        </div>
                    </div>

                    <!-- Info y botones de exportación -->
                    <div class="flex flex-wrap items-center gap-4 mb-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center text-sm text-gray-700">
                            <i class="bx bx-user mr-1"></i>
                            <strong>{{ inscripciones?.total || 0 }}</strong>&nbsp;inscriptos
                        </div>
                        <div class="flex gap-2 ml-auto">
                            <button
                                @click="exportarPdf"
                                class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200 flex items-center text-sm"
                            >
                                <i class="bx bxs-file-pdf mr-1"></i> PDF
                            </button>
                            <button
                                @click="exportarCsv"
                                class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200 flex items-center text-sm"
                            >
                                <i class="bx bxs-file-export mr-1"></i> Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de Cursado -->
            <div v-if="tipo === 'cursado'" class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="bx bx-filter-alt mr-2 text-blue-600"></i>
                    Filtrar Inscripciones a Cursado
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Período -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Período</label>
                        <select
                            v-model="filtrosLocales.periodo_id"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            @change="filtrar"
                        >
                            <option value="">Todos los períodos</option>
                            <option v-for="periodo in periodos" :key="periodo.id" :value="periodo.id">
                                {{ periodo.nombre }}
                            </option>
                        </select>
                    </div>

                    <!-- Carrera -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Carrera</label>
                        <select
                            v-model="filtrosLocales.carrera_id"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            @change="filtrar"
                        >
                            <option value="">Todas las carreras</option>
                            <option v-for="carrera in carreras" :key="carrera.Id" :value="carrera.Id">
                                {{ carrera.nombre }}
                            </option>
                        </select>
                    </div>

                    <!-- Año de Cursado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                        <select
                            v-model="filtrosLocales.anio_cursado"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            @change="filtrar"
                        >
                            <option value="">Todos los años</option>
                            <option v-for="anio in aniosDisponibles" :key="anio" :value="anio">
                                {{ anio }}°
                            </option>
                        </select>
                    </div>

                    <!-- Materia -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Materia</label>
                        <select
                            v-model="filtrosLocales.materia_id"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            @change="filtrar"
                        >
                            <option value="">Todas las materias</option>
                            <option v-for="materia in materiasFiltradas" :key="materia.id" :value="materia.id">
                                {{ materia.nombre }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Botones de exportación -->
                <div class="mt-4 flex gap-2">
                    <button
                        @click="exportarPdf"
                        :disabled="!puedeExportar"
                        :class="[
                            'px-4 py-2 rounded-lg transition-colors duration-200 flex items-center',
                            puedeExportar
                                ? 'bg-red-600 hover:bg-red-700 text-white'
                                : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                        ]"
                    >
                        <i class="bx bxs-file-pdf mr-1"></i> Exportar PDF
                    </button>
                    <button
                        @click="exportarCsv"
                        :disabled="!puedeExportar"
                        :class="[
                            'px-4 py-2 rounded-lg transition-colors duration-200 flex items-center',
                            puedeExportar
                                ? 'bg-green-600 hover:bg-green-700 text-white'
                                : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                        ]"
                    >
                        <i class="bx bxs-file-export mr-1"></i> Exportar Excel
                    </button>
                </div>
            </div>

            <!-- Tabla de Inscripciones -->
            <div v-if="(tipo === 'mesas' && filtrosLocales.mesa_id) || tipo === 'cursado'" class="bg-white rounded-lg shadow-md overflow-hidden" :class="{ 'mt-0': tipo === 'mesas' }">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N°</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alumno</th>
                                <th v-if="tipo === 'cursado'" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th v-if="tipo === 'mesas'" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nota</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(inscripcion, index) in inscripciones.data" :key="inscripcion.id" class="hover:bg-gray-50">
                                <!-- Número -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ index + 1 + (inscripciones.current_page - 1) * inscripciones.per_page }}
                                </td>
                                <!-- DNI -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ inscripcion.alumno.dni }}
                                </td>
                                <!-- Alumno -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ inscripcion.alumno.apellido }}, {{ inscripcion.alumno.nombre }}
                                    </div>
                                </td>
                                <!-- Materia (solo cursado) -->
                                <td v-if="tipo === 'cursado'" class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ inscripcion.materia.nombre }}</div>
                                    <div class="text-xs text-gray-500">{{ inscripcion.materia.carrera?.nombre }}</div>
                                </td>
                                <!-- Estado -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="['px-2 py-1 text-xs font-semibold rounded-full', getEstadoColor(inscripcion.estado)]">
                                        {{ inscripcion.estado.charAt(0).toUpperCase() + inscripcion.estado.slice(1) }}
                                    </span>
                                </td>
                                <!-- Nota (solo mesas) -->
                                <td v-if="tipo === 'mesas'" class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium" :class="inscripcion.nota >= 6 ? 'text-green-600' : 'text-gray-500'">
                                        {{ inscripcion.nota || '-' }}
                                    </span>
                                </td>
                                <!-- Acciones -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button
                                        @click="eliminarInscripcion(inscripcion.id, inscripcion.alumno.apellido + ', ' + inscripcion.alumno.nombre, tipo === 'mesas' ? inscripcion.mesa?.materia?.nombre : inscripcion.materia?.nombre)"
                                        class="text-red-600 hover:text-red-900"
                                        title="Eliminar inscripción"
                                    >
                                        <i class="bx bx-trash text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Mensaje si no hay resultados -->
                <div v-if="inscripciones.data.length === 0" class="p-8 text-center text-gray-500">
                    <i class="bx bx-inbox text-5xl mb-2"></i>
                    <p>No se encontraron inscripciones</p>
                </div>

                <!-- Paginación -->
                <div v-if="inscripciones.data.length > 0" class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="text-sm text-gray-700">
                            Mostrando {{ inscripciones.from }} a {{ inscripciones.to }} de {{ inscripciones.total }} inscripciones
                        </div>
                        <div class="flex gap-1">
                            <component
                                v-for="link in inscripciones.links"
                                :key="link.label"
                                :is="link.url ? Link : 'span'"
                                :href="link.url || undefined"
                                :class="[
                                    'px-3 py-1 rounded border text-sm',
                                    link.active ? 'bg-blue-600 text-white border-blue-600' : '',
                                    !link.url ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 cursor-pointer'
                                ]"
                                v-html="link.label"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dialog component -->
        <Dialog />
    </SidebarLayout>
</template>
