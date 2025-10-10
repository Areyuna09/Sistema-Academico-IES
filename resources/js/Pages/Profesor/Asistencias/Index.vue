<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
  profesorMateria: { type: Object, required: true },
  asistencias: { type: Object, required: true },
  filtros: { type: Object, required: true }
});

const fechaDesde = ref(props.filtros.fecha_desde);
const fechaHasta = ref(props.filtros.fecha_hasta);
const expandido = ref({});

const filtrar = () => {
  router.get(route('profesor.asistencias.index', props.profesorMateria.id), {
    fecha_desde: fechaDesde.value,
    fecha_hasta: fechaHasta.value
  }, {
    preserveState: true,
    preserveScroll: true
  });
};

const toggleDetalles = (fecha) => {
  expandido.value[fecha] = !expandido.value[fecha];
};

const getEstadoColor = (estado) => {
  const colores = {
    presente: 'text-green-600 bg-green-50',
    ausente: 'text-red-600 bg-red-50',
    tarde: 'text-yellow-600 bg-yellow-50',
    justificada: 'text-blue-600 bg-blue-50'
  };
  return colores[estado] || 'text-gray-600 bg-gray-50';
};

const getEstadoTexto = (estado) => {
  const textos = {
    presente: 'Presente',
    ausente: 'Ausente',
    tarde: 'Tarde',
    justificada: 'Justificada'
  };
  return textos[estado] || estado;
};
</script>

<template>
  <SidebarLayout :user="$page.props.auth.user">
    <template #header>
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">Historial de Asistencias</h1>
        <p class="mt-1 text-sm text-gray-600">
          {{ profesorMateria.materia_nombre }} - {{ profesorMateria.carrera_nombre }}
        </p>
      </div>
    </template>

    <div class="p-6">
      <!-- Navegación -->
      <div class="mb-6 flex flex-wrap gap-3">
        <Link 
          :href="route('profesor.asistencias.create', profesorMateria.id)"
          class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors"
        >
          <i class="bx bx-plus text-lg mr-2"></i>
          Tomar Asistencia
        </Link>

        <!-- LÍNEA CORREGIDA -->
        <Link 
          :href="route('expediente.materia.show', profesorMateria.id)"
          class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded transition-colors"
        >
          <i class="bx bx-arrow-back text-lg mr-2"></i>
          Volver a Materia
        </Link>
      </div>

      <!-- Filtros -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Filtrar por Fecha</h3>
        <div class="flex flex-wrap gap-4 items-end">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Desde</label>
            <input 
              v-model="fechaDesde"
              type="date"
              class="border border-gray-300 rounded px-3 py-2"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Hasta</label>
            <input 
              v-model="fechaHasta"
              type="date"
              class="border border-gray-300 rounded px-3 py-2"
            />
          </div>
          <button 
            @click="filtrar"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors"
          >
            <i class="bx bx-search-alt text-lg mr-2"></i>
            Filtrar
          </button>
        </div>
      </div>

      <!-- Listado de asistencias -->
      <div v-if="Object.keys(asistencias).length === 0" class="bg-white rounded-lg shadow p-8 text-center">
        <i class="bx bx-calendar-x text-6xl text-gray-400"></i>
        <p class="mt-4 text-gray-500">No hay asistencias registradas en este período</p>
        <Link 
          :href="route('profesor.asistencias.create', profesorMateria.id)"
          class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded"
        >
          Tomar Asistencia
        </Link>
      </div>

      <div v-else class="space-y-4">
        <div 
          v-for="(asistencia, fecha) in asistencias" 
          :key="fecha"
          class="bg-white rounded-lg shadow overflow-hidden"
        >
          <!-- Header de la fecha -->
          <div 
            @click="toggleDetalles(fecha)"
            class="bg-gray-50 px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors"
          >
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-4">
                <i class="bx bx-calendar text-2xl text-blue-600"></i>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">
                    {{ asistencia.fecha_formateada }}
                  </h3>
                  <p class="text-sm text-gray-500">
                    {{ asistencia.total_alumnos }} alumnos registrados
                  </p>
                </div>
              </div>

              <!-- Resumen de estados -->
              <div class="flex items-center gap-4">
                <div class="flex gap-3 text-sm">
                  <span class="flex items-center gap-1">
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    <strong>{{ asistencia.totales.presente }}</strong>
                  </span>
                  <span class="flex items-center gap-1">
                    <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                    <strong>{{ asistencia.totales.ausente }}</strong>
                  </span>
                  <span class="flex items-center gap-1">
                    <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                    <strong>{{ asistencia.totales.tarde }}</strong>
                  </span>
                  <span class="flex items-center gap-1">
                    <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                    <strong>{{ asistencia.totales.justificada }}</strong>
                  </span>
                </div>

                <i 
                  :class="[
                    'bx text-2xl transition-transform',
                    expandido[fecha] ? 'bx-chevron-up' : 'bx-chevron-down'
                  ]"
                ></i>
              </div>
            </div>
          </div>

          <!-- Detalles expandibles -->
          <transition
            enter-active-class="transition-all duration-200 ease-out"
            enter-from-class="max-h-0 opacity-0"
            enter-to-class="max-h-screen opacity-100"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="max-h-screen opacity-100"
            leave-to-class="max-h-0 opacity-0"
          >
            <div v-show="expandido[fecha]" class="border-t border-gray-200">
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">DNI</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alumno</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Observaciones</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr 
                      v-for="detalle in asistencia.detalles" 
                      :key="detalle.id"
                      class="hover:bg-gray-50"
                    >
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ detalle.alumno_dni }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ detalle.alumno_nombre }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span 
                          :class="[
                            'px-3 py-1 rounded-full text-xs font-semibold',
                            getEstadoColor(detalle.estado)
                          ]"
                        >
                          {{ getEstadoTexto(detalle.estado) }}
                        </span>
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-500">
                        {{ detalle.observaciones || '-' }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </transition>
        </div>
      </div>
    </div>
  </SidebarLayout>
</template>