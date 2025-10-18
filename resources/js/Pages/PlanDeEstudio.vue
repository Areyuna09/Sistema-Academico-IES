<script setup>
import { onMounted, ref, computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import SidebarLayout from '@/Layouts/SidebarLayout.vue'

const page = usePage()
const alumnoId = page.props.auth?.user?.alumno_id

const loading = ref(true)
const error = ref(null)
const plan = ref({ alumno: null, resumen: null, materias: [] })

// Modo carrera (cuando el usuario no es alumno)
const carreras = ref([])
const carreraId = ref('')
const carreraPlan = ref({ carrera: null, materias: [] })

// Filtros locales
const filtroAnio = ref('')
const filtroCuatrimestre = ref('')
const filtroEstado = ref('')

const fetchPlan = async () => {
  if (!alumnoId) {
    // Cargar listado de carreras para selección
    try {
      const { data } = await axios.get('/api/carreras')
      carreras.value = data
      if (data?.length) {
        carreraId.value = String(data[0].id)
        await fetchCarreraPlan()
      }
    } catch (e) {
      error.value = 'No se pudo cargar el listado de carreras.'
    } finally {
      loading.value = false
    }
    return
  }
  try {
    const { data } = await axios.get(`/alumnos/${alumnoId}/plan-estudio`)
    plan.value = data
  } catch (e) {
    error.value = 'No se pudo cargar el plan de estudio.'
  } finally {
    loading.value = false
  }
}

const fetchCarreraPlan = async () => {
  if (!carreraId.value) return
  loading.value = true
  try {
    const { data } = await axios.get(`/api/carreras/${carreraId.value}/plan`)
    carreraPlan.value = data
  } catch (e) {
    error.value = 'No se pudo cargar el plan de la carrera.'
  } finally {
    loading.value = false
  }
}

onMounted(fetchPlan)

const materiasFiltradas = computed(() => {
  const base = alumnoId ? (plan.value.materias || []) : ((carreraPlan.value.materias || []).map(m => ({
    materia: m,
    estado: null,
  })))

  let items = base

  if (filtroAnio.value) {
    items = items.filter(m => String(m.materia.anio || '') === String(filtroAnio.value))
  }
  if (filtroCuatrimestre.value) {
    items = items.filter(m => String(m.materia.cuatrimestre || '') === String(filtroCuatrimestre.value))
  }
  if (filtroEstado.value && alumnoId) {
    items = items.filter(m => m.estado === filtroEstado.value)
  }

  return items
})

const aniosDisponibles = computed(() => {
  const list = alumnoId ? (plan.value.materias || []) : ((carreraPlan.value.materias || []).map(m => ({ materia: m })))
  const set = new Set(list.map(m => m.materia.anio).filter(Boolean))
  return Array.from(set).sort((a, b) => a - b)
})

const cuatrisDisponibles = computed(() => {
  const list = alumnoId ? (plan.value.materias || []) : ((carreraPlan.value.materias || []).map(m => ({ materia: m })))
  const set = new Set(list.map(m => m.materia.cuatrimestre).filter(Boolean))
  return Array.from(set).sort((a, b) => (a ?? 0) - (b ?? 0))
})

const estadoBadgeClass = (estado) => {
  switch (estado) {
    case 'aprobada': return 'bg-green-100 text-green-700 border border-green-300'
    case 'promocionada': return 'bg-blue-100 text-blue-700 border border-blue-300'
    case 'regularizada': return 'bg-yellow-100 text-yellow-700 border border-yellow-300'
    case 'pendiente': return 'bg-gray-100 text-gray-600 border border-gray-300'
    default: return 'bg-gray-100 text-gray-600 border border-gray-300'
  }
}

const estadoTexto = (estado) => {
  switch (estado) {
    case 'aprobada': return 'Aprobada'
    case 'promocionada': return 'Promocionada'
    case 'regularizada': return 'Cursando'
    case 'pendiente': return 'Pendiente'
    default: return 'Pendiente'
  }
}

const estadoIcono = (estado) => {
  switch (estado) {
    case 'aprobada': return 'bx-check-circle'
    case 'promocionada': return 'bx-trophy'
    case 'regularizada': return 'bx-time-five'
    case 'pendiente': return 'bx-circle'
    default: return 'bx-circle'
  }
}
</script>

<template>
  <Head title="Plan de Estudio" />
  <SidebarLayout :user="$page.props.auth.user">
    <template #header>
      <div>
        <h1 class="text-xl font-semibold text-white">Plan de Estudio</h1>
        <p class="text-sm text-gray-300 mt-1">
          {{ alumnoId ? 'Visualiza tu progreso académico' : 'Consulta el plan de estudios de las carreras' }}
        </p>
      </div>
    </template>

    <div class="p-6">
      <h1 class="text-2xl font-semibold mb-4">
        {{ alumnoId ? 'Mi Plan de Estudio' : 'Plan de Estudio' }}
      </h1>

      <div v-if="loading" class="text-center py-8 text-gray-600">Cargando...</div>
      <div v-else-if="error" class="bg-red-50 border border-red-200 text-red-600 rounded-lg p-4">{{ error }}</div>

      <div v-else>
        <!-- Header con selector de carrera -->
        <div class="mb-6">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <div class="text-lg font-medium">
              Carrera:
              <span class="font-bold text-blue-600">
                <template v-if="alumnoId">
                  {{ plan.alumno?.carrera?.nombre || 'Sin carrera' }}
                </template>
                <template v-else>
                  <select v-model="carreraId" @change="fetchCarreraPlan" class="border rounded px-2 py-1 ml-2">
                    <option v-for="c in carreras" :key="c.id" :value="String(c.id)">{{ c.nombre }}</option>
                  </select>
                </template>
              </span>
            </div>
          </div>

          <!-- Estadísticas (solo para alumnos) -->
          <div v-if="alumnoId" class="grid grid-cols-2 md:grid-cols-6 gap-3 mt-3 text-sm">
            <div class="p-3 bg-white rounded border">
              <div class="text-gray-500">Total</div>
              <div class="text-xl font-bold">{{ plan.resumen?.totalMaterias || 0 }}</div>
            </div>
            <div class="p-3 bg-white rounded border">
              <div class="text-gray-500">Pendientes</div>
              <div class="text-xl font-bold text-gray-600">{{ plan.resumen?.pendientes || 0 }}</div>
            </div>
            <div class="p-3 bg-white rounded border">
              <div class="text-gray-500">Cursando</div>
              <div class="text-xl font-bold text-yellow-600">{{ plan.resumen?.regularizadas || 0 }}</div>
            </div>
            <div class="p-3 bg-white rounded border">
              <div class="text-gray-500">Promocionadas</div>
              <div class="text-xl font-bold text-blue-600">{{ plan.resumen?.promocionadas || 0 }}</div>
            </div>
            <div class="p-3 bg-white rounded border">
              <div class="text-gray-500">Aprobadas</div>
              <div class="text-xl font-bold text-green-600">{{ plan.resumen?.aprobadas || 0 }}</div>
            </div>
            <div class="p-3 bg-white rounded border col-span-2 md:col-span-1">
              <div class="text-gray-500">Avance</div>
              <div class="text-xl font-bold text-blue-600">{{ plan.resumen?.avancePorcentaje || 0 }}%</div>
            </div>
          </div>

          <!-- Barra de progreso (solo para alumnos) -->
          <div v-if="alumnoId" class="w-full bg-gray-200 rounded h-2 mt-3">
            <div
              class="bg-green-500 h-2 rounded transition-all duration-500"
              :style="{ width: `${plan.resumen?.avancePorcentaje || 0}%` }"
            ></div>
          </div>
        </div>

        <!-- Filtros -->
        <div class="flex flex-wrap gap-3 items-end mb-4">
          <div v-if="aniosDisponibles.length">
            <label class="block text-xs text-gray-600 mb-1">Año</label>
            <select v-model="filtroAnio" class="border rounded px-3 py-2 text-sm">
              <option value="">Todos</option>
              <option v-for="a in aniosDisponibles" :key="a" :value="a">{{ a }}° Año</option>
            </select>
          </div>
          <div v-if="cuatrisDisponibles.length">
            <label class="block text-xs text-gray-600 mb-1">Cuatrimestre</label>
            <select v-model="filtroCuatrimestre" class="border rounded px-3 py-2 text-sm">
              <option value="">Todos</option>
              <option v-for="c in cuatrisDisponibles" :key="c" :value="c">{{ c }}° Cuatrimestre</option>
            </select>
          </div>
          <div v-if="alumnoId">
            <label class="block text-xs text-gray-600 mb-1">Estado</label>
            <select v-model="filtroEstado" class="border rounded px-3 py-2 text-sm">
              <option value="">Todos</option>
              <option value="aprobada">Aprobadas</option>
              <option value="promocionada">Promocionadas</option>
              <option value="regularizada">Cursando</option>
              <option value="pendiente">Pendientes</option>
            </select>
          </div>
        </div>

        <!-- Tabla de materias -->
        <div class="overflow-x-auto bg-white rounded border shadow-sm">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="text-left px-4 py-3 font-semibold text-gray-700">Materia</th>
                <th class="text-center px-4 py-3 font-semibold text-gray-700">Año</th>
                <th class="text-center px-4 py-3 font-semibold text-gray-700">Cuatrimestre</th>
                <th v-if="alumnoId" class="text-center px-4 py-3 font-semibold text-gray-700">Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="item in materiasFiltradas"
                :key="item.materia.id"
                class="border-b hover:bg-gray-50 transition-colors"
              >
                <td class="px-4 py-3 font-medium text-gray-800">{{ item.materia.nombre }}</td>
                <td class="px-4 py-3 text-center text-gray-600">{{ item.materia.anio || '-' }}°</td>
                <td class="px-4 py-3 text-center text-gray-600">
                  {{ item.materia.cuatrimestre ? item.materia.cuatrimestre + '°' : '-' }}
                </td>
                <td v-if="alumnoId" class="px-4 py-3 text-center">
                  <span
                    :class="['inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold', estadoBadgeClass(item.estado)]"
                  >
                    <i :class="['bx', estadoIcono(item.estado)]"></i>
                    {{ estadoTexto(item.estado) }}
                  </span>
                </td>
              </tr>
              <tr v-if="materiasFiltradas.length === 0">
                <td :colspan="alumnoId ? 4 : 3" class="px-4 py-8 text-center text-gray-500">
                  No se encontraron materias
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </SidebarLayout>
</template>
