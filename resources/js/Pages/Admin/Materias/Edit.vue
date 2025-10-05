<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    materia: Object,
    carreras: Array,
});

const form = useForm({
    nombre: props.materia.nombre,
    carrera: props.materia.carrera,
    semestre: props.materia.semestre,
    anno: props.materia.anno,
    resolucion: props.materia.resolucion,
});

const submit = () => {
    form.put(route('admin.materias.update', props.materia.id));
};
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Editar Materia</h1>
                <p class="text-xs text-gray-400 mt-0.5">Modificar datos de {{ materia.nombre }}</p>
            </div>
        </template>

        <div class="p-8 max-w-3xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <form @submit.prevent="submit">
                    <div class="space-y-6">
                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre de la Materia <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.nombre"
                                type="text"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                placeholder="Ej: Programación I"
                            />
                            <p v-if="form.errors.nombre" class="text-red-600 text-sm mt-1">{{ form.errors.nombre }}</p>
                        </div>

                        <!-- Carrera -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Carrera <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.carrera"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            >
                                <option value="">Seleccione una carrera</option>
                                <option v-for="carrera in carreras" :key="carrera.Id" :value="carrera.Id">
                                    {{ carrera.nombre }}
                                </option>
                            </select>
                            <p v-if="form.errors.carrera" class="text-red-600 text-sm mt-1">{{ form.errors.carrera }}</p>
                        </div>

                        <!-- Año y Semestre -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Año <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.anno"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option value="1">1° Año</option>
                                    <option value="2">2° Año</option>
                                    <option value="3">3° Año</option>
                                    <option value="4">4° Año</option>
                                    <option value="5">5° Año</option>
                                    <option value="6">6° Año</option>
                                </select>
                                <p v-if="form.errors.anno" class="text-red-600 text-sm mt-1">{{ form.errors.anno }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Semestre <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.semestre"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option value="1">1° Semestre</option>
                                    <option value="2">2° Semestre</option>
                                </select>
                                <p v-if="form.errors.semestre" class="text-red-600 text-sm mt-1">{{ form.errors.semestre }}</p>
                            </div>
                        </div>

                        <!-- Resolución -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Resolución
                            </label>
                            <input
                                v-model="form.resolucion"
                                type="text"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                placeholder="Ej: Res. N° 1234/2024"
                            />
                            <p v-if="form.errors.resolucion" class="text-red-600 text-sm mt-1">{{ form.errors.resolucion }}</p>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 mt-6">
                        <Link
                            :href="route('admin.materias.index')"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 disabled:opacity-50"
                        >
                            <i class="bx bx-save mr-1"></i>
                            {{ form.processing ? 'Guardando...' : 'Actualizar Materia' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </SidebarLayout>
</template>
