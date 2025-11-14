<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch, computed } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    materia: {
        type: Object,
        default: null
    },
    carreras: {
        type: Array,
        required: true
    },
    duracionCarreras: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['close', 'saved']);

const form = useForm({
    nombre: '',
    carrera: '',
    semestre: '',
    anno: '',
    resolucion: ''
});

// Computed para obtener los años disponibles según la carrera seleccionada
const annosDisponibles = computed(() => {
    if (!form.carrera) return [];

    const maxAnno = props.duracionCarreras[form.carrera] || 4;
    const annos = [];

    for (let i = 1; i <= maxAnno; i++) {
        annos.push(i);
    }

    return annos;
});

// Si hay una materia (modo edición), cargar sus datos
watch(() => props.materia, (nuevaMateria) => {
    if (nuevaMateria) {
        form.nombre = nuevaMateria.nombre || '';
        // Si carrera es un objeto (relación cargada), extraer solo el ID
        form.carrera = (typeof nuevaMateria.carrera === 'object' && nuevaMateria.carrera?.Id)
            ? nuevaMateria.carrera.Id
            : (nuevaMateria.carrera || '');
        form.semestre = nuevaMateria.semestre || '';
        form.anno = nuevaMateria.anno || '';
        form.resolucion = nuevaMateria.resolucion || '';
    } else {
        form.reset();
    }
}, { immediate: true });

const submit = () => {
    const url = props.materia
        ? route('admin.materias.update', props.materia.id)
        : route('admin.materias.store');

    const method = props.materia ? 'put' : 'post';

    form[method](url, {
        preserveScroll: true,
        onSuccess: () => {
            emit('saved');
            emit('close');
            form.reset();
        },
        onError: (errors) => {
            console.error('Errores al guardar materia:', errors);
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
                class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                @click.stop
            >
                <!-- Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ materia ? 'Editar Materia' : 'Nueva Materia' }}
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
                    <div class="space-y-4">
                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre de la Materia <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.nombre"
                                type="text"
                                required
                                maxlength="100"
                                pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\d\-\(\)\.]+"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :class="{ 'border-red-500': form.errors.nombre }"
                                placeholder="Ej: Programación I"
                                title="Solo letras, números, espacios, guiones y paréntesis"
                            />
                            <div v-if="form.errors.nombre" class="mt-1 text-sm text-red-600">{{ form.errors.nombre }}</div>
                        </div>

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

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Año -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Año <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.anno"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.anno }"
                                    :disabled="!form.carrera"
                                >
                                    <option value="">{{ form.carrera ? 'Año' : 'Seleccione una carrera primero' }}</option>
                                    <option v-for="anno in annosDisponibles" :key="anno" :value="anno">
                                        {{ anno }}° Año
                                    </option>
                                </select>
                                <div v-if="form.errors.anno" class="mt-1 text-sm text-red-600">{{ form.errors.anno }}</div>
                            </div>

                            <!-- Cuatrimestre -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Cuatrimestre <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.semestre"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.semestre }"
                                >
                                    <option value="">Cuatrimestre</option>
                                    <option value="1">1° Cuatrimestre</option>
                                    <option value="2">2° Cuatrimestre</option>
                                </select>
                                <div v-if="form.errors.semestre" class="mt-1 text-sm text-red-600">{{ form.errors.semestre }}</div>
                            </div>

                            <!-- Resolución -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Resolución
                                </label>
                                <input
                                    v-model="form.resolucion"
                                    type="text"
                                    maxlength="55"
                                    pattern="[a-zA-Z0-9\s\-\/]*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.resolucion }"
                                    placeholder="N° Resolución"
                                    title="Solo letras, números, espacios, guiones y barras"
                                />
                                <div v-if="form.errors.resolucion" class="mt-1 text-sm text-red-600">{{ form.errors.resolucion }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-6 border-t mt-6">
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
                            <span v-else>{{ materia ? 'Actualizar' : 'Crear' }} Materia</span>
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
