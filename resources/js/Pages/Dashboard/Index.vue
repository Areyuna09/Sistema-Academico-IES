<script setup>
import { computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    alumno: Object,
    cursadas: Array,
    stats: Object,
});

const porcentajeAprobadas = computed(() =>
    ((props.stats.aprobadas / props.stats.totalMaterias) * 100).toFixed(0)
);

const porcentajeRegulares = computed(() =>
    ((props.stats.regulares / props.stats.totalRegulares) * 100).toFixed(0)
);
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex min-h-screen bg-gradient-to-br from-white-500 to-gray-800">
        <!-- Sidebar -->
        <aside
            class="w-20 md:w-64 bg-gradient-to-b from-cyan-800 to-gray-700 text-white flex flex-col p-4 md:p-6"
        >
            <h1 class="hidden md:block text-2xl font-bold mb-10">IES</h1>
            <nav
                class="flex-1 space-y-6 flex flex-col items-center md:items-start"
            >
                <Link
                    href="/dashboard"
                    class="flex items-center space-x-2 hover:text-cyan-300"
                >
                    <span class="text-xl">🏠</span>
                    <span class="hidden md:inline">Inicio</span>
                </Link>
                <Link
                    href="/inscripciones"
                    class="flex items-center space-x-2 hover:text-cyan-300"
                >
                    <span class="text-xl">📝</span>
                    <span class="hidden md:inline">Inscripciones</span>
                </Link>
                <Link
                    href="/expediente"
                    class="flex items-center space-x-2 hover:text-cyan-300"
                >
                    <span class="text-xl">📂</span>
                    <span class="hidden md:inline">Expediente</span>
                </Link>
                <Link
                    href="/mesas"
                    class="flex items-center space-x-2 hover:text-cyan-300"
                >
                    <span class="text-xl">📅</span>
                    <span class="hidden md:inline">Mesas</span>
                </Link>
                <Link
                    href="/excepciones"
                    class="flex items-center space-x-2 hover:text-cyan-300"
                >
                    <span class="text-xl">⚡</span>
                    <span class="hidden md:inline">Excepciones</span>
                </Link>
                <Link
                    href="/notificaciones"
                    class="flex items-center space-x-2 hover:text-cyan-300"
                >
                    <span class="text-xl">🔔</span>
                    <span class="hidden md:inline">Notificaciones</span>
                </Link>
            </nav>
            <footer class="text-sm text-gray-300 mt-6 hidden md:block">
                © 2025 Instituto
            </footer>
        </aside>

        <!-- Main content -->
        <main class="flex-1 p-6 md:p-10 text-black">
            <!-- Header -->
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-3xl font-bold">
                    Bienvenido, {{ alumno.nombre }}
                </h2>
                <img
                    :src="`https://ui-avatars.com/api/?name=${alumno.nombre}`"
                    alt="avatar"
                    class="w-12 h-12 rounded-full border-5 border-white"
                />
            </div>

            <!-- Grid de tarjetas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Inscripciones -->
                <div
                    class="bg-gray-100 text-black rounded-2xl p-8 shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1"
                >
                    <h3 class="text-lg font-bold mb-4">Inscripciones</h3>
                    <p class="text-sm">Materias del cuatrimestre</p>
                </div>

                <!-- Expediente -->
                <div
                    class="bg-gray-100 text-black rounded-2xl p-6 shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1"
                >
                    <h3 class="text-lg font-bold mb-4">Expediente</h3>
                    <p class="text-sm">Historial académico</p>
                </div>

                <!-- Mesas -->
                <div
                    class="bg-gray-100 text-black rounded-2xl p-6 shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1"
                >
                    <h3 class="text-lg font-bold mb-4">Mesas</h3>
                    <p class="text-sm">Inscripción a exámenes</p>
                </div>

                <!-- Info importante -->
                <div
                    class="bg-gray-100 text-black rounded-2xl p-6 shadow-lg col-span-1 md:col-span-2"
                >
                    <h3 class="text-lg font-bold mb-4">
                        Información Importante
                    </h3>
                    <ul class="space-y-2 text-sm">
                        <li>
                            ✔️ Las inscripciones cierran el último día del mes
                        </li>
                        <li>
                            ✔️ Verifica las correlativas antes de inscribirte
                        </li>
                        <li>✔️ Máximo 5 materias por cuatrimestre</li>
                    </ul>
                </div>

                <!-- Progreso académico -->
                <div
                    class="bg-gray-100 text-black rounded-2xl p-6 shadow-lg hover:shadow-2xl transition"
                >
                    <h3 class="text-lg font-bold mb-4">Progreso Académico</h3>
                    <p class="text-sm mb-2">
                        Aprobadas: {{ stats.aprobadas }}/{{
                            stats.totalMaterias
                        }}
                    </p>
                    <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                        <div
                            class="bg-green-500 h-3 rounded-full"
                            :style="{ width: porcentajeAprobadas + '%' }"
                        ></div>
                    </div>
                    <p class="text-sm mb-2">
                        Regulares: {{ stats.regulares }}/{{
                            stats.totalRegulares
                        }}
                    </p>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div
                            class="bg-yellow-500 h-3 rounded-full"
                            :style="{ width: porcentajeRegulares + '%' }"
                        ></div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
