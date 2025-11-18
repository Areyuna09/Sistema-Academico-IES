<script setup>
import { Head, Link } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { computed, ref, onMounted } from 'vue';
import axios from 'axios';

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

// Estado del modal de materias
const modalMateriasAbierto = ref(false);
const materiasProfesor = ref([]);
const cargandoMaterias = ref(false);
const errorMaterias = ref('');

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
                onClick: abrirModalMaterias
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
                titulo: 'Inscripciones a materias',
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
                titulo: 'Inscripciones a Mesas de Examen',
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

// Funciones para el modal de materias
const abrirModalMaterias = async () => {
    modalMateriasAbierto.value = true;
    await cargarMaterias();
};

const cerrarModalMaterias = () => {
    modalMateriasAbierto.value = false;
};

const cargarMaterias = async () => {
    cargandoMaterias.value = true;
    errorMaterias.value = '';

    try {
        const response = await axios.get(route('expediente.alumnos-profesor'));
        materiasProfesor.value = response.data.materias;
    } catch (error) {
        errorMaterias.value = 'Error al cargar las materias';
        console.error(error);
    } finally {
        cargandoMaterias.value = false;
    }
};

const irAMateria = (materiaId) => {
    window.location.href = route('profesor.mis-materias.show', materiaId);
};
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
                        <!-- Con función onClick -->
                        <div
                            v-if="acceso.onClick"
                            @click="acceso.onClick"
                            class="group cursor-pointer"
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
                        </div>
                        <!-- Con route -->
                        <Link
                            v-else-if="acceso.route"
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
                        <!-- Con href -->
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

        <!-- Modal de Selección de Materias -->
        <div v-if="modalMateriasAbierto" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[85vh] overflow-hidden flex flex-col">
                <!-- Header del modal -->
                <div class="bg-orange-600 text-white px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="bx bx-book text-3xl mr-3"></i>
                        <div>
                            <h3 class="text-xl font-semibold">Mis Materias</h3>
                            <p class="text-sm text-orange-100 mt-1">Selecciona una materia para ver su detalle</p>
                        </div>
                    </div>
                    <button @click="cerrarModalMaterias" class="text-white hover:text-gray-200 transition-colors">
                        <i class="bx bx-x text-3xl"></i>
                    </button>
                </div>

                <!-- Contenido del modal -->
                <div class="flex-1 overflow-y-auto p-6">
                    <!-- Estado de carga -->
                    <div v-if="cargandoMaterias" class="text-center py-12">
                        <i class="bx bx-loader-alt animate-spin text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">Cargando materias...</p>
                    </div>

                    <!-- Error -->
                    <div v-else-if="errorMaterias" class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center text-red-700">
                            <i class="bx bx-error-circle mr-2 text-xl"></i>
                            {{ errorMaterias }}
                        </div>
                    </div>

                    <!-- Lista de materias -->
                    <div v-else-if="materiasProfesor && materiasProfesor.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div 
                            v-for="materia in materiasProfesor" 
                            :key="materia.materia_id"
                            @click="irAMateria(materia.materia_id)"
                            class="bg-white border-2 border-gray-200 rounded-lg p-5 hover:border-orange-400 hover:shadow-lg transition-all cursor-pointer group"
                        >
                            <!-- Header de la materia -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1 group-hover:text-orange-600 transition-colors">
                                        {{ materia.materia }}
                                    </h3>
                                    <p class="text-sm text-gray-600">{{ materia.carrera }}</p>
                                </div>
                                <i class="bx bx-chevron-right text-2xl text-gray-400 group-hover:text-orange-600 transition-colors"></i>
                            </div>

                            <!-- Advertencia de configuración pendiente -->
                            <div v-if="!materia.configuracion?.configuracion_completa" class="mb-3 p-2 bg-yellow-50 border border-yellow-300 rounded-lg">
                                <div class="flex items-center text-yellow-800 text-xs">
                                    <i class="bx bx-warning mr-2 text-base"></i>
                                    <span><strong>Configuración pendiente</strong></span>
                                </div>
                            </div>

                            <!-- Badge de alumnos -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="bg-orange-100 text-orange-700 text-xs font-medium px-3 py-1 rounded-full">
                                    <i class="bx bx-group mr-1"></i>
                                    {{ materia.total_alumnos }} alumnos
                                </span>
                                <div class="flex gap-2">
                                    <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded">
                                        {{ materia.cursado }}
                                    </span>
                                    <span class="bg-purple-100 text-purple-700 text-xs font-medium px-2 py-1 rounded">
                                        Div. {{ materia.division }}
                                    </span>
                                </div>
                            </div>

                            <!-- Parámetros configurados (si existe configuración) -->
                            <div v-if="materia.configuracion?.configuracion_completa" class="p-2 bg-green-50 border border-green-200 rounded-lg">
                                <div class="grid grid-cols-2 gap-2 text-xs text-gray-700">
                                    <div class="flex items-center">
                                        <i class="bx bx-medal text-green-600 mr-1"></i>
                                        <span>Promo: <strong>{{ materia.configuracion.nota_minima_promocion }}</strong></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="bx bx-check text-green-600 mr-1"></i>
                                        <span>Reg: <strong>{{ materia.configuracion.nota_minima_regularidad }}</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sin materias -->
                    <div v-else class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bx bx-book-open text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600 mb-4">No tienes materias asignadas</p>
                        <p class="text-sm text-gray-500">Contacta con el administrador para asignarte materias</p>
                    </div>
                </div>

                <!-- Footer del modal -->
                <div class="bg-gray-100 px-6 py-4 flex justify-between items-center">
                    <p class="text-sm text-gray-600">
                        <i class="bx bx-info-circle mr-1"></i>
                        Haz clic en una materia para ver su detalle completo
                    </p>
                    <button
                        @click="cerrarModalMaterias"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>