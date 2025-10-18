<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const form = useForm({
    nombre: '',
    resolucion: '',
});

const submit = () => {
    form.post(route('admin.carreras.store'));
};
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Nueva Carrera</h1>
                <p class="text-xs text-gray-400 mt-0.5">Crear una nueva carrera</p>
            </div>
        </template>

        <div class="p-8 max-w-3xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <form @submit.prevent="submit">
                    <div class="space-y-6">
                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre de la Carrera <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.nombre"
                                type="text"
                                required
                                maxlength="55"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                placeholder="Ej: Tecnicatura en Desarrollo de Software"
                            />
                            <p v-if="form.errors.nombre" class="text-red-600 text-sm mt-1">{{ form.errors.nombre }}</p>
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
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                placeholder="Ej: Res. N° 1234/2024"
                            />
                            <p v-if="form.errors.resolucion" class="text-red-600 text-sm mt-1">{{ form.errors.resolucion }}</p>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 mt-6">
                        <Link
                            :href="route('admin.carreras.index')"
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
                            {{ form.processing ? 'Guardando...' : 'Crear Carrera' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </SidebarLayout>
</template>
