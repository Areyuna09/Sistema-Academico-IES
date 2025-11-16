<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import Dialog from '@/Components/Dialog.vue';
import { useDialog } from '@/Composables/useDialog';

const props = defineProps({
    tipo: String, // 'cursado' o 'mesas'
    inscripciones: Object,
    periodos: Array,
    carreras: Array,
    filtros: Object,
});

const filtrosLocales = ref({
    tipo: props.tipo || 'cursado',
    periodo_id: props.filtros.periodo_id || '',
    carrera_id: props.filtros.carrera_id || '',
    estado: props.filtros.estado || '',
    buscar: props.filtros.buscar || '',
});

const { confirm: showConfirm } = useDialog();

const cambiarTipo = (nuevoTipo) => {
    filtrosLocales.value.tipo = nuevoTipo;
    // Resetear período si cambiamos a mesas (no tienen período en el filtro)
    if (nuevoTipo === 'mesas') {
        filtrosLocales.value.periodo_id = '';
    }
    filtrar();
};

const filtrar = () => {
    router.get(route('admin.inscripciones.index'), filtrosLocales.value, {
        preserveState: true,
        preserveScroll: true,
    });
};

const limpiarFiltros = () => {
    filtrosLocales.value = {
        tipo: props.tipo || 'cursado',
        periodo_id: '',
        carrera_id: '',
        estado: '',
        buscar: '',
    };
    filtrar();
};

const eliminarInscripcion = async (id, alumnoNombre, materiaNombre) => {
    const tipoTexto = props.tipo === 'mesas' ? 'mesa de' : 'cursado de';
    const confirmed = await showConfirm(
        `¿Está seguro de eliminar la inscripción de ${alumnoNombre} a ${tipoTexto} ${materiaNombre}? Esta acción no se puede deshacer.`,
        'Confirmar eliminación'
    );

    if (confirmed) {
        router.delete(route('admin.inscripciones.destroy', id), {
            data: { tipo: props.tipo },
            preserveScroll: true,
        });
    }
};

const cambiarEstado = (id, nuevoEstado, nota = null) => {
    router.put(route('admin.inscripciones.update', id), {
        estado: nuevoEstado,
        nota: nota,
        tipo: props.tipo,
    }, {
        preserveScroll: true,
    });
};

const getEstadoColor = (estado) => {
    // Colores para inscripciones a cursado
    const coloresCursado = {
        confirmada: 'bg-green-100 text-green-800',
        pendiente: 'bg-yellow-100 text-yellow-800',
        cancelada: 'bg-red-100 text-red-800',
    };

    // Colores para inscripciones a mesas
    const coloresMesas = {
        inscripto: 'bg-blue-100 text-blue-800',
        presente: 'bg-cyan-100 text-cyan-800',
        ausente: 'bg-orange-100 text-orange-800',
        aprobado: 'bg-green-100 text-green-800',
        desaprobado: 'bg-red-100 text-red-800',
    };

    const colores = props.tipo === 'mesas' ? coloresMesas : coloresCursado;
    return colores[estado] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Head title="Gestión de Inscripciones" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Gestión de Inscripciones</h1>
                <p class="text-xs text-gray-400 mt-0.5">Administración de inscripciones a cursado y mesas de examen</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Tabs para alternar entre tipos -->
            <div class="mb-6 flex gap-2">
                <button
                    @click="cambiarTipo('cursado')"
                    :class="[
                        'px-6 py-2.5 rounded-lg font-medium transition-all duration-200',
                        tipo === 'cursado'
                            ? 'bg-blue-600 text-white shadow-md'
                            : 'bg-white text-gray-600 hover:bg-gray-50'
                    ]"
                >
                    <i class="bx bx-clipboard mr-2"></i>
                    Inscripciones a Cursado
                </button>
                <button
                    @click="cambiarTipo('mesas')"
                    :class="[
                        'px-6 py-2.5 rounded-lg font-medium transition-all duration-200',
                        tipo === 'mesas'
                            ? 'bg-blue-600 text-white shadow-md'
                            : 'bg-white text-gray-600 hover:bg-gray-50'
                    ]"
                >
                    <i class="bx bx-calendar-event mr-2"></i>
                    Inscripciones a Mesas
                </button>
                
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ tipo === 'cursado' ? 'Inscripciones a Cursado' : 'Inscripciones a Mesas de Examen' }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Búsqueda -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar Alumno</label>
                        <input
                            v-model="filtrosLocales.buscar"
                            type="text"
                            placeholder="DNI o nombre..."
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            @keyup.enter="filtrar"
                        />
                    </div>

                    <!-- Período (solo para cursado) -->
                    <div v-if="tipo === 'cursado'">
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

                    <!-- Estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select
                            v-model="filtrosLocales.estado"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            @change="filtrar"
                        >
                            <option value="">Todos los estados</option>
                            <!-- Estados para cursado -->
                            <template v-if="tipo === 'cursado'">
                                <option value="confirmada">Confirmada</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="cancelada">Cancelada</option>
                            </template>
                            <!-- Estados para mesas -->
                            <template v-else>
                                <option value="inscripto">Inscripto</option>
                                <option value="presente">Presente</option>
                                <option value="ausente">Ausente</option>
                                <option value="aprobado">Aprobado</option>
                                <option value="desaprobado">Desaprobado</option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="flex gap-2 mt-4">
                    <button
                        @click="filtrar"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200"
                    >
                        <i class="bx bx-search mr-1"></i> Buscar
                    </button>
                    <button
                        @click="limpiarFiltros"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200"
                    >
                        <i class="bx bx-x mr-1"></i> Limpiar
                    </button>
                </div>
            </div>

            <!-- Tabla de Inscripciones -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alumno</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia</th>
                                <!-- Columnas específicas según tipo -->
                                <th v-if="tipo === 'cursado'" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                                <th v-if="tipo === 'mesas'" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Mesa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th v-if="tipo === 'mesas'" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nota</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inscripción</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="inscripcion in inscripciones.data" :key="inscripcion.id" class="hover:bg-gray-50">
                                <!-- Alumno -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ inscripcion.alumno.nombre_completo }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        DNI: {{ inscripcion.alumno.dni }}
                                    </div>
                                </td>
                                <!-- Materia -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ tipo === 'mesas' ? inscripcion.mesa.materia.nombre : inscripcion.materia.nombre }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ tipo === 'mesas' ? inscripcion.mesa.materia.carrera.nombre : inscripcion.materia.carrera.nombre }}
                                    </div>
                                </td>
                                <!-- Período (solo cursado) -->
                                <td v-if="tipo === 'cursado'" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ inscripcion.periodo.nombre }}
                                </td>
                                <!-- Fecha Mesa (solo mesas) -->
                                <td v-if="tipo === 'mesas'" class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ new Date(inscripcion.mesa.fecha).toLocaleDateString('es-AR') }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ inscripcion.mesa.hora }}</div>
                                </td>
                                <!-- Estado -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select
                                        :value="inscripcion.estado"
                                        @change="cambiarEstado(inscripcion.id, $event.target.value)"
                                        :class="['text-xs px-2 py-1 rounded-full font-semibold border-0', getEstadoColor(inscripcion.estado)]"
                                    >
                                        <!-- Estados para cursado -->
                                        <template v-if="tipo === 'cursado'">
                                            <option value="confirmada">Confirmada</option>
                                            <option value="pendiente">Pendiente</option>
                                            <option value="cancelada">Cancelada</option>
                                        </template>
                                        <!-- Estados para mesas -->
                                        <template v-else>
                                            <option value="inscripto">Inscripto</option>
                                            <option value="presente">Presente</option>
                                            <option value="ausente">Ausente</option>
                                            <option value="aprobado">Aprobado</option>
                                            <option value="desaprobado">Desaprobado</option>
                                        </template>
                                    </select>
                                </td>
                                <!-- Nota (solo mesas) -->
                                <td v-if="tipo === 'mesas'" class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold" :class="inscripcion.nota >= 6 ? 'text-green-600' : 'text-red-600'">
                                        {{ inscripcion.nota || '-' }}
                                    </div>
                                </td>
                                <!-- Fecha Inscripción -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ new Date(inscripcion.fecha_inscripcion).toLocaleDateString('es-AR') }}
                                </td>
                                <!-- Acciones -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button
                                        @click="eliminarInscripcion(inscripcion.id, inscripcion.alumno.nombre_completo, tipo === 'mesas' ? inscripcion.mesa.materia.nombre : inscripcion.materia.nombre)"
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
                    <p>No se encontraron inscripciones con los filtros aplicados.</p>
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
