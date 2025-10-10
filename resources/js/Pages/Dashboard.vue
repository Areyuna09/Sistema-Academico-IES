<script setup>
import { Head, Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    tipoUsuario: {
        type: String,
        default: 'alumno'
    },
    metricas: {
        type: Object,
        default: null
    },
    materias: {
        type: Array,
        default: () => []
    },
    mensaje: {
        type: String,
        default: null
    }
});
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
            <!-- Dashboard para Profesores -->
            <div v-if="tipoUsuario === 'profesor'">
                <!-- Mensaje si no hay perfil -->
                <div v-if="mensaje" class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg mb-6">
                    <div class="flex items-center">
                        <i class="bx bx-info-circle text-yellow-600 text-xl mr-2"></i>
                        <p class="text-yellow-800">{{ mensaje }}</p>
                    </div>
                </div>

                <!-- Métricas -->
                <div v-if="metricas" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Total Materias -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-5 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <i class="bx bx-book-open text-3xl opacity-80"></i>
                            <span class="text-xs bg-white/20 px-2 py-1 rounded-full">Materias</span>
                        </div>
                        <p class="text-3xl font-bold mb-1">{{ metricas.total_materias }}</p>
                        <p class="text-xs opacity-90">Materias asignadas</p>
                    </div>

                    <!-- Total Alumnos -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-5 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <i class="bx bx-group text-3xl opacity-80"></i>
                            <span class="text-xs bg-white/20 px-2 py-1 rounded-full">Alumnos</span>
                        </div>
                        <p class="text-3xl font-bold mb-1">{{ metricas.total_alumnos }}</p>
                        <p class="text-xs opacity-90">Estudiantes activos</p>
                    </div>

                    <!-- Asistencias Pendientes -->
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg p-5 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <i class="bx bx-calendar-check text-3xl opacity-80"></i>
                            <span class="text-xs bg-white/20 px-2 py-1 rounded-full">Asistencias</span>
                        </div>
                        <p class="text-3xl font-bold mb-1">{{ metricas.asistencias_pendientes }}</p>
                        <p class="text-xs opacity-90">Pendientes de carga</p>
                    </div>

                    <!-- Notas Pendientes -->
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-5 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <i class="bx bx-edit text-3xl opacity-80"></i>
                            <span class="text-xs bg-white/20 px-2 py-1 rounded-full">Notas</span>
                        </div>
                        <p class="text-3xl font-bold mb-1">{{ metricas.notas_pendientes }}</p>
                        <p class="text-xs opacity-90">Pendientes de aprobación</p>
                    </div>
                </div>

                <!-- Mis Materias -->
                <div v-if="materias && materias.length > 0">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bx bx-book text-xl mr-2 text-blue-600"></i>
                            Mis Materias
                        </h3>
                        <Link :href="route('profesor.mis-materias.index')" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Ver todas →
                        </Link>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <Link
                            v-for="materia in materias"
                            :key="materia.id"
                            :href="route('profesor.mis-materias.show', materia.id)"
                            class="bg-white border border-gray-200 rounded-lg p-4 hover:border-blue-400 hover:shadow-md transition-all group"
                        >
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">{{ materia.nombre }}</h4>
                                    <p class="text-xs text-gray-600">{{ materia.carrera }}</p>
                                </div>
                                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">
                                    {{ materia.cantidad_alumnos }} alumnos
                                </span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 text-xs">
                                <div>
                                    <span class="text-gray-500">Cursado:</span>
                                    <span class="block font-medium text-gray-900">{{ materia.cursado }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">División:</span>
                                    <span class="block font-medium text-gray-900">{{ materia.division }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Aula:</span>
                                    <span class="block font-medium text-gray-900">{{ materia.aula }}</span>
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-gray-600 flex items-center">
                                <i class="bx bx-time-five mr-1"></i>
                                {{ materia.horario }}
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Accesos rápidos -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <Link :href="route('expediente.index')" class="group">
                        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-blue-400 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="bx bx-folder-open text-blue-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-base font-semibold text-gray-900">Expediente</h3>
                                    <p class="text-xs text-gray-500">Gestionar alumnos y legajos</p>
                                </div>
                                <i class="bx bx-chevron-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                            </div>
                        </div>
                    </Link>

                    <Link :href="route('profesor.mis-materias.index')" class="group">
                        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-orange-400 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="bx bx-book text-orange-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-base font-semibold text-gray-900">Materias</h3>
                                    <p class="text-xs text-gray-500">Ver detalle de materias</p>
                                </div>
                                <i class="bx bx-chevron-right text-gray-400 group-hover:text-orange-600 transition-colors"></i>
                            </div>
                        </div>
                    </Link>

                    <Link :href="route('profile.edit')" class="group">
                        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:border-green-400 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="bx bx-user text-green-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-base font-semibold text-gray-900">Mi Perfil</h3>
                                    <p class="text-xs text-gray-500">Actualizar datos personales</p>
                                </div>
                                <i class="bx bx-chevron-right text-gray-400 group-hover:text-green-600 transition-colors"></i>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Dashboard por defecto (Alumnos, Admin) -->
            <div v-else>
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
        </div>
    </SidebarLayout>
</template>
