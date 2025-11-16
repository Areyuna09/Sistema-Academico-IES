<template>
    <Head title="Planes de Estudio" />

  <SidebarLayout :user="$page.props.auth.user">
    <template #header>
      <div>
        <h1 class="text-xl font-semibold text-white">Gestión de Planes de Estudio</h1>
        <p class="text-xs text-gray-400 mt-0.5">Administrar planes de estudio por carrera</p>
      </div>
    </template>

    <div class="p-8 max-w-7xl mx-auto">
      <!-- Información general -->
      <div class="mb-6 rounded-lg bg-blue-50 p-4">
        <p class="text-sm text-blue-800">
          <strong>Información:</strong> Gestiona los planes de estudio de cada carrera.
          Puede haber múltiples planes activos simultáneamente (alumnos antiguos y nuevos).
          El plan marcado como <strong>"Vigente"</strong> es el que se asigna por defecto a nuevos inscriptos.
        </p>
      </div>

      <!-- Lista de Carreras -->
      <div v-if="carreras.length === 0" class="rounded-lg bg-white p-8 text-center shadow-md">
        <p class="text-gray-500">No hay carreras registradas en el sistema.</p>
      </div>

      <div v-else class="space-y-6">
        <div
          v-for="carrera in carreras"
          :key="carrera.id"
          class="overflow-hidden rounded-lg bg-white shadow-md"
        >
          <!-- Cabecera de Carrera -->
          <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-lg font-semibold text-gray-900">
                  {{ carrera.nombre }}
                </h3>
                <div class="mt-1 flex gap-4 text-sm text-gray-600">
                  <span v-if="carrera.resolucion">
                    Resolución: {{ carrera.resolucion }}
                  </span>
                  <span>{{ carrera.materias_count }} materias</span>
                  <span>{{ carrera.alumnos_count }} alumnos</span>
                </div>
              </div>
              <button
                @click="abrirModalCrear(carrera.id)"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
              >
                + Nuevo Plan
              </button>
            </div>
          </div>

          <!-- Lista de Planes -->
          <div class="p-6">
            <div v-if="carrera.planes.length === 0" class="text-center text-gray-500">
              <p>No hay planes de estudio para esta carrera.</p>
              <p class="mt-1 text-sm">Crea el primer plan usando el botón "Nuevo Plan".</p>
            </div>

            <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
              <TarjetaPlan
                v-for="plan in carrera.planes"
                :key="plan.id"
                :plan="plan"
                :carrera-nombre="carrera.nombre"
                @ver-materias="abrirModalMaterias"
                @editar="abrirModalEditar"
                @clonar="abrirModalClonar"
                @toggle-activo="toggleActivo"
                @toggle-vigente="toggleVigente"
                @eliminar="confirmarEliminar"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modales -->
    <ModalCrearEditarPlan
      :show="modalCrearEditar.show"
      :modo="modalCrearEditar.modo"
      :plan="modalCrearEditar.plan"
      :carrera-id="modalCrearEditar.carreraId"
      @close="cerrarModalCrearEditar"
      @guardado="handlePlanGuardado"
    />

    <ModalClonarPlan
      :show="modalClonar.show"
      :plan="modalClonar.plan"
      @close="cerrarModalClonar"
      @clonado="handlePlanClonado"
    />

    <!-- Modal de Confirmación de Eliminación -->
    <ModalConfirmacion
      :show="modalEliminar.show"
      :titulo="'Eliminar Plan de Estudio'"
      :mensaje="`¿Estás seguro de eliminar el plan &quot;${modalEliminar.planNombre}&quot;? Esta acción no se puede deshacer.`"
      :tipo="'danger'"
      @confirmar="eliminarPlan"
      @cancelar="cerrarModalEliminar"
    />

    <!-- Modal de Gestión de Materias -->
    <ModalGestionMaterias
      :show="modalMaterias.show"
      :plan="modalMaterias.plan"
      @close="cerrarModalMaterias"
    />
  </SidebarLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import SidebarLayout from '@/Layouts/SidebarLayout.vue'
import TarjetaPlan from './Components/TarjetaPlan.vue'
import ModalCrearEditarPlan from './Components/ModalCrearEditarPlan.vue'
import ModalClonarPlan from './Components/ModalClonarPlan.vue'
import ModalConfirmacion from '@/Components/ModalConfirmacion.vue'
import ModalGestionMaterias from './Components/ModalGestionMaterias.vue'

// Props
const props = defineProps({
  carreras: {
    type: Array,
    required: true
  }
})

// Estado de modales
const modalCrearEditar = ref({
  show: false,
  modo: 'crear', // 'crear' | 'editar'
  plan: null,
  carreraId: null
})

const modalClonar = ref({
  show: false,
  plan: null
})

const modalEliminar = ref({
  show: false,
  planId: null,
  planNombre: ''
})

const modalMaterias = ref({
  show: false,
  plan: null
})

// Funciones de modal Crear/Editar
const abrirModalCrear = (carreraId) => {
  modalCrearEditar.value = {
    show: true,
    modo: 'crear',
    plan: null,
    carreraId: carreraId
  }
}

const abrirModalEditar = (plan) => {
  modalCrearEditar.value = {
    show: true,
    modo: 'editar',
    plan: plan,
    carreraId: null
  }
}

const cerrarModalCrearEditar = () => {
  modalCrearEditar.value = {
    show: false,
    modo: 'crear',
    plan: null,
    carreraId: null
  }
}

const handlePlanGuardado = () => {
  cerrarModalCrearEditar()
  router.reload({ only: ['carreras'] })
}

// Funciones de modal Clonar
const abrirModalClonar = (plan) => {
  modalClonar.value = {
    show: true,
    plan: plan
  }
}

const cerrarModalClonar = () => {
  modalClonar.value = {
    show: false,
    plan: null
  }
}

const handlePlanClonado = () => {
  cerrarModalClonar()
  router.reload({ only: ['carreras'] })
}

// Funciones de Toggle Activo
const toggleActivo = (plan) => {
  router.patch(
    route('admin.planes-estudio.toggle-activo', plan.id),
    {},
    {
      preserveScroll: true,
      onSuccess: () => {
        router.reload({ only: ['carreras'] })
      }
    }
  )
}

// Funciones de Toggle Vigente
const toggleVigente = (plan) => {
  router.patch(
    route('admin.planes-estudio.toggle-vigente', plan.id),
    {},
    {
      preserveScroll: true,
      onSuccess: () => {
        router.reload({ only: ['carreras'] })
      }
    }
  )
}

// Funciones de Eliminación
const confirmarEliminar = (plan) => {
  modalEliminar.value = {
    show: true,
    planId: plan.id,
    planNombre: plan.nombre
  }
}

const cerrarModalEliminar = () => {
  modalEliminar.value = {
    show: false,
    planId: null,
    planNombre: ''
  }
}

const eliminarPlan = () => {
  router.delete(route('admin.planes-estudio.destroy', modalEliminar.value.planId), {
    preserveScroll: true,
    onSuccess: () => {
      cerrarModalEliminar()
      router.reload({ only: ['carreras'] })
    }
  })
}

// Funciones de Gestión de Materias
const abrirModalMaterias = (plan) => {
  modalMaterias.value = {
    show: true,
    plan: plan
  }
}

const cerrarModalMaterias = () => {
  modalMaterias.value = {
    show: false,
    plan: null
  }
}
</script>
