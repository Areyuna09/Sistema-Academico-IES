<script setup>
import { Head } from "@inertiajs/vue3";
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";

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
</script>

<template>
    <Head title="Mi Perfil" />

    <SidebarLayout :user="$page.props.auth.user">
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
                :alumno="alumno"
                :profesor="profesor"
            />

            <!-- Password Update -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 md:p-8 shadow">
                <UpdatePasswordForm />
            </div>

            <!-- Delete Account (solo para admin) -->
            <div v-if="!alumno && !profesor" class="bg-white border border-gray-200 rounded-lg p-6 md:p-8 shadow">
                <DeleteUserForm />
            </div>
        </div>
    </SidebarLayout>
</template>
