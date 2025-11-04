<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    alumno: {
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
    telefono: '',
    celular: '',
    legajo: '',
    anno: new Date().getFullYear(), // Año actual por defecto
    carrera: '',
    curso: '',
    division: ''
});

// Si hay un alumno (modo edición), cargar sus datos
watch(() => props.alumno, (nuevoAlumno) => {
    if (nuevoAlumno) {
        form.dni = nuevoAlumno.dni || '';
        form.apellido = nuevoAlumno.apellido || '';
        form.nombre = nuevoAlumno.nombre || '';
        form.email = nuevoAlumno.email || '';
        form.telefono = nuevoAlumno.telefono || '';
        form.celular = nuevoAlumno.celular || '';
        form.legajo = nuevoAlumno.legajo || '';
        form.anno = nuevoAlumno.anno || new Date().getFullYear();
        form.carrera = nuevoAlumno.carrera || '';
        form.curso = nuevoAlumno.curso || '';
        form.division = nuevoAlumno.division || '';
    } else {
        form.reset();
        form.anno = new Date().getFullYear(); // Resetear al año actual
    }
}, { immediate: true });

// Calcular curso automáticamente según año de ingreso
watch(() => form.anno, (nuevoAnno) => {
    if (nuevoAnno) {
        const annoActual = new Date().getFullYear();
        const cursoCalculado = annoActual - nuevoAnno + 1;
        // El curso debe estar entre 1 y 6
        if (cursoCalculado >= 1 && cursoCalculado <= 6) {
            form.curso = cursoCalculado;
        } else if (cursoCalculado < 1) {
            form.curso = 1;
        } else {
            form.curso = 6;
        }
    }
});

const submit = () => {
    const url = props.alumno
        ? route('alumnos.update', props.alumno.id)
        : route('alumnos.store');

    const method = props.alumno ? 'put' : 'post';

    form[method](url, {
        preserveScroll: true,
        onSuccess: () => {
            emit('saved');
            emit('close');
            form.reset();
        },
        onError: (errors) => {
            console.error('Errores al guardar alumno:', errors);
        }
    });
};

const close = () => {
    form.reset();
    form.clearErrors();
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
                        {{ alumno ? 'Editar Alumno' : 'Nuevo Alumno' }}
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

                            <!-- Legajo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Legajo</label>
                                <input
                                    v-model="form.legajo"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
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

                            <!-- Teléfono -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                <input
                                    v-model="form.telefono"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                            </div>

                            <!-- Celular -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Celular</label>
                                <input
                                    v-model="form.celular"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Datos académicos -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Datos Académicos</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                            <!-- Año -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Año de ingreso</label>
                                <input
                                    v-model="form.anno"
                                    type="number"
                                    min="1900"
                                    :max="new Date().getFullYear() + 1"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                            </div>

                            <!-- Curso (calculado automáticamente) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Curso
                                    <span class="text-xs text-gray-500 font-normal">(calculado automáticamente)</span>
                                </label>
                                <input
                                    v-model="form.curso"
                                    type="number"
                                    min="1"
                                    max="6"
                                    disabled
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 cursor-not-allowed"
                                />
                            </div>

                            <!-- División -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">División</label>
                                <select
                                    v-model="form.division"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="">Seleccione división</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
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
                            <span v-else>{{ alumno ? 'Actualizar' : 'Crear' }} Alumno</span>
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
