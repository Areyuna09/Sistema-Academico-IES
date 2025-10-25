<script setup>
import { Head, Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    tipoUsuario: {
        type: String,
        default: 'default'
    },
    metricas: {
        type: Object,
        default: null
    },
    mensaje: {
        type: String,
        default: null
    }
});

// Determinar saludo según hora del día
const obtenerSaludo = () => {
    const hora = new Date().getHours();
    if (hora < 12) return 'Buenos días';
    if (hora < 19) return 'Buenas tardes';
    return 'Buenas noches';
};

// Configuración de métricas según tipo de usuario
const configMetricas = computed(() => {
    if (props.tipoUsuario === 'profesor') {
        return [
            {
                titulo: 'Materias',
                valor: props.metricas?.total_materias || 0,
                subtitulo: 'Asignadas',
                icono: 'bx-book-open',
                gradient: 'from-blue-500 to-blue-600'
            },
            {
                titulo: 'Alumnos',
                valor: props.metricas?.total_alumnos || 0,
                subtitulo: 'Inscritos',
                icono: 'bx-group',
                gradient: 'from-green-500 to-green-600'
            },
            {
                titulo: 'Asistencias',
                valor: props.metricas?.asistencias_pendientes || 0,
                subtitulo: 'Pendientes',
                icono: 'bx-calendar-check',
                gradient: 'from-orange-500 to-orange-600'
            },
            {
                titulo: 'Notas',
                valor: props.metricas?.notas_pendientes || 0,
                subtitulo: 'Por aprobar',
                icono: 'bx-edit',
                gradient: 'from-purple-500 to-purple-600'
            }
        ];
    } else if (props.tipoUsuario === 'alumno') {
        return [
            {
                titulo: 'Cursando',
                valor: props.metricas?.materias_cursando || 0,
                subtitulo: 'Materias',
                icono: 'bx-book-open',
                gradient: 'from-blue-500 to-blue-600'
            },
            {
                titulo: 'Aprobadas',
                valor: props.metricas?.materias_aprobadas || 0,
                subtitulo: 'Materias',
                icono: 'bx-check-circle',
                gradient: 'from-green-500 to-green-600'
            },
            {
                titulo: 'Promedio',
                valor: props.metricas?.promedio_general || '0.00',
                subtitulo: 'General',
                icono: 'bx-trending-up',
                gradient: 'from-indigo-500 to-indigo-600'
            },
            {
                titulo: 'Asistencia',
                valor: `${props.metricas?.asistencia || 0}%`,
                subtitulo: 'General',
                icono: 'bx-calendar-check',
                gradient: 'from-purple-500 to-purple-600'
            }
        ];
    } else if (props.tipoUsuario === 'admin') {
        return [
            {
                titulo: 'Usuarios',
                valor: props.metricas?.total_usuarios || 0,
                subtitulo: 'Activos',
                icono: 'bx-user',
                gradient: 'from-blue-500 to-blue-600'
            },
            {
                titulo: 'Materias',
                valor: props.metricas?.total_materias || 0,
                subtitulo: 'Registradas',
                icono: 'bx-book',
                gradient: 'from-green-500 to-green-600'
            },
            {
                titulo: 'Inscripciones',
                valor: props.metricas?.inscripciones_activas || 0,
                subtitulo: 'Activas',
                icono: 'bx-clipboard',
                gradient: 'from-indigo-500 to-indigo-600'
            },
            {
                titulo: 'Notas',
                valor: props.metricas?.notas_pendientes || 0,
                subtitulo: 'Por aprobar',
                icono: 'bx-edit',
                gradient: 'from-purple-500 to-purple-600'
            }
        ];
    }
    return [];
});

// Accesos rápidos según tipo de usuario
const accesosRapidos = computed(() => {
    if (props.tipoUsuario === 'profesor') {
        return [
            {
                titulo: 'Expediente',
                descripcion: 'Gestionar alumnos y legajos',
                icono: 'bx-folder-open',
                color: 'blue',
                bgColor: 'bg-blue-100',
                textColor: 'text-blue-600',
                hoverBorder: 'hover:border-blue-400',
                route: 'expediente.index'
            },
            {
                titulo: 'Mis Materias',
                descripcion: 'Ver y gestionar materias',
                icono: 'bx-book',
                color: 'orange',
                bgColor: 'bg-orange-100',
                textColor: 'text-orange-600',
                hoverBorder: 'hover:border-orange-400',
                route: 'profesor.mis-materias.index'
            },
            {
                titulo: 'Mi Perfil',
                descripcion: 'Actualizar datos personales',
                icono: 'bx-user',
                color: 'green',
                bgColor: 'bg-green-100',
                textColor: 'text-green-600',
                hoverBorder: 'hover:border-green-400',
                route: 'profile.edit'
            }
        ];
    } else if (props.tipoUsuario === 'alumno') {
        return [
            {
                titulo: 'Inscripciones',
                descripcion: 'Materias del cuatrimestre',
                icono: 'bx-clipboard',
                color: 'blue',
                bgColor: 'bg-blue-100',
                textColor: 'text-blue-600',
                hoverBorder: 'hover:border-blue-400',
                route: 'inscripciones.index'
            },
            {
                titulo: 'Mi Expediente',
                descripcion: 'Historial académico completo',
                icono: 'bx-folder-open',
                color: 'green',
                bgColor: 'bg-green-100',
                textColor: 'text-green-600',
                hoverBorder: 'hover:border-green-400',
                href: '/expediente'
            },
            {
                titulo: 'Mesas de Examen',
                descripcion: 'Inscripción a exámenes',
                icono: 'bx-calendar-event',
                color: 'purple',
                bgColor: 'bg-purple-100',
                textColor: 'text-purple-600',
                hoverBorder: 'hover:border-purple-400',
                href: '/mesas'
            }
        ];
    } else if (props.tipoUsuario === 'admin') {
        return [
            {
                titulo: 'Expediente',
                descripcion: 'Gestión académica completa',
                icono: 'bx-folder-open',
                color: 'blue',
                bgColor: 'bg-blue-100',
                textColor: 'text-blue-600',
                hoverBorder: 'hover:border-blue-400',
                route: 'expediente.index'
            },
            {
                titulo: 'Usuarios',
                descripcion: 'Administrar usuarios',
                icono: 'bx-group',
                color: 'green',
                bgColor: 'bg-green-100',
                textColor: 'text-green-600',
                hoverBorder: 'hover:border-green-400',
                route: 'admin.usuarios.index'
            },
            {
                titulo: 'Configuración',
                descripcion: 'Ajustes del sistema',
                icono: 'bx-cog',
                color: 'purple',
                bgColor: 'bg-purple-100',
                textColor: 'text-purple-600',
                hoverBorder: 'hover:border-purple-400',
                route: 'profile.edit'
            }
        ];
    }
    return [];
});
</script>

<template>
    <Head title="Dashboard" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Dashboard</h1>
                <p class="text-xs text-gray-400 mt-0.5">{{ obtenerSaludo() }}, {{ $page.props.auth.user.name }}</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Banner Hero -->
            <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-xl p-8 mb-8 text-white relative overflow-hidden">
                <!-- Patrón decorativo de fondo -->
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid)" />
                    </svg>
                </div>

                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">{{ obtenerSaludo() }}, {{ $page.props.auth.user.name }}</h2>
                        <p class="text-blue-100 text-lg">
                            <template v-if="tipoUsuario === 'profesor'">Bienvenido al panel de profesor</template>
                            <template v-else-if="tipoUsuario === 'alumno'">Bienvenido a tu panel de estudiante</template>
                            <template v-else-if="tipoUsuario === 'admin'">Panel de administración</template>
                            <template v-else>Sistema Académico IES</template>
                        </p>
                        <div class="mt-3 text-sm text-blue-100">
                            <i class="bx bx-calendar mr-1"></i>
                            {{ new Date().toLocaleDateString('es-AR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                        </div>
                    </div>
                    <!-- Ilustración decorativa -->
                    <div class="hidden md:block">
                        <div class="w-32 h-32 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <i :class="['bx text-6xl', tipoUsuario === 'profesor' ? 'bx-chalkboard' : tipoUsuario === 'alumno' ? 'bx-book-reader' : 'bx-briefcase']"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensaje de error/advertencia -->
            <div v-if="mensaje" class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="bx bx-info-circle text-yellow-600 text-xl mr-2"></i>
                    <p class="text-yellow-800">{{ mensaje }}</p>
                </div>
            </div>

            <!-- Métricas -->
            <div v-if="metricas && configMetricas.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div
                    v-for="(metrica, index) in configMetricas"
                    :key="index"
                    :class="['bg-gradient-to-br rounded-xl p-6 text-white transform transition-all duration-200 hover:scale-105 hover:shadow-lg', metrica.gradient]"
                >
                    <div class="flex items-center justify-between mb-3">
                        <i :class="['bx text-4xl opacity-80', metrica.icono]"></i>
                        <span class="text-xs bg-white/20 px-3 py-1 rounded-full font-medium">{{ metrica.titulo }}</span>
                    </div>
                    <p class="text-4xl font-bold mb-1">{{ metrica.valor }}</p>
                    <p class="text-sm opacity-90">{{ metrica.subtitulo }}</p>
                </div>
            </div>

            <!-- Accesos Rápidos -->
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="bx bx-grid-alt text-2xl mr-2 text-indigo-600"></i>
                    Accesos Rápidos
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <template v-for="(acceso, index) in accesosRapidos" :key="index">
                        <Link
                            v-if="acceso.route"
                            :href="route(acceso.route)"
                            class="group"
                        >
                            <div :class="['bg-white border-2 border-gray-200 rounded-xl p-6 transition-all duration-200 hover:shadow-xl', acceso.hoverBorder]">
                                <div class="flex items-center mb-4">
                                    <div :class="['w-14 h-14 rounded-xl flex items-center justify-center mr-4', acceso.bgColor]">
                                        <i :class="['bx text-2xl', acceso.icono, acceso.textColor]"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ acceso.titulo }}</h3>
                                    </div>
                                    <i :class="['bx bx-chevron-right text-2xl text-gray-400 transition-colors', `group-hover:${acceso.textColor}`]"></i>
                                </div>
                                <p class="text-sm text-gray-600">{{ acceso.descripcion }}</p>
                            </div>
                        </Link>
                        <a
                            v-else
                            :href="acceso.href"
                            class="group"
                        >
                            <div :class="['bg-white border-2 border-gray-200 rounded-xl p-6 transition-all duration-200 hover:shadow-xl', acceso.hoverBorder]">
                                <div class="flex items-center mb-4">
                                    <div :class="['w-14 h-14 rounded-xl flex items-center justify-center mr-4', acceso.bgColor]">
                                        <i :class="['bx text-2xl', acceso.icono, acceso.textColor]"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ acceso.titulo }}</h3>
                                    </div>
                                    <i :class="['bx bx-chevron-right text-2xl text-gray-400 transition-colors', `group-hover:${acceso.textColor}`]"></i>
                                </div>
                                <p class="text-sm text-gray-600">{{ acceso.descripcion }}</p>
                            </div>
                        </a>
                    </template>
                </div>
            </div>

            <!-- Información adicional/Tips -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="bx bx-info-circle text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Información Importante</h3>
                        <div class="text-sm text-gray-700 space-y-2">
                            <template v-if="tipoUsuario === 'profesor'">
                                <p>• Recuerda cargar las asistencias y notas de tus materias regularmente.</p>
                                <p>• Las notas finales requieren aprobación de Secretaría antes de ser oficiales.</p>
                                <p>• Puedes ver el detalle de cada materia haciendo clic en "Mis Materias".</p>
                            </template>
                            <template v-else-if="tipoUsuario === 'alumno'">
                                <p>• Verifica tu horario de cursada y fechas de exámenes regularmente.</p>
                                <p>• Mantén al día tus datos de contacto en el perfil.</p>
                                <p>• Consulta tu expediente para ver tu progreso académico completo.</p>
                            </template>
                            <template v-else-if="tipoUsuario === 'admin'">
                                <p>• Revisa regularmente las notas pendientes de aprobación.</p>
                                <p>• Mantén actualizados los datos de materias y usuarios.</p>
                                <p>• Gestiona los legajos desde el panel de Expediente.</p>
                            </template>
                            <template v-else>
                                <p>• Sistema Académico del Instituto de Educación Superior</p>
                                <p>• Para soporte técnico, contacta con el administrador del sistema.</p>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
