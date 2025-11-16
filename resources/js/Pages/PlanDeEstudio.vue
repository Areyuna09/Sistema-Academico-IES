<script setup>
import { onMounted, ref, computed, watch } from 'vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import axios from 'axios'
import SidebarLayout from '@/Layouts/SidebarLayout.vue'

// Crear instancia de axios con baseURL fija
const axiosInstance = axios.create({
  baseURL: window.location.origin
})

const page = usePage()
const alumnoId = page.props.auth?.user?.alumno_id
const userTipo = page.props.auth?.user?.tipo
const esAdmin = computed(() => [1, 2].includes(userTipo))

const loading = ref(true)
const error = ref(null)
const plan = ref({ alumno: null, resumen: null, materias: [] })

// Modo carrera (cuando el usuario no es alumno)
const carreras = ref([])
const carreraId = ref('')
const planesDisponibles = ref([]) // Lista de planes de la carrera seleccionada
const planSeleccionadoId = ref(null) // ID del plan seleccionado
const carreraPlan = ref({ carrera: null, plan: null, materias: [] })
const duracionCarreras = ref({})

// Años expandidos (toggle)
const aniosExpandidos = ref({})

// Estado del modal para asignar materias al plan (admin)
const modalAsignarMateria = ref({
  visible: false,
  materiaSeleccionada: null
})

// Estado del modal para reemplazar materia en el plan (admin)
const modalReemplazarMateria = ref({
  visible: false,
  materiaActual: null,
  materiaNueva: null
})

// Estado del modal para crear nuevo plan (admin)
const modalNuevoPlan = ref({
  visible: false,
  nombre: '',
  anio: new Date().getFullYear(),
  resolucion: '',
  activo: false,
  guardando: false
})

const materiasDisponiblesParaAsignar = ref([])
const cargandoMateriasDisponibles = ref(false)
const errorModal = ref('')

const fetchPlan = async () => {
  if (!alumnoId) {
    // Cargar listado de carreras para selección
    try {
      const { data } = await axios.get('/carreras')
      carreras.value = data

      // Cargar duración de carreras
      if (data?.length) {
        // Calcular duración basado en las materias de cada carrera
        for (const carrera of data) {
          try {
            const planData = await axios.get(`/carreras/${carrera.id}/plan`)
            const maxAnno = planData.data.materias.reduce((max, m) => {
              return Math.max(max, m.anio || 0)
            }, 0)
            duracionCarreras.value[carrera.id] = maxAnno || 4
          } catch (e) {
            duracionCarreras.value[carrera.id] = 4 // Default
          }
        }

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
    // Expandir primer año por defecto
    if (materiasPorAnio.value.length > 0) {
      aniosExpandidos.value[materiasPorAnio.value[0].anio] = true
    }
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
    // Cargar lista de planes disponibles de esta carrera
    const { data: planesData } = await axios.get(`/carreras/${carreraId.value}/planes`)
    planesDisponibles.value = planesData.planes || []

    // Si hay planes, seleccionar el activo por defecto
    if (planesDisponibles.value.length > 0) {
      const planActivo = planesDisponibles.value.find(p => p.activo)
      planSeleccionadoId.value = planActivo ? planActivo.id : planesDisponibles.value[0].id
    }

    // Cargar el plan seleccionado (o el plan activo por defecto)
    const endpoint = planSeleccionadoId.value
      ? `/carreras/${carreraId.value}/planes/${planSeleccionadoId.value}`
      : `/carreras/${carreraId.value}/plan`

    const { data } = await axios.get(endpoint)
    carreraPlan.value = data

    // Expandir primer año
    if (materiasPorAnioCarrera.value.length > 0) {
      aniosExpandidos.value[materiasPorAnioCarrera.value[0].anio] = true
    }
  } catch (e) {
    error.value = 'No se pudo cargar el plan de la carrera.'
  } finally {
    loading.value = false
  }
}

onMounted(fetchPlan)

// Recargar el plan cuando cambie la carrera seleccionada
watch(carreraId, async (newCarreraId) => {
  if (newCarreraId && !alumnoId) {
    await fetchCarreraPlan()
  }
})

// Recargar el plan cuando cambie el plan seleccionado
watch(planSeleccionadoId, async (newPlanId) => {
  if (newPlanId && carreraId.value && !alumnoId) {
    loading.value = true
    try {
      const { data } = await axios.get(`/carreras/${carreraId.value}/planes/${newPlanId}`)
      carreraPlan.value = data
      // Expandir primer año
      if (materiasPorAnioCarrera.value.length > 0) {
        aniosExpandidos.value[materiasPorAnioCarrera.value[0].anio] = true
      }
    } catch (e) {
      error.value = 'No se pudo cargar el plan seleccionado.'
    } finally {
      loading.value = false
    }
  }
})

// Organizar materias por año
const materiasPorAnio = computed(() => {
  if (!alumnoId || !plan.value.materias) return []

  const grupos = {}
  plan.value.materias.forEach(item => {
    const anio = item.materia.anio || 0
    if (!grupos[anio]) {
      grupos[anio] = {
        anio,
        materias: [],
        total: 0,
        aprobadas: 0,
        cursando: 0,
        pendientes: 0
      }
    }
    grupos[anio].materias.push(item)
    grupos[anio].total++
    if (item.estado === 'aprobada' || item.estado === 'promocionada') grupos[anio].aprobadas++
    else if (item.estado === 'regularizada') grupos[anio].cursando++
    else grupos[anio].pendientes++
  })

  return Object.values(grupos).sort((a, b) => a.anio - b.anio)
})

const materiasPorAnioCarrera = computed(() => {
  if (alumnoId || !carreraPlan.value.materias) return []

  const grupos = {}
  carreraPlan.value.materias.forEach(materia => {
    const anio = materia.anio || 0
    if (!grupos[anio]) {
      grupos[anio] = {
        anio,
        materias: []
      }
    }
    grupos[anio].materias.push({ materia, estado: null })
  })

  return Object.values(grupos).sort((a, b) => a.anio - b.anio)
})

const toggleAnio = (anio) => {
  aniosExpandidos.value[anio] = !aniosExpandidos.value[anio]
}

const estadoBadgeClass = (estado) => {
  switch (estado) {
    case 'aprobada': return 'bg-green-100 text-green-700 border-green-300'
    case 'promocionada': return 'bg-blue-100 text-blue-700 border-blue-300'
    case 'regularizada': return 'bg-yellow-100 text-yellow-700 border-yellow-300'
    case 'pendiente': return 'bg-gray-100 text-gray-600 border-gray-300'
    default: return 'bg-gray-100 text-gray-600 border-gray-300'
  }
}

const estadoGradient = (estado) => {
  switch (estado) {
    case 'aprobada': return 'from-green-500 to-green-600'
    case 'promocionada': return 'from-blue-500 to-blue-600'
    case 'regularizada': return 'from-yellow-500 to-yellow-600'
    case 'pendiente': return 'from-gray-400 to-gray-500'
    default: return 'from-gray-400 to-gray-500'
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

const porcentajeProgreso = (grupo) => {
  if (!grupo || grupo.total === 0) return 0
  return Math.round((grupo.aprobadas / grupo.total) * 100)
}

// Funciones de gestión admin del plan de estudio

// Cargar materias disponibles que no están en el plan
const cargarMateriasDisponibles = async () => {
  if (!carreraId.value) return

  cargandoMateriasDisponibles.value = true
  try {
    // Obtener TODAS las materias de la carrera desde el endpoint de materias
    const response = await axiosInstance.get(`/admin/materias?carrera_id=${carreraId.value}`)
    let todasLasMaterias = []

    // El endpoint de materias devuelve un objeto con data
    if (response.data.data) {
      todasLasMaterias = response.data.data
    } else if (Array.isArray(response.data)) {
      todasLasMaterias = response.data
    }

    // Filtrar solo las que NO están ya en el plan
    const materiasEnPlan = carreraPlan.value.materias.map(m => m.id)
    materiasDisponiblesParaAsignar.value = todasLasMaterias.filter(
      m => !materiasEnPlan.includes(m.id)
    )
  } catch (e) {
    console.error('Error al cargar materias disponibles:', e)
    errorModal.value = 'No se pudieron cargar las materias disponibles'
  } finally {
    cargandoMateriasDisponibles.value = false
  }
}

// Abrir modal para asignar materia al plan
const abrirModalNuevoPlan = () => {
  modalNuevoPlan.value = {
    visible: true,
    nombre: '',
    anio: new Date().getFullYear(),
    resolucion: '',
    activo: false,
    guardando: false
  }
  errorModal.value = ''
}

const cerrarModalNuevoPlan = () => {
  modalNuevoPlan.value.visible = false
}

const crearNuevoPlan = async () => {
  if (!modalNuevoPlan.value.nombre || !modalNuevoPlan.value.anio) {
    errorModal.value = 'El nombre y año son obligatorios'
    return
  }

  modalNuevoPlan.value.guardando = true
  errorModal.value = ''

  try {
    await axiosInstance.post(`/admin/carreras/${carreraId.value}/planes`, {
      nombre: modalNuevoPlan.value.nombre,
      anio: modalNuevoPlan.value.anio,
      resolucion: modalNuevoPlan.value.resolucion,
      activo: modalNuevoPlan.value.activo
    })

    cerrarModalNuevoPlan()
    await fetchCarreraPlan() // Recargar para ver el nuevo plan
  } catch (error) {
    errorModal.value = error.response?.data?.message || 'Error al crear el plan'
  } finally {
    modalNuevoPlan.value.guardando = false
  }
}

const abrirModalAsignar = async () => {
  await cargarMateriasDisponibles()
  modalAsignarMateria.value = {
    visible: true,
    materiaSeleccionada: null
  }
  errorModal.value = ''
}

// Asignar materia al plan (sin modificar la materia en sí)
const asignarMateria = async () => {
  if (!modalAsignarMateria.value.materiaSeleccionada) {
    errorModal.value = 'Debes seleccionar una materia'
    return
  }

  if (!carreraPlan.value.plan?.id) {
    errorModal.value = 'No hay un plan activo para esta carrera'
    return
  }

  try {
    // Llamar al endpoint para asignar la materia al plan
    await axiosInstance.post(
      `/carreras/${carreraId.value}/planes/${carreraPlan.value.plan.id}/materias`,
      {
        materia_id: modalAsignarMateria.value.materiaSeleccionada
      }
    )

    modalAsignarMateria.value.visible = false
    errorModal.value = ''
    await fetchCarreraPlan()
  } catch (e) {
    console.error('Error al asignar materia:', e)
    errorModal.value = e.response?.data?.message || 'Error al asignar la materia al plan'
  }
}

// Abrir modal para reemplazar materia
const abrirModalReemplazar = async (materiaActual) => {
  await cargarMateriasDisponibles()
  modalReemplazarMateria.value = {
    visible: true,
    materiaActual: materiaActual,
    materiaNueva: null
  }
  errorModal.value = ''
}

// Reemplazar materia en el plan (cambiar una por otra)
const reemplazarMateria = async () => {
  if (!modalReemplazarMateria.value.materiaNueva) {
    errorModal.value = 'Debes seleccionar una materia para reemplazar'
    return
  }

  try {
    // En la práctica, esto solo actualiza la visualización del plan
    // Las materias no se modifican en el sistema
    modalReemplazarMateria.value.visible = false
    await fetchCarreraPlan()
  } catch (e) {
    errorModal.value = 'Error al reemplazar la materia'
  }
}

// Quitar materia del plan (no la elimina del sistema)
const quitarDelPlan = async (materia) => {
  if (!carreraPlan.value.plan?.id) {
    alert('No hay un plan activo para esta carrera')
    return
  }

  const confirmacion = confirm(
    `¿Quitar "${materia.nombre}" del plan de estudio?\n\nNota: Esto NO eliminará la materia del sistema, solo la quitará del plan.`
  )

  if (!confirmacion) return

  try {
    // Llamar al endpoint para quitar la materia del plan
    await axiosInstance.delete(
      `/carreras/${carreraId.value}/planes/${carreraPlan.value.plan.id}/materias/${materia.id}`
    )

    await fetchCarreraPlan()
  } catch (e) {
    console.error('Error al quitar materia:', e)
    const mensaje = e.response?.data?.message || 'Error al quitar la materia del plan'
    alert(mensaje)
  }
}

const cerrarModal = () => {
  modalAsignarMateria.value.visible = false
  modalReemplazarMateria.value.visible = false
  errorModal.value = ''
}
</script>

<template>
  <Head title="Plan de Estudio" />
  <SidebarLayout :user="$page.props.auth.user">
    <template #header>
      <div>
        <h1 class="text-xl font-semibold text-white">Plan de Estudio</h1>
        <p class="text-xs text-gray-400 mt-0.5">
          {{ alumnoId ? 'Visualiza tu progreso académico' : 'Consulta el plan de estudios de las carreras' }}
        </p>
      </div>
    </template>

    <div class="p-4 md:p-8 max-w-7xl mx-auto">
      <!-- Loading -->
      <div v-if="loading" class="flex items-center justify-center py-12">
        <div class="text-center">
          <i class="bx bx-loader-alt bx-spin text-4xl text-indigo-600 mb-2"></i>
          <p class="text-gray-600">Cargando plan de estudio...</p>
        </div>
      </div>

      <!-- Error -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-6 flex items-center">
        <i class="bx bx-error-circle text-3xl mr-3"></i>
        <div>
          <p class="font-semibold">Error al cargar</p>
          <p class="text-sm">{{ error }}</p>
        </div>
      </div>

      <!-- Contenido Principal -->
      <div v-else>
        <!-- Información del Plan / Selector de Carrera -->
        <div class="bg-white rounded-lg p-3 md:p-4 lg:p-6 mb-3 md:mb-4 lg:mb-6 border border-gray-200">
          <!-- Para alumnos: mostrar info del plan -->
          <div v-if="alumnoId">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-lg md:text-xl lg:text-2xl font-bold text-gray-900 leading-tight">{{ plan.alumno?.carrera?.nombre || 'Plan de Estudio' }}</h2>
                <div class="mt-1.5 md:mt-2 flex flex-wrap gap-2 md:gap-3 lg:gap-4 text-xs md:text-sm text-gray-600">
                  <div class="flex items-center gap-1">
                    <i class='bx bx-user text-sm md:text-base'></i>
                    <span>{{ plan.alumno?.nombre }}</span>
                  </div>
                  <div v-if="plan.resumen" class="flex items-center gap-1">
                    <i class='bx bx-book-open text-sm md:text-base'></i>
                    <span>{{ plan.resumen.totalMaterias }} materias</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Para no-alumnos: selector de carrera -->
          <div v-else>
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Seleccionar Carrera:</label>
                <select
                  v-model="carreraId"
                  @change="fetchCarreraPlan"
                  class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:border-indigo-500 transition-colors"
                >
                  <option v-for="c in carreras" :key="c.id" :value="String(c.id)">
                    {{ c.nombre }}
                  </option>
                </select>
              </div>

              <!-- Selector de Plan de Estudio -->
              <div v-if="planesDisponibles.length > 0">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Plan de Estudio:</label>
                <select
                  v-model="planSeleccionadoId"
                  class="w-full border-2 border-gray-300 rounded-lg px-4 py-2.5 text-gray-900 focus:outline-none focus:border-indigo-500 transition-colors"
                >
                  <option v-for="plan in planesDisponibles" :key="plan.id" :value="plan.id">
                    {{ plan.nombre }} ({{ plan.anio }})
                    <span v-if="plan.activo" class="text-green-600">★ Activo</span>
                  </option>
                </select>
              </div>

              <!-- Botón crear nuevo plan (solo admin) -->
              <button
                v-if="esAdmin && carreraId"
                @click="abrirModalNuevoPlan"
                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg px-4 py-2.5 font-semibold transition-all flex items-center justify-center gap-2 shadow-lg"
              >
                <i class="bx bx-plus text-xl"></i>
                Crear Nuevo Plan de Estudio
              </button>

              <!-- Botón asignar materia al plan (solo admin) - móvil -->
              <button
                v-if="esAdmin && carreraId"
                @click="abrirModalAsignar"
                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-lg px-4 py-2.5 font-semibold transition-all flex items-center justify-center gap-2 shadow-lg"
              >
                <i class="bx bx-plus-circle text-xl"></i>
                Asignar Materia al Plan
              </button>
            </div>
            <div v-if="carreraPlan.carrera" class="mt-4">
              <h2 class="text-xl font-bold text-gray-900">{{ carreraPlan.carrera.nombre }}</h2>
              <p class="text-sm text-gray-600">Vista general del plan de estudios</p>
            </div>
          </div>
        </div>

        <!-- Métricas (solo alumnos) -->
        <div v-if="alumnoId && plan.resumen" class="mb-4 md:mb-6">
          <!-- Resumen principal en móvil -->
          <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg p-3 md:p-4 text-white mb-2 md:mb-3">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs md:text-sm opacity-90 mb-0.5 md:mb-1">Progreso del plan</p>
                <p class="text-2xl md:text-3xl font-bold">{{ plan.resumen.avancePorcentaje }}%</p>
              </div>
              <i class="bx bx-trophy text-4xl md:text-5xl opacity-20"></i>
            </div>
            <div class="mt-2 md:mt-3 bg-white/20 rounded-full h-1.5 md:h-2">
              <div
                class="bg-white h-1.5 md:h-2 rounded-full transition-all duration-700"
                :style="{ width: `${plan.resumen.avancePorcentaje}%` }"
              ></div>
            </div>
          </div>

          <!-- Métricas compactas -->
          <div class="grid grid-cols-2 gap-2 md:gap-3">
            <!-- Aprobadas -->
            <div class="bg-white border-2 border-green-200 rounded-lg p-2 md:p-3">
              <div class="flex items-center gap-1 md:gap-2 mb-0.5 md:mb-1">
                <i class="bx bx-check-circle text-base md:text-xl text-green-600"></i>
                <span class="text-[10px] md:text-xs font-semibold text-gray-600">Aprobadas</span>
              </div>
              <p class="text-xl md:text-2xl font-bold text-green-600">{{ plan.resumen.aprobadas + plan.resumen.promocionadas }}</p>
            </div>

            <!-- Cursando -->
            <div class="bg-white border-2 border-yellow-200 rounded-lg p-2 md:p-3">
              <div class="flex items-center gap-1 md:gap-2 mb-0.5 md:mb-1">
                <i class="bx bx-time-five text-base md:text-xl text-yellow-600"></i>
                <span class="text-[10px] md:text-xs font-semibold text-gray-600">Cursando</span>
              </div>
              <p class="text-xl md:text-2xl font-bold text-yellow-600">{{ plan.resumen.regularizadas }}</p>
            </div>

            <!-- Pendientes -->
            <div class="bg-white border-2 border-gray-200 rounded-lg p-2 md:p-3">
              <div class="flex items-center gap-1 md:gap-2 mb-0.5 md:mb-1">
                <i class="bx bx-circle text-base md:text-xl text-gray-600"></i>
                <span class="text-[10px] md:text-xs font-semibold text-gray-600">Pendientes</span>
              </div>
              <p class="text-xl md:text-2xl font-bold text-gray-600">{{ plan.resumen.pendientes }}</p>
            </div>

            <!-- Total -->
            <div class="bg-white border-2 border-indigo-200 rounded-lg p-2 md:p-3">
              <div class="flex items-center gap-1 md:gap-2 mb-0.5 md:mb-1">
                <i class="bx bx-book-open text-base md:text-xl text-indigo-600"></i>
                <span class="text-[10px] md:text-xs font-semibold text-gray-600">Total</span>
              </div>
              <p class="text-xl md:text-2xl font-bold text-indigo-600">{{ plan.resumen.totalMaterias }}</p>
            </div>
          </div>
        </div>

        <!-- Materias Organizadas por Año -->
        <div class="space-y-3 md:space-y-4">
          <h3 class="text-base md:text-lg lg:text-xl font-semibold text-gray-900 flex items-center mb-3 md:mb-4">
            <i class="bx bx-calendar text-lg md:text-xl lg:text-2xl mr-1.5 md:mr-2 text-indigo-600"></i>
            Materias por Año
          </h3>

          <div
            v-for="grupo in (alumnoId ? materiasPorAnio : materiasPorAnioCarrera)"
            :key="grupo.anio"
            class="bg-white border-2 border-gray-200 rounded-xl overflow-hidden hover:border-indigo-300 transition-all duration-200"
          >
            <!-- Header del Año (clickeable) -->
            <div
              @click="toggleAnio(grupo.anio)"
              class="bg-gradient-to-r from-indigo-50 to-purple-50 p-3 md:p-5 cursor-pointer hover:from-indigo-100 hover:to-purple-100 transition-colors"
            >
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 md:gap-4">
                  <div class="w-10 h-10 md:w-12 md:h-12 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-base md:text-lg">
                    {{ grupo.anio }}°
                  </div>
                  <div>
                    <h4 class="text-base md:text-lg font-semibold text-gray-900">
                      Año {{ grupo.anio }}
                      <span class="text-xs md:text-sm font-normal text-gray-600 ml-1 md:ml-2">
                        ({{ grupo.materias.length }} {{ grupo.materias.length === 1 ? 'materia' : 'materias' }})
                      </span>
                    </h4>
                    <!-- Progreso del año (solo alumnos) -->
                    <div v-if="alumnoId" class="flex flex-wrap items-center gap-2 md:gap-3 mt-1 text-xs md:text-sm">
                      <span class="text-green-600">
                        <i class="bx bx-check-circle"></i> {{ grupo.aprobadas }}
                        <span class="hidden sm:inline">aprobadas</span>
                      </span>
                      <span class="text-yellow-600">
                        <i class="bx bx-time"></i> {{ grupo.cursando }}
                        <span class="hidden sm:inline">cursando</span>
                      </span>
                      <span class="text-gray-600">
                        <i class="bx bx-circle"></i> {{ grupo.pendientes }}
                        <span class="hidden sm:inline">pendientes</span>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="flex items-center gap-2 md:gap-4">
                  <!-- Barra de progreso del año (solo alumnos) -->
                  <div v-if="alumnoId" class="hidden lg:block">
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                      <div
                        class="bg-gradient-to-r from-green-500 to-emerald-600 h-2 rounded-full transition-all duration-500"
                        :style="{ width: `${porcentajeProgreso(grupo)}%` }"
                      ></div>
                    </div>
                    <p class="text-xs text-gray-600 mt-1 text-center">{{ porcentajeProgreso(grupo) }}%</p>
                  </div>

                  <!-- Icono expand/collapse -->
                  <i
                    :class="['bx text-xl md:text-2xl text-gray-600 transition-transform duration-200', aniosExpandidos[grupo.anio] ? 'bx-chevron-up' : 'bx-chevron-down']"
                  ></i>
                </div>
              </div>
            </div>

            <!-- Contenido del Año (expandible) -->
            <div
              v-show="aniosExpandidos[grupo.anio]"
              class="p-3 md:p-5 bg-white"
            >
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 md:gap-3 lg:gap-4">
                <div
                  v-for="item in grupo.materias"
                  :key="item.materia.id"
                  :class="['border-2 rounded-lg p-2.5 md:p-3 lg:p-4 transition-all duration-200 hover:shadow-md', alumnoId ? `border-${estadoBadgeClass(item.estado).split(' ')[0].split('-')[1]}-200` : 'border-gray-200 hover:border-indigo-300']"
                >
                  <!-- Header de la materia -->
                  <div class="flex items-start justify-between mb-2 md:mb-3">
                    <div class="flex-1">
                      <h5 class="font-semibold text-gray-900 text-sm md:text-base mb-1 leading-tight">{{ item.materia.nombre }}</h5>
                      <div class="flex items-center gap-1 md:gap-2 text-[10px] md:text-xs text-gray-600">
                        <span v-if="item.materia.cuatrimestre" class="bg-gray-100 px-1.5 md:px-2 py-0.5 rounded text-[10px] md:text-xs">
                          {{ item.materia.cuatrimestre }}° Cuatr.
                        </span>
                      </div>
                    </div>
                    <!-- Ícono de estado (solo alumnos) -->
                    <div v-if="alumnoId" :class="['w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center bg-gradient-to-br flex-shrink-0 ml-2', estadoGradient(item.estado)]">
                      <i :class="['bx text-base md:text-xl text-white', estadoIcono(item.estado)]"></i>
                    </div>
                  </div>

                  <!-- Estado (solo alumnos) -->
                  <div v-if="alumnoId">
                    <span :class="['inline-flex items-center gap-1 px-2 md:px-3 py-0.5 md:py-1 rounded-full text-[10px] md:text-xs font-semibold border', estadoBadgeClass(item.estado)]">
                      {{ estadoTexto(item.estado) }}
                    </span>
                    <div v-if="item.nota" class="mt-1.5 md:mt-2 text-xs md:text-sm">
                      <span class="text-gray-600">Nota:</span>
                      <span class="font-bold text-gray-900 ml-1">{{ item.nota }}</span>
                    </div>
                    <div v-if="item.fecha_estado" class="mt-0.5 md:mt-1 text-[10px] md:text-xs text-gray-500">
                      <i class="bx bx-calendar text-xs"></i>
                      {{ new Date(item.fecha_estado).toLocaleDateString('es-AR') }}
                    </div>
                  </div>

                  <!-- Placeholder para no-alumnos -->
                  <div v-else>
                    <div class="text-[10px] md:text-xs text-gray-500 mb-2 md:mb-3">
                      Materia del plan de estudios
                    </div>

                    <!-- Botones de administración (solo admin) -->
                    <div v-if="esAdmin" class="flex gap-1.5 md:gap-2 mt-2">
                      <button
                        @click="abrirModalReemplazar(item.materia)"
                        class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200 rounded-md md:rounded-lg px-1.5 md:px-2 py-1 md:py-1.5 text-[10px] md:text-xs font-semibold transition-colors flex items-center justify-center gap-0.5 md:gap-1"
                      >
                        <i class="bx bx-transfer text-sm md:text-base"></i>
                        <span class="hidden sm:inline">Reemplazar</span>
                      </button>
                      <button
                        @click="quitarDelPlan(item.materia)"
                        class="flex-1 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-md md:rounded-lg px-1.5 md:px-2 py-1 md:py-1.5 text-[10px] md:text-xs font-semibold transition-colors flex items-center justify-center gap-0.5 md:gap-1"
                      >
                        <i class="bx bx-x text-sm md:text-base"></i>
                        <span class="hidden sm:inline">Quitar</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Mensaje si no hay materias -->
              <div v-if="grupo.materias.length === 0" class="text-center py-8 text-gray-500">
                <i class="bx bx-info-circle text-3xl mb-2"></i>
                <p>No hay materias registradas para este año</p>
              </div>
            </div>
          </div>

          <!-- Mensaje si no hay años -->
          <div v-if="(alumnoId ? materiasPorAnio : materiasPorAnioCarrera).length === 0" class="bg-gray-50 border-2 border-gray-200 rounded-xl p-12 text-center">
            <i class="bx bx-book-open text-5xl text-gray-400 mb-3"></i>
            <p class="text-gray-600 text-lg">No se encontraron materias en el plan de estudio</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para asignar materia al plan (solo admin) -->
    <div
      v-if="modalAsignarMateria.visible"
      class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
      @click.self="cerrarModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
        <!-- Header del modal -->
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <i class="bx bx-plus-circle text-3xl text-green-600"></i>
            Asignar Materia al Plan
          </h3>
          <button
            @click="cerrarModal"
            class="text-gray-400 hover:text-gray-600 transition-colors"
          >
            <i class="bx bx-x text-3xl"></i>
          </button>
        </div>

        <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
          <p class="text-blue-800 text-sm">
            <i class="bx bx-info-circle mr-1"></i>
            Selecciona una materia existente para agregarla al plan de estudio. Las materias se gestionan en el módulo de Administración.
          </p>
        </div>

        <!-- Formulario -->
        <form @submit.prevent="asignarMateria" class="space-y-4">
          <!-- Selector de Materia -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Seleccionar Materia</label>
            <div v-if="cargandoMateriasDisponibles" class="text-sm text-gray-500 py-4">
              <i class="bx bx-loader-alt bx-spin mr-2"></i>
              Cargando materias...
            </div>
            <select
              v-else
              v-model="modalAsignarMateria.materiaSeleccionada"
              class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500 transition-colors"
              required
            >
              <option :value="null">Seleccione una materia</option>
              <option
                v-for="materia in materiasDisponiblesParaAsignar"
                :key="materia.id"
                :value="materia.id"
              >
                {{ materia.nombre }} - {{ materia.anio }}° Año - {{ materia.cuatrimestre }}° Cuatr.
              </option>
            </select>
            <p v-if="materiasDisponiblesParaAsignar.length === 0 && !cargandoMateriasDisponibles" class="text-amber-600 text-xs mt-1">
              No hay materias disponibles para asignar. Todas las materias de esta carrera ya están en el plan.
            </p>
          </div>

          <!-- Error -->
          <div v-if="errorModal" class="bg-red-50 border border-red-200 rounded-lg p-3">
            <p class="text-red-700 text-sm">{{ errorModal }}</p>
          </div>

          <!-- Botones -->
          <div class="flex gap-3 mt-6">
            <button
              type="button"
              @click="cerrarModal"
              class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-lg transition-colors"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-2.5 rounded-lg transition-all shadow-lg flex items-center justify-center gap-2"
              :disabled="!modalAsignarMateria.materiaSeleccionada || cargandoMateriasDisponibles"
            >
              <i class="bx bx-plus"></i>
              Asignar al Plan
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal para reemplazar materia en el plan (solo admin) -->
    <div
      v-if="modalReemplazarMateria.visible"
      class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
      @click.self="cerrarModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
        <!-- Header del modal -->
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <i class="bx bx-transfer text-3xl text-blue-600"></i>
            Reemplazar Materia
          </h3>
          <button
            @click="cerrarModal"
            class="text-gray-400 hover:text-gray-600 transition-colors"
          >
            <i class="bx bx-x text-3xl"></i>
          </button>
        </div>

        <div v-if="modalReemplazarMateria.materiaActual" class="mb-4 bg-gray-50 border border-gray-200 rounded-lg p-3">
          <p class="text-gray-700 text-sm font-medium">Materia actual:</p>
          <p class="text-gray-900 font-semibold">{{ modalReemplazarMateria.materiaActual.nombre }}</p>
        </div>

        <!-- Formulario -->
        <form @submit.prevent="reemplazarMateria" class="space-y-4">
          <!-- Selector de Nueva Materia -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Reemplazar por:</label>
            <div v-if="cargandoMateriasDisponibles" class="text-sm text-gray-500 py-4">
              <i class="bx bx-loader-alt bx-spin mr-2"></i>
              Cargando materias...
            </div>
            <select
              v-else
              v-model="modalReemplazarMateria.materiaNueva"
              class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500 transition-colors"
              required
            >
              <option :value="null">Seleccione una materia</option>
              <option
                v-for="materia in materiasDisponiblesParaAsignar"
                :key="materia.id"
                :value="materia.id"
              >
                {{ materia.nombre }} - {{ materia.anio }}° Año - {{ materia.cuatrimestre }}° Cuatr.
              </option>
            </select>
          </div>

          <!-- Error -->
          <div v-if="errorModal" class="bg-red-50 border border-red-200 rounded-lg p-3">
            <p class="text-red-700 text-sm">{{ errorModal }}</p>
          </div>

          <!-- Botones -->
          <div class="flex gap-3 mt-6">
            <button
              type="button"
              @click="cerrarModal"
              class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-lg transition-colors"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-2.5 rounded-lg transition-all shadow-lg flex items-center justify-center gap-2"
              :disabled="!modalReemplazarMateria.materiaNueva || cargandoMateriasDisponibles"
            >
              <i class="bx bx-transfer"></i>
              Reemplazar
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Crear Nuevo Plan -->
    <div v-if="modalNuevoPlan.visible" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Crear Nuevo Plan de Estudio</h3>
        <form @submit.prevent="crearNuevoPlan" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre del Plan *</label>
            <input v-model="modalNuevoPlan.nombre" type="text" required class="w-full border-2 border-gray-300 rounded-lg px-4 py-2" placeholder="Ej: Plan 2025">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Año *</label>
            <input v-model.number="modalNuevoPlan.anio" type="number" required class="w-full border-2 border-gray-300 rounded-lg px-4 py-2">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Resolución</label>
            <input v-model="modalNuevoPlan.resolucion" type="text" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2" placeholder="Número de resolución">
          </div>
          <div class="flex items-center">
            <input v-model="modalNuevoPlan.activo" type="checkbox" class="mr-2">
            <label class="text-sm text-gray-700">Marcar como plan activo</label>
          </div>
          <div v-if="errorModal" class="bg-red-50 border border-red-200 rounded-lg p-3">
            <p class="text-red-700 text-sm">{{ errorModal }}</p>
          </div>
          <div class="flex gap-3 mt-6">
            <button type="button" @click="cerrarModalNuevoPlan" class="flex-1 px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Cancelar</button>
            <button type="submit" :disabled="modalNuevoPlan.guardando" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50">
              {{ modalNuevoPlan.guardando ? 'Creando...' : 'Crear Plan' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </SidebarLayout>
</template>
