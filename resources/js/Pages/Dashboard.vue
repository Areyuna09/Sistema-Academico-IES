<script setup>
import { Head, Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
</script>

<template>
    <Head title="Dashboard" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Dashboard</h1>
                <p class="text-xs text-gray-400 mt-0.5">Bienvenido, {{ $page.props.auth.user.name }}</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Cards principales -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Card Inscripciones -->
                <Link :href="route('inscripciones.index')" class="group">
                    <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-blue-400 hover:shadow-md transition-all duration-200 cursor-pointer">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="bx bx-clipboard text-blue-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-gray-900">Inscripciones</h3>
                                <p class="text-xs text-gray-500">Materias del cuatrimestre</p>
                            </div>
                            <i class="bx bx-chevron-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                        </div>
                    </div>
                </Link>

                <!-- Card Expediente -->
                <a href="/expediente" class="group">
                    <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-green-400 hover:shadow-md transition-all duration-200 cursor-pointer">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="bx bx-folder-open text-green-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-gray-900">Expediente</h3>
                                <p class="text-xs text-gray-500">Historial académico</p>
                            </div>
                            <i class="bx bx-chevron-right text-gray-400 group-hover:text-green-600 transition-colors"></i>
                        </div>
                    </div>
                </a>

                <!-- Card Mesas de Examen -->
                <a href="/mesas" class="group">
                    <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-purple-400 hover:shadow-md transition-all duration-200 cursor-pointer">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="bx bx-calendar-event text-purple-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-gray-900">Mesas</h3>
                                <p class="text-xs text-gray-500">Inscripción a exámenes</p>
                            </div>
                            <i class="bx bx-chevron-right text-gray-400 group-hover:text-purple-600 transition-colors"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Información adicional -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Card Información -->
                <div class="bg-white border border-gray-200 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <i class="bx bx-info-circle text-blue-600 text-xl mr-2"></i>
                        <h3 class="text-base font-semibold text-gray-900">Información Importante</h3>
                    </div>
                    <ul class="space-y-2.5">
                        <li class="flex items-start text-sm">
                            <i class="bx bx-check text-blue-600 mr-2 mt-0.5"></i>
                            <span class="text-gray-600">Las inscripciones cierran el último día del mes</span>
                        </li>
                        <li class="flex items-start text-sm">
                            <i class="bx bx-check text-blue-600 mr-2 mt-0.5"></i>
                            <span class="text-gray-600">Verifica las correlativas antes de inscribirte</span>
                        </li>
                        <li class="flex items-start text-sm">
                            <i class="bx bx-check text-blue-600 mr-2 mt-0.5"></i>
                            <span class="text-gray-600">Máximo 5 materias por cuatrimestre</span>
                        </li>
                    </ul>
                </div>

                <!-- Card Acceso rápido -->
                <div class="bg-white border border-gray-200 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <i class="bx bx-link-alt text-gray-600 text-xl mr-2"></i>
                        <h3 class="text-base font-semibold text-gray-900">Accesos Rápidos</h3>
                    </div>
                    <div class="space-y-1">
                        <Link
                            :href="route('inscripciones.index')"
                            class="flex items-center justify-between px-3 py-2 rounded-md hover:bg-gray-50 transition-colors group text-sm"
                        >
                            <span class="text-gray-700 group-hover:text-blue-600">Inscripción a materias</span>
                            <i class="bx bx-chevron-right text-gray-400 group-hover:text-blue-600"></i>
                        </Link>
                        <a
                            href="/expediente"
                            class="flex items-center justify-between px-3 py-2 rounded-md hover:bg-gray-50 transition-colors group text-sm"
                        >
                            <span class="text-gray-700 group-hover:text-blue-600">Ver mi expediente</span>
                            <i class="bx bx-chevron-right text-gray-400 group-hover:text-blue-600"></i>
                        </a>
                        <Link
                            :href="route('profile.edit')"
                            class="flex items-center justify-between px-3 py-2 rounded-md hover:bg-gray-50 transition-colors group text-sm"
                        >
                            <span class="text-gray-700 group-hover:text-blue-600">Mi perfil</span>
                            <i class="bx bx-chevron-right text-gray-400 group-hover:text-blue-600"></i>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
