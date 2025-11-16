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
              class="w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
            >
              <DialogTitle
                as="h3"
                class="text-lg font-medium leading-6 text-gray-900"
              >
                {{ modo === 'crear' ? 'Crear Nuevo Plan de Estudio' : 'Editar Plan de Estudio' }}
              </DialogTitle>

              <form @submit.prevent="guardar" class="mt-4">
                <!-- Nombre -->
                <div class="mb-4">
                  <label for="nombre" class="block text-sm font-medium text-gray-700">
                    Nombre del Plan <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="nombre"
                    v-model="form.nombre"
                    type="text"
                    required
                    maxlength="100"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Ej: Plan 2025, Plan Actualizado, etc."
                  />
                  <p v-if="errors.nombre" class="mt-1 text-sm text-red-600">
                    {{ errors.nombre }}
                  </p>
                </div>

                <!-- Año -->
                <div class="mb-4">
                  <label for="anio" class="block text-sm font-medium text-gray-700">
                    Año <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="anio"
                    v-model.number="form.anio"
                    type="number"
                    required
                    :min="1900"
                    :max="new Date().getFullYear() + 10"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="2025"
                  />
                  <p v-if="errors.anio" class="mt-1 text-sm text-red-600">
                    {{ errors.anio }}
                  </p>
                </div>

                <!-- Resolución -->
                <div class="mb-4">
                  <label for="resolucion" class="block text-sm font-medium text-gray-700">
                    Resolución (opcional)
                  </label>
                  <input
                    id="resolucion"
                    v-model="form.resolucion"
                    type="text"
                    maxlength="255"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Ej: Res. 123/2025"
                  />
                  <p v-if="errors.resolucion" class="mt-1 text-sm text-red-600">
                    {{ errors.resolucion }}
                  </p>
                </div>

                <!-- Descripción -->
                <div class="mb-4">
                  <label for="descripcion" class="block text-sm font-medium text-gray-700">
                    Descripción (opcional)
                  </label>
                  <textarea
                    id="descripcion"
                    v-model="form.descripcion"
                    rows="3"
                    maxlength="1000"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Descripción breve del plan, cambios realizados, etc."
                  />
                  <p class="mt-1 text-xs text-gray-500">
                    {{ form.descripcion?.length || 0 }}/1000 caracteres
                  </p>
                  <p v-if="errors.descripcion" class="mt-1 text-sm text-red-600">
                    {{ errors.descripcion }}
                  </p>
                </div>

                <!-- Activo -->
                <div class="mb-4">
                  <div class="flex items-start">
                    <div class="flex h-5 items-center">
                      <input
                        id="activo"
                        v-model="form.activo"
                        type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500"
                      />
                    </div>
                    <div class="ml-3 text-sm">
                      <label for="activo" class="font-medium text-gray-700">
                        Plan activo
                      </label>
                      <p class="text-gray-500">
                        Los planes activos son aquellos que están siendo utilizados actualmente por alumnos.
                        Puede haber múltiples planes activos simultáneamente.
                      </p>
                    </div>
                  </div>
                  <p v-if="errors.activo" class="mt-1 text-sm text-red-600">
                    {{ errors.activo }}
                  </p>
                </div>

                <!-- Vigente -->
                <div class="mb-6">
                  <div class="flex items-start">
                    <div class="flex h-5 items-center">
                      <input
                        id="vigente"
                        v-model="form.vigente"
                        type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                      />
                    </div>
                    <div class="ml-3 text-sm">
                      <label for="vigente" class="font-medium text-gray-700">
                        Plan vigente (para nuevos inscriptos)
                      </label>
                      <p class="text-gray-500">
                        Solo puede haber un plan vigente por carrera. Es el plan que se asigna por defecto
                        a los alumnos que se inscriben por primera vez. Al marcar este como vigente,
                        los demás planes de esta carrera se marcarán como no vigentes automáticamente.
                      </p>
                    </div>
                  </div>
                  <p v-if="errors.vigente" class="mt-1 text-sm text-red-600">
                    {{ errors.vigente }}
                  </p>
                </div>

                <!-- Error general -->
                <div v-if="errors.error" class="mb-4 rounded-md bg-red-50 p-4">
                  <p class="text-sm text-red-800">{{ errors.error }}</p>
                </div>

                <!-- Botones -->
                <div class="flex justify-end gap-3">
                  <button
                    type="button"
                    @click="cerrar"
                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    :disabled="processing"
                  >
                    Cancelar
                  </button>
                  <button
                    type="submit"
                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                    :disabled="processing"
                  >
                    {{ processing ? 'Guardando...' : (modo === 'crear' ? 'Crear Plan' : 'Guardar Cambios') }}
                  </button>
                </div>
              </form>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue'

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  modo: {
    type: String,
    required: true,
    validator: (value) => ['crear', 'editar'].includes(value)
  },
  plan: {
    type: Object,
    default: null
  },
  carreraId: {
    type: Number,
    default: null
  }
})

const emit = defineEmits(['close', 'guardado'])

const form = ref({
  nombre: '',
  anio: new Date().getFullYear(),
  resolucion: '',
  descripcion: '',
  activo: false,
  vigente: false
})

const errors = ref({})
const processing = ref(false)

// Watch para resetear el formulario cuando se abre/cierra o cambia el plan
watch(() => props.show, (newValue) => {
  if (newValue) {
    resetForm()
  }
})

watch(() => props.plan, () => {
  if (props.show) {
    resetForm()
  }
})

const resetForm = () => {
  errors.value = {}

  if (props.modo === 'editar' && props.plan) {
    form.value = {
      nombre: props.plan.nombre || '',
      anio: props.plan.anio || new Date().getFullYear(),
      resolucion: props.plan.resolucion || '',
      descripcion: props.plan.descripcion || '',
      activo: props.plan.activo || false,
      vigente: props.plan.vigente || false
    }
  } else {
    form.value = {
      nombre: '',
      anio: new Date().getFullYear(),
      resolucion: '',
      descripcion: '',
      activo: false,
      vigente: false
    }
  }
}

const cerrar = () => {
  if (!processing.value) {
    emit('close')
  }
}

const guardar = () => {
  errors.value = {}
  processing.value = true

  if (props.modo === 'crear') {
    // Crear nuevo plan
    router.post(
      route('admin.planes-estudio.store', { carrera: props.carreraId }),
      {
        ...form.value,
        carrera_id: props.carreraId
      },
      {
        preserveScroll: true,
        onSuccess: () => {
          emit('guardado')
        },
        onError: (err) => {
          errors.value = err
        },
        onFinish: () => {
          processing.value = false
        }
      }
    )
  } else {
    // Editar plan existente
    router.put(
      route('admin.planes-estudio.update', props.plan.id),
      form.value,
      {
        preserveScroll: true,
        onSuccess: () => {
          emit('guardado')
        },
        onError: (err) => {
          errors.value = err
        },
        onFinish: () => {
          processing.value = false
        }
      }
    )
  }
}
</script>
