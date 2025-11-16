<template>
  <TransitionRoot appear :show="show" as="template">
    <Dialog as="div" @close="cerrar" class="relative z-50">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel
              class="w-full max-w-5xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
            >
              <DialogTitle
                as="h3"
                class="text-lg font-medium leading-6 text-gray-900 flex items-center justify-between"
              >
                <span>Materias del Plan: {{ planData?.nombre }}</span>
                <button
                  @click="cerrar"
                  class="text-gray-400 hover:text-gray-600"
                >
                  <i class="bx bx-x text-2xl"></i>
                </button>
              </DialogTitle>

              <!-- Loading -->
              <div v-if="loading" class="py-12 text-center">
                <i class="bx bx-loader-alt bx-spin text-4xl text-blue-600"></i>
                <p class="mt-2 text-gray-600">Cargando materias...</p>
              </div>

              <!-- Contenido -->
              <div v-else class="mt-4">
                <!-- Formulario Agregar Materia -->
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4">
                  <h4 class="mb-3 font-semibold text-gray-900">Agregar Materia al Plan</h4>
                  <form @submit.prevent="agregarMateria" class="flex gap-3">
                    <select
                      v-model="formAgregar.materia_id"
                      required
                      class="flex-1 rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500"
                    >
                      <option value="">Seleccionar materia...</option>
                      <optgroup
                        v-for="grupo in materiasDisponiblesPorAnio"
                        :key="grupo.anio"
                        :label="`Año ${grupo.anio || 'No especificado'}`"
                      >
                        <option
                          v-for="materia in grupo.materias"
                          :key="materia.id"
                          :value="materia.id"
                        >
                          {{ materia.nombre }} - Cuatr. {{ materia.cuatrimestre || 'N/A' }}
                        </option>
                      </optgroup>
                    </select>
                    <button
                      type="submit"
                      :disabled="!formAgregar.materia_id || procesando"
                      class="rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 disabled:opacity-50"
                    >
                      {{ procesando ? 'Agregando...' : '+ Agregar' }}
                    </button>
                  </form>
                </div>

                <!-- Materias por año -->
                <div v-if="materiasPorAnio.length === 0" class="py-8 text-center text-gray-500">
                  <p>No hay materias en este plan.</p>
                  <p class="text-sm mt-1">Usa el botón "Agregar Materia" para comenzar.</p>
                </div>

                <div v-else class="space-y-4">
                  <div
                    v-for="grupo in materiasPorAnio"
                    :key="grupo.anio"
                    class="rounded-lg border border-gray-200 overflow-hidden"
                  >
                    <!-- Header del año -->
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                      <h4 class="font-semibold text-gray-900">
                        Año {{ grupo.anio || 'No especificado' }}
                        <span class="ml-2 text-sm font-normal text-gray-600">
                          ({{ grupo.materias.length }} materias)
                        </span>
                      </h4>
                    </div>

                    <!-- Lista de materias -->
                    <div class="divide-y divide-gray-200">
                      <div
                        v-for="materia in grupo.materias"
                        :key="materia.id"
                        class="px-4 py-3 hover:bg-gray-50"
                      >
                        <div class="flex items-center justify-between">
                          <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ materia.nombre }}</p>
                            <p class="text-sm text-gray-500">
                              Cuatrimestre: {{ materia.cuatrimestre || 'N/A' }}
                            </p>
                          </div>

                          <div class="flex gap-2">
                            <!-- Botón Toggle Reemplazar -->
                            <button
                              v-if="!materiaReemplazando || materiaReemplazando.id !== materia.id"
                              @click="iniciarReemplazo(materia)"
                              class="rounded bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700"
                              title="Reemplazar materia"
                            >
                              🔄 Reemplazar
                            </button>
                            <button
                              v-else
                              @click="cancelarReemplazo"
                              class="rounded bg-gray-500 px-3 py-1.5 text-sm font-medium text-white hover:bg-gray-600"
                            >
                              Cancelar
                            </button>

                            <!-- Botón Eliminar -->
                            <button
                              @click="confirmarEliminar(materia)"
                              class="rounded bg-red-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-700"
                              title="Quitar del plan"
                            >
                              🗑️ Quitar
                            </button>
                          </div>
                        </div>

                        <!-- Formulario Reemplazar (inline) -->
                        <div
                          v-if="materiaReemplazando && materiaReemplazando.id === materia.id"
                          class="mt-3 rounded-md border border-blue-200 bg-blue-50 p-3"
                        >
                          <form @submit.prevent="reemplazarMateria" class="flex gap-3">
                            <select
                              v-model="formReemplazar.materia_nueva_id"
                              required
                              class="flex-1 rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                              <option value="">Reemplazar por...</option>
                              <optgroup
                                v-for="grupo in materiasDisponiblesPorAnio"
                                :key="grupo.anio"
                                :label="`Año ${grupo.anio || 'No especificado'}`"
                              >
                                <option
                                  v-for="mat in grupo.materias"
                                  :key="mat.id"
                                  :value="mat.id"
                                >
                                  {{ mat.nombre }} - Cuatr. {{ mat.cuatrimestre || 'N/A' }}
                                </option>
                              </optgroup>
                            </select>
                            <button
                              type="submit"
                              :disabled="!formReemplazar.materia_nueva_id || procesando"
                              class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
                            >
                              {{ procesando ? 'Reemplazando...' : 'Confirmar' }}
                            </button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- Modal de Confirmación para Eliminar -->
  <ModalConfirmacion
    :show="modalEliminar.show"
    :titulo="'Quitar Materia del Plan'"
    :mensaje="`¿Está seguro de quitar &quot;${modalEliminar.materiaNombre}&quot; del plan? Esta acción no elimina la materia, solo la quita de este plan.`"
    :tipo="'warning'"
    @confirmar="eliminarMateria"
    @cancelar="cancelarEliminar"
  />
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue'
import ModalConfirmacion from '@/Components/ModalConfirmacion.vue'

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  plan: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close'])

const loading = ref(false)
const procesando = ref(false)
const planData = ref(null)
const materias = ref([])
const materiasDisponibles = ref([])

// Estado para reemplazo inline
const materiaReemplazando = ref(null)

// Estado para modal de eliminación
const modalEliminar = ref({
  show: false,
  planId: null,
  materiaId: null,
  materiaNombre: ''
})

// Forms
const formAgregar = ref({
  materia_id: ''
})

const formReemplazar = ref({
  materia_nueva_id: ''
})

// Agrupar materias por año
const materiasPorAnio = computed(() => {
  const grupos = {}

  materias.value.forEach(materia => {
    const anio = materia.anio || 0
    if (!grupos[anio]) {
      grupos[anio] = {
        anio,
        materias: []
      }
    }
    grupos[anio].materias.push(materia)
  })

  // Ordenar grupos por año y materias dentro de cada grupo por cuatrimestre
  return Object.values(grupos)
    .sort((a, b) => a.anio - b.anio)
    .map(grupo => ({
      ...grupo,
      materias: grupo.materias.sort((a, b) => {
        // Ordenar por cuatrimestre, luego por nombre
        if (a.cuatrimestre !== b.cuatrimestre) {
          return (a.cuatrimestre || 0) - (b.cuatrimestre || 0)
        }
        return (a.nombre || '').localeCompare(b.nombre || '')
      })
    }))
})

// Agrupar materias disponibles por año
const materiasDisponiblesPorAnio = computed(() => {
  const grupos = {}

  materiasDisponibles.value.forEach(materia => {
    const anio = materia.anio || 0
    if (!grupos[anio]) {
      grupos[anio] = {
        anio,
        materias: []
      }
    }
    grupos[anio].materias.push(materia)
  })

  // Ordenar grupos por año y materias dentro de cada grupo por cuatrimestre
  return Object.values(grupos)
    .sort((a, b) => a.anio - b.anio)
    .map(grupo => ({
      ...grupo,
      materias: grupo.materias.sort((a, b) => {
        // Ordenar por cuatrimestre, luego por nombre
        if (a.cuatrimestre !== b.cuatrimestre) {
          return (a.cuatrimestre || 0) - (b.cuatrimestre || 0)
        }
        return (a.nombre || '').localeCompare(b.nombre || '')
      })
    }))
})

// Cargar materias cuando se abre el modal
watch(() => props.show, async (newValue) => {
  if (newValue && props.plan) {
    await cargarMaterias()
  } else {
    // Limpiar estado al cerrar
    materiaReemplazando.value = null
    formAgregar.value.materia_id = ''
    formReemplazar.value.materia_nueva_id = ''
  }
})

const cargarMaterias = async () => {
  if (!props.plan) return

  loading.value = true
  try {
    const { data } = await axios.get(`/admin/planes-estudio/planes/${props.plan.id}/materias`)
    console.log('Datos recibidos del backend:', data)
    console.log('Materias del plan:', data.materias)
    console.log('Materias disponibles:', data.materiasDisponibles)
    planData.value = data.plan
    materias.value = data.materias
    materiasDisponibles.value = data.materiasDisponibles
  } catch (error) {
    console.error('Error al cargar materias:', error)
  } finally {
    loading.value = false
  }
}

const cerrar = () => {
  emit('close')
}

// Agregar materia
const agregarMateria = () => {
  if (!formAgregar.value.materia_id) return

  procesando.value = true

  router.post(
    `/admin/planes-estudio/planes/${props.plan.id}/materias`,
    { materia_id: formAgregar.value.materia_id },
    {
      preserveScroll: true,
      onSuccess: () => {
        formAgregar.value.materia_id = ''
        cargarMaterias()
      },
      onFinish: () => {
        procesando.value = false
      }
    }
  )
}

// Reemplazar materia
const iniciarReemplazo = (materia) => {
  materiaReemplazando.value = materia
  formReemplazar.value.materia_nueva_id = ''
}

const cancelarReemplazo = () => {
  materiaReemplazando.value = null
  formReemplazar.value.materia_nueva_id = ''
}

const reemplazarMateria = () => {
  if (!formReemplazar.value.materia_nueva_id || !materiaReemplazando.value) return

  procesando.value = true

  router.post(
    `/admin/planes-estudio/planes/${props.plan.id}/reemplazar-materia`,
    {
      materia_actual_id: materiaReemplazando.value.id,
      materia_nueva_id: formReemplazar.value.materia_nueva_id
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        cancelarReemplazo()
        cargarMaterias()
      },
      onFinish: () => {
        procesando.value = false
      }
    }
  )
}

// Eliminar materia
const confirmarEliminar = (materia) => {
  modalEliminar.value = {
    show: true,
    planId: props.plan.id,
    materiaId: materia.id,
    materiaNombre: materia.nombre
  }
}

const cancelarEliminar = () => {
  modalEliminar.value = {
    show: false,
    planId: null,
    materiaId: null,
    materiaNombre: ''
  }
}

const eliminarMateria = () => {
  console.log('eliminarMateria llamado')
  console.log('modalEliminar.value:', modalEliminar.value)

  if (!modalEliminar.value.planId || !modalEliminar.value.materiaId) {
    console.log('Falta planId o materiaId, retornando')
    return
  }

  const url = `/admin/planes-estudio/planes/${modalEliminar.value.planId}/materias/${modalEliminar.value.materiaId}`
  console.log('URL DELETE:', url)

  router.delete(url, {
    preserveScroll: true,
    onSuccess: () => {
      console.log('DELETE exitoso')
      cancelarEliminar()
      cargarMaterias()
    },
    onError: (errors) => {
      console.error('Error en DELETE:', errors)
    }
  })
}
</script>
