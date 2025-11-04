<script setup>
import { computed } from 'vue';
import { Head } from "@inertiajs/vue3";
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import UpdateAlumnoProfileForm from "./Partials/UpdateAlumnoProfileForm.vue";

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
});

// Usuarios especiales con estilo VIP (dorado)
const esUsuarioVIP = computed(() => {
    if (!props.alumno) return false;
    const dniVIPs = ['46180633', '44674217']; // Ramon y Alan
    return dniVIPs.includes(props.alumno?.dni);
});
</script>

<template>
    <Head title="Mi Perfil" />

    <!-- Vista para alumnos -->
    <SidebarLayout v-if="alumno" :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Mi Perfil</h1>
                <p class="text-xs text-gray-400 mt-0.5">Administra tu información personal</p>
            </div>
        </template>

        <div class="p-4 md:p-8 max-w-7xl mx-auto space-y-4 md:space-y-6">
            <!-- Información del Estudiante VIP -->
            <div
                class="rounded-xl shadow-lg p-4 md:p-6 text-white transition-all duration-300"
                :class="esUsuarioVIP
                    ? 'bg-gradient-to-r from-yellow-600 via-amber-500 to-yellow-600 border-2 border-yellow-300'
                    : 'bg-gradient-to-r from-blue-600 to-blue-700'"
            >
                <div class="flex items-start gap-3 md:gap-4">
                    <div
                        class="w-12 h-12 md:w-16 md:h-16 rounded-full flex items-center justify-center flex-shrink-0"
                        :class="esUsuarioVIP
                            ? 'bg-white bg-opacity-30 shadow-lg'
                            : 'bg-white bg-opacity-20'"
                    >
                        <i
                            class="bx text-2xl md:text-4xl"
                            :class="[esUsuarioVIP ? 'bx-crown' : 'bx-user']"
                        ></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="text-base md:text-2xl font-bold">{{ alumno.nombre_completo }}</h2>
                            <span v-if="esUsuarioVIP" class="px-2 md:px-3 py-1 bg-white bg-opacity-90 text-yellow-900 text-[10px] md:text-xs font-bold rounded-full shadow-md">
                                ⭐ VIP DEVELOPER
                            </span>
                        </div>
                        <p
                            class="text-xs md:text-sm mt-1 md:mt-2"
                            :class="esUsuarioVIP ? 'text-yellow-50' : 'text-blue-100'"
                        >
                            DNI {{ alumno.dni }}
                        </p>
                        <p
                            class="text-xs md:text-sm mt-0.5 md:mt-1"
                            :class="esUsuarioVIP ? 'text-yellow-50' : 'text-blue-100'"
                        >
                            {{ alumno.carrera?.nombre || 'Sin carrera' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulario de información del alumno -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 md:p-6 shadow">
                <UpdateAlumnoProfileForm
                    :alumno="alumno"
                    :status="status"
                />
            </div>

            <!-- Formulario de cambio de contraseña -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 md:p-6 shadow">
                <UpdatePasswordForm />
            </div>
        </div>
    </SidebarLayout>

    <!-- Vista para admin/profesores/otros usuarios -->
    <SidebarLayout v-else :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Mi Perfil</h1>
                <p class="text-xs text-gray-400 mt-0.5">Administra tu información personal</p>
            </div>
        </template>

        <div class="p-4 md:p-8 max-w-4xl mx-auto space-y-6">
            <!-- Profile Information and Avatar -->
            <UpdateProfileInformationForm
                :must-verify-email="mustVerifyEmail"
                :status="status"
            />

            <!-- Password Update -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 md:p-8 shadow">
                <UpdatePasswordForm />
            </div>

            <!-- Delete Account -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 md:p-8 shadow">
                <DeleteUserForm />
            </div>
        </div>
    </SidebarLayout>
</template>
