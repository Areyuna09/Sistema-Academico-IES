<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const currentYear = new Date().getFullYear();

const form = useForm({
    nombre: '',
    cuatrimestre: 1,
    anio: currentYear,
    fecha_inicio_inscripcion: '',
    fecha_fin_inscripcion: '',
    fecha_inicio_cursada: '',
    fecha_fin_cursada: '',
    activo: false,
});

const validationErrors = ref({});

// Validar que no sea fin de semana
const esFinDeSemana = (fecha) => {
    if (!fecha) return false;
    const date = new Date(fecha + 'T00:00:00');
    const dayOfWeek = date.getDay();
    return dayOfWeek === 0 || dayOfWeek === 6;
};

const submit = () => {
    validationErrors.value = {};

    // Validar fechas de fin de semana
    if (esFinDeSemana(form.fecha_inicio_inscripcion)) {
        validationErrors.value.fecha_inicio_inscripcion = 'No se pueden seleccionar sábados ni domingos';
    }
    if (esFinDeSemana(form.fecha_fin_inscripcion)) {
        validationErrors.value.fecha_fin_inscripcion = 'No se pueden seleccionar sábados ni domingos';
    }
    if (esFinDeSemana(form.fecha_inicio_cursada)) {
        validationErrors.value.fecha_inicio_cursada = 'No se pueden seleccionar sábados ni domingos';
    }
    if (esFinDeSemana(form.fecha_fin_cursada)) {
        validationErrors.value.fecha_fin_cursada = 'No se pueden seleccionar sábados ni domingos';
    }

    // Si hay errores de validación, no enviar
    if (Object.keys(validationErrors.value).length > 0) {
        return;
    }

    form.post(route('admin.periodos.store'));
};
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Nuevo Período Lectivo</h1>
                <p class="text-xs text-gray-400 mt-0.5">Crear un nuevo período de inscripción y cursada</p>
            </div>
        </template>

        <div class="p-8 max-w-3xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <form @submit.prevent="submit">
                    <div class="space-y-6">
                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre del Período <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.nombre"
                                type="text"
                                required
                                maxlength="100"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                placeholder="Ej: Primer Cuatrimestre 2025"
                            />
                            <p v-if="form.errors.nombre" class="text-red-600 text-sm mt-1">{{ form.errors.nombre }}</p>
                        </div>

                        <!-- Año y Cuatrimestre -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Año <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.anio"
                                    type="number"
                                    required
                                    min="2020"
                                    max="2050"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                />
                                <p v-if="form.errors.anio" class="text-red-600 text-sm mt-1">{{ form.errors.anio }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Cuatrimestre <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.cuatrimestre"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option value="1">1° Cuatrimestre</option>
                                    <option value="2">2° Cuatrimestre</option>
                                </select>
                                <p v-if="form.errors.cuatrimestre" class="text-red-600 text-sm mt-1">{{ form.errors.cuatrimestre }}</p>
                            </div>
                        </div>

                        <!-- Fechas de Inscripción -->
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Período de Inscripción</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Fecha Inicio <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.fecha_inicio_inscripcion"
                                        type="date"
                                        required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    />
                                    <p v-if="validationErrors.fecha_inicio_inscripcion || form.errors.fecha_inicio_inscripcion" class="text-red-600 text-sm mt-1">
                                        {{ validationErrors.fecha_inicio_inscripcion || form.errors.fecha_inicio_inscripcion }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Fecha Fin <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.fecha_fin_inscripcion"
                                        type="date"
                                        required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    />
                                    <p v-if="validationErrors.fecha_fin_inscripcion || form.errors.fecha_fin_inscripcion" class="text-red-600 text-sm mt-1">
                                        {{ validationErrors.fecha_fin_inscripcion || form.errors.fecha_fin_inscripcion }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Fechas de Cursada -->
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Período de Cursada</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Fecha Inicio <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.fecha_inicio_cursada"
                                        type="date"
                                        required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    />
                                    <p v-if="validationErrors.fecha_inicio_cursada || form.errors.fecha_inicio_cursada" class="text-red-600 text-sm mt-1">
                                        {{ validationErrors.fecha_inicio_cursada || form.errors.fecha_inicio_cursada }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Fecha Fin <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.fecha_fin_cursada"
                                        type="date"
                                        required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    />
                                    <p v-if="validationErrors.fecha_fin_cursada || form.errors.fecha_fin_cursada" class="text-red-600 text-sm mt-1">
                                        {{ validationErrors.fecha_fin_cursada || form.errors.fecha_fin_cursada }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="flex items-center">
                            <label class="flex items-center cursor-pointer">
                                <input
                                    v-model="form.activo"
                                    type="checkbox"
                                    class="rounded border-gray-300 text-blue-600 focus:ring focus:ring-blue-200"
                                />
                                <span class="ml-2 text-sm font-medium text-gray-700">
                                    Marcar como período activo
                                </span>
                            </label>
                            <p class="ml-2 text-xs text-gray-500">(Desactivará automáticamente otros períodos)</p>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 mt-6">
                        <Link
                            :href="route('admin.periodos.index')"
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
                            {{ form.processing ? 'Guardando...' : 'Crear Período' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </SidebarLayout>
</template>
