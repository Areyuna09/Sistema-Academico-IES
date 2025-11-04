<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import axios from 'axios';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    carreras: Array
});

const form = useForm({
    dni: '',
    apellido: '',
    nombre: '',
    email: '',
    carrera: '',
    division: '',
    materias: [] // Array de IDs de materias seleccionadas
});

const materiasDisponibles = ref([]);
const cargandoMaterias = ref(false);

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
    form.post(route('profesores.store'), {
        onSuccess: () => {
            // Redirige al panel de expedientes
        }
    });
};
</script>

<template>
    <SidebarLayout>
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Nuevo Profesor</h1>
                    <p class="text-gray-600 mt-2">Complete los datos del profesor para registrarlo en el sistema</p>
                </div>

                <!-- Formulario -->
                <form @submit.prevent="submit" class="bg-white shadow-lg rounded-lg p-8">
                    <!-- Datos personales -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Datos Personales</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- DNI -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    DNI <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.dni"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.dni }"
                                />
                                <div v-if="form.errors.dni" class="mt-1 text-sm text-red-600">{{ form.errors.dni }}</div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.email }"
                                />
                                <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</div>
                            </div>

                            <!-- Apellido -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Apellido <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.apellido"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.apellido }"
                                />
                                <div v-if="form.errors.apellido" class="mt-1 text-sm text-red-600">{{ form.errors.apellido }}</div>
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.nombre"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.nombre }"
                                />
                                <div v-if="form.errors.nombre" class="mt-1 text-sm text-red-600">{{ form.errors.nombre }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Asignación de materias -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Asignación de Materias</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <!-- Carrera -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Carrera <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.carrera"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    División <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.division"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.division }"
                                >
                                    <option value="">Seleccione división</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                                <div v-if="form.errors.division" class="mt-1 text-sm text-red-600">{{ form.errors.division }}</div>
                            </div>
                        </div>

                        <!-- Materias disponibles -->
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
                            <div v-else class="border border-gray-300 rounded-lg max-h-96 overflow-y-auto">
                                <div class="divide-y divide-gray-200">
                                    <div
                                        v-for="materia in materiasDisponibles"
                                        :key="materia.id"
                                        class="flex items-center p-3 hover:bg-gray-50"
                                    >
                                        <input
                                            :id="'materia-' + materia.id"
                                            v-model="form.materias"
                                            :value="materia.id"
                                            type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                        />
                                        <label
                                            :for="'materia-' + materia.id"
                                            class="ml-3 text-sm text-gray-700 cursor-pointer flex-1"
                                        >
                                            {{ materia.nombre }}
                                            <span class="text-gray-500 ml-2">({{ materia.anno }}° Año)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                Seleccione las materias que este profesor dictará
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
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <button
                            type="button"
                            @click="$inertia.visit(route('expediente.index'))"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Guardando...</span>
                            <span v-else>Guardar Profesor</span>
                        </button>
                    </div>

                    <!-- Error general -->
                    <div v-if="form.errors.error" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-800 text-sm">{{ form.errors.error }}</p>
                    </div>
                </form>
            </div>
        </div>
    </SidebarLayout>
</template>
