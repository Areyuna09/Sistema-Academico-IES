<script setup>
import { ref, computed, watch } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    usuario: Object,
    alumnosSinUsuario: Array,
    profesoresSinUsuario: Array,
    paises: Array,
    provincias: Array,
    sexos: Array,
});

const form = useForm({
    dni: props.usuario.dni,
    nombre: props.usuario.nombre,
    email: props.usuario.email,
    tipo: props.usuario.tipo,
    password: '',
    password_confirmation: '',
    telefono: props.usuario.telefono,
    alumno_id: props.usuario.alumno_id,
    profesor_id: props.usuario.profesor_id,
    pais: props.usuario.pais,
    provincia: props.usuario.provincia,
    sexo: props.usuario.sexo,
    activo: props.usuario.activo,
});

// Provincias filtradas por país seleccionado
const provinciasFiltradas = computed(() => {
    if (!form.pais) return props.provincias;
    return props.provincias.filter(p => p.pais_id === form.pais);
});

// Limpiar vinculaciones al cambiar tipo
watch(() => form.tipo, (nuevoTipo) => {
    if (nuevoTipo != 4) {
        form.alumno_id = null;
    }
    if (nuevoTipo != 3) {
        form.profesor_id = null;
    }
});

const submit = () => {
    form.put(route('admin.usuarios.update', props.usuario.id));
};

const mostrarVinculacionAlumno = computed(() => form.tipo == 4);
const mostrarVinculacionProfesor = computed(() => form.tipo == 3);

const getTipoBadge = (tipo) => {
    const badges = {
        1: { text: 'Admin', class: 'bg-purple-100 text-purple-800', icon: 'bx-shield' },
        3: { text: 'Profesor', class: 'bg-blue-100 text-blue-800', icon: 'bx-chalkboard' },
        4: { text: 'Alumno', class: 'bg-green-100 text-green-800', icon: 'bx-user' },
    };
    return badges[tipo] || { text: 'Usuario', class: 'bg-gray-100 text-gray-800', icon: 'bx-user' };
};
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Editar Usuario</h1>
                <p class="text-xs text-gray-400 mt-0.5">Modificar datos del usuario {{ usuario.nombre }}</p>
            </div>
        </template>

        <div class="p-8 max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <form @submit.prevent="submit">
                    <!-- Tipo de Usuario -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tipo de Usuario</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all"
                                :class="form.tipo === 1 ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-purple-300'">
                                <input
                                    type="radio"
                                    v-model="form.tipo"
                                    :value="1"
                                    class="sr-only"
                                />
                                <div class="flex items-center gap-3">
                                    <i class="bx bx-shield text-2xl text-purple-600"></i>
                                    <span class="font-semibold text-gray-700">Admin</span>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all"
                                :class="form.tipo === 3 ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'">
                                <input
                                    type="radio"
                                    v-model="form.tipo"
                                    :value="3"
                                    class="sr-only"
                                />
                                <div class="flex items-center gap-3">
                                    <i class="bx bx-chalkboard text-2xl text-blue-600"></i>
                                    <span class="font-semibold text-gray-700">Profesor</span>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all"
                                :class="form.tipo === 4 ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-green-300'">
                                <input
                                    type="radio"
                                    v-model="form.tipo"
                                    :value="4"
                                    class="sr-only"
                                />
                                <div class="flex items-center gap-3">
                                    <i class="bx bx-user text-2xl text-green-600"></i>
                                    <span class="font-semibold text-gray-700">Alumno</span>
                                </div>
                            </label>
                        </div>
                        <p v-if="form.errors.tipo" class="text-red-600 text-sm mt-1">{{ form.errors.tipo }}</p>
                    </div>

                    <!-- Vinculación Actual -->
                    <div v-if="usuario.alumno || usuario.profesor" class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Vinculación Actual</h4>
                        <div v-if="usuario.alumno" class="flex items-center gap-2">
                            <i class="bx bx-user text-green-600"></i>
                            <span class="text-sm text-gray-700">Alumno: {{ usuario.alumno.nombre_completo }}</span>
                        </div>
                        <div v-if="usuario.profesor" class="flex items-center gap-2">
                            <i class="bx bx-chalkboard text-blue-600"></i>
                            <span class="text-sm text-gray-700">Profesor: {{ usuario.profesor.nombre_completo }}</span>
                        </div>
                    </div>

                    <!-- Vinculación con Alumno (si tipo = Alumno) -->
                    <div v-if="mostrarVinculacionAlumno" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Vincular con Alumno</h4>
                        <select
                            v-model="form.alumno_id"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200"
                        >
                            <option :value="null">Seleccione un alumno (opcional)</option>
                            <option v-for="alumno in alumnosSinUsuario" :key="alumno.id" :value="alumno.id">
                                {{ alumno.nombre_completo }} - DNI: {{ alumno.dni }}
                            </option>
                        </select>
                        <p v-if="form.errors.alumno_id" class="text-red-600 text-sm mt-1">{{ form.errors.alumno_id }}</p>
                    </div>

                    <!-- Vinculación con Profesor (si tipo = Profesor) -->
                    <div v-if="mostrarVinculacionProfesor" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Vincular con Profesor</h4>
                        <select
                            v-model="form.profesor_id"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                            <option :value="null">Seleccione un profesor (opcional)</option>
                            <option v-for="profesor in profesoresSinUsuario" :key="profesor.id" :value="profesor.id">
                                {{ profesor.nombre_completo }} - DNI: {{ profesor.dni }}
                            </option>
                        </select>
                        <p v-if="form.errors.profesor_id" class="text-red-600 text-sm mt-1">{{ form.errors.profesor_id }}</p>
                    </div>

                    <!-- Datos de Acceso -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Datos de Acceso</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    DNI <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.dni"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                />
                                <p v-if="form.errors.dni" class="text-red-600 text-sm mt-1">{{ form.errors.dni }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                />
                                <p v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nombre Completo <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.nombre"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                />
                                <p v-if="form.errors.nombre" class="text-red-600 text-sm mt-1">{{ form.errors.nombre }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nueva Contraseña (dejar en blanco para no cambiar)
                                </label>
                                <input
                                    v-model="form.password"
                                    type="password"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="Nueva contraseña"
                                />
                                <p v-if="form.errors.password" class="text-red-600 text-sm mt-1">{{ form.errors.password }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Confirmar Nueva Contraseña
                                </label>
                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="Confirmar contraseña"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                <input
                                    v-model="form.telefono"
                                    type="text"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                />
                                <p v-if="form.errors.telefono" class="text-red-600 text-sm mt-1">{{ form.errors.telefono }}</p>
                            </div>

                            <div class="flex items-center">
                                <label class="flex items-center cursor-pointer">
                                    <input
                                        v-model="form.activo"
                                        type="checkbox"
                                        class="rounded border-gray-300 text-blue-600 focus:ring focus:ring-blue-200"
                                    />
                                    <span class="ml-2 text-sm font-medium text-gray-700">Usuario activo</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Datos Personales -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Datos Personales</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">País</label>
                                <select
                                    v-model="form.pais"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option v-for="pais in paises" :key="pais.id" :value="pais.id">
                                        {{ pais.nombre }}
                                    </option>
                                </select>
                                <p v-if="form.errors.pais" class="text-red-600 text-sm mt-1">{{ form.errors.pais }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Provincia</label>
                                <select
                                    v-model="form.provincia"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option v-for="provincia in provinciasFiltradas" :key="provincia.id" :value="provincia.id">
                                        {{ provincia.nombre }}
                                    </option>
                                </select>
                                <p v-if="form.errors.provincia" class="text-red-600 text-sm mt-1">{{ form.errors.provincia }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sexo</label>
                                <select
                                    v-model="form.sexo"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option :value="null">Seleccione...</option>
                                    <option v-for="sexo in sexos" :key="sexo.id" :value="sexo.id">
                                        {{ sexo.nombre }}
                                    </option>
                                </select>
                                <p v-if="form.errors.sexo" class="text-red-600 text-sm mt-1">{{ form.errors.sexo }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                        <Link
                            :href="route('admin.usuarios.index')"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 disabled:opacity-50"
                        >
                            <i class="bx bx-save mr-1"></i>
                            {{ form.processing ? 'Guardando...' : 'Actualizar Usuario' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </SidebarLayout>
</template>
