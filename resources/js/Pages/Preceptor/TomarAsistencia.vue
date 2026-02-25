<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    profesorMateria: { type: Object, required: true },
    alumnos: { type: Array, required: true },
    fechaHoy: { type: String, required: true },
    periodoActivo: { type: Object, default: null },
});

const form = useForm({
    fecha: props.fechaHoy,
    asistencias: props.alumnos.map(alumno => ({
        alumno_id: alumno.id,
        estado: alumno.asistencia_hoy?.estado || 'presente',
        observaciones: alumno.asistencia_hoy?.observaciones || '',
    })),
});

const yaExiste = computed(() => props.alumnos.some(a => a.asistencia_hoy));

const marcarTodos = (estado) => {
    form.asistencias.forEach(a => {
        a.estado = estado;
    });
};

const contadores = computed(() => {
    const totales = { presente: 0, ausente: 0, tarde: 0, justificada: 0 };
    form.asistencias.forEach(a => { totales[a.estado]++; });
    return totales;
});

const submit = () => {
    form.post(route('preceptor.asistencias.guardar', props.profesorMateria.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Tomar Asistencia" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Tomar Asistencia</h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ profesorMateria.materia_nombre }} - {{ profesorMateria.carrera_nombre }}
                </p>
            </div>
        </template>

        <div class="p-4 md:p-6 max-w-5xl mx-auto">
            <!-- Navegacion -->
            <div class="mb-4 flex flex-wrap gap-2">
                <Link
                    :href="route('preceptor.asistencias.index')"
                    class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded transition-colors"
                >
                    <i class="bx bx-arrow-back mr-1"></i> Volver
                </Link>
                <Link
                    :href="route('preceptor.asistencias.historial', profesorMateria.id)"
                    class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded transition-colors"
                >
                    <i class="bx bx-history mr-1"></i> Historial
                </Link>
            </div>

            <!-- Periodo activo -->
            <div v-if="periodoActivo" class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 mb-4 flex items-center gap-2">
                <i class="bx bx-calendar text-blue-600 text-lg"></i>
                <span class="text-sm text-blue-800">Periodo activo: <strong>{{ periodoActivo.nombre }}</strong></span>
            </div>

            <!-- Info materia -->
            <div class="bg-white rounded-lg shadow p-4 mb-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500">Materia:</span>
                        <span class="ml-1 font-medium">{{ profesorMateria.materia_nombre }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Carrera:</span>
                        <span class="ml-1 font-medium">{{ profesorMateria.carrera_nombre }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Profesor:</span>
                        <span class="ml-1 font-medium">{{ profesorMateria.profesor_nombre }}</span>
                    </div>
                    <div v-if="profesorMateria.cursado">
                        <span class="text-gray-500">Cursado:</span>
                        <span class="ml-1 font-medium">{{ profesorMateria.cursado }}</span>
                    </div>
                    <div v-if="profesorMateria.division">
                        <span class="text-gray-500">Division:</span>
                        <span class="ml-1 font-medium">{{ profesorMateria.division }}</span>
                    </div>
                </div>
            </div>

            <!-- Aviso si ya existe -->
            <div v-if="yaExiste" class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4 flex items-center gap-2 text-sm">
                <i class="bx bx-info-circle text-yellow-600 text-lg"></i>
                <span class="text-yellow-800">Ya se registro asistencia hoy para esta materia. Los cambios actualizaran los registros existentes.</span>
            </div>

            <form @submit.prevent="submit">
                <!-- Fecha y acciones rapidas -->
                <div class="bg-white rounded-lg shadow p-4 mb-4">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Fecha</label>
                            <input
                                v-model="form.fecha"
                                type="date"
                                class="border border-gray-300 rounded px-3 py-2 text-sm"
                                required
                            />
                        </div>
                        <div class="flex gap-2">
                            <button
                                type="button"
                                @click="marcarTodos('presente')"
                                class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded transition-colors"
                            >
                                <i class="bx bx-check"></i> Todos Presentes
                            </button>
                            <button
                                type="button"
                                @click="marcarTodos('ausente')"
                                class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition-colors"
                            >
                                <i class="bx bx-x"></i> Todos Ausentes
                            </button>
                        </div>
                    </div>

                    <!-- Contadores -->
                    <div class="mt-3 flex flex-wrap gap-4 text-sm">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 bg-green-500 rounded-full"></span>
                            Presentes: <strong>{{ contadores.presente }}</strong>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 bg-red-500 rounded-full"></span>
                            Ausentes: <strong>{{ contadores.ausente }}</strong>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 bg-yellow-500 rounded-full"></span>
                            Tarde: <strong>{{ contadores.tarde }}</strong>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 bg-blue-500 rounded-full"></span>
                            Justificadas: <strong>{{ contadores.justificada }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Sin alumnos -->
                <div v-if="alumnos.length === 0" class="bg-white rounded-lg shadow p-8 text-center">
                    <i class="bx bx-user-x text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">No hay alumnos inscriptos en esta materia.</p>
                </div>

                <!-- Tabla de alumnos -->
                <div v-else class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alumno</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">DNI</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Legajo</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="(alumno, index) in alumnos"
                                    :key="alumno.id"
                                    class="hover:bg-gray-50"
                                >
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">
                                        {{ alumno.apellido }}, {{ alumno.nombre }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ alumno.dni }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ alumno.legajo }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <select
                                            v-model="form.asistencias[index].estado"
                                            class="border border-gray-300 rounded px-2 py-1 text-sm"
                                            :class="{
                                                'bg-green-50 border-green-300': form.asistencias[index].estado === 'presente',
                                                'bg-red-50 border-red-300': form.asistencias[index].estado === 'ausente',
                                                'bg-yellow-50 border-yellow-300': form.asistencias[index].estado === 'tarde',
                                                'bg-blue-50 border-blue-300': form.asistencias[index].estado === 'justificada',
                                            }"
                                        >
                                            <option value="presente">Presente</option>
                                            <option value="ausente">Ausente</option>
                                            <option value="tarde">Tarde</option>
                                            <option value="justificada">Justificada</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input
                                            v-model="form.asistencias[index].observaciones"
                                            type="text"
                                            placeholder="Observaciones..."
                                            class="border border-gray-300 rounded px-2 py-1 text-sm w-full min-w-[150px]"
                                            maxlength="500"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Botones guardar -->
                <div v-if="alumnos.length > 0" class="mt-4 flex justify-end gap-3">
                    <Link
                        :href="route('preceptor.asistencias.index')"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 text-sm transition-colors"
                    >
                        Cancelar
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors disabled:bg-gray-400"
                    >
                        {{ form.processing ? 'Guardando...' : 'Guardar Asistencias' }}
                    </button>
                </div>

                <div v-if="Object.keys(form.errors).length > 0" class="mt-3">
                    <div v-for="(error, key) in form.errors" :key="key" class="text-red-600 text-sm">
                        {{ error }}
                    </div>
                </div>
            </form>
        </div>
    </SidebarLayout>
</template>
