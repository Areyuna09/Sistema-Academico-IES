<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import axios from 'axios';

const props = defineProps({
    materias: {
        type: Object,
        default: () => ({ data: [], links: [] })
    },
    carreras: {
        type: Array,
        default: () => []
    },
    usuarios: {
        type: Object,
        default: () => ({ data: [], links: [] })
    },
    filtrosMaterias: {
        type: Object,
        default: () => ({})
    },
    filtrosUsuarios: {
        type: Object,
        default: () => ({})
    },
});

// Debug: ver qué datos llegan
console.log('AdminPanel Props:', props);

// Tab activo
const tabActivo = ref('materias');

// Variables para búsqueda de legajos
const dniBusqueda = ref('');
const buscando = ref(false);
const alumnoEncontrado = ref(null);
const historialAcademico = ref(null);
const errorBusqueda = ref('');

// Forms para filtros
const formMaterias = useForm({
    carrera: props.filtrosMaterias?.carrera || '',
    semestre: props.filtrosMaterias?.semestre || '',
    anno: props.filtrosMaterias?.anno || '',
    buscar: props.filtrosMaterias?.buscar || '',
});

const formProfesores = useForm({
    activo: props.filtrosUsuarios?.activo || '',
    buscar: props.filtrosUsuarios?.buscar || '',
});

const formAlumnos = useForm({
    activo: props.filtrosUsuarios?.activo || '',
    buscar: props.filtrosUsuarios?.buscar || '',
    carrera: props.filtrosUsuarios?.carrera || '',
});

const opciones = [
    {
        id: 'materias',
        titulo: 'Materias',
        descripcion: 'Gestión de materias',
        icono: 'bx-book-open',
        bgColor: 'bg-blue-100',
        iconColor: 'text-blue-600',
        hoverBorder: 'hover:border-blue-400',
        activeBorder: 'border-blue-500',
        activeBg: 'bg-blue-50'
    },
    {
        id: 'profesores',
        titulo: 'Profesores',
        descripcion: 'Listado de profesores',
        icono: 'bx-chalkboard',
        bgColor: 'bg-purple-100',
        iconColor: 'text-purple-600',
        hoverBorder: 'hover:border-purple-400',
        activeBorder: 'border-purple-500',
        activeBg: 'bg-purple-50'
    },
    {
        id: 'alumnos',
        titulo: 'Alumnos',
        descripcion: 'Gestión de alumnos',
        icono: 'bx-group',
        bgColor: 'bg-green-100',
        iconColor: 'text-green-600',
        hoverBorder: 'hover:border-green-400',
        activeBorder: 'border-green-500',
        activeBg: 'bg-green-50'
    },
    {
        id: 'legajos',
        titulo: 'Legajos',
        descripcion: 'Expedientes académicos',
        icono: 'bx-folder-open',
        bgColor: 'bg-orange-100',
        iconColor: 'text-orange-600',
        hoverBorder: 'hover:border-orange-400',
        activeBorder: 'border-orange-500',
        activeBg: 'bg-orange-50'
    }
];

const cambiarTab = (tabId) => {
    tabActivo.value = tabId;
    if (tabId !== 'legajos') {
        limpiarBusqueda();
    }
};

// Funciones para Materias
const buscarMaterias = () => {
    formMaterias.get(route('expediente.index'), {
        preserveState: true,
        preserveScroll: true,
    });
};

const limpiarFiltrosMaterias = () => {
    formMaterias.carrera = '';
    formMaterias.semestre = '';
    formMaterias.anno = '';
    formMaterias.buscar = '';
    formMaterias.get(route('expediente.index'));
};

const eliminarMateria = (materia) => {
    if (confirm(`¿Está seguro de eliminar la materia "${materia.nombre}"?`)) {
        router.delete(route('admin.materias.destroy', materia.id), {
            onSuccess: () => {
                formMaterias.get(route('expediente.index'), {
                    preserveState: true,
                    preserveScroll: true,
                });
            }
        });
    }
};

const getSemestreBadge = (semestre) => {
    return semestre === 1
        ? { text: '1° Sem', class: 'bg-blue-100 text-blue-800' }
        : { text: '2° Sem', class: 'bg-purple-100 text-purple-800' };
};

// Funciones para Profesores
const buscarProfesores = () => {
    formProfesores.get(route('expediente.index'), {
        preserveState: true,
        preserveScroll: true,
    });
};

const limpiarFiltrosProfesores = () => {
    formProfesores.activo = '';
    formProfesores.buscar = '';
    formProfesores.get(route('expediente.index'));
};

// Funciones para Alumnos
const buscarAlumnos = () => {
    formAlumnos.get(route('expediente.index'), {
        preserveState: true,
        preserveScroll: true,
    });
};

const limpiarFiltrosAlumnos = () => {
    formAlumnos.activo = '';
    formAlumnos.buscar = '';
    formAlumnos.carrera = '';
    formAlumnos.get(route('expediente.index'));
};

const toggleActivo = (usuario) => {
    if (confirm(`¿Está seguro de ${usuario.activo ? 'desactivar' : 'activar'} este usuario?`)) {
        router.post(route('admin.usuarios.toggle', usuario.id), {}, {
            preserveScroll: true,
        });
    }
};

const eliminarUsuario = (usuario) => {
    if (confirm(`¿Está seguro de eliminar el usuario de ${usuario.nombre}? Esta acción no se puede deshacer.`)) {
        router.delete(route('admin.usuarios.destroy', usuario.id), {
            preserveScroll: true,
        });
    }
};

// Funciones para Legajos
const buscarAlumno = async () => {
    if (!dniBusqueda.value.trim()) {
        errorBusqueda.value = 'Por favor ingrese un DNI';
        return;
    }

    buscando.value = true;
    errorBusqueda.value = '';
    alumnoEncontrado.value = null;
    historialAcademico.value = null;

    try {
        const response = await axios.post(route('expediente.buscar-dni'), {
            dni: dniBusqueda.value
        });

        alumnoEncontrado.value = response.data.alumno;
        historialAcademico.value = response.data.historial;
    } catch (error) {
        if (error.response && error.response.status === 404) {
            errorBusqueda.value = 'No se encontró ningún alumno con ese DNI';
        } else {
            errorBusqueda.value = 'Ocurrió un error al buscar el alumno';
        }
    } finally {
        buscando.value = false;
    }
};

const limpiarBusqueda = () => {
    dniBusqueda.value = '';
    alumnoEncontrado.value = null;
    historialAcademico.value = null;
    errorBusqueda.value = '';
};
</script>

<template>
    <Head title="Panel de Administración - Expediente" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Panel de Administración</h1>
                <p class="text-xs text-gray-400 mt-0.5">Gestión de expedientes y registros académicos</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Grid de opciones / Tabs -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div
                    v-for="opcion in opciones"
                    :key="opcion.id"
                    @click="cambiarTab(opcion.id)"
                    class="group cursor-pointer"
                >
                    <div :class="[
                        'bg-white border-2 rounded-lg p-5 hover:shadow-md transition-all duration-200',
                        tabActivo === opcion.id ? [opcion.activeBorder, opcion.activeBg] : ['border-gray-200', opcion.hoverBorder]
                    ]">
                        <div class="flex items-center mb-3">
                            <div :class="[
                                'w-10 h-10 rounded-lg flex items-center justify-center mr-3',
                                opcion.bgColor
                            ]">
                                <i :class="['bx text-xl', opcion.icono, opcion.iconColor]"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-gray-900">{{ opcion.titulo }}</h3>
                                <p class="text-xs text-gray-500">{{ opcion.descripcion }}</p>
                            </div>
                            <i v-if="tabActivo === opcion.id" class="bx bx-check-circle text-2xl text-green-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido dinámico según el tab activo -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <!-- Tab Materias -->
                <div v-if="tabActivo === 'materias'">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <i class="bx bx-book-open text-3xl text-blue-600 mr-3"></i>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Gestión de Materias</h2>
                                <p class="text-sm text-gray-600">Crear y administrar materias del instituto</p>
                            </div>
                        </div>
                        <Link
                            :href="route('admin.materias.create')"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200"
                        >
                            <i class="bx bx-plus text-xl mr-2"></i>
                            Nueva Materia
                        </Link>
                    </div>

                    <!-- Filtros -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <form @submit.prevent="buscarMaterias" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Carrera</label>
                                <select
                                    v-model="formMaterias.carrera"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option value="">Todas las carreras</option>
                                    <option v-for="carrera in carreras" :key="carrera.Id" :value="carrera.Id">
                                        {{ carrera.nombre }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                                <select
                                    v-model="formMaterias.anno"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option value="">Todos los años</option>
                                    <option value="1">1° Año</option>
                                    <option value="2">2° Año</option>
                                    <option value="3">3° Año</option>
                                    <option value="4">4° Año</option>
                                    <option value="5">5° Año</option>
                                    <option value="6">6° Año</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Semestre</label>
                                <select
                                    v-model="formMaterias.semestre"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                >
                                    <option value="">Ambos semestres</option>
                                    <option value="1">1° Semestre</option>
                                    <option value="2">2° Semestre</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                                <input
                                    v-model="formMaterias.buscar"
                                    type="text"
                                    placeholder="Nombre de materia..."
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                />
                            </div>

                            <div class="flex items-end gap-2">
                                <button
                                    type="submit"
                                    class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200"
                                >
                                    <i class="bx bx-search mr-1"></i> Buscar
                                </button>
                                <button
                                    type="button"
                                    @click="limpiarFiltrosMaterias"
                                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200"
                                >
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tabla de materias -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Materia
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Carrera
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Año
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Semestre
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Resolución
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="materia in materias?.data || []" :key="materia.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ materia.nombre }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600">{{ materia.carrera?.nombre || '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ materia.anno }}° Año
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', getSemestreBadge(materia.semestre).class]">
                                            {{ getSemestreBadge(materia.semestre).text }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600">{{ materia.resolucion || '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <Link
                                                :href="route('admin.materias.edit', materia.id)"
                                                class="text-blue-600 hover:text-blue-900"
                                                title="Editar"
                                            >
                                                <i class="bx bx-edit text-lg"></i>
                                            </Link>
                                            <button
                                                @click="eliminarMateria(materia)"
                                                class="text-red-600 hover:text-red-900"
                                                title="Eliminar"
                                            >
                                                <i class="bx bx-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!materias?.data || materias.data.length === 0">
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <i class="bx bx-search-alt text-4xl mb-2"></i>
                                        <p>No se encontraron materias</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div v-if="materias?.links && materias.links.length > 3" class="mt-6 flex justify-center">
                        <div class="flex flex-wrap gap-1">
                            <component
                                :is="link.url ? Link : 'span'"
                                v-for="(link, index) in materias.links"
                                :key="index"
                                :href="link.url"
                                :class="[
                                    'px-3 py-2 text-sm rounded-lg',
                                    link.active
                                        ? 'bg-blue-600 text-white'
                                        : link.url
                                        ? 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300'
                                        : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                ]"
                                v-html="link.label"
                            />
                        </div>
                    </div>
                </div>

                <!-- Tab Profesores -->
                <div v-if="tabActivo === 'profesores'">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <i class="bx bx-chalkboard text-3xl text-purple-600 mr-3"></i>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Profesores</h2>
                                <p class="text-sm text-gray-600">Listado de profesores del instituto</p>
                            </div>
                        </div>
                        <Link
                            :href="route('admin.usuarios.create')"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors duration-200"
                        >
                            <i class="bx bx-plus text-xl mr-2"></i>
                            Nuevo Profesor
                        </Link>
                    </div>

                    <!-- Filtros -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                                <select
                                    v-model="formProfesores.activo"
                                    class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
                                >
                                    <option value="">Todos</option>
                                    <option value="1">Activos</option>
                                    <option value="0">Inactivos</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                                <input
                                    v-model="formProfesores.buscar"
                                    type="text"
                                    placeholder="DNI, nombre o email..."
                                    class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
                                    @keyup.enter="buscarProfesores"
                                />
                            </div>

                            <div class="flex items-end gap-2">
                                <button
                                    @click="buscarProfesores"
                                    class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors duration-200"
                                >
                                    <i class="bx bx-search mr-1"></i> Buscar
                                </button>
                                <button
                                    @click="limpiarFiltrosProfesores"
                                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200"
                                >
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de profesores -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="usuario in usuarios?.data?.filter(u => u.tipo === 3) || []" :key="usuario.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ usuario.dni }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ usuario.nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ usuario.email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="[
                                                'px-2 py-1 text-xs font-semibold rounded-full',
                                                usuario.activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                            ]"
                                        >
                                            {{ usuario.activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <Link
                                                :href="route('admin.usuarios.edit', usuario.id)"
                                                class="text-blue-600 hover:text-blue-900"
                                                title="Editar"
                                            >
                                                <i class="bx bx-edit text-lg"></i>
                                            </Link>
                                            <button
                                                @click="toggleActivo(usuario)"
                                                :class="[
                                                    'hover:opacity-75',
                                                    usuario.activo ? 'text-yellow-600' : 'text-green-600'
                                                ]"
                                                :title="usuario.activo ? 'Desactivar' : 'Activar'"
                                            >
                                                <i :class="[
                                                    'bx text-lg',
                                                    usuario.activo ? 'bx-toggle-right' : 'bx-toggle-left'
                                                ]"></i>
                                            </button>
                                            <button
                                                @click="eliminarUsuario(usuario)"
                                                class="text-red-600 hover:text-red-900"
                                                title="Eliminar"
                                            >
                                                <i class="bx bx-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!usuarios?.data || usuarios.data.filter(u => u.tipo === 3).length === 0">
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <i class="bx bx-user-x text-4xl mb-2"></i>
                                        <p>No se encontraron profesores</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Alumnos -->
                <div v-if="tabActivo === 'alumnos'">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <i class="bx bx-group text-3xl text-green-600 mr-3"></i>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Gestión de Alumnos</h2>
                                <p class="text-sm text-gray-600">Administrar estudiantes del instituto</p>
                            </div>
                        </div>
                        <Link
                            :href="route('admin.usuarios.create')"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors duration-200"
                        >
                            <i class="bx bx-plus text-xl mr-2"></i>
                            Nuevo Alumno
                        </Link>
                    </div>

                    <!-- Filtros -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Carrera</label>
                                <select
                                    v-model="formAlumnos.carrera"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200"
                                >
                                    <option value="">Todas las carreras</option>
                                    <option v-for="carrera in carreras" :key="carrera.Id" :value="carrera.Id">
                                        {{ carrera.nombre }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                                <select
                                    v-model="formAlumnos.activo"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200"
                                >
                                    <option value="">Todos</option>
                                    <option value="1">Activos</option>
                                    <option value="0">Inactivos</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                                <input
                                    v-model="formAlumnos.buscar"
                                    type="text"
                                    placeholder="DNI, nombre o email..."
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200"
                                    @keyup.enter="buscarAlumnos"
                                />
                            </div>

                            <div class="flex items-end gap-2">
                                <button
                                    @click="buscarAlumnos"
                                    class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200"
                                >
                                    <i class="bx bx-search mr-1"></i> Buscar
                                </button>
                                <button
                                    @click="limpiarFiltrosAlumnos"
                                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200"
                                >
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de alumnos -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vinculado a</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="usuario in usuarios?.data?.filter(u => u.tipo === 4) || []" :key="usuario.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ usuario.dni }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ usuario.nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ usuario.email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span v-if="usuario.alumno" class="flex items-center">
                                            <i class="bx bx-user text-green-600 mr-1"></i>
                                            {{ usuario.alumno.nombre_completo }}
                                        </span>
                                        <span v-else class="text-gray-400">-</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="[
                                                'px-2 py-1 text-xs font-semibold rounded-full',
                                                usuario.activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                            ]"
                                        >
                                            {{ usuario.activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <Link
                                                v-if="usuario.alumno_id"
                                                :href="route('expediente.show', usuario.alumno_id)"
                                                class="text-orange-600 hover:text-orange-900"
                                                title="Ver expediente"
                                            >
                                                <i class="bx bx-folder-open text-lg"></i>
                                            </Link>
                                            <Link
                                                :href="route('admin.usuarios.edit', usuario.id)"
                                                class="text-blue-600 hover:text-blue-900"
                                                title="Editar"
                                            >
                                                <i class="bx bx-edit text-lg"></i>
                                            </Link>
                                            <button
                                                @click="toggleActivo(usuario)"
                                                :class="[
                                                    'hover:opacity-75',
                                                    usuario.activo ? 'text-yellow-600' : 'text-green-600'
                                                ]"
                                                :title="usuario.activo ? 'Desactivar' : 'Activar'"
                                            >
                                                <i :class="[
                                                    'bx text-lg',
                                                    usuario.activo ? 'bx-toggle-right' : 'bx-toggle-left'
                                                ]"></i>
                                            </button>
                                            <button
                                                @click="eliminarUsuario(usuario)"
                                                class="text-red-600 hover:text-red-900"
                                                title="Eliminar"
                                            >
                                                <i class="bx bx-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!usuarios?.data || usuarios.data.filter(u => u.tipo === 4).length === 0">
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <i class="bx bx-user-x text-4xl mb-2"></i>
                                        <p>No se encontraron alumnos</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Legajos -->
                <div v-if="tabActivo === 'legajos'">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <i class="bx bx-folder-open text-3xl text-orange-600 mr-3"></i>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Legajos de Alumnos</h2>
                                <p class="text-sm text-gray-600">Consulta expedientes académicos completos</p>
                            </div>
                        </div>
                        <button
                            v-if="alumnoEncontrado"
                            @click="limpiarBusqueda"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
                        >
                            <i class="bx bx-x mr-2"></i>
                            Limpiar
                        </button>
                    </div>

                    <!-- Formulario de búsqueda por DNI -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar alumno por DNI</label>
                        <div class="flex gap-2">
                            <input
                                v-model="dniBusqueda"
                                type="text"
                                placeholder="Ingrese el DNI del alumno"
                                @keyup.enter="buscarAlumno"
                                pattern="[0-9]*"
                                inputmode="numeric"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                :disabled="buscando"
                            />
                            <button
                                @click="buscarAlumno"
                                :disabled="buscando"
                                class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <i :class="['bx mr-2', buscando ? 'bx-loader-alt animate-spin' : 'bx-search']"></i>
                                {{ buscando ? 'Buscando...' : 'Buscar' }}
                            </button>
                        </div>

                        <!-- Mensaje de error -->
                        <div v-if="errorBusqueda" class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg flex items-center text-red-700">
                            <i class="bx bx-error-circle mr-2 text-xl"></i>
                            {{ errorBusqueda }}
                        </div>
                    </div>

                    <!-- Resultados de la búsqueda -->
                    <div v-if="alumnoEncontrado">
                        <!-- Datos del alumno -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">DNI</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ alumnoEncontrado.dni }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Apellido</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ alumnoEncontrado.apellido }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Nombre</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ alumnoEncontrado.nombre }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Año Ingr.</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ alumnoEncontrado.anio_ingreso }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Carrera</label>
                                    <p class="text-sm font-semibold text-gray-900">{{ alumnoEncontrado.carrera }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Historial académico por año -->
                        <div v-for="(materias, anno) in historialAcademico" :key="anno" class="mb-6">
                            <div class="bg-gray-800 text-white px-4 py-2 rounded-t-lg">
                                <h3 class="font-semibold">{{ anno }}</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse bg-white">
                                    <thead class="bg-gray-100">
                                        <tr class="border-b border-gray-300">
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 border-r">Materia</th>
                                            <th class="px-2 py-2 text-center text-xs font-semibold text-gray-700 border-r">R</th>
                                            <th class="px-2 py-2 text-center text-xs font-semibold text-gray-700 border-r">P</th>
                                            <th class="px-2 py-2 text-center text-xs font-semibold text-gray-700 border-r">E</th>
                                            <th class="px-2 py-2 text-center text-xs font-semibold text-gray-700 border-r">L</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 border-r">Nota</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 border-r">Fecha</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 border-r">Libro</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700">Folio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(materia, index) in materias" :key="index" class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ materia.materia }}</td>
                                            <td class="px-2 py-2 text-center text-sm text-gray-900 border-r">{{ materia.regular }}</td>
                                            <td class="px-2 py-2 text-center text-sm text-gray-900 border-r">{{ materia.promocional }}</td>
                                            <td class="px-2 py-2 text-center text-sm text-gray-900 border-r">{{ materia.equivalencia }}</td>
                                            <td class="px-2 py-2 text-center text-sm text-gray-900 border-r">{{ materia.libre }}</td>
                                            <td class="px-3 py-2 text-center text-sm font-semibold text-gray-900 border-r">{{ materia.nota }}</td>
                                            <td class="px-3 py-2 text-center text-sm text-gray-900 border-r">{{ materia.fecha }}</td>
                                            <td class="px-3 py-2 text-center text-sm text-gray-900 border-r">{{ materia.libro }}</td>
                                            <td class="px-3 py-2 text-center text-sm text-gray-900">{{ materia.folio }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Mensaje si no hay historial -->
                        <div v-if="!historialAcademico || Object.keys(historialAcademico).length === 0" class="text-center py-8 bg-gray-50 rounded-lg">
                            <i class="bx bx-info-circle text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-600">Este alumno aún no tiene materias cursadas registradas</p>
                        </div>
                    </div>

                    <!-- Estado inicial -->
                    <div v-else-if="!buscando && !errorBusqueda" class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bx bx-search-alt text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">Ingresa un DNI para consultar el legajo del alumno</p>
                    </div>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
