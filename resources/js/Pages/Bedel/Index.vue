<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    user: Object,
    metricas: Object,
});

const page = usePage();
const permisosAcceso = computed(() => page.props.permisosAcceso || {});

const obtenerSaludo = () => {
    const hora = new Date().getHours();
    if (hora < 12) return 'Buenos días';
    if (hora < 19) return 'Buenas tardes';
    return 'Buenas noches';
};

const metricasCards = computed(() => [
    {
        titulo: 'Inscripciones',
        valor: props.metricas?.inscripciones_activas || 0,
        subtitulo: 'Activas',
        icono: 'bx-clipboard',
        gradient: 'from-blue-500 to-blue-600',
    },
    {
        titulo: 'Alumnos',
        valor: props.metricas?.total_alumnos || 0,
        subtitulo: 'Registrados',
        icono: 'bx-group',
        gradient: 'from-green-500 to-green-600',
    },
    {
        titulo: 'Notas',
        valor: props.metricas?.notas_pendientes || 0,
        subtitulo: 'Por aprobar',
        icono: 'bx-edit',
        gradient: 'from-purple-500 to-purple-600',
    },
]);

const accesosRapidos = computed(() => {
    const accesos = [
        {
            titulo: 'Expediente',
            descripcion: 'Gestionar legajos académicos',
            icono: 'bx-folder-open',
            bgColor: 'bg-blue-100',
            textColor: 'text-blue-600',
            hoverBorder: 'hover:border-blue-400',
            route: 'expediente.index',
        },
        {
            titulo: 'Inscripciones',
            descripcion: 'Gestionar inscripciones de alumnos',
            icono: 'bx-clipboard',
            bgColor: 'bg-indigo-100',
            textColor: 'text-indigo-600',
            hoverBorder: 'hover:border-indigo-400',
            href: '/inscripciones',
            acceso: 'admin_inscripciones',
        },
        {
            titulo: 'Mesas de Examen',
            descripcion: 'Gestionar mesas de examen',
            icono: 'bx-calendar-event',
            bgColor: 'bg-purple-100',
            textColor: 'text-purple-600',
            hoverBorder: 'hover:border-purple-400',
            href: '/mesas',
            acceso: 'admin_mesas',
        },
        {
            titulo: 'Usuarios',
            descripcion: 'Administrar usuarios del sistema',
            icono: 'bx-group',
            bgColor: 'bg-green-100',
            textColor: 'text-green-600',
            hoverBorder: 'hover:border-green-400',
            route: 'admin.usuarios.index',
            acceso: 'admin_usuarios',
        },
    ];

    return accesos.filter(a => {
        if (!a.acceso) return true;
        return permisosAcceso.value[a.acceso];
    });
});
</script>

<template>
    <Head title="Panel de Bedel" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Panel de Bedel</h1>
                <p class="text-xs text-gray-400 mt-0.5">{{ obtenerSaludo() }}, {{ user.nombre }}</p>
            </div>
        </template>

        <div class="p-4 md:p-8 max-w-7xl mx-auto">
            <!-- Banner -->
            <div class="bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-600 rounded-lg md:rounded-xl p-4 md:p-8 mb-4 md:mb-8 text-white relative overflow-hidden">
                <div class="relative z-10 flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-xl md:text-3xl font-bold mb-1 md:mb-2 truncate">{{ obtenerSaludo() }}, {{ user.nombre }}</h2>
                        <p class="text-teal-100 text-sm md:text-lg">Panel de Bedel</p>
                        <div v-if="metricas.periodo_activo" class="mt-2 text-xs md:text-sm text-teal-100">
                            <i class="bx bx-calendar mr-1"></i>
                            {{ metricas.periodo_activo }}
                        </div>
                    </div>
                    <div class="hidden md:block flex-shrink-0 ml-4">
                        <div class="w-20 h-20 md:w-32 md:h-32 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <i class="bx bx-id-card text-4xl md:text-6xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metricas -->
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 md:gap-4 mb-6 md:mb-8">
                <div
                    v-for="(metrica, index) in metricasCards"
                    :key="index"
                    :class="['bg-gradient-to-br rounded-lg md:rounded-xl p-3 md:p-6 text-white transform transition-all duration-200 hover:scale-105 hover:shadow-lg', metrica.gradient]"
                >
                    <div class="flex items-center justify-between mb-1 md:mb-3">
                        <i :class="['bx text-2xl md:text-4xl opacity-80', metrica.icono]"></i>
                        <span class="text-[8px] md:text-xs bg-white/20 px-1.5 md:px-3 py-0.5 md:py-1 rounded-full font-medium truncate">{{ metrica.titulo }}</span>
                    </div>
                    <p class="text-xl md:text-4xl font-bold mb-0.5 md:mb-1">{{ metrica.valor }}</p>
                    <p class="text-[10px] md:text-sm opacity-90 truncate">{{ metrica.subtitulo }}</p>
                </div>
            </div>

            <!-- Accesos Rapidos -->
            <div class="mb-6">
                <h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-3 md:mb-4 flex items-center">
                    <i class="bx bx-grid-alt text-xl md:text-2xl mr-2 text-teal-600"></i>
                    Accesos Rapidos
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                    <template v-for="(acceso, index) in accesosRapidos" :key="index">
                        <Link
                            v-if="acceso.route"
                            :href="route(acceso.route)"
                            class="group"
                        >
                            <div :class="['bg-white border-2 border-gray-200 rounded-lg md:rounded-xl p-4 md:p-6 transition-all duration-200 hover:shadow-xl', acceso.hoverBorder]">
                                <div class="flex items-center mb-2 md:mb-4">
                                    <div :class="['w-10 h-10 md:w-14 md:h-14 rounded-lg md:rounded-xl flex items-center justify-center mr-3 md:mr-4', acceso.bgColor]">
                                        <i :class="['bx text-xl md:text-2xl', acceso.icono, acceso.textColor]"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base md:text-lg font-semibold text-gray-900 group-hover:text-teal-600 transition-colors truncate">{{ acceso.titulo }}</h3>
                                    </div>
                                    <i class="bx bx-chevron-right text-xl md:text-2xl text-gray-400 group-hover:text-teal-600 transition-colors flex-shrink-0"></i>
                                </div>
                                <p class="text-xs md:text-sm text-gray-600">{{ acceso.descripcion }}</p>
                            </div>
                        </Link>
                        <a
                            v-else
                            :href="acceso.href"
                            class="group"
                        >
                            <div :class="['bg-white border-2 border-gray-200 rounded-lg md:rounded-xl p-4 md:p-6 transition-all duration-200 hover:shadow-xl', acceso.hoverBorder]">
                                <div class="flex items-center mb-2 md:mb-4">
                                    <div :class="['w-10 h-10 md:w-14 md:h-14 rounded-lg md:rounded-xl flex items-center justify-center mr-3 md:mr-4', acceso.bgColor]">
                                        <i :class="['bx text-xl md:text-2xl', acceso.icono, acceso.textColor]"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base md:text-lg font-semibold text-gray-900 group-hover:text-teal-600 transition-colors truncate">{{ acceso.titulo }}</h3>
                                    </div>
                                    <i class="bx bx-chevron-right text-xl md:text-2xl text-gray-400 group-hover:text-teal-600 transition-colors flex-shrink-0"></i>
                                </div>
                                <p class="text-xs md:text-sm text-gray-600">{{ acceso.descripcion }}</p>
                            </div>
                        </a>
                    </template>
                </div>
            </div>

            <!-- Info -->
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 border border-teal-200 rounded-lg md:rounded-xl p-4 md:p-6">
                <div class="flex items-start">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-3 md:mr-4 flex-shrink-0">
                        <i class="bx bx-info-circle text-teal-600 text-lg md:text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold text-gray-900 mb-2">Panel de Bedel</h3>
                        <div class="text-xs md:text-sm text-gray-700 space-y-1">
                            <p>Gestiona inscripciones, legajos y datos de alumnos desde este panel.</p>
                            <p>Utiliza los accesos rapidos o el menu lateral para navegar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
