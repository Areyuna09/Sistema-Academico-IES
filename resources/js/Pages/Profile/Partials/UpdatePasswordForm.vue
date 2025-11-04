<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const updatePassword = () => {
    form.put(route("password.update"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset("password", "password_confirmation");
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset("current_password");
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-3">
                <i class="bx bx-lock-alt text-3xl text-blue-600"></i>
                Cambiar Contraseña
            </h2>

            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Asegúrate de usar una contraseña segura para proteger tu cuenta
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <InputLabel for="current_password" value="Contraseña Actual" class="text-base font-semibold" />

                    <div class="relative mt-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bx bx-lock text-gray-400 text-xl"></i>
                        </div>
                        <TextInput
                            id="current_password"
                            ref="currentPasswordInput"
                            v-model="form.current_password"
                            type="password"
                            class="pl-10 block w-full rounded-lg"
                            autocomplete="current-password"
                        />
                    </div>

                    <InputError
                        :message="form.errors.current_password"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel for="password" value="Nueva Contraseña" class="text-base font-semibold" />

                    <div class="relative mt-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bx bx-key text-gray-400 text-xl"></i>
                        </div>
                        <TextInput
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="pl-10 block w-full rounded-lg"
                            autocomplete="new-password"
                        />
                    </div>

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div>
                    <InputLabel
                        for="password_confirmation"
                        value="Confirmar Nueva Contraseña"
                        class="text-base font-semibold"
                    />

                    <div class="relative mt-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bx bx-check-shield text-gray-400 text-xl"></i>
                        </div>
                        <TextInput
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="pl-10 block w-full rounded-lg"
                            autocomplete="new-password"
                        />
                    </div>

                    <InputError
                        :message="form.errors.password_confirmation"
                        class="mt-2"
                    />
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <PrimaryButton
                    :disabled="form.processing"
                    class="px-8 py-3 text-base font-semibold shadow-lg hover:shadow-xl"
                >
                    <i class="bx bx-save mr-2"></i>
                    {{ form.processing ? 'Cambiando...' : 'Cambiar Contraseña' }}
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
                        Contraseña actualizada correctamente
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
