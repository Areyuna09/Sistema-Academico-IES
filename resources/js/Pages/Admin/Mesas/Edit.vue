<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    mesa: Object,
    carreras: Array,
    materias: Array,
    profesores: Array,
    periodos: Array,
});

const form = useForm({
    materia_id: props.mesa.materia.id,
    fecha_examen: props.mesa.fecha_examen.split('T')[0],
    hora_examen: props.mesa.hora_examen,
    llamado: props.mesa.llamado,
    periodo_id: props.mesa.periodo_id || '',
    presidente_id: props.mesa.presidente?.id || '',
    vocal1_id: props.mesa.vocal1?.id || '',
    vocal2_id: props.mesa.vocal2?.id || '',
    estado: props.mesa.estado,
    observaciones: props.mesa.observaciones || '',
});

// Filtros locales
const filtroCarrera = ref('');
const filtroAnio = ref('');

// Materias filtradas según los filtros seleccionados
const materiasFiltradas = computed(() => {
    let filtradas = props.materias;

    if (filtroCarrera.value) {
        filtradas = filtradas.filter(m => m.carrera?.Id == filtroCarrera.value);
    }

    if (filtroAnio.value) {
        filtradas = filtradas.filter(m => m.anno == filtroAnio.value);
    }

    return filtradas;
});

// Obtener años únicos de las materias
const aniosDisponibles = computed(() => {
    const anios = [...new Set(props.materias.map(m => m.anno))].filter(Boolean);
    return anios.sort((a, b) => a - b);
});

// Validar que no sea fin de semana
const validarNoFinDeSemana = (fecha) => {
    if (!fecha) return;

    const date = new Date(fecha + 'T00:00:00');
    const dayOfWeek = date.getDay();

    // 0 = Domingo, 6 = Sábado
    if (dayOfWeek === 0 || dayOfWeek === 6) {
        alert('No se pueden seleccionar sábados ni domingos para exámenes');
        form.fecha_examen = '';
    }
};

// Watcher para validar fecha de examen
watch(() => form.fecha_examen, (valor) => validarNoFinDeSemana(valor));

const submit = () => {
    form.put(route('admin.mesas.update', props.mesa.id));
};
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Editar Mesa de Examen</h1>
                <p class="text-xs text-gray-400 mt-0.5">Modificar datos de la mesa de examen</p>
            </div>
        </template>

        <div class="p-8 max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <Link :href="route('admin.mesas.index')" class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="bx bx-arrow-back mr-1"></i>
                    Volver a Mesas
                </Link>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <form @submit.prevent="submit">
                    <!-- Información Básica -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
                            <i class="bx bx-book text-xl mr-2 text-blue-600"></i>
                            Información de la Mesa
                        </h3>

                        <!-- Filtros -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="bx bx-filter mr-1"></i>
                                    Filtrar por Carrera
                                </label>
                                <select
                                    v-model="filtroCarrera"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option value="">Todas las carreras</option>
                                    <option v-for="carrera in carreras" :key="carrera.Id" :value="carrera.Id">
                                        {{ carrera.nombre }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="bx bx-filter mr-1"></i>
                                    Filtrar por Año
                                </label>
                                <select
                                    v-model="filtroAnio"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option value="">Todos los años</option>
                                    <option v-for="anio in aniosDisponibles" :key="anio" :value="anio">
                                        {{ anio }}° Año
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Materia -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Materia <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.materia_id"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option value="">Seleccione una materia</option>
                                    <option
                                        v-for="materia in materiasFiltradas"
                                        :key="materia.id"
                                        :value="materia.id"
                                    >
                                        {{ materia.nombre }} - {{ materia.anno }}° Año - {{ materia.carrera?.nombre }}
                                    </option>
                                </select>
                                <p v-if="form.errors.materia_id" class="text-red-600 text-sm mt-1">{{ form.errors.materia_id }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Mostrando {{ materiasFiltradas.length }} de {{ materias.length }} materias
                                </p>
                            </div>

                            <!-- Fecha -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Fecha del Examen <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.fecha_examen"
                                    type="date"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                />
                                <p v-if="form.errors.fecha_examen" class="text-red-600 text-sm mt-1">{{ form.errors.fecha_examen }}</p>
                            </div>

                            <!-- Hora -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Hora <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.hora_examen"
                                    type="time"
                                    min="17:30"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                />
                                <p v-if="form.errors.hora_examen" class="text-red-600 text-sm mt-1">{{ form.errors.hora_examen }}</p>
                                <p class="text-xs text-gray-500 mt-1">Horario mínimo: 17:30 (inicio de clases)</p>
                            </div>

                            <!-- Llamado -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Llamado <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model.number="form.llamado"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option :value="1">1° Llamado</option>
                                    <option :value="2">2° Llamado</option>
                                    <option :value="3">3° Llamado</option>
                                </select>
                                <p v-if="form.errors.llamado" class="text-red-600 text-sm mt-1">{{ form.errors.llamado }}</p>
                            </div>

                            <!-- Estado -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Estado <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.estado"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option value="activa">Activa</option>
                                    <option value="cerrada">Cerrada</option>
                                    <option value="suspendida">Suspendida</option>
                                </select>
                                <p v-if="form.errors.estado" class="text-red-600 text-sm mt-1">{{ form.errors.estado }}</p>
                            </div>

                            <!-- Período -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Período Académico</label>
                                <select
                                    v-model="form.periodo_id"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option value="">Sin período específico</option>
                                    <option v-for="periodo in periodos" :key="periodo.id" :value="periodo.id">
                                        {{ periodo.nombre }}
                                    </option>
                                </select>
                                <p v-if="form.errors.periodo_id" class="text-red-600 text-sm mt-1">{{ form.errors.periodo_id }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tribunal -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
                            <i class="bx bx-group text-xl mr-2 text-blue-600"></i>
                            Tribunal Evaluador
                            <span class="ml-2 text-sm font-normal text-gray-500">(Opcional)</span>
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">El tribunal puede ser asignado más adelante si es necesario</p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Presidente -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Presidente</label>
                                <select
                                    v-model="form.presidente_id"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option value="">Sin asignar</option>
                                    <option v-for="profesor in profesores" :key="profesor.id" :value="profesor.id">
                                        {{ profesor.nombre_completo }}
                                    </option>
                                </select>
                                <p v-if="form.errors.presidente_id" class="text-red-600 text-sm mt-1">{{ form.errors.presidente_id }}</p>
                            </div>

                            <!-- Vocal 1 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Vocal 1</label>
                                <select
                                    v-model="form.vocal1_id"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option value="">Sin asignar</option>
                                    <option v-for="profesor in profesores" :key="profesor.id" :value="profesor.id">
                                        {{ profesor.nombre_completo }}
                                    </option>
                                </select>
                                <p v-if="form.errors.vocal1_id" class="text-red-600 text-sm mt-1">{{ form.errors.vocal1_id }}</p>
                            </div>

                            <!-- Vocal 2 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Vocal 2</label>
                                <select
                                    v-model="form.vocal2_id"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option value="">Sin asignar</option>
                                    <option v-for="profesor in profesores" :key="profesor.id" :value="profesor.id">
                                        {{ profesor.nombre_completo }}
                                    </option>
                                </select>
                                <p v-if="form.errors.vocal2_id" class="text-red-600 text-sm mt-1">{{ form.errors.vocal2_id }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                        <textarea
                            v-model="form.observaciones"
                            rows="3"
                            placeholder="Notas o comentarios adicionales sobre la mesa de examen"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        ></textarea>
                        <p v-if="form.errors.observaciones" class="text-red-600 text-sm mt-1">{{ form.errors.observaciones }}</p>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                        <Link
                            :href="route('admin.mesas.index')"
                            class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 disabled:opacity-50"
                        >
                            <i class="bx bx-save mr-1"></i>
                            {{ form.processing ? 'Guardando...' : 'Actualizar Mesa' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </SidebarLayout>
</template>
