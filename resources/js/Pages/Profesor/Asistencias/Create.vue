<script setup>
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
  profesorMateria: { type: Object, required: true },
  alumnos: { type: Array, required: true },
  fechaHoy: { type: String, required: true }
});

// Inicializar form con datos de asistencia
const form = useForm({
  fecha: props.fechaHoy,
  asistencias: props.alumnos.map(alumno => ({
    alumno_id: alumno.id,
    estado: alumno.asistencia_hoy?.estado || 'presente',
    observaciones: alumno.asistencia_hoy?.observaciones || ''
  }))
});

// Marcar todos con un estado
const marcarTodos = (estado) => {
  form.asistencias.forEach(asistencia => {
    asistencia.estado = estado;
  });
};

// Contadores
const contadores = computed(() => {
  const totales = {
    presente: 0,
    ausente: 0,
    tarde: 0,
    justificada: 0
  };
  
  form.asistencias.forEach(a => {
    totales[a.estado]++;
  });
  
  return totales;
});

const submit = () => {
  form.post(route('profesor.asistencias.store', props.profesorMateria.id), {
    preserveScroll: true
  });
};
</script>

<template>
    <Head title="Registrar Asistencia" />
  <SidebarLayout :user="$page.props.auth.user">
    <template #header>
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">Tomar Asistencia</h1>
        <p class="mt-1 text-sm text-gray-600">
          {{ profesorMateria.materia_nombre }} - {{ profesorMateria.carrera_nombre }}
        </p>
      </div>
    </template>

    <div class="p-6">
      <!-- Botones de navegación -->
      <div class="mb-6 flex flex-wrap gap-3">
        <Link 
          :href="route('profesor.asistencias.index', profesorMateria.id)"
          class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded transition-colors"
        >
          <i class="bx bx-history text-lg mr-2"></i>
          Ver Historial
        </Link>

        <Link 
          :href="route('expediente.materia.show', profesorMateria.id)"
          class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded transition-colors"
        >
          <i class="bx bx-arrow-back text-lg mr-2"></i>
          Volver a Materia
        </Link>
      </div>

      <form @submit.prevent="submit">
        <!-- Fecha y acciones rápidas -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Fecha de Asistencia
              </label>
              <input 
                v-model="form.fecha"
                type="date"
                class="border border-gray-300 rounded px-3 py-2"
                required
              />
            </div>

            <div class="flex gap-2">
              <button 
                type="button"
                @click="marcarTodos('presente')"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded transition-colors"
              >
                <i class="bx bx-check"></i> Todos Presentes
              </button>
              <button 
                type="button"
                @click="marcarTodos('ausente')"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition-colors"
              >
                <i class="bx bx-x"></i> Todos Ausentes
              </button>
            </div>
          </div>

          <!-- Contadores -->
          <div class="mt-4 flex flex-wrap gap-4 text-sm">
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 bg-green-500 rounded-full"></span>
              <span>Presentes: <strong>{{ contadores.presente }}</strong></span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 bg-red-500 rounded-full"></span>
              <span>Ausentes: <strong>{{ contadores.ausente }}</strong></span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
              <span>Tarde: <strong>{{ contadores.tarde }}</strong></span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
              <span>Justificadas: <strong>{{ contadores.justificada }}</strong></span>
            </div>
          </div>
        </div>

        <!-- Lista de alumnos -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alumno</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">DNI</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Legajo</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Observaciones</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr 
                  v-for="(alumno, index) in alumnos" 
                  :key="alumno.id"
                  class="hover:bg-gray-50"
                >
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ alumno.apellido }}, {{ alumno.nombre }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ alumno.dni }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ alumno.legajo }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <select 
                      v-model="form.asistencias[index].estado"
                      class="border border-gray-300 rounded px-3 py-1 text-sm"
                      :class="{
                        'bg-green-50 border-green-300': form.asistencias[index].estado === 'presente',
                        'bg-red-50 border-red-300': form.asistencias[index].estado === 'ausente',
                        'bg-yellow-50 border-yellow-300': form.asistencias[index].estado === 'tarde',
                        'bg-blue-50 border-blue-300': form.asistencias[index].estado === 'justificada'
                      }"
                    >
                      <option value="presente">Presente</option>
                      <option value="ausente">Ausente</option>
                      <option value="tarde">Tarde</option>
                      <option value="justificada">Justificada</option>
                    </select>
                  </td>
                  <td class="px-6 py-4">
                    <input 
                      v-model="form.asistencias[index].observaciones"
                      type="text"
                      placeholder="Observaciones..."
                      class="border border-gray-300 rounded px-3 py-1 text-sm w-full"
                      maxlength="500"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Botones de acción -->
        <div class="mt-6 flex justify-end gap-3">
          <Link 
            :href="route('expediente.materia.show', profesorMateria.id)"
            class="px-6 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition-colors"
          >
            Cancelar
          </Link>
          <button 
            type="submit"
            :disabled="form.processing"
            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors disabled:bg-gray-400"
          >
            {{ form.processing ? 'Guardando...' : 'Guardar Asistencias' }}
          </button>
        </div>

        <div v-if="form.errors" class="mt-4">
          <div v-for="(error, key) in form.errors" :key="key" class="text-red-600 text-sm">
            {{ error }}
          </div>
        </div>
      </form>
    </div>
  </SidebarLayout>
</template>