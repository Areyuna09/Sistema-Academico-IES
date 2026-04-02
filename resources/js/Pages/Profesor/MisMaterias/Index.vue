<script setup>
import { ref } from 'vue';
import { router, Link, Head } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    materias:            { type: Array,  default: () => [] },
    periodosDisponibles: { type: Array,  default: () => [] },
    periodoActivo:       { type: Object, default: null },
    periodoSeleccionado: { type: Number, default: null },
    esArchivo:           { type: Boolean, default: false },
    mensaje:             { type: String, default: null },
});

const periodoFiltro = ref(props.periodoSeleccionado ?? '');

const cambiarPeriodo = () => {
    router.get(
        route('expediente.mis-materias'),
        periodoFiltro.value ? { periodo_id: periodoFiltro.value } : {},
        { preserveState: true, preserveScroll: true }
    );
};
</script>

<template>
    <Head title="Mis Materias" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Mis Materias</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        <span v-if="periodoActivo">Período activo: <strong>{{ periodoActivo.nombre }}</strong></span>
                        <span v-else class="text-yellow-600">Sin período activo</span>
                    </p>
                </div>

                <!-- Selector de período -->
                <div v-if="periodosDisponibles.length > 1" class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Período:</label>
                    <select
                        v-model="periodoFiltro"
                        @change="cambiarPeriodo"
                        class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-400"
                    >
                        <option value="">Período activo</option>
                        <option v-for="p in periodosDisponibles" :key="p.id" :value="p.id">
                            {{ p.label }}
                        </option>
                    </select>
                </div>
            </div>
        </template>

        <div class="p-6">

            <!-- Aviso de archivo -->
            <div v-if="esArchivo" class="mb-4 p-3 bg-yellow-50 border border-yellow-300 rounded-lg flex items-center gap-2 text-sm text-yellow-800">
                <i class="bx bx-archive text-lg"></i>
                Estás viendo un período archivado. Las acciones de carga están deshabilitadas.
            </div>

            <!-- Mensaje sin perfil -->
            <div v-if="mensaje" class="p-6 bg-white rounded-lg shadow text-center text-gray-500">
                <i class="bx bx-error-circle text-5xl text-yellow-400 mb-3"></i>
                <p>{{ mensaje }}</p>
            </div>

            <!-- Sin materias -->
            <div v-else-if="materias.length === 0" class="p-10 bg-white rounded-lg shadow text-center text-gray-500">
                <i class="bx bx-book-open text-6xl text-gray-300 mb-4"></i>
                <p class="text-lg font-medium">No tenés materias asignadas en este período</p>
                <p class="text-sm mt-1">Contactá al administrador si creés que esto es un error.</p>
            </div>

            <!-- Grid de materias -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                <div
                    v-for="materia in materias"
                    :key="materia.id"
                    class="bg-white rounded-xl shadow hover:shadow-md transition-shadow border border-gray-100 flex flex-col"
                >
                    <!-- Encabezado de la card -->
                    <div class="p-5 flex-1">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <h3 class="text-base font-semibold text-gray-900 leading-snug">
                                {{ materia.materia_nombre }}
                            </h3>
                            <!-- Badge de división — destacado -->
                            <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                Div. {{ materia.division }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-500 mb-4">{{ materia.carrera_nombre }}</p>

                        <!-- Datos rápidos -->
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="bx bx-group text-lg text-blue-400"></i>
                                <span><strong>{{ materia.cantidad_alumnos }}</strong> alumnos</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="bx bx-time text-lg text-green-400"></i>
                                <span>{{ materia.cursado ?? '—' }}</span>
                            </div>
                        </div>

                        <!-- Período -->
                        <div v-if="materia.periodo" class="mt-3">
                            <span
                                :class="[
                                    'inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs',
                                    materia.periodo.activo
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-gray-100 text-gray-500'
                                ]"
                            >
                                <span
                                    :class="['w-1.5 h-1.5 rounded-full', materia.periodo.activo ? 'bg-green-500' : 'bg-gray-400']"
                                ></span>
                                {{ materia.periodo.nombre }}
                            </span>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="border-t border-gray-100 px-5 py-3 flex flex-wrap gap-2">
                        <Link
                            :href="route('expediente.materia.show', materia.id)"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors"
                        >
                            <i class="bx bx-book"></i> Ver materia
                        </Link>

                        <Link
                            v-if="!esArchivo"
                            :href="route('profesor.asistencias.create', materia.id)"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition-colors"
                        >
                            <i class="bx bx-check-square"></i> Asistencia
                        </Link>

                        <Link
                            :href="route('profesor.asistencias.index', materia.id)"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded transition-colors"
                        >
                            <i class="bx bx-history"></i> Historial
                        </Link>
                    </div>
                </div>
            </div>

        </div>
    </SidebarLayout>
</template>
