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
        <header>
            <h2 class="text-base md:text-lg font-semibold text-gray-900 dark:text-gray-100">
                <i class="bx bx-lock-alt text-blue-600 mr-2"></i>
                Cambiar Contraseña
            </h2>

            <p class="mt-1 text-xs md:text-sm text-gray-600 dark:text-gray-400">
                Asegúrate de usar una contraseña segura para proteger tu cuenta
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-4 md:mt-6 space-y-4 md:space-y-6">
            <div>
                <InputLabel for="current_password" value="Contraseña Actual" />

                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="mt-1 block w-full text-sm md:text-base"
                    autocomplete="current-password"
                />

                <InputError
                    :message="form.errors.current_password"
                    class="mt-2"
                />
            </div>

            <div>
                <InputLabel for="password" value="Nueva Contraseña" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full text-sm md:text-base"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Confirmar Nueva Contraseña"
                />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full text-sm md:text-base"
                    autocomplete="new-password"
                />

                <InputError
                    :message="form.errors.password_confirmation"
                    class="mt-2"
                />
            </div>

            <div class="flex items-center gap-3 md:gap-4 pt-2 md:pt-4">
                <PrimaryButton :disabled="form.processing" class="text-sm md:text-base">
                    <i class="bx bx-save mr-2"></i>
                    Cambiar Contraseña
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-xs md:text-sm text-green-600 font-medium flex items-center gap-1 dark:text-green-400"
                    >
                        <i class="bx bx-check-circle"></i>
                        Contraseña actualizada correctamente
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
