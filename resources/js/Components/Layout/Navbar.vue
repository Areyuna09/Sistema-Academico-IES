<script setup>
defineProps({
    sidebarOpen: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['toggleSidebar']);
</script>

<template>
    <header class="bg-gray-800 shadow-lg border-b border-gray-700">
        <div class="flex items-center justify-between px-6 py-2">
            <!-- Botón hamburguesa -->
            <button
                @click="emit('toggleSidebar')"
                class="p-2 text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition-all duration-200 mr-3"
            >
                <i class="bx bx-menu text-xl"></i>
            </button>

            <div class="flex-1">
                <slot name="header" />
            </div>

            <!-- Notifications -->
            <div class="flex items-center space-x-2">
                <button class="relative p-2 text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition-all duration-200">
                    <i class="bx bx-bell text-lg"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
            </div>
        </div>
    </header>
</template>
