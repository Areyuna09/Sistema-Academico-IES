<script setup>
defineProps({
    sidebarOpen: {
        type: Boolean,
        default: false
    },
    showHelpButton: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['toggleSidebar', 'showHelp']);
</script>

<template>
    <header class="bg-gray-800 shadow-lg border-b border-gray-700">
        <div class="flex items-center justify-between px-3 sm:px-6 py-2">
            <!-- Botón hamburguesa -->
            <button
                @click="emit('toggleSidebar')"
                class="p-1.5 sm:p-2 text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition-all duration-200 mr-2 sm:mr-3"
            >
                <i class="bx bx-menu text-lg sm:text-xl"></i>
            </button>

            <div class="flex-1 min-w-0">
                <slot name="header" />
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-1 sm:space-x-2">
                <!-- Botón de ayuda/tutorial (solo visible si showHelpButton=true) -->
                <button
                    v-if="showHelpButton"
                    @click="emit('showHelp')"
                    class="onboarding-help-button p-1.5 sm:p-2 text-gray-400 hover:bg-blue-600 hover:text-white rounded-lg transition-all duration-200"
                    title="Ver tutorial del sistema"
                >
                    <i class="bx bx-help-circle text-base sm:text-lg"></i>
                </button>

            </div>
        </div>
    </header>
</template>
