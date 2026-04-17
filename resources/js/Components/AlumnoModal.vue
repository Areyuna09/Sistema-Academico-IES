<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    show:     { type: Boolean, default: false },
    alumno:   { type: Object,  default: null },
    carreras: { type: Array,   required: true }
});

const emit = defineEmits(['close', 'saved']);

const mostrarSegundaCarrera = ref(false);

const form = useForm({
    dni:       '',
    apellido:  '',
    nombre:    '',
    email:     '',
    telefono:  '',
    celular:   '',
    legajo:    '',
    anno:      new Date().getFullYear(),
    carrera:   '',
    curso:     '',
    division:  '',
    // Segunda carrera
    carrera2:  '',
    legajo2:   '',
    anno2:     new Date().getFullYear(),
    curso2:    '',
    division2: '',
});

watch(() => props.alumno, (a) => {
    mostrarSegundaCarrera.value = false;
    if (a) {
        form.dni       = a.dni       || '';
        form.apellido  = a.apellido  || '';
        form.nombre    = a.nombre    || '';
        form.email     = a.email     || '';
        form.telefono  = a.telefono  || '';
        form.celular   = a.celular   || '';
        form.legajo    = a.legajo    || '';
        form.anno      = a.anno      || new Date().getFullYear();
        form.carrera   = (typeof a.carrera === 'object' && a.carrera?.Id) ? a.carrera.Id : (a.carrera || '');
        form.curso     = a.curso     || '';
        form.division  = a.division  != null ? String(a.division) : '';
        form.carrera2  = a.carrera2  || '';
        form.legajo2   = a.legajo2   || '';
        form.anno2     = a.anno2     || new Date().getFullYear();
        form.curso2    = a.curso2    || '';
        form.division2 = a.division2 != null ? String(a.division2) : '';

        if (a.carrera2) mostrarSegundaCarrera.value = true;
    } else {
        form.reset();
        form.anno = new Date().getFullYear();
    }
}, { immediate: true });

// Calcular curso automáticamente al crear
watch(() => form.anno, (v) => {
    if (v && !props.alumno) {
        form.curso = Math.min(Math.max(new Date().getFullYear() - v + 1, 1), 6);
    }
});

const quitarSegundaCarrera = () => {
    form.carrera2  = '';
    form.legajo2   = '';
    form.anno2     = new Date().getFullYear();
    form.curso2    = '';
    form.division2 = '';
    mostrarSegundaCarrera.value = false;
};

const carrerasDisponiblesParaSegunda = () =>
    props.carreras.filter(c => String(c.Id) !== String(form.carrera));

const submit = () => {
    const url    = props.alumno ? route('alumnos.update', props.alumno.id) : route('alumnos.store');
    const method = props.alumno ? 'put' : 'post';
    form[method](url, {
        preserveScroll: true,
        onSuccess: () => { emit('saved'); emit('close'); form.reset(); },
        onError: (e) => console.error(e),
    });
};

const close = () => {
    form.reset();
    form.clearErrors();
    mostrarSegundaCarrera.value = false;
    emit('close');
};
</script>

<template>
    <Transition enter-active-class="transition-opacity duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition-opacity duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 z-40" @click="close"></div>
    </Transition>

    <Transition enter-active-class="transition-all duration-200" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition-all duration-200" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
        <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" @click.stop>

                <!-- Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900">{{ alumno ? 'Editar Alumno' : 'Nuevo Alumno' }}</h2>
                    <button @click="close" class="text-gray-400 hover:text-gray-600"><i class="bx bx-x text-3xl"></i></button>
                </div>

                <form @submit.prevent="submit" class="p-6">

                    <!-- Datos personales -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Datos Personales</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">DNI <span class="text-red-500">*</span></label>
                                <input v-model="form.dni" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" :class="{ 'border-red-500': form.errors.dni }" />
                                <p v-if="form.errors.dni" class="mt-1 text-sm text-red-600">{{ form.errors.dni }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Legajo</label>
                                <input v-model="form.legajo" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Apellido <span class="text-red-500">*</span></label>
                                <input v-model="form.apellido" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" :class="{ 'border-red-500': form.errors.apellido }" />
                                <p v-if="form.errors.apellido" class="mt-1 text-sm text-red-600">{{ form.errors.apellido }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                                <input v-model="form.nombre" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" :class="{ 'border-red-500': form.errors.nombre }" />
                                <p v-if="form.errors.nombre" class="mt-1 text-sm text-red-600">{{ form.errors.nombre }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input v-model="form.email" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" :class="{ 'border-red-500': form.errors.email }" />
                                <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                <input v-model="form.telefono" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Celular</label>
                                <input v-model="form.celular" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
                            </div>
                        </div>
                    </div>

                    <!-- Carrera principal -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Carrera Principal</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Carrera <span class="text-red-500">*</span></label>
                                <select v-model="form.carrera" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" :class="{ 'border-red-500': form.errors.carrera }">
                                    <option value="">Seleccione una carrera</option>
                                    <option v-for="c in carreras" :key="c.Id" :value="c.Id">{{ c.nombre }}</option>
                                </select>
                                <p v-if="form.errors.carrera" class="mt-1 text-sm text-red-600">{{ form.errors.carrera }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Año de ingreso</label>
                                <input v-model="form.anno" type="number" min="1900" :max="new Date().getFullYear() + 1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Curso <span class="text-xs text-gray-400">(calculado)</span></label>
                                <input v-model="form.curso" type="number" min="1" max="6" disabled class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">División</label>
                                <select v-model="form.division" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="">Sin división</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Segunda carrera -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between border-b pb-2 mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <i class="bx bx-book-add mr-1 text-indigo-600"></i>
                                Segunda Carrera
                                <span class="text-sm font-normal text-gray-400 ml-1">(opcional)</span>
                            </h3>
                            <div class="flex gap-2">
                                <button
                                    v-if="!mostrarSegundaCarrera"
                                    type="button"
                                    @click="mostrarSegundaCarrera = true"
                                    class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors"
                                >
                                    <i class="bx bx-plus mr-1"></i>Agregar segunda carrera
                                </button>
                                <button
                                    v-else
                                    type="button"
                                    @click="quitarSegundaCarrera"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-semibold rounded-lg transition-colors"
                                >
                                    <i class="bx bx-trash mr-1"></i>Quitar segunda carrera
                                </button>
                            </div>
                        </div>

                        <div v-if="mostrarSegundaCarrera" class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Carrera <span class="text-red-500">*</span></label>
                                    <select v-model="form.carrera2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" :class="{ 'border-red-500': form.errors.carrera2 }">
                                        <option value="">Seleccione una carrera</option>
                                        <option v-for="c in carrerasDisponiblesParaSegunda()" :key="c.Id" :value="c.Id">{{ c.nombre }}</option>
                                    </select>
                                    <p v-if="form.errors.carrera2" class="mt-1 text-sm text-red-600">{{ form.errors.carrera2 }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Año de ingreso</label>
                                    <input v-model="form.anno2" type="number" min="1900" :max="new Date().getFullYear() + 1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Legajo</label>
                                    <input v-model="form.legajo2" type="text" placeholder="Ej: B-456" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Curso</label>
                                    <input v-model="form.curso2" type="number" min="1" max="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">División</label>
                                    <select v-model="form.division2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Sin división</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" @click="close" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">Cancelar</button>
                        <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors disabled:opacity-50">
                            <span v-if="form.processing">Guardando...</span>
                            <span v-else>{{ alumno ? 'Actualizar' : 'Crear' }} Alumno</span>
                        </button>
                    </div>

                    <div v-if="form.errors.error" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-800 text-sm">{{ form.errors.error }}</p>
                    </div>
                </form>
            </div>
        </div>
    </Transition>
</template>
