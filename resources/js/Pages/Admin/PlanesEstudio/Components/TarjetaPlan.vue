<template>
  <div
    class="relative rounded-lg border p-4 transition-shadow hover:shadow-md"
    :class="plan.activo ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white'"
  >
    <!-- Badges de Estado -->
    <div class="absolute right-4 top-4 flex flex-col gap-2 items-end">
      <!-- Badge Vigente (para nuevos inscriptos) -->
      <span
        v-if="plan.vigente"
        class="rounded-full px-3 py-1 text-xs font-semibold bg-blue-600 text-white"
        title="Plan vigente para nuevos inscriptos"
      >
        ✓ Vigente
      </span>

      <!-- Badge Activo/Archivado -->
      <span
        class="rounded-full px-3 py-1 text-xs font-semibold"
        :class="
          plan.activo
            ? 'bg-green-600 text-white'
            : 'bg-gray-400 text-white'
        "
      >
        {{ plan.activo ? '● Activo' : '○ Archivado' }}
      </span>
    </div>

    <!-- Contenido Principal -->
    <div class="mb-4 pr-24">
      <h4 class="text-lg font-semibold text-gray-900">
        {{ plan.nombre }}
      </h4>
      <div class="mt-2 space-y-1 text-sm text-gray-600">
        <p>
          <span class="font-medium">Año:</span> {{ plan.anio }}
        </p>
        <p v-if="plan.resolucion">
          <span class="font-medium">Resolución:</span> {{ plan.resolucion }}
        </p>
        <p>
          <span class="font-medium">Materias:</span> {{ plan.materias_count }}
        </p>
      </div>

      <!-- Descripción -->
      <p v-if="plan.descripcion" class="mt-3 text-sm text-gray-700 italic">
        {{ plan.descripcion }}
      </p>

      <!-- Fechas -->
      <div class="mt-3 flex gap-4 text-xs text-gray-500">
        <span v-if="plan.created_at">Creado: {{ plan.created_at }}</span>
        <span v-if="plan.updated_at">Modificado: {{ plan.updated_at }}</span>
      </div>
    </div>

    <!-- Acciones -->
    <div class="flex flex-wrap gap-2 border-t border-gray-200 pt-4">
      <!-- Ver Materias -->
      <button
        @click="$emit('ver-materias', plan)"
        class="flex-1 rounded bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
        title="Ver y gestionar materias del plan"
      >
        📚 Ver Materias
      </button>

      <!-- Editar -->
      <button
        @click="$emit('editar', plan)"
        class="rounded bg-gray-600 px-3 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500"
        title="Editar plan"
      >
        ✏️ Editar
      </button>

      <!-- Clonar -->
      <button
        @click="$emit('clonar', plan)"
        class="rounded bg-purple-600 px-3 py-2 text-sm font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500"
        title="Clonar plan con sus materias"
      >
        📋 Clonar
      </button>

      <!-- Toggle Activo/Archivado -->
      <button
        @click="$emit('toggle-activo', plan)"
        class="rounded px-3 py-2 text-sm font-medium text-white focus:outline-none focus:ring-2"
        :class="
          plan.activo
            ? 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-500'
            : 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
        "
        :title="plan.activo ? 'Archivar plan' : 'Activar plan'"
      >
        {{ plan.activo ? '📦 Archivar' : '✓ Activar' }}
      </button>

      <!-- Toggle Vigente -->
      <button
        @click="$emit('toggle-vigente', plan)"
        class="rounded px-3 py-2 text-sm font-medium text-white focus:outline-none focus:ring-2"
        :class="
          plan.vigente
            ? 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500'
            : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'
        "
        :title="plan.vigente ? 'Desmarcar como vigente' : 'Marcar como vigente para nuevos inscriptos'"
      >
        {{ plan.vigente ? '⊗ No vigente' : '★ Vigente' }}
      </button>

      <!-- Eliminar -->
      <button
        @click="$emit('eliminar', plan)"
        class="rounded bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
        title="Eliminar plan"
      >
        🗑️ Eliminar
      </button>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
  plan: {
    type: Object,
    required: true
  },
  carreraNombre: {
    type: String,
    required: true
  }
})

defineEmits(['ver-materias', 'editar', 'clonar', 'toggle-activo', 'toggle-vigente', 'eliminar'])
</script>
