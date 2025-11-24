<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    alumno: {
        type: Object,
        default: null,
    },
    profesor: {
        type: Object,
        default: null,
    },
});

const page = usePage();
const user = page.props.auth.user;
const modulosConfig = computed(() => page.props.modulosConfig || {});
const estaModuloActivo = (clave) => {
    // Admins (tipo 1, 2) y profesores (tipo 3) siempre tienen acceso
    const tipoUsuario = page.props.auth.user?.tipo;
    if ([1, 2, 3].includes(tipoUsuario)) {
        return true;
    }
    // Para alumnos (tipo 4), verificar si el módulo está activo
    return modulosConfig.value[clave] !== false;
};

// Verificar si el alumno puede editar su perfil
const puedeEditarPerfil = computed(() => {
    // Si no es alumno, puede editar
    if (user.tipo !== 4) return true;
    // Si es alumno, verificar el módulo
    return estaModuloActivo('alumno_editar_perfil');
});

const form = useForm({
    name: user.name,
    email: user.email,
    telefono: props.alumno?.telefono || props.profesor?.telefono || '',
    celular: props.alumno?.celular || props.profesor?.celular || '',
    descripcion_personalizada: props.alumno?.descripcion_personalizada || '',
});

// Avatar upload
const avatarForm = useForm({
    avatar: null,
});

const previewUrl = ref(null);
const fileInput = ref(null);

const currentAvatar = computed(() => {
    if (previewUrl.value) return previewUrl.value;
    if (user.avatar) return `/storage/${user.avatar}`;
    return null;
});

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        avatarForm.avatar = file;

        // Crear preview
        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const uploadAvatar = () => {
    avatarForm.post(route('profile.avatar.update'), {
        preserveScroll: true,
        onSuccess: () => {
            avatarForm.reset();
            previewUrl.value = null;
            if (fileInput.value) {
                fileInput.value.value = '';
            }
        },
    });
};

const deleteAvatar = () => {
    if (confirm('¿Estás seguro de que deseas eliminar tu foto de perfil?')) {
        router.delete(route('profile.avatar.delete'), {
            preserveScroll: true,
        });
    }
};

const triggerFileInput = () => {
    fileInput.value?.click();
};

// Determinar el rol para el badge
const roleBadge = computed(() => {
    if (props.alumno) return { text: 'ALUMNO', class: 'bg-blue-100 text-blue-800' };
    if (props.profesor) return { text: 'PROFESOR', class: 'bg-purple-100 text-purple-800' };
    return { text: 'ADMINISTRADOR', class: 'bg-gray-100 text-gray-800' };
});

// Información adicional según el rol
const additionalInfo = computed(() => {
    if (props.alumno) {
        return {
            dni: props.alumno.dni,
            carrera: props.alumno.carrera?.nombre,
            legajo: props.alumno.legajo,
            curso: props.alumno.curso,
            division: props.alumno.division,
        };
    }
    if (props.profesor) {
        return {
            dni: props.profesor.dni,
            carrera: props.profesor.carrera?.nombre,
        };
    }
    return null;
});
</script>

<template>
    <div class="bg-white border border-gray-200 rounded-lg shadow">
        <!-- Avatar Section - Centered Header -->
        <div class="p-6 md:p-8 border-b border-gray-100">
            <div class="flex flex-col items-center text-center">
                <!-- Avatar (solo si el módulo está activo) -->
                <div v-if="estaModuloActivo('avatares')" class="relative group mb-4">
                    <div class="w-24 h-24 md:w-28 md:h-28 rounded-full overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 shadow-md">
                        <img
                            v-if="currentAvatar"
                            :src="currentAvatar"
                            :alt="user.name"
                            class="w-full h-full object-cover"
                        />
                        <div
                            v-else
                            class="w-full h-full flex items-center justify-center text-white text-3xl md:text-4xl font-bold"
                        >
                            {{ user.name.charAt(0).toUpperCase() }}
                        </div>
                    </div>
                    <!-- Camera overlay -->
                    <button
                        @click="triggerFileInput"
                        type="button"
                        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                        <i class="bx bx-camera text-white text-2xl"></i>
                    </button>
                </div>

                <!-- Avatar simple (solo iniciales cuando el módulo está desactivado) -->
                <div v-else class="relative mb-4">
                    <div class="w-24 h-24 md:w-28 md:h-28 rounded-full overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 shadow-md">
                        <div class="w-full h-full flex items-center justify-center text-white text-3xl md:text-4xl font-bold">
                            {{ user.name.charAt(0).toUpperCase() }}
                        </div>
                    </div>
                </div>

                <!-- User Name and Badge -->
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">{{ user.name }}</h2>
                <span :class="['inline-block px-3 py-1 rounded-full text-xs font-bold mt-2', roleBadge.class]">
                    {{ roleBadge.text }}
                </span>
                <p class="text-sm text-gray-500 mt-2">{{ user.email }}</p>

                <!-- Avatar Actions (solo si el módulo está activo) -->
                <div v-if="estaModuloActivo('avatares')" class="flex gap-3 mt-4">
                    <input
                        ref="fileInput"
                        type="file"
                        accept="image/jpeg,image/jpg,image/png,image/gif"
                        @change="handleFileChange"
                        class="hidden"
                    />

                    <button
                        v-if="previewUrl"
                        @click="uploadAvatar"
                        :disabled="avatarForm.processing"
                        type="button"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white text-sm font-medium rounded-lg transition-colors"
                    >
                        <i class="bx bx-check mr-1"></i>
                        {{ avatarForm.processing ? 'Guardando...' : 'Guardar' }}
                    </button>

                    <button
                        v-else
                        @click="triggerFileInput"
                        type="button"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors"
                    >
                        <i class="bx bx-upload mr-1"></i>
                        Cambiar foto
                    </button>

                    <button
                        v-if="user.avatar"
                        @click="deleteAvatar"
                        type="button"
                        class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 text-sm font-medium rounded-lg transition-colors"
                    >
                        <i class="bx bx-trash mr-1"></i>
                        Eliminar
                    </button>
                </div>

                <InputError v-if="estaModuloActivo('avatares')" class="mt-2 text-xs" :message="avatarForm.errors.avatar" />

                <!-- Success message -->
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="status === 'avatar-updated' || status === 'avatar-deleted'"
                        class="mt-3 text-sm text-green-600 font-medium"
                    >
                        <i class="bx bx-check-circle mr-1"></i>
                        {{ status === 'avatar-updated' ? 'Foto actualizada' : 'Foto eliminada' }}
                    </p>
                </Transition>
            </div>
        </div>

        <!-- Profile Form -->
        <form @submit.prevent="form.patch(route('profile.update'))" class="p-6 md:p-8 space-y-6">
            <!-- Información adicional (solo lectura) - Arriba del formulario -->
            <div v-if="additionalInfo" class="grid grid-cols-1 md:grid-cols-3 gap-4 pb-4 border-b border-gray-200">
                <div v-if="additionalInfo.dni">
                    <p class="text-xs font-semibold text-gray-500 uppercase">DNI</p>
                    <p class="text-sm text-gray-900 font-medium mt-1">{{ additionalInfo.dni }}</p>
                </div>
                <div v-if="additionalInfo.carrera">
                    <p class="text-xs font-semibold text-gray-500 uppercase">Carrera</p>
                    <p class="text-sm text-gray-900 font-medium mt-1">{{ additionalInfo.carrera }}</p>
                </div>
                <div v-if="additionalInfo.legajo">
                    <p class="text-xs font-semibold text-gray-500 uppercase">Legajo</p>
                    <p class="text-sm text-gray-900 font-medium mt-1">{{ additionalInfo.legajo || 'Sin asignar' }}</p>
                </div>
                <div v-if="additionalInfo.curso">
                    <p class="text-xs font-semibold text-gray-500 uppercase">Curso</p>
                    <p class="text-sm text-gray-900 font-medium mt-1">
                        {{ additionalInfo.curso }}° {{ additionalInfo.division ? `- Div. ${additionalInfo.division}` : '' }}
                    </p>
                </div>
            </div>

            <!-- Name Field -->
            <div class="group">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nombre completo
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bx bx-user text-gray-400"></i>
                    </div>
                    <input
                        id="name"
                        type="text"
                        v-model="form.name"
                        :disabled="alumno || profesor"
                        :class="[
                            'pl-10 block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all',
                            (alumno || profesor) ? 'bg-gray-100 cursor-not-allowed' : 'bg-white'
                        ]"
                        required
                        maxlength="100"
                        autocomplete="name"
                    />
                </div>
                <p v-if="alumno || profesor" class="mt-1 text-xs text-gray-500">
                    <i class="bx bx-info-circle"></i>
                    El nombre no puede ser modificado. Contacta a administración para cambios.
                </p>
                <InputError class="mt-1 text-xs" :message="form.errors.name" />
            </div>

            <!-- Email Field -->
            <div class="group">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    Correo electrónico
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bx bx-envelope text-gray-400"></i>
                    </div>
                    <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        :disabled="!puedeEditarPerfil"
                        required
                        maxlength="100"
                        autocomplete="username"
                        :class="[
                            'pl-10 block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all',
                            !puedeEditarPerfil ? 'bg-gray-100 cursor-not-allowed' : 'bg-white'
                        ]"
                    />
                </div>
                <InputError class="mt-1 text-xs" :message="form.errors.email" />
            </div>

            <!-- Teléfono (solo para alumno y profesor) -->
            <div v-if="alumno || profesor" class="group">
                <label for="telefono" class="block text-sm font-semibold text-gray-700 mb-2">
                    Teléfono
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bx bx-phone text-gray-400"></i>
                    </div>
                    <input
                        id="telefono"
                        type="text"
                        v-model="form.telefono"
                        :disabled="!puedeEditarPerfil"
                        placeholder="Ej: 2644123456"
                        autocomplete="tel"
                        pattern="[0-9]*"
                        inputmode="numeric"
                        maxlength="20"
                        :class="[
                            'pl-10 block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all',
                            !puedeEditarPerfil ? 'bg-gray-100 cursor-not-allowed' : 'bg-white'
                        ]"
                    />
                </div>
                <InputError class="mt-1 text-xs" :message="form.errors.telefono" />
            </div>

            <!-- Celular (solo para alumno y profesor) -->
            <div v-if="alumno || profesor" class="group">
                <label for="celular" class="block text-sm font-semibold text-gray-700 mb-2">
                    Celular
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bx bx-mobile text-gray-400"></i>
                    </div>
                    <input
                        id="celular"
                        type="text"
                        v-model="form.celular"
                        :disabled="!puedeEditarPerfil"
                        placeholder="Ej: 2644567890"
                        autocomplete="tel"
                        pattern="[0-9]*"
                        inputmode="numeric"
                        maxlength="20"
                        :class="[
                            'pl-10 block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all',
                            !puedeEditarPerfil ? 'bg-gray-100 cursor-not-allowed' : 'bg-white'
                        ]"
                    />
                </div>
                <InputError class="mt-1 text-xs" :message="form.errors.celular" />
            </div>

            <!-- Descripción Personal (solo para alumnos) -->
            <div v-if="alumno" class="group">
                <label for="descripcion_personalizada" class="block text-sm font-semibold text-gray-700 mb-2">
                    Descripción Personal
                </label>
                <textarea
                    id="descripcion_personalizada"
                    v-model="form.descripcion_personalizada"
                    :disabled="!puedeEditarPerfil"
                    :class="[
                        'block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all',
                        !puedeEditarPerfil ? 'bg-gray-100 cursor-not-allowed' : 'bg-white'
                    ]"
                    rows="3"
                    maxlength="500"
                    placeholder="Escribe una breve descripción personal (opcional)"
                ></textarea>
                <p class="mt-1 text-xs text-gray-500">
                    {{ form.descripcion_personalizada?.length || 0 }}/500 caracteres
                </p>
                <InputError class="mt-1 text-xs" :message="form.errors.descripcion_personalizada" />
            </div>

            <!-- Email Verification Alert -->
            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex gap-3">
                    <i class="bx bx-error-circle text-yellow-600 text-xl flex-shrink-0"></i>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800 font-medium">Tu correo electrónico no ha sido verificado</p>
                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="mt-2 text-sm text-blue-600 hover:text-blue-700 font-medium underline"
                        >
                            Reenviar correo de verificación
                        </Link>
                    </div>
                </div>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-show="status === 'verification-link-sent'"
                        class="mt-3 text-sm text-green-600 font-medium flex items-center gap-1"
                    >
                        <i class="bx bx-check-circle"></i>
                        Correo enviado
                    </p>
                </Transition>
            </div>

            <!-- Mensaje informativo cuando no puede editar -->
            <div v-if="!puedeEditarPerfil" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center gap-2">
                    <i class="bx bx-info-circle text-yellow-600 text-xl"></i>
                    <p class="text-sm text-gray-800">
                        La edición del perfil está deshabilitada. Puedes cambiar tu contraseña en la sección de seguridad.
                    </p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-green-600 font-medium flex items-center gap-1"
                    >
                        <i class="bx bx-check-circle"></i>
                        Cambios guardados
                    </p>
                </Transition>

                <button
                    v-if="puedeEditarPerfil"
                    type="submit"
                    :disabled="form.processing"
                    class="ml-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-semibold rounded-lg transition-colors shadow-sm hover:shadow-md"
                >
                    <i class="bx bx-save mr-2"></i>
                    {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
                </button>
            </div>
        </form>
    </div>
</template>
