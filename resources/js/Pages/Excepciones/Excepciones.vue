<script setup>
import { ref, computed } from "vue";
import { router, Link, useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const props = defineProps({
    excepciones: Array,
    alumnos: Array,
});

const search = ref("");
const showModal = ref(false);

// ✅ Formulario Inertia
const form = useForm({
    alumno: "",
    tipo_excepcion: "",
    justificacion: "",
});

// Filtrado de excepciones
const filteredExcepciones = computed(() => {
    if (!search.value) return props.excepciones;
    return props.excepciones.filter((e) =>
        (e.alumno.nombre + " " + e.alumno.apellido)
            .toLowerCase()
            .includes(search.value.toLowerCase())
    );
});

// Crear excepción
function submit() {
    form.post(route("excepciones.store"), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
            Swal.fire({
                icon: "success",
                title: "¡Excepción creada!",
                text: "La excepción se registró correctamente.",
                timer: 2000,
                showConfirmButton: false,
            });
        },
        onError: () => {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Hubo un problema al guardar la excepción.",
            });
        },
    });
}

// Actualizar estado
function actualizarEstado(id, estado) {
    router.put(
        `/excepciones/${id}`,
        { estado },
        {
            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Estado actualizado",
                    text: `La excepción fue marcada como ${estado}.`,
                    timer: 2000,
                    showConfirmButton: false,
                });
            },
        }
    );
}

// Eliminar excepción
function eliminarExcepcion(id) {
    Swal.fire({
        title: "¿Está seguro?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/excepciones/${id}`, {
                onSuccess: () => {
                    Swal.fire({
                        icon: "success",
                        title: "Eliminada",
                        text: "La excepción fue eliminada correctamente.",
                        timer: 2000,
                        showConfirmButton: false,
                    });
                },
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
            <!-- Header -->
            <div class="flex justify-between items-center mb-8 text-white">
                <h2 class="text-3xl font-bold">Gestión de Excepciones</h2>
                <div class="flex space-x-2">
                    <input
                        v-model="search"
                        placeholder="🔍 Buscar por Estudiante"
                        class="px-3 py-2 rounded-lg border border-gray-300 text-black focus:ring-2 focus:ring-cyan-400"
                    />
                    <button
                        @click="showModal = true"
                        class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg shadow transition"
                    >
                        ➕ Nueva Excepción
                    </button>
                </div>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <table class="w-full border-collapse">
                    <thead class="bg-blue-500 text-white">
                        <tr>
                            <th class="p-2">ID</th>
                            <th class="p-2">Nombre</th>
                            <th class="p-2">Tipo</th>
                            <th class="p-2">Fecha</th>
                            <th class="p-2">Estado</th>
                            <th class="p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="excepcion in filteredExcepciones"
                            :key="excepcion.id"
                            class="border-b"
                        >
                            <td class="p-2 text-center">{{ excepcion.id }}</td>
                            <td class="p-2 text-center">
                                {{ excepcion.alumno.apellido }}
                                {{ excepcion.alumno.nombre }}
                            </td>
                            <td class="p-2 text-center">
                                {{ excepcion.tipo_excepcion }}
                            </td>
                            <td class="p-2 text-center">
                                {{
                                    new Date(
                                        excepcion.fecha_envio
                                    ).toLocaleDateString()
                                }}
                            </td>
                            <td class="p-2 text-center">
                                <span
                                    :class="{
                                        'bg-yellow-400 text-black px-2 py-1 rounded':
                                            excepcion.estado === 'pendiente',
                                        'bg-green-500 text-white px-2 py-1 rounded':
                                            excepcion.estado === 'aprobada',
                                        'bg-red-500 text-white px-2 py-1 rounded':
                                            excepcion.estado === 'rechazada',
                                    }"
                                    >{{ excepcion.estado }}</span
                                >
                            </td>
                            <td class="p-2 space-x-2 text-center">
                                <button
                                    @click="
                                        actualizarEstado(
                                            excepcion.id,
                                            'aprobada'
                                        )
                                    "
                                    class="bg-green-500 text-white px-3 py-1 rounded"
                                >
                                    ✔
                                </button>
                                <button
                                    @click="
                                        actualizarEstado(
                                            excepcion.id,
                                            'rechazada'
                                        )
                                    "
                                    class="bg-red-500 text-white px-3 py-1 rounded"
                                >
                                    ✘
                                </button>
                                <button
                                    @click="eliminarExcepcion(excepcion.id)"
                                    class="bg-red-600 text-white px-3 py-1 rounded"
                                >
                                    🗑️
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal Nueva Excepción -->
            <div
                v-if="showModal"
                class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center"
            >
                <div class="bg-white p-6 rounded-lg w-96">
                    <label class="block mb-2">Alumno</label>
                    <select
                        v-model="form.alumno"
                        class="border rounded w-full mb-4 p-2"
                    >
                        <option value="">Seleccione...</option>
                        <option
                            v-for="alumno in props.alumnos"
                            :key="alumno.id"
                            :value="alumno.id"
                        >
                            {{ alumno.apellido }}, {{ alumno.nombre }}
                        </option>
                    </select>

                    <label class="block mb-2">Tipo de Excepción</label>
                    <select
                        v-model="form.tipo_excepcion"
                        class="border rounded w-full mb-4 p-2"
                    >
                        <option value="Inasistencia">Inasistencia</option>
                        <option value="Justificacion">Justificación</option>
                        <option value="Reingreso">Reingreso</option>
                        <option value="Cambio de Plan">Cambio de Plan</option>
                    </select>

                    <p class="text-sm text-gray-500 mb-4">
                        Fecha: {{ new Date().toLocaleString() }}
                    </p>

                    <div class="flex justify-end gap-2">
                        <button
                            @click="showModal = false"
                            type="button"
                            class="px-3 py-2 border rounded"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="submit"
                            :disabled="form.processing"
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
