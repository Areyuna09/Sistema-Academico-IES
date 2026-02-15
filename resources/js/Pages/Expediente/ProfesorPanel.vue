<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import Dialog from '@/Components/Dialog.vue';
import AsistenciaCalendario from '@/Components/AsistenciaCalendario.vue';
import axios from 'axios';
import { useProfesorOnboarding } from '@/Composables/useOnboarding';
import { useDialog } from '@/Composables/useDialog';

// Onboarding tour
const { startTourIfFirstTime, startTour } = useProfesorOnboarding();

// Dialog
const { alert: showAlert } = useDialog();

// Tab activo
const tabActivo = ref('materias');

// Variables para búsqueda de legajos
const dniBusqueda = ref('');
const buscando = ref(false);
const alumnoEncontrado = ref(null);
const historialAcademico = ref(null);
const errorBusqueda = ref('');

// Variables para alumnos
const alumnosData = ref(null);
const cargandoAlumnos = ref(false);
const errorAlumnos = ref('');
const materiaSeleccionada = ref(null);

// Variables para materias
const materiasData = ref(null);
const cargandoMaterias = ref(false);
const errorMaterias = ref('');

// Variables para períodos
const periodoActual = ref(null); // null = período activo
const periodosDisponibles = ref([]);
const periodoActivoInfo = ref(null);
const esArchivo = computed(() => {
    if (!periodoActual.value || !periodoActivoInfo.value) return false;
    return periodoActual.value !== periodoActivoInfo.value.id;
});

// Variables para modales
const modalAsistenciaAbierto = ref(false);
const modalNotasAbierto = ref(false);
const materiaActual = ref(null);
const alumnosActual = ref([]);
const guardandoAsistencia = ref(false);
const guardandoNotas = ref(false);

// Variables para asistencias
const fechaAsistencia = ref(new Date().toISOString().split('T')[0]);
const asistencias = ref({});

// Variables para notas
const tipoEvaluacion = ref('');
const fechaNotas = ref(new Date().toISOString().split('T')[0]);
const notas = ref({});

// Variables para modal de expediente alumno
const modalExpedienteAbierto = ref(false);
const expedienteAlumno = ref(null);
const cargandoExpediente = ref(false);

// Variables para configuración académica
const modalConfiguracionAbierto = ref(false);
const guardandoConfiguracion = ref(false);
const configuracionActual = ref({
    nota_minima_promocion: 7.00,
    nota_minima_regularidad: 4.00,
    permite_promocion: true,
    porcentaje_asistencia_minimo: 75,
    criterios_evaluacion: ''
});

// Variables para modal de calendario de asistencias
const modalCalendarioAbierto = ref(false);
const calendarioData = ref({ fechas: {}, alumnos: [] });
const cargandoCalendario = ref(false);
const calendarioMateriaActual = ref(null);
const mesCalendario = ref(new Date().toISOString().slice(0, 7)); // 'YYYY-MM'

const opciones = [
    {
        id: 'materias',
        titulo: 'Materias',
        descripcion: 'Asistencias y notas',
        icono: 'bx-book',
        bgColor: 'bg-orange-100',
        iconColor: 'text-orange-600',
        hoverBorder: 'hover:border-orange-400',
        activeBorder: 'border-orange-500',
        activeBg: 'bg-orange-50'
    },
    {
        id: 'alumnos',
        titulo: 'Alumnos',
        descripcion: 'Estudiantes activos',
        icono: 'bx-group',
        bgColor: 'bg-teal-100',
        iconColor: 'text-teal-600',
        hoverBorder: 'hover:border-teal-400',
        activeBorder: 'border-teal-500',
        activeBg: 'bg-teal-50'
    },
    {
        id: 'legajos',
        titulo: 'Legajos',
        descripcion: 'Expedientes académicos',
        icono: 'bx-file',
        bgColor: 'bg-gray-100',
        iconColor: 'text-gray-600',
        hoverBorder: 'hover:border-gray-400',
        activeBorder: 'border-gray-500',
        activeBg: 'bg-gray-50'
    }
];

// Computed para obtener lista de DNIs permitidos
const dniAlumnosPermitidos = computed(() => {
    if (!materiasData.value) return [];
    
    const dnis = new Set();
    materiasData.value.forEach(materia => {
        if (materia.alumnos && Array.isArray(materia.alumnos)) {
            materia.alumnos.forEach(alumno => {
                if (alumno.dni) {
                    dnis.add(alumno.dni.toString());
                }
            });
        }
    });
    
    return Array.from(dnis);
});

const cambiarTab = (tabId) => {
    tabActivo.value = tabId;
    // Limpiar búsqueda al cambiar de tab
    if (tabId !== 'legajos') {
        limpiarBusqueda();
    }
    // Cargar materias si se selecciona el tab de materias
    if (tabId === 'materias' && !materiasData.value) {
        cargarMaterias();
    }
    // Cargar alumnos si se selecciona el tab de alumnos
    if (tabId === 'alumnos' && !alumnosData.value) {
        cargarAlumnos();
    }
};

const buscarAlumno = async () => {
    if (!dniBusqueda.value.trim()) {
        errorBusqueda.value = 'Por favor ingrese un DNI';
        return;
    }

    // Verificar si el profesor tiene acceso a este alumno
    const dniABuscar = dniBusqueda.value.trim();
    if (!dniAlumnosPermitidos.value.includes(dniABuscar)) {
        errorBusqueda.value = 'No tienes permiso para ver el legajo de este alumno. Solo puedes consultar alumnos inscriptos en tus materias.';
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
        } else if (error.response && error.response.status === 403) {
            errorBusqueda.value = 'No tienes permiso para ver el legajo de este alumno';
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

const verExpedienteAlumno = async (alumno) => {
    modalExpedienteAbierto.value = true;
    cargandoExpediente.value = true;
    expedienteAlumno.value = null;

    try {
        const response = await axios.get(route('api.expediente.alumno', alumno.id));
        expedienteAlumno.value = response.data;
    } catch (error) {
        await showAlert('Error al cargar el expediente del alumno', 'error');
        modalExpedienteAbierto.value = false;
    } finally {
        cargandoExpediente.value = false;
    }
};

const cerrarModalExpediente = () => {
    modalExpedienteAbierto.value = false;
    expedienteAlumno.value = null;
};

const cargarPeriodosProfesor = async () => {
    try {
        const response = await axios.get(route('api.expediente.periodos-profesor'));
        periodosDisponibles.value = response.data.periodos;
    } catch (error) {
        console.error('Error al cargar períodos:', error);
    }
};

const cargarMaterias = async () => {
    cargandoMaterias.value = true;
    errorMaterias.value = '';

    try {
        const params = {};
        if (periodoActual.value) {
            params.periodo_id = periodoActual.value;
        }
        const response = await axios.get(route('expediente.alumnos-profesor'), { params });
        materiasData.value = response.data.materias;
        if (response.data.periodo_activo) {
            periodoActivoInfo.value = response.data.periodo_activo;
            // Si no hay período seleccionado, usar el activo
            if (!periodoActual.value) {
                periodoActual.value = response.data.periodo_activo.id;
            }
        }
    } catch (error) {
        errorMaterias.value = 'Error al cargar las materias';
        console.error(error);
    } finally {
        cargandoMaterias.value = false;
    }
};

const cargarAlumnos = async () => {
    cargandoAlumnos.value = true;
    errorAlumnos.value = '';

    try {
        const params = {};
        if (periodoActual.value) {
            params.periodo_id = periodoActual.value;
        }
        const response = await axios.get(route('expediente.alumnos-profesor'), { params });
        alumnosData.value = response.data.materias;

        // Seleccionar la primera materia por defecto
        if (alumnosData.value && alumnosData.value.length > 0) {
            materiaSeleccionada.value = alumnosData.value[0].materia_id;
        }
    } catch (error) {
        errorAlumnos.value = 'Error al cargar los alumnos';
        console.error(error);
    } finally {
        cargandoAlumnos.value = false;
    }
};

const cambiarPeriodo = (periodoId) => {
    periodoActual.value = periodoId;
    // Recargar datos con el nuevo período
    materiasData.value = null;
    alumnosData.value = null;
    if (tabActivo.value === 'materias') {
        cargarMaterias();
    } else if (tabActivo.value === 'alumnos') {
        cargarAlumnos();
    }
};

const periodoSeleccionadoLabel = computed(() => {
    if (!periodoActual.value || !periodosDisponibles.value.length) return 'Período actual';
    const p = periodosDisponibles.value.find(p => p.id === periodoActual.value);
    return p ? p.label : 'Período actual';
});

// Funciones para modales de asistencia
const abrirModalAsistencia = (materia) => {
    materiaActual.value = materia;
    alumnosActual.value = materia.alumnos;
    fechaAsistencia.value = new Date().toISOString().split('T')[0];

    // Inicializar asistencias con todos los alumnos presentes por defecto
    asistencias.value = {};
    materia.alumnos.forEach(alumno => {
        asistencias.value[alumno.id] = { estado: 'presente', observaciones: '' };
    });

    modalAsistenciaAbierto.value = true;
};

const cerrarModalAsistencia = () => {
    modalAsistenciaAbierto.value = false;
    materiaActual.value = null;
    alumnosActual.value = [];
    asistencias.value = {};
};

const guardarAsistenciaDiaria = async () => {
    if (!materiaActual.value || !fechaAsistencia.value) {
        await showAlert('Por favor completa todos los campos requeridos', 'Atención');
        return;
    }

    guardandoAsistencia.value = true;

    try {
        // Preparar datos
        const asistenciasArray = Object.keys(asistencias.value).map(alumnoId => ({
            alumno_id: parseInt(alumnoId),
            estado: asistencias.value[alumnoId].estado,
            observaciones: asistencias.value[alumnoId].observaciones || null
        }));

        const response = await axios.post(route('expediente.guardar-asistencia'), {
            profesor_materia_id: materiaActual.value.materia_id,
            fecha: fechaAsistencia.value,
            asistencias: asistenciasArray
        });

        await showAlert(response.data.message, 'Éxito');
        cerrarModalAsistencia();
    } catch (error) {
        console.error(error);
        if (error.response && error.response.status === 422 && error.response.data && error.response.data.message) {
            await showAlert(error.response.data.message, 'Atención');
        } else if (error.response && error.response.data && error.response.data.message) {
            await showAlert('Error: ' + error.response.data.message, 'Error');
        } else {
            await showAlert('Error al guardar la asistencia', 'Error');
        }
    } finally {
        guardandoAsistencia.value = false;
    }
};

// Funciones para modales de notas
const abrirModalNotas = (materia) => {
    materiaActual.value = materia;
    alumnosActual.value = materia.alumnos;
    fechaNotas.value = new Date().toISOString().split('T')[0];
    tipoEvaluacion.value = '';

    // Inicializar notas vacías
    notas.value = {};
    materia.alumnos.forEach(alumno => {
        notas.value[alumno.id] = { nota: '', observaciones: '' };
    });

    modalNotasAbierto.value = true;
};

const cerrarModalNotas = () => {
    modalNotasAbierto.value = false;
    materiaActual.value = null;
    alumnosActual.value = [];
    notas.value = {};
    tipoEvaluacion.value = '';
};

const guardarNotasTemporales = async () => {
    if (!materiaActual.value || !fechaNotas.value || !tipoEvaluacion.value) {
        await showAlert('Por favor completa el tipo de evaluación y la fecha', 'Atención');
        return;
    }

    // Filtrar solo las notas que fueron ingresadas
    const notasIngresadas = Object.keys(notas.value)
        .filter(alumnoId => notas.value[alumnoId].nota !== '')
        .map(alumnoId => ({
            alumno_id: parseInt(alumnoId),
            nota: parseFloat(notas.value[alumnoId].nota),
            observaciones: notas.value[alumnoId].observaciones || null
        }));

    if (notasIngresadas.length === 0) {
        await showAlert('Debes ingresar al menos una nota', 'Atención');
        return;
    }

    guardandoNotas.value = true;

    try {
        const response = await axios.post(route('expediente.guardar-notas'), {
            profesor_materia_id: materiaActual.value.materia_id,
            tipo_evaluacion: tipoEvaluacion.value,
            fecha: fechaNotas.value,
            notas: notasIngresadas
        });

        await showAlert(response.data.message, 'Éxito');
        cerrarModalNotas();
    } catch (error) {
        console.error(error);
        if (error.response && error.response.data && error.response.data.message) {
            await showAlert('Error: ' + error.response.data.message, 'Error');
        } else {
            await showAlert('Error al guardar las notas', 'Error');
        }
    } finally {
        guardandoNotas.value = false;
    }
};

// Funciones para modal de configuración académica
const abrirModalConfiguracion = (materia) => {
    materiaActual.value = materia;

    // Cargar configuración existente
    const config = materia.configuracion || {};
    configuracionActual.value = {
        nota_minima_promocion: config.nota_minima_promocion || 7.00,
        nota_minima_regularidad: config.nota_minima_regularidad || 4.00,
        permite_promocion: config.permite_promocion !== undefined ? config.permite_promocion : true,
        porcentaje_asistencia_minimo: config.porcentaje_asistencia_minimo || 75,
        criterios_evaluacion: config.criterios_evaluacion || ''
    };

    modalConfiguracionAbierto.value = true;
};

const cerrarModalConfiguracion = () => {
    modalConfiguracionAbierto.value = false;
    materiaActual.value = null;
};

const guardarConfiguracionAcademica = async () => {
    if (!materiaActual.value) {
        await showAlert('No se seleccionó ninguna materia', 'Error');
        return;
    }

    guardandoConfiguracion.value = true;

    try {
        const response = await axios.post(
            route('expediente.configurar-parametros', materiaActual.value.materia_id),
            configuracionActual.value
        );

        await showAlert(response.data.message, 'Éxito');

        // Recargar materias para obtener la configuración actualizada
        await cargarMaterias();

        cerrarModalConfiguracion();
    } catch (error) {
        console.error(error);
        if (error.response && error.response.data && error.response.data.error) {
            await showAlert('Error: ' + error.response.data.error, 'Error');
        } else {
            await showAlert('Error al guardar la configuración', 'Error');
        }
    } finally {
        guardandoConfiguracion.value = false;
    }
};

// Funciones para modal de calendario de asistencias
const verCalendarioAsistencia = async (materia) => {
    calendarioMateriaActual.value = materia;
    mesCalendario.value = new Date().toISOString().slice(0, 7);
    modalCalendarioAbierto.value = true;
    cargandoCalendario.value = true;

    try {
        const response = await axios.get(route('api.expediente.asistencias-diarias', materia.materia_id));
        calendarioData.value = response.data;
    } catch (error) {
        await showAlert('Error al cargar las asistencias', 'Error');
        modalCalendarioAbierto.value = false;
    } finally {
        cargandoCalendario.value = false;
    }
};

const cerrarModalCalendario = () => {
    modalCalendarioAbierto.value = false;
    calendarioMateriaActual.value = null;
    calendarioData.value = { fechas: {}, alumnos: [] };
};

const cambiarMesCalendario = (direccion) => {
    const [anio, mes] = mesCalendario.value.split('-').map(Number);
    const fecha = new Date(anio, mes - 1 + direccion, 1);
    mesCalendario.value = `${fecha.getFullYear()}-${String(fecha.getMonth() + 1).padStart(2, '0')}`;
};

// Función para mostrar el tour de ayuda
const mostrarAyuda = () => {
    startTour();
};

// Cargar materias al montar el componente (ya que materias es el tab por defecto)
onMounted(() => {
    cargarMaterias();
    cargarPeriodosProfesor();
    // Iniciar tour si es la primera vez
    startTourIfFirstTime();
});
</script>

<template>
    <Head title="Expediente Académico" />

    <SidebarLayout
        :user="$page.props.auth.user"
        :show-help-button="true"
        @show-help="mostrarAyuda"
    >
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Expediente Académico</h1>
                <p class="text-xs text-gray-400 mt-0.5">Bienvenido, {{ $page.props.auth.user.name }}</p>
            </div>
        </template>

        <div class="p-8 max-w-7xl mx-auto">
            <!-- Grid de opciones / Tabs -->
            <div class="onboarding-tabs grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div
                    v-for="(opcion, index) in opciones"
                    :key="index"
                    @click="cambiarTab(opcion.id)"
                    :class="[
                        'group cursor-pointer',
                        opcion.id === 'alumnos' ? 'onboarding-tab-alumnos' : '',
                        opcion.id === 'legajos' ? 'onboarding-tab-legajos' : ''
                    ]"
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
                            <i class="bx bx-book text-3xl text-orange-600 mr-3"></i>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Mis Materias</h2>
                                <p class="text-sm text-gray-600">Gestiona tus materias, asistencias y notas</p>
                            </div>
                        </div>
                        <!-- Selector de período -->
                        <div v-if="periodosDisponibles.length > 0" class="flex items-center gap-3">
                            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 gap-3">
                                <div class="flex items-center gap-2 text-gray-500">
                                    <i class="bx bx-calendar text-lg"></i>
                                    <span class="text-xs font-medium hidden sm:inline">Período</span>
                                </div>
                                <select
                                    :value="periodoActual"
                                    @change="cambiarPeriodo(Number($event.target.value))"
                                    class="bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-sm font-medium text-gray-800 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 cursor-pointer min-w-[220px] appearance-none"
                                    :style="{ backgroundImage: 'url(\'data:image/svg+xml;charset=UTF-8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%236b7280%22 stroke-width=%222%22%3E%3Cpolyline points=%226 9 12 15 18 9%22/%3E%3C/svg%3E\')', backgroundRepeat: 'no-repeat', backgroundPosition: 'right 8px center', backgroundSize: '16px', paddingRight: '32px' }"
                                >
                                    <option v-for="p in periodosDisponibles" :key="p.id" :value="p.id">
                                        {{ p.label }}
                                    </option>
                                </select>
                                <span v-if="!esArchivo" class="flex items-center gap-1 px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                    Activo
                                </span>
                                <span v-else class="flex items-center gap-1 px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full whitespace-nowrap">
                                    <i class="bx bx-archive-in text-xs"></i>
                                    Archivo
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Banner de archivo -->
                    <div v-if="esArchivo" class="mb-4 p-3 bg-amber-50 border border-amber-300 rounded-lg flex items-center">
                        <i class="bx bx-archive text-amber-600 text-xl mr-3"></i>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-amber-800">Estás viendo materias archivadas del período: {{ periodoSeleccionadoLabel }}</p>
                            <p class="text-xs text-amber-600">Los datos son de solo lectura. Las acciones de gestión están deshabilitadas.</p>
                        </div>
                        <span class="px-2 py-1 bg-amber-200 text-amber-800 text-xs font-semibold rounded-full">Archivo</span>
                    </div>

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
                    <div v-else-if="materiasData && materiasData.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-for="materia in materiasData" :key="materia.materia_id" class="onboarding-materia-card bg-white border-2 border-gray-200 rounded-lg p-5 hover:border-orange-400 hover:shadow-md transition-all">
                            <!-- Header de la materia -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ materia.materia }}</h3>
                                    <p class="text-sm text-gray-600">{{ materia.carrera }}</p>
                                </div>
                                <span class="bg-orange-100 text-orange-700 text-xs font-medium px-3 py-1 rounded-full">
                                    {{ materia.total_alumnos }} alumnos
                                </span>
                            </div>

                            <!-- Advertencia de configuración pendiente -->
                            <div v-if="!materia.configuracion?.configuracion_completa" class="mb-3 p-2 bg-yellow-50 border border-yellow-300 rounded-lg">
                                <div class="flex items-center text-yellow-800 text-xs">
                                    <i class="bx bx-warning mr-2 text-base"></i>
                                    <span><strong>Configuración pendiente:</strong> Define los parámetros académicos</span>
                                </div>
                            </div>

                            <!-- Detalles de la materia -->
                            <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <i class="bx bx-calendar text-orange-500 mr-2"></i>
                                    <span>Cursado: <strong>{{ materia.cursado }}</strong></span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="bx bx-group text-orange-500 mr-2"></i>
                                    <span>División: <strong>{{ materia.division }}</strong></span>
                                </div>
                            </div>

                            <!-- Parámetros configurados (si existe configuración) -->
                            <div v-if="materia.configuracion?.configuracion_completa" class="mb-3 p-2 bg-green-50 border border-green-200 rounded-lg">
                                <div class="grid grid-cols-2 gap-2 text-xs text-gray-700">
                                    <div class="flex items-center">
                                        <i class="bx bx-medal text-green-600 mr-1"></i>
                                        <span>Promoción: <strong>{{ materia.configuracion.nota_minima_promocion }}</strong></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="bx bx-check text-green-600 mr-1"></i>
                                        <span>Regular: <strong>{{ materia.configuracion.nota_minima_regularidad }}</strong></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex gap-2 pt-3 border-t border-gray-200">
                                <button
                                    @click="abrirModalConfiguracion(materia)"
                                    :disabled="esArchivo"
                                    :class="[
                                        'px-3 py-2 text-sm text-white rounded-lg transition-colors flex items-center justify-center',
                                        esArchivo ? 'bg-gray-300 cursor-not-allowed' : 'bg-amber-600 hover:bg-amber-700'
                                    ]"
                                    title="Configurar parámetros académicos"
                                >
                                    <i class="bx bx-cog mr-1"></i>
                                    Configurar
                                </button>
                                <a
                                    :href="route('profesor.mis-materias.show', materia.materia_id)"
                                    class="flex-1 px-3 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-center"
                                >
                                    <i class="bx bx-show mr-1"></i>
                                    Ver detalle
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Sin materias -->
                    <div v-else class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bx bx-book-open text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">No tienes materias asignadas</p>
                    </div>
                </div>

                <!-- Tab Alumnos -->
                <div v-if="tabActivo === 'alumnos'">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <i class="bx bx-group text-3xl text-teal-600 mr-3"></i>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Alumnos</h2>
                                <p class="text-sm text-gray-600">Estudiantes inscriptos en tus materias</p>
                            </div>
                        </div>
                        <!-- Selector de período -->
                        <div v-if="periodosDisponibles.length > 0" class="flex items-center gap-3">
                            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 gap-3">
                                <div class="flex items-center gap-2 text-gray-500">
                                    <i class="bx bx-calendar text-lg"></i>
                                    <span class="text-xs font-medium hidden sm:inline">Período</span>
                                </div>
                                <select
                                    :value="periodoActual"
                                    @change="cambiarPeriodo(Number($event.target.value))"
                                    class="bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-sm font-medium text-gray-800 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 cursor-pointer min-w-[220px] appearance-none"
                                    :style="{ backgroundImage: 'url(\'data:image/svg+xml;charset=UTF-8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%236b7280%22 stroke-width=%222%22%3E%3Cpolyline points=%226 9 12 15 18 9%22/%3E%3C/svg%3E\')', backgroundRepeat: 'no-repeat', backgroundPosition: 'right 8px center', backgroundSize: '16px', paddingRight: '32px' }"
                                >
                                    <option v-for="p in periodosDisponibles" :key="p.id" :value="p.id">
                                        {{ p.label }}
                                    </option>
                                </select>
                                <span v-if="!esArchivo" class="flex items-center gap-1 px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                    Activo
                                </span>
                                <span v-else class="flex items-center gap-1 px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full whitespace-nowrap">
                                    <i class="bx bx-archive-in text-xs"></i>
                                    Archivo
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Banner de archivo -->
                    <div v-if="esArchivo" class="mb-4 p-3 bg-amber-50 border border-amber-300 rounded-lg flex items-center">
                        <i class="bx bx-archive text-amber-600 text-xl mr-3"></i>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-amber-800">Estás viendo alumnos de un período archivado: {{ periodoSeleccionadoLabel }}</p>
                            <p class="text-xs text-amber-600">Solo puedes consultar datos. Las acciones de gestión están deshabilitadas.</p>
                        </div>
                        <span class="px-2 py-1 bg-amber-200 text-amber-800 text-xs font-semibold rounded-full">Archivo</span>
                    </div>

                    <!-- Estado de carga -->
                    <div v-if="cargandoAlumnos" class="text-center py-12">
                        <i class="bx bx-loader-alt animate-spin text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">Cargando alumnos...</p>
                    </div>

                    <!-- Error -->
                    <div v-else-if="errorAlumnos" class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center text-red-700">
                            <i class="bx bx-error-circle mr-2 text-xl"></i>
                            {{ errorAlumnos }}
                        </div>
                    </div>

                    <!-- Contenido de alumnos -->
                    <div v-else-if="alumnosData && alumnosData.length > 0">
                        <!-- Selector de materia -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar materia</label>
                            <select
                                v-model="materiaSeleccionada"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                            >
                                <option v-for="materia in alumnosData" :key="materia.materia_id" :value="materia.materia_id">
                                    {{ materia.materia }} - {{ materia.carrera }} ({{ materia.cursado }}) - División {{ materia.division }} ({{ materia.total_alumnos }} alumnos)
                                </option>
                            </select>
                        </div>

                        <!-- Lista de alumnos de la materia seleccionada -->
                        <div v-for="materia in alumnosData.filter(m => m.materia_id === materiaSeleccionada)" :key="materia.materia_id">
                            <!-- Información de la materia -->
                            <div class="bg-teal-50 border border-teal-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ materia.materia }}</h3>
                                        <p class="text-sm text-gray-600">{{ materia.carrera }} - Cursado {{ materia.cursado }} - División {{ materia.division }}</p>
                                    </div>
                                    <div class="flex gap-2 flex-wrap">
                                        <button
                                            @click="abrirModalAsistencia(materia)"
                                            :disabled="materia.tiene_asistencia_final || esArchivo"
                                            :title="esArchivo ? 'No disponible en modo archivo' : (materia.tiene_asistencia_final ? 'Ya existe carga final de asistencia' : 'Registrar asistencia del día')"
                                            :class="[
                                                'onboarding-btn-asistencia px-4 py-2 text-white rounded-lg transition-colors flex items-center',
                                                (materia.tiene_asistencia_final || esArchivo) ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'
                                            ]"
                                        >
                                            <i class="bx bx-calendar-check mr-2"></i>
                                            Asistencia Diaria
                                        </button>
                                        <button
                                            @click="verCalendarioAsistencia(materia)"
                                            class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors flex items-center"
                                            title="Ver historial de asistencias"
                                        >
                                            <i class="bx bx-calendar mr-2"></i>
                                            Ver Asistencias
                                        </button>
                                        <button
                                            @click="abrirModalNotas(materia)"
                                            :disabled="esArchivo"
                                            :class="[
                                                'onboarding-btn-notas px-4 py-2 text-white rounded-lg transition-colors flex items-center',
                                                esArchivo ? 'bg-gray-300 cursor-not-allowed' : 'bg-purple-600 hover:bg-purple-700'
                                            ]"
                                            :title="esArchivo ? 'No disponible en modo archivo' : 'Cargar notas temporales'"
                                        >
                                            <i class="bx bx-edit mr-2"></i>
                                            Cargar Notas
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabla de alumnos -->
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse bg-white border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">#</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">DNI</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Apellido</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Nombre</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Email</th>
                                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(alumno, index) in materia.alumnos" :key="alumno.id" class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ index + 1 }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ alumno.dni }}</td>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ alumno.apellido }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ alumno.nombre }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ alumno.email }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <button
                                                    @click="verExpedienteAlumno(alumno)"
                                                    class="px-3 py-1 text-xs bg-teal-100 text-teal-700 rounded hover:bg-teal-200 transition-colors"
                                                    title="Ver expediente del alumno"
                                                >
                                                    <i class="bx bx-show"></i>
                                                    Ver detalle
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Resumen -->
                            <div class="mt-4 text-sm text-gray-600">
                                Total de alumnos: <strong>{{ materia.total_alumnos }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Sin materias -->
                    <div v-else class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bx bx-user-plus text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">No tienes materias asignadas o no hay alumnos inscriptos</p>
                    </div>
                </div>

                <!-- Tab Legajos -->
                <div v-if="tabActivo === 'legajos'">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <i class="bx bx-file text-3xl text-gray-600 mr-3"></i>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Legajos de Alumnos</h2>
                                <p class="text-sm text-gray-600">Consulta los expedientes académicos de tus alumnos</p>
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

                    <!-- Información de seguridad -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start text-blue-800">
                            <i class="bx bx-info-circle mr-2 text-xl flex-shrink-0"></i>
                            <div class="text-sm">
                                <p><strong>Nota:</strong> Solo puedes consultar legajos de alumnos inscriptos en tus materias.</p>
                                <p class="mt-1 text-xs text-blue-600">Alumnos disponibles para consulta: <strong>{{ dniAlumnosPermitidos.length }}</strong></p>
                            </div>
                        </div>
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
                                maxlength="10"
                                pattern="[0-9]*"
                                inputmode="numeric"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                                :disabled="buscando"
                            />
                            <button
                                @click="buscarAlumno"
                                :disabled="buscando"
                                class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
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
                        <p class="text-sm text-gray-500 mt-2">Solo puedes consultar alumnos de tus materias</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Asistencia Diaria -->
        <div v-if="modalAsistenciaAbierto" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                <!-- Header del modal -->
                <div class="bg-blue-600 text-white px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Asistencia Diaria</h3>
                        <p class="text-sm text-blue-100 mt-1" v-if="materiaActual">
                            {{ materiaActual.materia }} - {{ materiaActual.carrera }}
                        </p>
                    </div>
                    <button @click="cerrarModalAsistencia" class="text-white hover:text-gray-200">
                        <i class="bx bx-x text-3xl"></i>
                    </button>
                </div>

                <!-- Contenido del modal -->
                <div class="flex-1 overflow-y-auto p-6">
                    <!-- Selector de fecha -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                        <input
                            v-model="fechaAsistencia"
                            type="date"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                    <!-- Tabla de alumnos -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse bg-white border border-gray-200">
                            <thead class="bg-gray-100 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">#</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Alumno</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Estado</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(alumno, index) in alumnosActual" :key="alumno.id" class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <div class="font-medium">{{ alumno.apellido }}, {{ alumno.nombre }}</div>
                                        <div class="text-xs text-gray-500">DNI: {{ alumno.dni }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <select
                                            v-model="asistencias[alumno.id].estado"
                                            class="px-3 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500"
                                        >
                                            <option value="presente">Presente</option>
                                            <option value="ausente">Ausente</option>
                                            <option value="tarde">Tarde</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input
                                            v-model="asistencias[alumno.id].observaciones"
                                            type="text"
                                            maxlength="500"
                                            placeholder="Opcional"
                                            class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer del modal -->
                <div class="bg-gray-100 px-6 py-4 flex justify-end gap-3">
                    <button
                        @click="cerrarModalAsistencia"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="guardarAsistenciaDiaria"
                        :disabled="guardandoAsistencia"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                    >
                        <i :class="['bx mr-2', guardandoAsistencia ? 'bx-loader-alt animate-spin' : 'bx-save']"></i>
                        {{ guardandoAsistencia ? 'Guardando...' : 'Guardar Asistencia' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de Cargar Notas -->
        <div v-if="modalNotasAbierto" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                <!-- Header del modal -->
                <div class="bg-purple-600 text-white px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Cargar Notas</h3>
                        <p class="text-sm text-purple-100 mt-1" v-if="materiaActual">
                            {{ materiaActual.materia }} - {{ materiaActual.carrera }}
                        </p>
                    </div>
                    <button @click="cerrarModalNotas" class="text-white hover:text-gray-200">
                        <i class="bx bx-x text-3xl"></i>
                    </button>
                </div>

                <!-- Contenido del modal -->
                <div class="flex-1 overflow-y-auto p-6">
                    <!-- Controles superiores -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Evaluación *</label>
                            <select
                                v-model="tipoEvaluacion"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                                <option value="">Seleccionar...</option>
                                <option value="Parcial 1">Parcial 1</option>
                                <option value="Parcial 2">Parcial 2</option>
                                <option value="Recuperatorio 1">Recuperatorio 1</option>
                                <option value="Recuperatorio 2">Recuperatorio 2</option>
                                <option value="Trabajo Práctico">Trabajo Práctico</option>
                                <option value="Final">Final</option>
                                <option value="Otra">Otra</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha *</label>
                            <input
                                v-model="fechaNotas"
                                type="date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            />
                        </div>
                    </div>

                    <!-- Tabla de alumnos -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse bg-white border border-gray-200">
                            <thead class="bg-gray-100 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">#</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Alumno</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border-b">Nota (1-10)</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(alumno, index) in alumnosActual" :key="alumno.id" class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <div class="font-medium">{{ alumno.apellido }}, {{ alumno.nombre }}</div>
                                        <div class="text-xs text-gray-500">DNI: {{ alumno.dni }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input
                                            v-model="notas[alumno.id].nota"
                                            type="number"
                                            min="1"
                                            max="10"
                                            step="0.01"
                                            placeholder="-"
                                            class="w-20 px-2 py-1 border border-gray-300 rounded text-sm text-center focus:ring-2 focus:ring-purple-500"
                                        />
                                    </td>
                                    <td class="px-4 py-3">
                                        <input
                                            v-model="notas[alumno.id].observaciones"
                                            type="text"
                                            maxlength="500"
                                            placeholder="Opcional"
                                            class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-purple-500"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Nota informativa -->
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start text-blue-800">
                            <i class="bx bx-info-circle mr-2 text-xl flex-shrink-0"></i>
                            <div class="text-sm space-y-1">
                                <p><strong>Importante:</strong></p>
                                <ul class="list-disc list-inside space-y-1 ml-2">
                                    <li><strong>Notas de Parciales/Prácticos:</strong> Se aprueban y transfieren automáticamente al legajo. El sistema calcula si el alumno promociona, queda regular o libre según tu configuración.</li>
                                    <li><strong>Notas de Final:</strong> Quedan pendientes de aprobación por el administrador o bedel antes de transferirse al legajo oficial.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer del modal -->
                <div class="bg-gray-100 px-6 py-4 flex justify-end gap-3">
                    <button
                        @click="cerrarModalNotas"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="guardarNotasTemporales"
                        :disabled="guardandoNotas"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                    >
                        <i :class="['bx mr-2', guardandoNotas ? 'bx-loader-alt animate-spin' : 'bx-save']"></i>
                        {{ guardandoNotas ? 'Guardando...' : 'Guardar Notas' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de Configuración Académica -->
        <div v-if="modalConfiguracionAbierto" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                <!-- Header del modal -->
                <div class="bg-amber-600 text-white px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Configuración Académica</h3>
                        <p class="text-sm text-amber-100 mt-1" v-if="materiaActual">
                            {{ materiaActual.materia }} - {{ materiaActual.carrera }}
                        </p>
                    </div>
                    <button @click="cerrarModalConfiguracion" class="text-white hover:text-gray-200">
                        <i class="bx bx-x text-3xl"></i>
                    </button>
                </div>

                <!-- Contenido del modal -->
                <div class="flex-1 overflow-y-auto p-6 space-y-5">
                    <!-- Información introductoria -->
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start text-blue-800">
                            <i class="bx bx-info-circle mr-2 text-xl flex-shrink-0"></i>
                            <div class="text-sm">
                                <p><strong>Define los parámetros académicos</strong> para esta materia. Estos valores se usarán para calcular automáticamente si los alumnos promocionan, quedan regulares o libres.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Nota mínima de promoción -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="bx bx-medal text-amber-600 mr-1"></i>
                            Nota mínima para Promoción
                        </label>
                        <input
                            v-model.number="configuracionActual.nota_minima_promocion"
                            type="number"
                            min="1"
                            max="10"
                            step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            placeholder="Ejemplo: 7.00"
                        />
                        <p class="mt-1 text-xs text-gray-500">El alumno promociona la materia sin rendir final si obtiene esta nota o superior.</p>
                    </div>

                    <!-- Nota mínima de regularidad -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="bx bx-check text-green-600 mr-1"></i>
                            Nota mínima para Regularidad
                        </label>
                        <input
                            v-model.number="configuracionActual.nota_minima_regularidad"
                            type="number"
                            min="1"
                            max="10"
                            step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            placeholder="Ejemplo: 4.00"
                        />
                        <p class="mt-1 text-xs text-gray-500">El alumno queda regular si obtiene esta nota o superior (debe rendir final). Si saca menos, queda libre.</p>
                    </div>

                    <!-- Permite promoción -->
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input
                                v-model="configuracionActual.permite_promocion"
                                type="checkbox"
                                class="w-5 h-5 text-amber-600 border-gray-300 rounded focus:ring-amber-500 focus:ring-2"
                            />
                            <span class="ml-3 text-sm font-semibold text-gray-700">
                                <i class="bx bx-trending-up text-amber-600 mr-1"></i>
                                Permite promoción directa
                            </span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500 ml-8">Si está desmarcado, todos los alumnos deberán rendir final obligatoriamente (sin importar la nota de cursada).</p>
                    </div>

                    <!-- Porcentaje de asistencia -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="bx bx-calendar-check text-blue-600 mr-1"></i>
                            Porcentaje mínimo de Asistencia (%)
                        </label>
                        <input
                            v-model.number="configuracionActual.porcentaje_asistencia_minimo"
                            type="number"
                            min="0"
                            max="100"
                            step="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            placeholder="Ejemplo: 75"
                        />
                        <p class="mt-1 text-xs text-gray-500">Porcentaje mínimo de asistencia requerido para regularizar la materia.</p>
                    </div>

                    <!-- Criterios de evaluación -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="bx bx-list-ul text-purple-600 mr-1"></i>
                            Criterios de Evaluación (Opcional)
                        </label>
                        <textarea
                            v-model="configuracionActual.criterios_evaluacion"
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent resize-none"
                            placeholder="Ejemplo: 2 parciales + trabajos prácticos. Cada parcial vale 40% y los prácticos 20%."
                        ></textarea>
                        <p class="mt-1 text-xs text-gray-500">Describe cómo se compone la nota final de la materia (opcional, solo informativo).</p>
                    </div>

                    <!-- Resumen visual -->
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Resumen de configuración:</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li class="flex items-center">
                                <i class="bx bx-medal text-amber-600 mr-2"></i>
                                <span>Nota ≥ <strong>{{ configuracionActual.nota_minima_promocion }}</strong> → Promociona ({{ configuracionActual.permite_promocion ? 'sin final' : 'debe rendir final' }})</span>
                            </li>
                            <li class="flex items-center">
                                <i class="bx bx-check text-green-600 mr-2"></i>
                                <span>Nota ≥ <strong>{{ configuracionActual.nota_minima_regularidad }}</strong> → Regular (debe rendir final)</span>
                            </li>
                            <li class="flex items-center">
                                <i class="bx bx-x text-red-600 mr-2"></i>
                                <span>Nota < <strong>{{ configuracionActual.nota_minima_regularidad }}</strong> → Libre</span>
                            </li>
                            <li class="flex items-center">
                                <i class="bx bx-calendar-check text-blue-600 mr-2"></i>
                                <span>Asistencia mínima: <strong>{{ configuracionActual.porcentaje_asistencia_minimo }}%</strong></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Footer del modal -->
                <div class="bg-gray-100 px-6 py-4 flex justify-end gap-3">
                    <button
                        @click="cerrarModalConfiguracion"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="guardarConfiguracionAcademica"
                        :disabled="guardandoConfiguracion"
                        class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                    >
                        <i :class="['bx mr-2', guardandoConfiguracion ? 'bx-loader-alt animate-spin' : 'bx-save']"></i>
                        {{ guardandoConfiguracion ? 'Guardando...' : 'Guardar Configuración' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Expediente Alumno -->
        <div v-if="modalExpedienteAbierto" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                <!-- Header -->
                <div class="bg-teal-600 px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">Expediente del Alumno</h3>
                        <p v-if="expedienteAlumno" class="text-teal-100 text-sm">{{ expedienteAlumno.alumno.nombre_completo }}</p>
                    </div>
                    <button @click="cerrarModalExpediente" class="text-white hover:text-gray-200">
                        <i class="bx bx-x text-2xl"></i>
                    </button>
                </div>

                <!-- Contenido -->
                <div class="flex-1 overflow-y-auto p-6">
                    <!-- Loading -->
                    <div v-if="cargandoExpediente" class="text-center py-12">
                        <i class="bx bx-loader-alt animate-spin text-4xl text-teal-600"></i>
                        <p class="text-gray-600 mt-2">Cargando expediente...</p>
                    </div>

                    <div v-else-if="expedienteAlumno">
                        <!-- Datos del alumno -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500 font-semibold">DNI</p>
                                <p class="text-sm font-bold text-gray-900">{{ expedienteAlumno.alumno.dni }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500 font-semibold">Legajo</p>
                                <p class="text-sm font-bold text-gray-900">{{ expedienteAlumno.alumno.legajo || 'Sin asignar' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500 font-semibold">Carrera</p>
                                <p class="text-sm font-bold text-gray-900">{{ expedienteAlumno.carrera?.nombre || 'Sin carrera' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500 font-semibold">Curso</p>
                                <p class="text-sm font-bold text-gray-900">{{ expedienteAlumno.alumno.curso ? `${expedienteAlumno.alumno.curso}° - Div. ${expedienteAlumno.alumno.division}` : '-' }}</p>
                            </div>
                        </div>

                        <!-- Estadísticas -->
                        <div class="grid grid-cols-4 gap-3 mb-5">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-blue-700">{{ expedienteAlumno.estadisticas.total_materias }}</p>
                                <p class="text-xs text-blue-600">Total</p>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-green-700">{{ expedienteAlumno.estadisticas.aprobadas }}</p>
                                <p class="text-xs text-green-600">Aprobadas</p>
                            </div>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-yellow-700">{{ expedienteAlumno.estadisticas.regulares }}</p>
                                <p class="text-xs text-yellow-600">Regulares</p>
                            </div>
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-purple-700">{{ expedienteAlumno.estadisticas.promedio ?? '-' }}</p>
                                <p class="text-xs text-purple-600">Promedio</p>
                            </div>
                        </div>

                        <!-- Barra de progreso -->
                        <div class="mb-5">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Progreso académico</span>
                                <span>{{ expedienteAlumno.estadisticas.porcentaje_progreso }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-teal-500 h-2 rounded-full transition-all" :style="{ width: expedienteAlumno.estadisticas.porcentaje_progreso + '%' }"></div>
                            </div>
                        </div>

                        <!-- Historial de materias -->
                        <h4 class="text-sm font-bold text-gray-700 mb-2">Historial Académico</h4>
                        <div v-if="expedienteAlumno.historial.length > 0" class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Materia</th>
                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600">Año</th>
                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600">Nota</th>
                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600">Estado</th>
                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="materia in expedienteAlumno.historial" :key="materia.id" class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="px-3 py-2 text-gray-900">{{ materia.materia.nombre }}</td>
                                        <td class="px-3 py-2 text-center text-gray-600">{{ materia.materia.anno }}°</td>
                                        <td class="px-3 py-2 text-center font-semibold" :class="materia.nota_final >= 4 ? 'text-green-700' : 'text-red-600'">
                                            {{ materia.nota_final || '-' }}
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <span v-if="materia.rendida" class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700">Aprobada</span>
                                            <span v-else-if="materia.cursada" class="px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-700">Regular</span>
                                            <span v-else class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">Pendiente</span>
                                        </td>
                                        <td class="px-3 py-2 text-center text-gray-500 text-xs">{{ materia.fecha || '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="text-center py-6 bg-gray-50 rounded-lg">
                            <p class="text-gray-500 text-sm">Este alumno aún no tiene materias registradas</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-100 px-6 py-3 flex justify-end">
                    <button @click="cerrarModalExpediente" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Calendario de Asistencias -->
        <div v-if="modalCalendarioAbierto" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-[95vw] w-full max-h-[90vh] overflow-hidden flex flex-col">
                <!-- Header -->
                <div class="bg-teal-600 text-white px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Calendario de Asistencias</h3>
                        <p class="text-sm text-teal-100 mt-1" v-if="calendarioMateriaActual">
                            {{ calendarioMateriaActual.materia }} - {{ calendarioMateriaActual.carrera }}
                        </p>
                    </div>
                    <button @click="cerrarModalCalendario" class="text-white hover:text-gray-200">
                        <i class="bx bx-x text-3xl"></i>
                    </button>
                </div>

                <!-- Contenido -->
                <div class="flex-1 overflow-y-auto p-6">
                    <!-- Loading -->
                    <div v-if="cargandoCalendario" class="text-center py-12">
                        <i class="bx bx-loader-alt animate-spin text-4xl text-teal-600"></i>
                        <p class="text-gray-600 mt-2">Cargando asistencias...</p>
                    </div>

                    <!-- Calendario -->
                    <AsistenciaCalendario
                        v-else
                        :asistencias="calendarioData.fechas"
                        :alumnos="calendarioData.alumnos"
                        :mes="mesCalendario"
                        @cambiar-mes="cambiarMesCalendario"
                    />
                </div>

                <!-- Footer -->
                <div class="bg-gray-100 px-6 py-3 flex justify-end">
                    <button @click="cerrarModalCalendario" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>

        <!-- Dialog component -->
        <Dialog />
    </SidebarLayout>
</template>