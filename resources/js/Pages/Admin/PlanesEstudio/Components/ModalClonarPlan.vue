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
              class="w-full max-w-lg transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
            >
              <DialogTitle
                as="h3"
                class="text-lg font-medium leading-6 text-gray-900"
              >
                Clonar Plan de Estudio
              </DialogTitle>

              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  Se creará una copia del plan <strong>{{ plan?.nombre }}</strong> con todas
                  sus materias. El nuevo plan se creará como archivado.
                </p>
              </div>

              <form @submit.prevent="clonar" class="mt-4">
                <!-- Nombre del nuevo plan -->
                <div class="mb-4">
                  <label for="nombre" class="block text-sm font-medium text-gray-700">
                    Nombre del Nuevo Plan <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="nombre"
                    v-model="form.nombre"
                    type="text"
                    required
                    maxlength="100"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    :placeholder="`${plan?.nombre} - Copia`"
                  />
                  <p v-if="errors.nombre" class="mt-1 text-sm text-red-600">
                    {{ errors.nombre }}
                  </p>
                </div>

                <!-- Año del nuevo plan -->
                <div class="mb-4">
                  <label for="anio" class="block text-sm font-medium text-gray-700">
                    Año del Nuevo Plan <span class="text-red-500">*</span>
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

                <!-- Información adicional -->
                <div class="mb-6 rounded-md bg-blue-50 p-4">
                  <div class="flex">
                    <div class="flex-shrink-0">
                      <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div class="ml-3 flex-1">
                      <p class="text-sm text-blue-700">
                        Se copiarán <strong>{{ plan?.materias_count || 0 }} materias</strong> con su orden.
                        El plan clonado se creará como <strong>archivado</strong> y deberás activarlo manualmente si lo deseas.
                      </p>
                    </div>
                  </div>
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
                    class="rounded-md bg-purple-600 px-4 py-2 text-sm font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 disabled:opacity-50"
                    :disabled="processing"
                  >
                    {{ processing ? 'Clonando...' : '📋 Clonar Plan' }}
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
import { ref, watch } from 'vue'
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
  plan: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'clonado'])

const form = ref({
  nombre: '',
  anio: new Date().getFullYear()
})

const errors = ref({})
const processing = ref(false)

// Watch para resetear el formulario cuando se abre
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
  form.value = {
    nombre: props.plan ? `${props.plan.nombre} - Copia` : '',
    anio: new Date().getFullYear()
  }
}

const cerrar = () => {
  if (!processing.value) {
    emit('close')
  }
}

const clonar = () => {
  if (!props.plan) {
    return
  }

  errors.value = {}
  processing.value = true

  router.post(
    route('admin.planes-estudio.clonar', props.plan.id),
    form.value,
    {
      preserveScroll: true,
      onSuccess: () => {
        emit('clonado')
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
</script>
