<script setup>
import { ref, computed } from "vue";
import { router, Link, useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const props = defineProps({
    notificaciones: Array,
});

const search = ref("");
const filtroEstado = ref("todas");
const showModal = ref(false);

const form = useForm({
    nombre_plantilla: "",
    disparador: "",
    publico_objetivo: "Estudiante",
    estado: "activa",
});

// Filtro
const filteredNotificaciones = computed(() => {
    let data = props.notificaciones;

    if (search.value) {
        data = data.filter((n) =>
            n.nombre_plantilla
                .toLowerCase()
                .includes(search.value.toLowerCase())
        );
    }

    if (filtroEstado.value !== "todas") {
        data = data.filter((n) => n.estado === filtroEstado.value);
    }

    return data;
});

// Guardar
function submit() {
    form.post(route("notificaciones.store"), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
            Swal.fire("✔", "Notificación creada", "success");
        },
    });
}

// Cambiar estado
function toggleEstado(id, estado) {
    router.put(
        `/notificaciones/${id}`,
        { estado },
        {
            onSuccess: () =>
                Swal.fire("✔", `Notificación ${estado}`, "success"),
        }
    );
}

// Eliminar
function eliminar(id) {
    Swal.fire({
        title: "¿Eliminar?",
        text: "No podrás recuperar esta notificación.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/notificaciones/${id}`, {
                onSuccess: () => Swal.fire("✔", "Eliminada", "success"),
            });
        }
    });
}
</script>

<template>
    <div class="flex min-h-screen bg-gray-100">
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

        <!-- Contenido -->
        <main class="flex-1 p-8 bg-gradient-to-br from-cyan-700 to-gray-400">
            <div class="flex justify-between items-center mb-8 text-white">
                <h2 class="text-3xl font-bold">Gestión de Notificaciones</h2>
                <div class="flex space-x-2">
                    <select
                        v-model="filtroEstado"
                        class="px-3 py-2 rounded-lg border text-black"
                    >
                        <option value="todas">Todas</option>
                        <option value="activa">Activas</option>
                        <option value="inactiva">Inactivas</option>
                    </select>
                    <input
                        v-model="search"
                        placeholder="🔍 Buscar plantilla"
                        class="px-3 py-2 rounded-lg border border-gray-300 text-black focus:ring-2 focus:ring-cyan-400"
                    />
                    <button
                        @click="showModal = true"
                        class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg shadow"
                    >
                        ➕ Nueva
                    </button>
                </div>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <table class="w-full border-collapse">
                    <thead class="bg-blue-500 text-white">
                        <tr>
                            <th class="p-2">Plantilla</th>
                            <th class="p-2">Disparador</th>
                            <th class="p-2">Público</th>
                            <th class="p-2">Estado</th>
                            <th class="p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="n in filteredNotificaciones"
                            :key="n.id"
                            class="border-b"
                        >
                            <td class="p-2 text-center">
                                {{ n.nombre_plantilla }}
                            </td>
                            <td class="p-2 text-center">{{ n.disparador }}</td>
                            <td class="p-2 text-center">
                                {{ n.publico_objetivo }}
                            </td>
                            <td class="p-2 text-center">
                                <span
                                    :class="{
                                        'bg-green-500 text-white px-2 py-1 rounded':
                                            n.estado === 'activa',
                                        'bg-red-500 text-white px-2 py-1 rounded':
                                            n.estado === 'inactiva',
                                    }"
                                >
                                    {{ n.estado }}
                                </span>
                            </td>
                            <td class="p-2 space-x-2 text-center">
                                <button
                                    @click="
                                        toggleEstado(
                                            n.id,
                                            n.estado === 'activa'
                                                ? 'inactiva'
                                                : 'activa'
                                        )
                                    "
                                    class="bg-cyan-500 text-white px-3 py-1 rounded"
                                >
                                    🔄
                                </button>
                                <button
                                    @click="eliminar(n.id)"
                                    class="bg-red-500 text-white px-3 py-1 rounded"
                                >
                                    🗑️
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal -->
            <div
                v-if="showModal"
                class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center"
            >
                <div class="bg-white p-6 rounded-lg w-96">
                    <h3 class="text-xl font-bold mb-4">Nueva Notificación</h3>

                    <label class="block mb-2">Nombre Plantilla</label>
                    <input
                        v-model="form.nombre_plantilla"
                        class="border rounded w-full mb-4 p-2"
                    />

                    <label class="block mb-2">Disparador</label>
                    <input
                        v-model="form.disparador"
                        class="border rounded w-full mb-4 p-2"
                    />

                    <label class="block mb-2">Público Objetivo</label>
                    <input
                        v-model="form.publico_objetivo"
                        class="border rounded w-full mb-4 p-2"
                    />

                    <div class="flex justify-end gap-2">
                        <button
                            @click="showModal = false"
                            class="px-3 py-2 border rounded"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="submit"
                            class="px-3 py-2 bg-green-600 text-white rounded"
                        >
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
