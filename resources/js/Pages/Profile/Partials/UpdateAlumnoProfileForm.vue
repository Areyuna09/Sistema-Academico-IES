<script setup>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    alumno: {
        type: Object,
        required: true,
    },
    status: {
        type: String,
        default: null,
    },
});

const form = useForm({
    name: props.alumno.nombre_completo,
    email: props.alumno.email || '',
    telefono: props.alumno.telefono || '',
    celular: props.alumno.celular || '',
    descripcion_personalizada: props.alumno.descripcion_personalizada || '',
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.name = props.alumno.nombre_completo;
            form.email = props.alumno.email || '';
            form.telefono = props.alumno.telefono || '';
            form.celular = props.alumno.celular || '';
            form.descripcion_personalizada = props.alumno.descripcion_personalizada || '';
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-base md:text-lg font-semibold text-gray-900">
                <i class="bx bx-user-circle text-blue-600 mr-2"></i>
                Información Personal
            </h2>
            <p class="mt-1 text-xs md:text-sm text-gray-600">
                Actualiza tu información de contacto y descripción personal
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

            <!-- Descripción Personalizada -->
            <div>
                <InputLabel for="descripcion_personalizada" value="Descripción Personal" />
                <textarea
                    id="descripcion_personalizada"
                    v-model="form.descripcion_personalizada"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm md:text-base"
                    rows="3"
                    maxlength="500"
                    placeholder="Escribe una breve descripción personal (opcional)"
                ></textarea>
                <p class="mt-1 text-[10px] md:text-xs text-gray-500">
                    {{ form.descripcion_personalizada?.length || 0 }}/500 caracteres
                </p>
                <InputError class="mt-2" :message="form.errors.descripcion_personalizada" />
            </div>

            <!-- Información adicional (solo lectura) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4 pt-3 md:pt-4 border-t border-gray-200">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold text-gray-500 uppercase">DNI</p>
                    <p class="text-sm md:text-base text-gray-900 font-medium">{{ alumno.dni }}</p>
                </div>
                <div>
                    <p class="text-[10px] md:text-xs font-semibold text-gray-500 uppercase">Legajo</p>
                    <p class="text-sm md:text-base text-gray-900 font-medium">{{ alumno.legajo || 'Sin asignar' }}</p>
                </div>
                <div>
                    <p class="text-[10px] md:text-xs font-semibold text-gray-500 uppercase">Año y División</p>
                    <p class="text-sm md:text-base text-gray-900 font-medium">
                        {{ alumno.curso }}° {{ alumno.division ? `- Div. ${alumno.division}` : '' }}
                    </p>
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
