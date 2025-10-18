<script setup>
import { ref, computed, watch } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    alumnosSinUsuario: Array,
    profesoresSinUsuario: Array,
    paises: Array,
    provincias: Array,
    sexos: Array,
});

const form = useForm({
    dni: '',
    nombre: '',
    email: '',
    tipo: 4, // Por defecto alumno
    password: '',
    password_confirmation: '',
    telefono: '',
    alumno_id: null,
    profesor_id: null,
    pais: 1, // Por defecto Argentina
    provincia: 1, // Por defecto San Juan
    sexo: null,
    activo: true,
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

// Auto-completar datos al seleccionar alumno/profesor
watch(() => form.alumno_id, (alumnoId) => {
    if (alumnoId) {
        const alumno = props.alumnosSinUsuario.find(a => a.id === alumnoId);
        if (alumno) {
            form.nombre = alumno.nombre_completo;
            form.dni = alumno.dni;
            form.email = alumno.email || '';
            form.telefono = alumno.telefono || '';
        }
    } else {
        // Limpiar campos si se deselecciona
        form.nombre = '';
        form.dni = '';
        form.email = '';
        form.telefono = '';
    }
});

watch(() => form.profesor_id, (profesorId) => {
    if (profesorId) {
        const profesor = props.profesoresSinUsuario.find(p => p.id === profesorId);
        if (profesor) {
            form.nombre = profesor.nombre_completo;
            form.dni = profesor.dni;
            form.email = profesor.email || '';
            form.telefono = '';
        }
    } else {
        // Limpiar campos si se deselecciona
        form.nombre = '';
        form.dni = '';
        form.email = '';
        form.telefono = '';
    }
});

const submit = () => {
    form.post(route('admin.usuarios.store'));
};

const mostrarVinculacionAlumno = computed(() => form.tipo == 4);
const mostrarVinculacionProfesor = computed(() => form.tipo == 3);
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Nuevo Usuario</h1>
                <p class="text-xs text-gray-400 mt-0.5">Crear un nuevo usuario del sistema</p>
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
                        <p class="text-xs text-gray-600 mt-1">
                            Si vincula con un alumno existente, los datos se autocompletarán.
                        </p>
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
                        <p class="text-xs text-gray-600 mt-1">
                            Si vincula con un profesor existente, los datos se autocompletarán.
                        </p>
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
                                    pattern="[0-9]+"
                                    inputmode="numeric"
                                    maxlength="10"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="12345678"
                                    title="Solo se permiten números"
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
                                    maxlength="100"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="usuario@example.com"
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
                                    maxlength="100"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="Juan Pérez"
                                />
                                <p v-if="form.errors.nombre" class="text-red-600 text-sm mt-1">{{ form.errors.nombre }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Contraseña <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.password"
                                    type="password"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="Mínimo 6 caracteres"
                                />
                                <p v-if="form.errors.password" class="text-red-600 text-sm mt-1">{{ form.errors.password }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Confirmar Contraseña <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="Repetir contraseña"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                <input
                                    v-model="form.telefono"
                                    type="text"
                                    pattern="[0-9\s\-\+\(\)]+"
                                    inputmode="tel"
                                    maxlength="20"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="381-5123456 o +54 381 5123456"
                                    title="Se permiten números, espacios, guiones, paréntesis y signo +"
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
                            {{ form.processing ? 'Guardando...' : 'Crear Usuario' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </SidebarLayout>
</template>
