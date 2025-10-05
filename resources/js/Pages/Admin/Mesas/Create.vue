<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    carreras: Array,
    materias: Array,
    profesores: Array,
    periodos: Array,
});

const form = useForm({
    materia_id: '',
    fecha_examen: '',
    hora_examen: '',
    llamado: 1,
    periodo_id: '',
    aula: '',
    presidente_id: '',
    vocal1_id: '',
    vocal2_id: '',
    fecha_inicio_inscripcion: '',
    fecha_fin_inscripcion: '',
    observaciones: '',
});

const carreraSeleccionada = computed(() => {
    if (!form.materia_id) return null;
    const materia = props.materias.find(m => m.id === form.materia_id);
    return materia?.carrera?.Id;
});

const materiasFiltradas = computed(() => {
    if (!carreraSeleccionada.value) return props.materias;
    return props.materias.filter(m => m.carrera?.Id === carreraSeleccionada.value);
});

const submit = () => {
    form.post(route('admin.mesas.store'));
};
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Crear Mesa de Examen</h1>
                <p class="text-xs text-gray-400 mt-0.5">Programar una nueva mesa de examen</p>
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
                                    <optgroup v-for="carrera in carreras" :key="carrera.Id" :label="carrera.nombre">
                                        <option
                                            v-for="materia in materias.filter(m => m.carrera?.Id === carrera.Id)"
                                            :key="materia.id"
                                            :value="materia.id"
                                        >
                                            {{ materia.nombre }} - {{ materia.anno }}° Año
                                        </option>
                                    </optgroup>
                                </select>
                                <p v-if="form.errors.materia_id" class="text-red-600 text-sm mt-1">{{ form.errors.materia_id }}</p>
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
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                />
                                <p v-if="form.errors.hora_examen" class="text-red-600 text-sm mt-1">{{ form.errors.hora_examen }}</p>
                            </div>

                            <!-- Período de Inscripción -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Período de Inscripción
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">Fecha Inicio</label>
                                        <input
                                            v-model="form.fecha_inicio_inscripcion"
                                            type="date"
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                        />
                                        <p v-if="form.errors.fecha_inicio_inscripcion" class="text-red-600 text-xs mt-1">{{ form.errors.fecha_inicio_inscripcion }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">Fecha Fin</label>
                                        <input
                                            v-model="form.fecha_fin_inscripcion"
                                            type="date"
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                        />
                                        <p v-if="form.errors.fecha_fin_inscripcion" class="text-red-600 text-xs mt-1">{{ form.errors.fecha_fin_inscripcion }}</p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Si no se especifica, la inscripción estará disponible todo el tiempo</p>
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

                            <!-- Aula -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Aula</label>
                                <input
                                    v-model="form.aula"
                                    type="text"
                                    placeholder="Ej: Aula 101"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                />
                                <p v-if="form.errors.aula" class="text-red-600 text-sm mt-1">{{ form.errors.aula }}</p>
                            </div>

                            <!-- Período -->
                            <div class="md:col-span-2">
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
                        </h3>

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
                            {{ form.processing ? 'Guardando...' : 'Crear Mesa' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </SidebarLayout>
</template>
