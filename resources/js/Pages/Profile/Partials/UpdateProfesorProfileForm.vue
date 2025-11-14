<script setup>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    profesor: {
        type: Object,
        required: true,
    },
    status: {
        type: String,
        default: null,
    },
});

const form = useForm({
    name: props.profesor.nombre_completo,
    email: props.profesor.email || '',
    telefono: props.profesor.telefono || '',
    celular: props.profesor.celular || '',
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.name = props.profesor.nombre_completo;
            form.email = props.profesor.email || '';
            form.telefono = props.profesor.telefono || '';
            form.celular = props.profesor.celular || '';
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-base md:text-lg font-semibold text-gray-900">
                <i class="bx bx-chalkboard text-purple-600 mr-2"></i>
                Información Personal
            </h2>
            <p class="mt-1 text-xs md:text-sm text-gray-600">
                Actualiza tu información de contacto
            </p>
        </header>

        <form @submit.prevent="submit" class="mt-4 md:mt-6 space-y-4 md:space-y-6">
            <!-- Nombre (solo lectura) -->
            <div>
                <InputLabel for="name" value="Nombre Completo" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full bg-gray-100 cursor-not-allowed text-sm md:text-base"
                    v-model="form.name"
                    disabled
                />
                <p class="mt-1 text-[10px] md:text-xs text-gray-500">
                    <i class="bx bx-info-circle"></i>
                    El nombre no puede ser modificado. Contacta a administración para cambios.
                </p>
            </div>

            <!-- Email -->
            <div>
                <InputLabel for="email" value="Correo Electrónico" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full text-sm md:text-base"
                    v-model="form.email"
                    required
                    maxlength="100"
                    autocomplete="email"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Teléfono -->
            <div>
                <InputLabel for="telefono" value="Teléfono" />
                <TextInput
                    id="telefono"
                    type="text"
                    class="mt-1 block w-full text-sm md:text-base"
                    v-model="form.telefono"
                    placeholder="Ej: 2644123456"
                    autocomplete="tel"
                    pattern="[0-9]*"
                    inputmode="numeric"
                    maxlength="20"
                    title="Solo se permiten números"
                />
                <InputError class="mt-2" :message="form.errors.telefono" />
            </div>

            <!-- Celular -->
            <div>
                <InputLabel for="celular" value="Celular" />
                <TextInput
                    id="celular"
                    type="text"
                    class="mt-1 block w-full text-sm md:text-base"
                    v-model="form.celular"
                    placeholder="Ej: 2644567890"
                    autocomplete="tel"
                    pattern="[0-9]*"
                    inputmode="numeric"
                    maxlength="20"
                    title="Solo se permiten números"
                />
                <InputError class="mt-2" :message="form.errors.celular" />
            </div>

            <!-- Información adicional (solo lectura) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 pt-3 md:pt-4 border-t border-gray-200">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold text-gray-500 uppercase">DNI</p>
                    <p class="text-sm md:text-base text-gray-900 font-medium">{{ profesor.dni }}</p>
                </div>
                <div v-if="profesor.carrera">
                    <p class="text-[10px] md:text-xs font-semibold text-gray-500 uppercase">Carrera</p>
                    <p class="text-sm md:text-base text-gray-900 font-medium">{{ profesor.carrera.nombre }}</p>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex items-center gap-3 md:gap-4 pt-2 md:pt-4">
                <PrimaryButton :disabled="form.processing" class="text-sm md:text-base">
                    <i class="bx bx-save mr-2"></i>
                    Guardar Cambios
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful || status === 'profile-updated'"
                        class="text-xs md:text-sm text-green-600 font-medium flex items-center gap-1"
                    >
                        <i class="bx bx-check-circle"></i>
                        Cambios guardados correctamente
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
