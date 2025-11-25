<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    regla: Object,
    materias: Array,
    carreras: Array,
});

const form = useForm({
    materia_id: props.regla.materia_id,
    carrera_id: props.regla.carrera_id,
    tipo: props.regla.tipo,
    correlativa_id: props.regla.correlativa_id,
    estado_requerido: props.regla.estado_requerido,
    es_activa: props.regla.es_activa,
    observaciones: props.regla.observaciones || '',
});

// Materias filtradas por carrera
const materiasPorCarrera = computed(() => {
    if (!form.carrera_id) return props.materias;
    return props.materias.filter(m => m.carrera === parseInt(form.carrera_id));
});

// Materias filtradas para correlativa (excluir la materia seleccionada)
const materiasDisponibles = computed(() => {
    if (!form.materia_id) return materiasPorCarrera.value;
    return materiasPorCarrera.value.filter(m => m.id !== parseInt(form.materia_id));
});

const submit = () => {
    form.put(route('admin.correlativas.update', props.regla.id));
};
</script>

<template>
    <Head title="Editar Regla de Correlativa" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Editar Regla de Correlativa</h1>
                <p class="text-xs text-gray-400 mt-0.5">Modifica los parámetros de la regla de correlativa</p>
            </div>
        </template>

        <div class="p-8 max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <Link :href="route('admin.correlativas.index')"
                    class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                    <i class="bx bx-arrow-back mr-1"></i>
                    Volver a Correlativas
                </Link>
            </div>

                <!-- Formulario -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Carrera -->
                        <div>
                            <label for="carrera_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Carrera <span class="text-red-500">*</span>
                            </label>
                            <select id="carrera_id" v-model="form.carrera_id"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.carrera_id }">
                                <option value="">Seleccione una carrera</option>
                                <option v-for="carrera in carreras" :key="carrera.id" :value="carrera.id">
                                    {{ carrera.nombre }}
                                </option>
                            </select>
                            <p v-if="form.errors.carrera_id" class="mt-1 text-sm text-red-600">
                                {{ form.errors.carrera_id }}
                            </p>
                        </div>

                        <!-- Tipo de validación -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Validación <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                                    :class="{ 'border-blue-500 bg-blue-50': form.tipo === 'cursar', 'border-gray-300': form.tipo !== 'cursar' }">
                                    <input type="radio" v-model="form.tipo" value="cursar" class="sr-only">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <i class="bx bx-book-open text-2xl text-blue-600 mr-3"></i>
                                            <div>
                                                <p class="font-semibold text-gray-900">Para Cursar</p>
                                                <p class="text-xs text-gray-600">Validación para inscripción a cursado</p>
                                            </div>
                                        </div>
                                    </div>
                                    <i v-if="form.tipo === 'cursar'" class="bx bx-check-circle text-2xl text-blue-600"></i>
                                </label>

                                <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                                    :class="{ 'border-purple-500 bg-purple-50': form.tipo === 'rendir', 'border-gray-300': form.tipo !== 'rendir' }">
                                    <input type="radio" v-model="form.tipo" value="rendir" class="sr-only">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <i class="bx bx-clipboard text-2xl text-purple-600 mr-3"></i>
                                            <div>
                                                <p class="font-semibold text-gray-900">Para Rendir</p>
                                                <p class="text-xs text-gray-600">Validación para inscripción a examen</p>
                                            </div>
                                        </div>
                                    </div>
                                    <i v-if="form.tipo === 'rendir'" class="bx bx-check-circle text-2xl text-purple-600"></i>
                                </label>
                            </div>
                        </div>

                        <!-- Materia principal -->
                        <div>
                            <label for="materia_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Materia <span class="text-red-500">*</span>
                            </label>
                            <select id="materia_id" v-model="form.materia_id"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.materia_id }">
                                <option value="">Seleccione la materia</option>
                                <option v-for="materia in materiasPorCarrera" :key="materia.id" :value="materia.id">
                                    {{ materia.nombre }} ({{ materia.anno }}° año - {{ materia.semestre === 1 ? '1er' : '2do' }} Cuatr.)
                                </option>
                            </select>
                            <p v-if="form.errors.materia_id" class="mt-1 text-sm text-red-600">
                                {{ form.errors.materia_id }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">La materia a la cual se le aplicará esta correlativa</p>
                        </div>

                        <!-- Materia correlativa -->
                        <div>
                            <label for="correlativa_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Materia Correlativa <span class="text-red-500">*</span>
                            </label>
                            <select id="correlativa_id" v-model="form.correlativa_id"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.correlativa_id }">
                                <option value="">Seleccione la correlativa</option>
                                <option v-for="materia in materiasDisponibles" :key="materia.id" :value="materia.id">
                                    {{ materia.nombre }} ({{ materia.anno }}° año - {{ materia.semestre === 1 ? '1er' : '2do' }} Cuatr.)
                                </option>
                            </select>
                            <p v-if="form.errors.correlativa_id" class="mt-1 text-sm text-red-600">
                                {{ form.errors.correlativa_id }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">La materia que debe estar aprobada/regular</p>
                        </div>

                        <!-- Estado requerido -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Estado Requerido <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                                    :class="{ 'border-yellow-500 bg-yellow-50': form.estado_requerido === 'regular', 'border-gray-300': form.estado_requerido !== 'regular' }">
                                    <input type="radio" v-model="form.estado_requerido" value="regular" class="sr-only">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <i class="bx bx-check text-2xl text-yellow-600 mr-3"></i>
                                            <div>
                                                <p class="font-semibold text-gray-900">Regular</p>
                                                <p class="text-xs text-gray-600">Materia cursada y regularizada</p>
                                            </div>
                                        </div>
                                    </div>
                                    <i v-if="form.estado_requerido === 'regular'" class="bx bx-check-circle text-2xl text-yellow-600"></i>
                                </label>

                                <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                                    :class="{ 'border-green-500 bg-green-50': form.estado_requerido === 'aprobada', 'border-gray-300': form.estado_requerido !== 'aprobada' }">
                                    <input type="radio" v-model="form.estado_requerido" value="aprobada" class="sr-only">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <i class="bx bx-badge-check text-2xl text-green-600 mr-3"></i>
                                            <div>
                                                <p class="font-semibold text-gray-900">Aprobada</p>
                                                <p class="text-xs text-gray-600">Materia aprobada con examen final</p>
                                            </div>
                                        </div>
                                    </div>
                                    <i v-if="form.estado_requerido === 'aprobada'" class="bx bx-check-circle text-2xl text-green-600"></i>
                                </label>
                            </div>
                        </div>

                        <!-- Estado activa/inactiva -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Estado de la Regla</label>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Las reglas inactivas no se aplican en las validaciones
                                    </p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="form.es_activa" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ml-3 text-sm font-medium" :class="form.es_activa ? 'text-blue-600' : 'text-gray-500'">
                                        {{ form.es_activa ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div>
                            <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">
                                Observaciones
                            </label>
                            <textarea id="observaciones" v-model="form.observaciones" rows="3"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Notas adicionales sobre esta regla (opcional)"></textarea>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end gap-3 pt-6 border-t">
                            <Link :href="route('admin.correlativas.index')"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors duration-200">
                                Cancelar
                            </Link>
                            <button type="submit" :disabled="form.processing"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                                <i v-if="form.processing" class="bx bx-loader-alt animate-spin mr-2"></i>
                                <i v-else class="bx bx-save mr-2"></i>
                                {{ form.processing ? 'Actualizando...' : 'Actualizar Regla' }}
                            </button>
                        </div>
                    </form>
                </div>
        </div>
    </SidebarLayout>
</template>
