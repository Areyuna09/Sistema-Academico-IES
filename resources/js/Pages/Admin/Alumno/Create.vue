<script setup>
import { useForm } from "@inertiajs/vue3";
import SidebarLayout from "@/Layouts/SidebarLayout.vue";

const props = defineProps({
    carreras: Array,
});

const form = useForm({
    dni: "",
    apellido: "",
    nombre: "",
    email: "",
    telefono: "",
    celular: "",
    legajo: "",
    anno: new Date().getFullYear(),
    carrera: "",
    curso: "",
    division: "",
    turno: "",
});

const submit = () => {
    form.post(route("admin.alumnos.store"), {
        onSuccess: () => {
            // Redirige al listado de alumnos
            form.reset();
        },
    });
};
</script>

<template>
    <SidebarLayout>
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Nuevo Alumno
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Complete los datos del alumno para registrarlo en el
                        sistema
                    </p>
                </div>

                <!-- Formulario -->
                <form
                    @submit.prevent="submit"
                    class="bg-white shadow-lg rounded-lg p-8"
                >
                    <!-- Datos personales -->
                    <div class="mb-8">
                        <h2
                            class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2"
                        >
                            Datos Personales
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- DNI -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    DNI <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.dni"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{
                                        'border-red-500': form.errors.dni,
                                    }"
                                />
                                <div
                                    v-if="form.errors.dni"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.dni }}
                                </div>
                            </div>

                            <!-- Legajo -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Legajo</label
                                >
                                <input
                                    v-model="form.legajo"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                            </div>

                            <!-- Apellido -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Apellido <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.apellido"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{
                                        'border-red-500': form.errors.apellido,
                                    }"
                                />
                                <div
                                    v-if="form.errors.apellido"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.apellido }}
                                </div>
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Nombre <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.nombre"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{
                                        'border-red-500': form.errors.nombre,
                                    }"
                                />
                                <div
                                    v-if="form.errors.nombre"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.nombre }}
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Email</label
                                >
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{
                                        'border-red-500': form.errors.email,
                                    }"
                                />
                                <div
                                    v-if="form.errors.email"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Teléfono</label
                                >
                                <input
                                    v-model="form.telefono"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                            </div>

                            <!-- Celular -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Celular</label
                                >
                                <input
                                    v-model="form.celular"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Datos académicos -->
                    <div class="mb-8">
                        <h2
                            class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2"
                        >
                            Datos Académicos
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Carrera -->
                            <div class="md:col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Carrera <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.carrera"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{
                                        'border-red-500': form.errors.carrera,
                                    }"
                                >
                                    <option value="">
                                        Seleccione una carrera
                                    </option>
                                    <option
                                        v-for="carrera in carreras"
                                        :key="carrera.Id"
                                        :value="carrera.Id"
                                    >
                                        {{ carrera.nombre }}
                                    </option>
                                </select>
                                <div
                                    v-if="form.errors.carrera"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.carrera }}
                                </div>
                            </div>

                            <!-- Año de Ingreso -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Año de Ingreso</label
                                >
                                <input
                                    v-model.number="form.anno"
                                    type="number"
                                    :min="1900"
                                    :max="new Date().getFullYear() + 1"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                />
                            </div>

                            <!-- Curso -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Curso</label
                                >
                                <select
                                    v-model.number="form.curso"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="">Seleccione curso</option>
                                    <option :value="1">1° Año</option>
                                    <option :value="2">2° Año</option>
                                    <option :value="3">3° Año</option>
                                    <option :value="4">4° Año</option>
                                    <option :value="5">5° Año</option>
                                    <option :value="6">6° Año</option>
                                </select>
                            </div>

                            <!-- División -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >División</label
                                >
                                <select
                                    v-model="form.division"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="">
                                        Seleccione división
                                    </option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>

                            <!-- Turno -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Turno</label
                                >
                                <select
                                    v-model="form.turno"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="">Seleccione turno</option>
                                    <option value="Mañana">Mañana</option>
                                    <option value="Tarde">Tarde</option>
                                    <option value="Noche">Noche</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <button
                            type="button"
                            @click="
                                $inertia.visit(route('admin.alumnos.index'))
                            "
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Guardando...</span>
                            <span v-else>Guardar Alumno</span>
                        </button>
                    </div>

                    <!-- Error general -->
                    <div
                        v-if="form.errors.error"
                        class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg"
                    >
                        <p class="text-red-800 text-sm">
                            {{ form.errors.error }}
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </SidebarLayout>
</template>
