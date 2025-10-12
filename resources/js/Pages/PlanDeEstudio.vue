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

// estados removidos: ya no se visualiza la columna de estado

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
    nota: null,
    fecha_estado: null,
  })))
  let items = base
  if (filtroAnio.value) {
    items = items.filter(m => String(m.materia.anio || '') === String(filtroAnio.value))
  }
  if (filtroCuatrimestre.value) {
    items = items.filter(m => String(m.materia.cuatrimestre || '') === String(filtroCuatrimestre.value))
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
    default: return 'bg-gray-100 text-gray-700 border border-gray-300'
  }
}
</script>

<template>
  <Head title="Plan de Estudio" />
  <SidebarLayout :user="$page.props.auth.user">
    <template #header>
      <div>
        <h1 class="text-xl font-semibold text-white">Plan de Estudio</h1>
      </div>
    </template>
  <div class="p-6">
    <h1 class="text-2xl font-semibold mb-4">Mi Plan de Estudio</h1>

    <div v-if="loading">Cargando...</div>
    <div v-else-if="error" class="text-red-600">{{ error }}</div>

    <div v-else>
      <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
          <div class="text-lg font-medium">
            Carrera:
            <template v-if="alumnoId">
              {{ plan.alumno?.carrera?.nombre || 'Sin carrera' }}
            </template>
            <template v-else>
              <select v-model="carreraId" @change="fetchCarreraPlan" class="border rounded px-2 py-1">
                <option v-for="c in carreras" :key="c.id" :value="String(c.id)">{{ c.nombre }}</option>
              </select>
            </template>
          </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mt-3 text-sm">
          <div class="p-3 bg-white rounded border">
            <div class="text-gray-500">Total</div>
            <div class="text-xl font-bold">{{ alumnoId ? (plan.resumen?.totalMaterias || 0) : (carreraPlan.materias?.length || 0) }}</div>
          </div>
          <div class="p-3 bg-white rounded border">
            <div class="text-gray-500">Pendientes</div>
            <div class="text-xl font-bold">{{ alumnoId ? (plan.resumen?.pendientes || 0) : 0 }}</div>
          </div>
          <div class="p-3 bg-white rounded border">
            <div class="text-gray-500">Regularizadas</div>
            <div class="text-xl font-bold">{{ alumnoId ? (plan.resumen?.regularizadas || 0) : 0 }}</div>
          </div>
          <div class="p-3 bg-white rounded border">
            <div class="text-gray-500">Promocionadas</div>
            <div class="text-xl font-bold">{{ alumnoId ? (plan.resumen?.promocionadas || 0) : 0 }}</div>
          </div>
          <div class="p-3 bg-white rounded border">
            <div class="text-gray-500">Aprobadas</div>
            <div class="text-xl font-bold">{{ alumnoId ? (plan.resumen?.aprobadas || 0) : 0 }}</div>
          </div>
          <div class="p-3 bg-white rounded border col-span-2 md:col-span-1">
            <div class="text-gray-500">Avance</div>
            <div class="text-xl font-bold">{{ alumnoId ? (plan.resumen?.avancePorcentaje || 0) : 0 }}%</div>
          </div>
        </div>
        <div class="w-full bg-gray-200 rounded h-2 mt-2">
          <div class="bg-green-500 h-2 rounded" :style="{ width: `${alumnoId ? (plan.resumen?.avancePorcentaje || 0) : 0}%` }"></div>
        </div>
      </div>

      <div class="flex flex-wrap gap-3 items-end mb-4">
        <div v-if="aniosDisponibles.length">
          <label class="block text-xs text-gray-600 mb-1">Año</label>
          <select v-model="filtroAnio" class="border rounded px-2 py-1">
            <option value="">Todos</option>
            <option v-for="a in aniosDisponibles" :key="a" :value="a">{{ a }}</option>
          </select>
        </div>
        <div v-if="cuatrisDisponibles.length">
          <label class="block text-xs text-gray-600 mb-1">Cuatr.</label>
          <select v-model="filtroCuatrimestre" class="border rounded px-2 py-1">
            <option value="">Todos</option>
            <option v-for="c in cuatrisDisponibles" :key="c" :value="c">{{ c }}</option>
          </select>
        </div>
      </div>

      <div class="overflow-x-auto bg-white rounded border">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left px-3 py-2">Materia</th>
              <th class="text-left px-3 py-2">Año</th>
              <th class="text-left px-3 py-2">Cuatr.</th>
              <th class="text-left px-3 py-2">Nota</th>
              <th class="text-left px-3 py-2">Fecha</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in materiasFiltradas" :key="item.materia.id" class="border-t">
              <td class="px-3 py-2">{{ item.materia.nombre }}</td>
              <td class="px-3 py-2">{{ item.materia.anio || '-' }}</td>
              <td class="px-3 py-2">{{ item.materia.cuatrimestre || '-' }}</td>
              <td class="px-3 py-2">{{ alumnoId ? (item.nota ?? '-') : '—' }}</td>
              <td class="px-3 py-2">{{ alumnoId ? (item.fecha_estado ? new Date(item.fecha_estado).toLocaleDateString() : '-') : '—' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  </SidebarLayout>
</template>
