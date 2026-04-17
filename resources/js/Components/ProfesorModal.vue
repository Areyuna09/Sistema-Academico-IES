<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    profesor: {
        type: Object,
        default: null
    },
    carreras: {
        type: Array,
        required: true
    },
    periodoActivo: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close', 'saved']);

const form = useForm({
    dni: '',
    apellido: '',
    nombre: '',
    email: '',
    carrera: '',
    division: '1',      // se mantiene para compatibilidad con el modelo Profesor
    materias: [],       // IDs seleccionados (para los checkboxes)
    asignaciones: [],   // [{ materia_id, division }] — lo que procesa el controller
});

const profesorActual = ref(null);
const periodoActivoCargado = ref(null);
const materiasDisponibles = ref([]);
const cargandoMaterias = ref(false);

// { [materia_id]: '1' | '2' | 'ambas' }
const divisionesPorMateria = ref({});

const periodoActivoEfectivo = computed(() => props.periodoActivo || periodoActivoCargado.value);

const periodoActivoLabel = computed(() => {
    const p = periodoActivoEfectivo.value;
    if (!p) return null;
    const cuatri = p.cuatrimestre == '1' ? '1er' : '2do';
    return `${cuatri} Cuatrimestre ${p.anio}`;
});

const materiasAgrupadasPorPlan = computed(() => {
    const grupos = {};

    materiasDisponibles.value.forEach(materia => {
        if (materia.planes && materia.planes.length > 0) {
            materia.planes.forEach(plan => {
                if (!grupos[plan.id]) {
                    grupos[plan.id] = { plan: plan, materias: [] };
                }
                grupos[plan.id].materias.push(materia);
            });
        } else {
            if (!grupos['sin_plan']) {
                grupos['sin_plan'] = {
                    plan: { id: null, nombre: 'Sin plan asignado', vigente: false },
                    materias: []
                };
            }
            grupos['sin_plan'].materias.push(materia);
        }
    });

    return Object.values(grupos).sort((a, b) => {
        if (a.plan.id === null) return 1;
        if (b.plan.id === null) return -1;
        if (a.plan.vigente && !b.plan.vigente) return -1;
        if (!a.plan.vigente && b.plan.vigente) return 1;
        return (b.plan.anio || 0) - (a.plan.anio || 0);
    });
});

// ── Recalcular asignaciones cada vez que cambian materias o divisiones ────────
const recalcularAsignaciones = () => {
    const asigs = [];
    for (const materiaId of form.materias) {
        const div = divisionesPorMateria.value[materiaId] ?? '1';
        if (div === 'ambas') {
            asigs.push({ materia_id: materiaId, division: '1' });
            asigs.push({ materia_id: materiaId, division: '2' });
        } else {
            asigs.push({ materia_id: materiaId, division: div });
        }
    }
    form.asignaciones = asigs;
    form.division = asigs[0]?.division ?? '1';
};

const cambiarDivision = (materiaId, valor) => {
    // Reasignar objeto completo para forzar reactividad en Vue 3
    divisionesPorMateria.value = { ...divisionesPorMateria.value, [String(materiaId)]: valor };
    recalcularAsignaciones();
};

// ── Watch: cuando cambia la prop profesor (abrir modal en edición) ────────────
watch(() => props.profesor, (nuevoProfesor) => {
    console.log('ProfesorModal - profesor cambiado:', nuevoProfesor);
    if (nuevoProfesor) {
        profesorActual.value = nuevoProfesor;
        form.dni      = nuevoProfesor.dni || '';
        form.apellido = nuevoProfesor.apellido || '';
        form.nombre   = nuevoProfesor.nombre || '';
        form.email    = nuevoProfesor.email || '';
        form.carrera  = (typeof nuevoProfesor.carrera === 'object' && nuevoProfesor.carrera?.Id)
            ? nuevoProfesor.carrera.Id
            : (nuevoProfesor.carrera || '');

        // Reconstruir divisiones por materia desde asignaciones guardadas
        // El AdminPanel puede pasar nuevoProfesor.asignaciones ([{ materia_id, division }])
        // o el formato viejo con materias[] + division único
        const nuevasDivisiones = {};

        if (nuevoProfesor.asignaciones && nuevoProfesor.asignaciones.length > 0) {
            // Nuevo formato: agrupar por materia para detectar "ambas"
            const porMateria = {};
            nuevoProfesor.asignaciones.forEach(a => {
                const id = String(a.materia_id);
                if (!porMateria[id]) porMateria[id] = new Set();
                porMateria[id].add(String(a.division));
            });
            const ids = [];
            for (const [id, divs] of Object.entries(porMateria)) {
                ids.push(Number(id));
                nuevasDivisiones[id] = divs.has('1') && divs.has('2')
                    ? 'ambas'
                    : (divs.has('2') ? '2' : '1');
            }
            form.materias = ids;
        } else {
            // Formato viejo: materias[] con división única
            form.materias = nuevoProfesor.materias || [];
            const divUnica = String(nuevoProfesor.division || '1');
            form.materias.forEach(id => {
                nuevasDivisiones[String(id)] = divUnica;
            });
        }

        // Asignar objeto nuevo de una vez para disparar reactividad
        divisionesPorMateria.value = nuevasDivisiones;

        recalcularAsignaciones();
        console.log('ProfesorModal - materias cargadas:', form.materias);
        console.log('ProfesorModal - carrera ID:', form.carrera);
    } else {
        profesorActual.value = null;
        divisionesPorMateria.value = {};
        form.reset();
        materiasDisponibles.value = [];
    }
}, { immediate: true });

// ── Watch: cuando cambia la carrera, recargar materias ───────────────────────
watch(() => form.carrera, async (nuevaCarrera) => {
    if (!nuevaCarrera) {
        materiasDisponibles.value = [];
        form.materias = [];
        form.asignaciones = [];
        divisionesPorMateria.value = {};
        return;
    }

    cargandoMaterias.value = true;
    try {
        const response = await axios.get(route('expediente.materias-disponibles'), {
            params: { carrera_id: nuevaCarrera }
        });
        materiasDisponibles.value = response.data;
        // Cuando se recarga la lista (cambio de carrera), recalcular por si
        // había materias preseleccionadas en edición
        recalcularAsignaciones();
    } catch (error) {
        console.error('Error al cargar materias:', error);
    } finally {
        cargandoMaterias.value = false;
    }
});

// Cuando se tilda/destilda una materia, inicializar su división si es nueva
watch(() => [...form.materias], (nuevas) => {
    let cambio = false;
    const copia = { ...divisionesPorMateria.value };
    for (const id of nuevas) {
        if (!(String(id) in copia)) {
            copia[String(id)] = '1';
            cambio = true;
        }
    }
    if (cambio) divisionesPorMateria.value = copia;
    recalcularAsignaciones();
}, { deep: true });

// ── Submit ────────────────────────────────────────────────────────────────────
const submit = () => {
    console.log('=== SUBMIT PROFESOR ===');
    console.log('props.profesor:', props.profesor);
    console.log('profesorActual.value:', profesorActual.value);
    console.log('profesorActual.value?.id:', profesorActual.value?.id);

    const url = profesorActual.value
        ? route('profesores.update', profesorActual.value.id)
        : route('profesores.store');

    const method = profesorActual.value ? 'put' : 'post';

    console.log('URL:', url);
    console.log('Method:', method);
    console.log('Form data:', form.data());

    form[method](url, {
        preserveScroll: true,
        onSuccess: () => {
            emit('saved');
            emit('close');
            form.reset();
            materiasDisponibles.value = [];
            divisionesPorMateria.value = {};
            profesorActual.value = null;
        },
        onError: (errors) => {
            console.error('Errores al guardar profesor:', errors);
        }
    });
};

const close = () => {
    form.reset();
    form.clearErrors();
    materiasDisponibles.value = [];
    divisionesPorMateria.value = {};
    profesorActual.value = null;
    emit('close');
};
</script>

<template>
    <!-- Backdrop -->
    <Transition
        enter-active-class="transition-opacity duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="show"
            class="fixed inset-0 bg-black bg-opacity-50 z-40"
            @click="close"
        ></div>
    </Transition>

    <!-- Modal -->
    <Transition
        enter-active-class="transition-all duration-200"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition-all duration-200"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div
            v-if="show"
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
        >
            <div
                class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
                @click.stop
            >
                <!-- Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ profesorActual ? 'Editar Profesor' : 'Nuevo Profesor' }}
                    </h2>
                    <button @click="close" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bx bx-x text-3xl"></i>
                    </button>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="p-6">

                    <!-- Datos personales -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Datos Personales</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">DNI <span class="text-red-500">*</span></label>
                                <input v-model="form.dni" type="text" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.dni }" />
                                <div v-if="form.errors.dni" class="mt-1 text-sm text-red-600">{{ form.errors.dni }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input v-model="form.email" type="email"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.email }" />
                                <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Apellido <span class="text-red-500">*</span></label>
                                <input v-model="form.apellido" type="text" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.apellido }" />
                                <div v-if="form.errors.apellido" class="mt-1 text-sm text-red-600">{{ form.errors.apellido }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                                <input v-model="form.nombre" type="text" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.nombre }" />
                                <div v-if="form.errors.nombre" class="mt-1 text-sm text-red-600">{{ form.errors.nombre }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Asignación de materias -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Asignación de Materias</h3>

                        <!-- Info período activo -->
                        <div v-if="periodoActivoLabel" class="mb-4 p-3 bg-indigo-50 border border-indigo-200 rounded-lg flex items-center">
                            <i class="bx bx-calendar text-indigo-600 mr-2 text-lg"></i>
                            <p class="text-sm text-indigo-800">
                                Las materias se asignarán al período: <strong>{{ periodoActivoLabel }}</strong>
                            </p>
                        </div>
                        <div v-else class="mb-4 p-3 bg-yellow-50 border border-yellow-300 rounded-lg flex items-center">
                            <i class="bx bx-warning text-yellow-600 mr-2 text-lg"></i>
                            <p class="text-sm text-yellow-800">
                                <strong>Atención:</strong> No hay un período activo configurado. Las materias se asignarán sin período asociado.
                            </p>
                        </div>

                        <!-- Solo carrera — división va por materia -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Carrera <span class="text-red-500">*</span></label>
                            <select v-model="form.carrera" required
                                class="w-full md:w-1/2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :class="{ 'border-red-500': form.errors.carrera }">
                                <option value="">Seleccione una carrera</option>
                                <option v-for="carrera in carreras" :key="carrera.Id" :value="carrera.Id">
                                    {{ carrera.nombre }}
                                </option>
                            </select>
                            <div v-if="form.errors.carrera" class="mt-1 text-sm text-red-600">{{ form.errors.carrera }}</div>
                        </div>

                        <!-- Materias agrupadas por plan, con selector de división por fila -->
                        <div v-if="form.carrera">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700">Materias a dictar</label>
                                <span v-if="form.asignaciones.length" class="text-xs text-blue-600 font-medium">
                                    {{ form.asignaciones.length }} asignación(es) configurada(s)
                                </span>
                            </div>

                            <div v-if="cargandoMaterias" class="text-sm text-gray-500 py-4">Cargando materias...</div>
                            <div v-else-if="materiasDisponibles.length === 0" class="text-sm text-gray-500 py-4">
                                No hay materias disponibles para esta carrera
                            </div>

                            <div v-else class="space-y-3 max-h-96 overflow-y-auto">
                                <div
                                    v-for="grupo in materiasAgrupadasPorPlan"
                                    :key="grupo.plan.id || 'sin_plan'"
                                    class="border rounded-lg overflow-hidden"
                                    :class="grupo.plan.id === null ? 'border-yellow-300' : (grupo.plan.vigente ? 'border-blue-300' : 'border-gray-300')"
                                >
                                    <!-- Header del plan -->
                                    <div
                                        class="px-3 py-2 font-medium text-sm flex items-center justify-between"
                                        :class="grupo.plan.id === null ? 'bg-yellow-50 text-yellow-900' : (grupo.plan.vigente ? 'bg-blue-50 text-blue-900' : 'bg-gray-50 text-gray-900')"
                                    >
                                        <span>
                                            {{ grupo.plan.nombre }}
                                            <span v-if="grupo.plan.vigente" class="ml-1" title="Plan vigente para nuevos inscriptos">✓</span>
                                        </span>
                                        <span class="text-xs font-normal opacity-75">
                                            {{ grupo.materias.length }} {{ grupo.materias.length === 1 ? 'materia' : 'materias' }}
                                        </span>
                                    </div>

                                    <!-- Lista de materias -->
                                    <div class="divide-y divide-gray-200">
                                        <div
                                            v-for="materia in grupo.materias"
                                            :key="materia.id"
                                            class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 transition-colors"
                                            :class="{ 'bg-blue-50': form.materias.includes(materia.id) }"
                                        >
                                            <!-- Checkbox -->
                                            <input
                                                :id="'materia-' + materia.id + '-plan-' + (grupo.plan.id || 'sin_plan')"
                                                v-model="form.materias"
                                                :value="materia.id"
                                                type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 shrink-0"
                                            />

                                            <!-- Nombre + año -->
                                            <label
                                                :for="'materia-' + materia.id + '-plan-' + (grupo.plan.id || 'sin_plan')"
                                                class="text-sm cursor-pointer flex-1"
                                                :class="form.materias.includes(materia.id) ? 'font-medium text-blue-800' : 'text-gray-700'"
                                            >
                                                <div>{{ materia.nombre }}</div>
                                                <div class="text-xs text-gray-500 mt-0.5">
                                                    {{ materia.anno }}° Año — Cuatrimestre {{ materia.semestre }}
                                                </div>
                                            </label>

                                            <!-- Selector de división — solo si está seleccionada -->
                                            <div class="shrink-0">
                                                <div v-if="form.materias.includes(materia.id)" class="flex gap-1">
                                                    <button
                                                        v-for="opcion in ['1', '2', 'ambas']"
                                                        :key="opcion"
                                                        type="button"
                                                        @click.prevent="cambiarDivision(materia.id, opcion)"
                                                        :title="opcion === 'ambas' ? 'Crea dos asignaciones: Div.1 y Div.2' : 'División ' + opcion"
                                                        :class="[
                                                            'px-2 py-1 text-xs rounded border font-medium transition-colors',
                                                            divisionesPorMateria[String(materia.id)] === opcion
                                                                ? 'bg-blue-600 text-white border-blue-600'
                                                                : 'bg-white text-gray-600 border-gray-300 hover:border-blue-400 hover:text-blue-600'
                                                        ]"
                                                    >
                                                        {{ opcion === 'ambas' ? '1 y 2' : 'D' + opcion }}
                                                    </button>
                                                </div>
                                                <span v-else class="text-xs text-gray-300">—</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <p class="mt-2 text-xs text-gray-500">
                                Tildá las materias y elegí la división. Con <strong>"1 y 2"</strong> se crean dos asignaciones separadas para esa materia.
                            </p>
                        </div>

                        <div v-else class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <i class="bx bx-info-circle mr-2"></i>
                                Primero seleccione una carrera para ver las materias disponibles
                            </p>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" @click="close"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="form.processing"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <span v-if="form.processing">Guardando...</span>
                            <span v-else>{{ profesorActual ? 'Actualizar' : 'Crear' }} Profesor</span>
                        </button>
                    </div>

                    <div v-if="form.errors.error" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-800 text-sm">{{ form.errors.error }}</p>
                    </div>
                </form>
            </div>
        </div>
    </Transition>
</template>