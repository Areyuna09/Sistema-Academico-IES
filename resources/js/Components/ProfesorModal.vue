<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    profesor: {
        type: Object,
        default: null
    },
    carreras: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['close', 'saved']);

const form = useForm({
    dni: '',
    apellido: '',
    nombre: '',
    email: '',
    carrera: '',
    division: '',
    materias: []
});

// Guardar referencia al profesor para evitar problemas con el reactivo
const profesorActual = ref(null);

const materiasDisponibles = ref([]);
const cargandoMaterias = ref(false);

// Computed para agrupar materias por plan
const materiasAgrupadasPorPlan = computed(() => {
    const grupos = {};

    materiasDisponibles.value.forEach(materia => {
        if (materia.planes && materia.planes.length > 0) {
            // Agregar materia a cada plan al que pertenece
            materia.planes.forEach(plan => {
                if (!grupos[plan.id]) {
                    grupos[plan.id] = {
                        plan: plan,
                        materias: []
                    };
                }
                grupos[plan.id].materias.push(materia);
            });
        } else {
            // Materias sin plan
            if (!grupos['sin_plan']) {
                grupos['sin_plan'] = {
                    plan: { id: null, nombre: 'Sin plan asignado', vigente: false },
                    materias: []
                };
            }
            grupos['sin_plan'].materias.push(materia);
        }
    });

    // Convertir a array y ordenar: vigente primero, luego por año descendente
    return Object.values(grupos).sort((a, b) => {
        // Sin plan al final
        if (a.plan.id === null) return 1;
        if (b.plan.id === null) return -1;
        // Vigente primero
        if (a.plan.vigente && !b.plan.vigente) return -1;
        if (!a.plan.vigente && b.plan.vigente) return 1;
        // Por año descendente
        return (b.plan.anio || 0) - (a.plan.anio || 0);
    });
});

// Si hay un profesor (modo edición), cargar sus datos
watch(() => props.profesor, (nuevoProfesor) => {
    console.log('ProfesorModal - profesor cambiado:', nuevoProfesor);
    if (nuevoProfesor) {
        profesorActual.value = nuevoProfesor; // Guardar referencia
        form.dni = nuevoProfesor.dni || '';
        form.apellido = nuevoProfesor.apellido || '';
        form.nombre = nuevoProfesor.nombre || '';
        form.email = nuevoProfesor.email || '';
        // Si carrera es un objeto (relación cargada), extraer solo el ID
        form.carrera = (typeof nuevoProfesor.carrera === 'object' && nuevoProfesor.carrera?.Id)
            ? nuevoProfesor.carrera.Id
            : (nuevoProfesor.carrera || '');
        form.division = nuevoProfesor.division || '';
        form.materias = nuevoProfesor.materias || [];
        console.log('ProfesorModal - materias cargadas:', form.materias);
        console.log('ProfesorModal - carrera ID:', form.carrera);
    } else {
        profesorActual.value = null;
        form.reset();
        materiasDisponibles.value = [];
    }
}, { immediate: true });

// Cargar materias cuando cambia la carrera
watch(() => form.carrera, async (nuevaCarrera) => {
    if (!nuevaCarrera) {
        materiasDisponibles.value = [];
        form.materias = [];
        return;
    }

    cargandoMaterias.value = true;
    try {
        const response = await axios.get(route('expediente.materias-disponibles'), {
            params: { carrera_id: nuevaCarrera }
        });
        materiasDisponibles.value = response.data;
    } catch (error) {
        console.error('Error al cargar materias:', error);
    } finally {
        cargandoMaterias.value = false;
    }
});

const submit = () => {
    console.log('=== SUBMIT PROFESOR ===');
    console.log('props.profesor:', props.profesor);
    console.log('profesorActual.value:', profesorActual.value);
    console.log('profesorActual.value?.id:', profesorActual.value?.id);

    // Usar profesorActual en lugar de props.profesor para evitar problemas de reactividad
    const url = profesorActual.value
        ? route('profesores.update', profesorActual.value.id)
        : route('profesores.store');

    const method = profesorActual.value ? 'put' : 'post';

    console.log('URL:', url);
    console.log('Method:', method);
    console.log('Form data:', form.data());

    form[method](url, {
        preserveScroll: true,
        onSuccess: () => {
            emit('saved');
            emit('close');
            form.reset();
            materiasDisponibles.value = [];
            profesorActual.value = null;
        },
        onError: (errors) => {
            console.error('Errores al guardar profesor:', errors);
        }
    });
};

const close = () => {
    form.reset();
    form.clearErrors();
    materiasDisponibles.value = [];
    profesorActual.value = null;
    emit('close');
};
</script>

<template>
    <!-- Backdrop -->
    <Transition
        enter-active-class="transition-opacity duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="show"
            class="fixed inset-0 bg-black bg-opacity-50 z-40"
            @click="close"
        ></div>
    </Transition>

    <!-- Modal -->
    <Transition
        enter-active-class="transition-all duration-200"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition-all duration-200"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div
            v-if="show"
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
        >
            <div
                class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
                @click.stop
            >
                <!-- Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ profesorActual ? 'Editar Profesor' : 'Nuevo Profesor' }}
                    </h2>
                    <button
                        @click="close"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <i class="bx bx-x text-3xl"></i>
                    </button>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="p-6">
                    <!-- Datos personales -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Datos Personales</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- DNI -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    DNI <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.dni"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.dni }"
                                />
                                <div v-if="form.errors.dni" class="mt-1 text-sm text-red-600">{{ form.errors.dni }}</div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.email }"
                                />
                                <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</div>
                            </div>

                            <!-- Apellido -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Apellido <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.apellido"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.apellido }"
                                />
                                <div v-if="form.errors.apellido" class="mt-1 text-sm text-red-600">{{ form.errors.apellido }}</div>
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nombre <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.nombre"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.nombre }"
                                />
                                <div v-if="form.errors.nombre" class="mt-1 text-sm text-red-600">{{ form.errors.nombre }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Asignación de materias -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Asignación de Materias</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Carrera -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Carrera <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.carrera"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.carrera }"
                                >
                                    <option value="">Seleccione una carrera</option>
                                    <option v-for="carrera in carreras" :key="carrera.Id" :value="carrera.Id">
                                        {{ carrera.nombre }}
                                    </option>
                                </select>
                                <div v-if="form.errors.carrera" class="mt-1 text-sm text-red-600">{{ form.errors.carrera }}</div>
                            </div>

                            <!-- División -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    División <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.division"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.division }"
                                >
                                    <option value="">Seleccione división</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                                <div v-if="form.errors.division" class="mt-1 text-sm text-red-600">{{ form.errors.division }}</div>
                            </div>
                        </div>

                        <!-- Materias disponibles agrupadas por plan -->
                        <div v-if="form.carrera">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Materias a dictar
                            </label>
                            <div v-if="cargandoMaterias" class="text-sm text-gray-500 py-4">
                                Cargando materias...
                            </div>
                            <div v-else-if="materiasDisponibles.length === 0" class="text-sm text-gray-500 py-4">
                                No hay materias disponibles para esta carrera
                            </div>
                            <div v-else class="space-y-3 max-h-96 overflow-y-auto">
                                <!-- Grupo por cada plan -->
                                <div
                                    v-for="grupo in materiasAgrupadasPorPlan"
                                    :key="grupo.plan.id || 'sin_plan'"
                                    class="border rounded-lg overflow-hidden"
                                    :class="grupo.plan.id === null ? 'border-yellow-300' : (grupo.plan.vigente ? 'border-blue-300' : 'border-gray-300')"
                                >
                                    <!-- Header del plan -->
                                    <div
                                        class="px-3 py-2 font-medium text-sm flex items-center justify-between"
                                        :class="grupo.plan.id === null ? 'bg-yellow-50 text-yellow-900' : (grupo.plan.vigente ? 'bg-blue-50 text-blue-900' : 'bg-gray-50 text-gray-900')"
                                    >
                                        <span>
                                            {{ grupo.plan.nombre }}
                                            <span v-if="grupo.plan.vigente" class="ml-1" title="Plan vigente para nuevos inscriptos">✓</span>
                                        </span>
                                        <span class="text-xs font-normal opacity-75">
                                            {{ grupo.materias.length }} {{ grupo.materias.length === 1 ? 'materia' : 'materias' }}
                                        </span>
                                    </div>

                                    <!-- Lista de materias del plan -->
                                    <div class="divide-y divide-gray-200">
                                        <div
                                            v-for="materia in grupo.materias"
                                            :key="materia.id"
                                            class="flex items-start p-3 hover:bg-gray-50"
                                        >
                                            <input
                                                :id="'materia-' + materia.id + '-plan-' + (grupo.plan.id || 'sin_plan')"
                                                v-model="form.materias"
                                                :value="materia.id"
                                                type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mt-0.5"
                                            />
                                            <label
                                                :for="'materia-' + materia.id + '-plan-' + (grupo.plan.id || 'sin_plan')"
                                                class="ml-3 text-sm text-gray-700 cursor-pointer flex-1"
                                            >
                                                <div class="font-medium">{{ materia.nombre }}</div>
                                                <div class="text-xs text-gray-500 mt-0.5">
                                                    {{ materia.anno }}° Año - Cuatrimestre {{ materia.semestre }}
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                Las materias están agrupadas por plan de estudio. Seleccione las que este profesor dictará.
                            </p>
                        </div>
                        <div v-else class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <i class="bx bx-info-circle mr-2"></i>
                                Primero seleccione una carrera para ver las materias disponibles
                            </p>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button
                            type="button"
                            @click="close"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Guardando...</span>
                            <span v-else>{{ profesorActual ? 'Actualizar' : 'Crear' }} Profesor</span>
                        </button>
                    </div>

                    <!-- Error general -->
                    <div v-if="form.errors.error" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-800 text-sm">{{ form.errors.error }}</p>
                    </div>
                </form>
            </div>
        </div>
    </Transition>
</template>
