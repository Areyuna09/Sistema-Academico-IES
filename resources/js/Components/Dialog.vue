<script setup>
import { useDialog } from '@/Composables/useDialog';

const {
    isOpen,
    dialogType,
    dialogTitle,
    dialogMessage,
    promptValue,
    promptPlaceholder,
    handleConfirm,
    handleCancel
} = useDialog();
</script>

<template>
    <!-- Overlay -->
    <Transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="isOpen"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4"
            @click.self="dialogType !== 'alert' && handleCancel()"
        >
            <!-- Dialog -->
            <Transition
                enter-active-class="transition ease-out duration-200 transform"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition ease-in duration-150 transform"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
            >
                <div
                    v-if="isOpen"
                    class="bg-white rounded-lg shadow-xl max-w-md w-full"
                    @click.stop
                >
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div
                                :class="[
                                    'w-10 h-10 rounded-full flex items-center justify-center mr-3',
                                    dialogType === 'confirm' ? 'bg-blue-100' : dialogType === 'alert' ? 'bg-green-100' : 'bg-purple-100'
                                ]"
                            >
                                <i
                                    :class="[
                                        'bx text-2xl',
                                        dialogType === 'confirm' ? 'bx-help-circle text-blue-600' :
                                        dialogType === 'alert' ? 'bx-info-circle text-green-600' :
                                        'bx-edit text-purple-600'
                                    ]"
                                ></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ dialogTitle }}</h3>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-4">
                        <p class="text-gray-700 text-sm leading-relaxed">{{ dialogMessage }}</p>

                        <!-- Input para prompt -->
                        <input
                            v-if="dialogType === 'prompt'"
                            v-model="promptValue"
                            type="text"
                            :placeholder="promptPlaceholder"
                            class="mt-4 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            @keyup.enter="handleConfirm"
                        />
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end gap-3">
                        <button
                            v-if="dialogType !== 'alert'"
                            @click="handleCancel"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="handleConfirm"
                            :class="[
                                'px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors',
                                dialogType === 'confirm' ? 'bg-blue-600 hover:bg-blue-700' :
                                dialogType === 'alert' ? 'bg-green-600 hover:bg-green-700' :
                                'bg-purple-600 hover:bg-purple-700'
                            ]"
                        >
                            {{ dialogType === 'prompt' ? 'Enviar' : dialogType === 'alert' ? 'Aceptar' : 'Confirmar' }}
                        </button>
                    </div>
                </div>
            </Transition>
        </div>
    </Transition>
</template>
