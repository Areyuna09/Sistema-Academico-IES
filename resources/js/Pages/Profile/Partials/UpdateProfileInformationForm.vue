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
    <section class="space-y-8">
        <!-- Avatar Section -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-blue-100 dark:border-gray-700">
            <div class="flex flex-col items-center">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-6">
                    Foto de Perfil
                </h3>

                <!-- Avatar Display -->
                <div class="relative group mb-6">
                    <div class="w-40 h-40 rounded-full overflow-hidden bg-gradient-to-br from-blue-400 to-indigo-600 shadow-2xl ring-4 ring-white dark:ring-gray-800 transition-transform group-hover:scale-105">
                        <img
                            v-if="currentAvatar"
                            :src="currentAvatar"
                            :alt="user.name"
                            class="w-full h-full object-cover"
                        />
                        <div
                            v-else
                            class="w-full h-full flex items-center justify-center text-white text-5xl font-bold"
                        >
                            {{ user.name.charAt(0).toUpperCase() }}
                        </div>
                    </div>

                    <!-- Camera icon overlay on hover -->
                    <button
                        @click="triggerFileInput"
                        type="button"
                        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                    >
                        <i class="bx bx-camera text-white text-4xl"></i>
                    </button>
                </div>

                <!-- Avatar Upload Form -->
                <div class="w-full max-w-md space-y-4">
                    <input
                        ref="fileInput"
                        type="file"
                        accept="image/jpeg,image/jpg,image/png,image/gif"
                        @change="handleFileChange"
                        class="hidden"
                    />

                    <div class="flex gap-3 justify-center">
                        <button
                            @click="triggerFileInput"
                            type="button"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors shadow-lg hover:shadow-xl flex items-center gap-2"
                        >
                            <i class="bx bx-upload text-lg"></i>
                            Seleccionar imagen
                        </button>

                        <button
                            v-if="user.avatar"
                            @click="deleteAvatar"
                            type="button"
                            class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors shadow-lg hover:shadow-xl flex items-center gap-2"
                        >
                            <i class="bx bx-trash text-lg"></i>
                            Eliminar
                        </button>
                    </div>

                    <!-- Upload button (shown when file selected) -->
                    <div v-if="previewUrl" class="flex justify-center">
                        <button
                            @click="uploadAvatar"
                            :disabled="avatarForm.processing"
                            type="button"
                            class="px-8 py-3 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-semibold rounded-lg transition-colors shadow-lg hover:shadow-xl flex items-center gap-2"
                        >
                            <i class="bx bx-check-circle text-xl"></i>
                            {{ avatarForm.processing ? 'Guardando...' : 'Guardar foto' }}
                        </button>
                    </div>

                    <InputError class="text-center" :message="avatarForm.errors.avatar" />

                    <p class="text-xs text-center text-gray-500 dark:text-gray-400">
                        JPG, PNG o GIF. Máximo 2MB.
                    </p>

                    <!-- Success message -->
                    <Transition
                        enter-active-class="transition ease-in-out duration-300"
                        enter-from-class="opacity-0 transform scale-95"
                        leave-active-class="transition ease-in-out duration-300"
                        leave-to-class="opacity-0 transform scale-95"
                    >
                        <div
                            v-if="status === 'avatar-updated'"
                            class="text-center text-sm font-medium text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 py-2 px-4 rounded-lg"
                        >
                            <i class="bx bx-check-circle mr-1"></i>
                            Foto actualizada correctamente
                        </div>
                    </Transition>

                    <Transition
                        enter-active-class="transition ease-in-out duration-300"
                        enter-from-class="opacity-0 transform scale-95"
                        leave-active-class="transition ease-in-out duration-300"
                        leave-to-class="opacity-0 transform scale-95"
                    >
                        <div
                            v-if="status === 'avatar-deleted'"
                            class="text-center text-sm font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 py-2 px-4 rounded-lg"
                        >
                            <i class="bx bx-check-circle mr-1"></i>
                            Foto eliminada correctamente
                        </div>
                    </Transition>
                </div>
            </div>
        </div>

        <!-- Profile Information Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl border border-gray-200 dark:border-gray-700">
            <header class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-3">
                    <i class="bx bx-user-circle text-3xl text-blue-600"></i>
                    Información del perfil
                </h2>

                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Actualiza la información de tu cuenta y tu dirección de correo electrónico.
                </p>
            </header>

            <form
                @submit.prevent="form.patch(route('profile.update'))"
                class="space-y-6"
            >
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <InputLabel for="name" value="Nombre completo" class="text-base font-semibold" />

                        <div class="relative mt-2">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bx bx-user text-gray-400 text-xl"></i>
                            </div>
                            <TextInput
                                id="name"
                                type="text"
                                class="pl-10 block w-full rounded-lg"
                                v-model="form.name"
                                required
                                maxlength="100"
                                autocomplete="name"
                            />
                        </div>

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Correo electrónico" class="text-base font-semibold" />

                        <div class="relative mt-2">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bx bx-envelope text-gray-400 text-xl"></i>
                            </div>
                            <TextInput
                                id="email"
                                type="email"
                                class="pl-10 block w-full rounded-lg"
                                v-model="form.email"
                                required
                                maxlength="100"
                                autocomplete="username"
                            />
                        </div>

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                </div>

                <div v-if="mustVerifyEmail && user.email_verified_at === null" class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4 rounded-lg">
                    <div class="flex">
                        <i class="bx bx-error-circle text-yellow-400 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                Tu dirección de correo electrónico no ha sido verificada.
                            </p>
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="mt-2 text-sm text-yellow-800 dark:text-yellow-200 underline hover:text-yellow-900 dark:hover:text-yellow-100 font-medium"
                            >
                                Haz clic aquí para reenviar el correo de verificación.
                            </Link>
                        </div>
                    </div>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <div
                            v-show="status === 'verification-link-sent'"
                            class="mt-3 text-sm font-medium text-green-600 dark:text-green-400"
                        >
                            <i class="bx bx-check-circle mr-1"></i>
                            Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                        </div>
                    </Transition>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <PrimaryButton
                        :disabled="form.processing"
                        class="px-8 py-3 text-base font-semibold shadow-lg hover:shadow-xl"
                    >
                        <i class="bx bx-save mr-2"></i>
                        {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
                    </PrimaryButton>

                    <Transition
                        enter-active-class="transition ease-in-out duration-300"
                        enter-from-class="opacity-0 transform translate-x-2"
                        leave-active-class="transition ease-in-out duration-300"
                        leave-to-class="opacity-0 transform translate-x-2"
                    >
                        <p
                            v-if="form.recentlySuccessful"
                            class="text-sm font-medium text-green-600 dark:text-green-400 flex items-center gap-2"
                        >
                            <i class="bx bx-check-circle text-lg"></i>
                            Guardado correctamente
                        </p>
                    </Transition>
                </div>
            </form>
        </div>
    </section>
</template>
