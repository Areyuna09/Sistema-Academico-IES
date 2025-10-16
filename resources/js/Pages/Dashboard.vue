<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
    You're logged in!
    <div class="mt-4">
        <a
            href="/panel-profesor"
            class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
        >
            Ir al Panel del Profesor
        </a>
        <a href="/panel-profesor/notas"
            class="ml-4 inline-block px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
            Notas Temporales
        </a>
    </div>
</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
