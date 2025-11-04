<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
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
</script>

<template>
    <div class="bg-white border border-gray-200 rounded-lg shadow">
        <!-- Avatar Section - Centered Header -->
        <div class="p-6 md:p-8 border-b border-gray-100">
            <div class="flex flex-col items-center text-center">
                <!-- Avatar -->
                <div class="relative group mb-4">
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

                <!-- User Name -->
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">{{ user.name }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ user.email }}</p>

                <!-- Avatar Actions -->
                <div class="flex gap-3 mt-4">
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

                <InputError class="mt-2 text-xs" :message="avatarForm.errors.avatar" />

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
                        required
                        maxlength="100"
                        autocomplete="name"
                        class="pl-10 block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    />
                </div>
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
                        required
                        maxlength="100"
                        autocomplete="username"
                        class="pl-10 block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    />
                </div>
                <InputError class="mt-1 text-xs" :message="form.errors.email" />
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
