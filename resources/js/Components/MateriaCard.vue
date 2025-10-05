<script setup>
import { computed } from 'vue';

const props = defineProps({
    materia: {
        type: Object,
        required: true
    },
    correlativas: {
        type: Object,
        default: () => ({ cumplidas: [], faltantes: [] })
    },
    seleccionada: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['toggle-seleccion']);

const puedeInscribirse = computed(() => {
    return props.materia.puede_cursar && props.correlativas.faltantes.length === 0;
});

const estadoClasses = computed(() => {
    // Si ya está inscrito
    if (props.materia.ya_inscrito) {
        return {
            border: 'border-green-300',
            bg: 'bg-green-50',
            badge: 'bg-green-500 text-white',
            badgeText: 'Ya inscrito',
            icon: 'bx-check-double'
        };
    }

    if (!puedeInscribirse.value) {
        return {
            border: 'border-red-200',
            bg: 'bg-red-50',
            badge: 'bg-red-100 text-red-700',
            badgeText: 'No disponible',
            icon: 'bx-x'
        };
    }
    if (props.seleccionada) {
        return {
            border: 'border-blue-500',
            bg: 'bg-blue-50',
            badge: 'bg-blue-100 text-blue-700',
            badgeText: 'Seleccionada',
            icon: 'bx-check-circle'
        };
    }
    return {
        border: 'border-gray-200',
        bg: 'bg-white',
        badge: 'bg-green-100 text-green-700',
        badgeText: 'Disponible',
        icon: 'bx-check'
    };
});

const estaCorrelativaCumplida = (correlativa) => {
    // Verificar si la correlativa está en la lista de cumplidas
    // Buscar por ID (más confiable) o por nombre
    return props.correlativas.cumplidas.some(corr => {
        // Si tenemos ID, comparar por ID
        if (correlativa.id && corr.materia_id) {
            return corr.materia_id === correlativa.id;
        }
        // Si no, comparar por nombre (case insensitive)
        const nombreCumplida = corr.materia_nombre?.toLowerCase().trim();
        const nombreBuscado = correlativa.nombre?.toLowerCase().trim();
        return nombreCumplida === nombreBuscado;
    });
};
</script>

<template>
    <div
        :class="[
            'rounded-lg border-2 p-4 transition-all duration-200',
            estadoClasses.border,
            estadoClasses.bg,
            seleccionada ? 'shadow-lg' : 'shadow',
            puedeInscribirse && !materia.ya_inscrito ? 'hover:shadow-md cursor-pointer' : 'opacity-75'
        ]"
        @click="puedeInscribirse && !materia.ya_inscrito && emit('toggle-seleccion', materia)"
    >
        <!-- Header: Nombre + Badge -->
        <div class="flex items-start justify-between gap-2 mb-3">
            <h3 class="text-base font-semibold text-gray-900 flex-1 leading-tight">
                {{ materia.nombre }}
            </h3>
            <span
                :class="[
                    'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium flex-shrink-0',
                    estadoClasses.badge
                ]"
            >
                <i
                    :class="[
                        'bx text-sm mr-1',
                        estadoClasses.icon
                    ]"
                ></i>
                {{ estadoClasses.badgeText }}
            </span>
        </div>

        <!-- Información compacta -->
        <div class="space-y-2 text-sm text-gray-600">
            <!-- Año y Semestre -->
            <div v-if="materia.anno || materia.semestre" class="flex items-center gap-4">
                <div v-if="materia.anno" class="flex items-center">
                    <i class="bx bx-book text-gray-400 mr-1.5"></i>
                    <span>{{ materia.anno }}° Año</span>
                </div>
                <div v-if="materia.semestre" class="flex items-center">
                    <i class="bx bx-time text-gray-400 mr-1.5"></i>
                    <span>{{ materia.semestre }}° Cuatr.</span>
                </div>
            </div>

            <!-- Profesores -->
            <div v-if="materia.profesores && materia.profesores.length > 0" class="flex items-start">
                <i class="bx bx-user text-gray-400 mr-1.5 mt-0.5"></i>
                <span class="flex-1 leading-tight">
                    {{ materia.profesores.join(', ') }}
                </span>
            </div>

            <!-- Correlativas cumplidas -->
            <div v-if="correlativas.cumplidas && correlativas.cumplidas.length > 0" class="pt-2 border-t border-gray-200">
                <p class="text-xs font-semibold text-green-700 mb-2 flex items-center">
                    <i class="bx bx-check-circle text-green-600 mr-1.5"></i>
                    Correlativas cumplidas:
                </p>
                <div class="space-y-1">
                    <div
                        v-for="(corr, index) in correlativas.cumplidas"
                        :key="index"
                        class="flex items-center text-xs p-2 rounded-md bg-green-50 border border-green-200"
                    >
                        <i class="bx bx-check text-green-600 text-base mr-2 flex-shrink-0"></i>
                        <div class="flex-1">
                            <span class="font-medium text-gray-800">{{ corr.materia_nombre }}</span>
                            <span
                                :class="[
                                    'ml-2 px-1.5 py-0.5 rounded text-xs font-medium',
                                    corr.estado === 'aprobada' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'
                                ]"
                            >
                                {{ corr.estado === 'aprobada' ? 'Aprobada' : 'Regular' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Correlativas faltantes -->
            <div v-if="correlativas.faltantes && correlativas.faltantes.length > 0" class="pt-2 border-t border-red-200">
                <p class="text-xs font-semibold text-red-700 mb-2 flex items-center">
                    <i class="bx bx-x-circle text-red-600 mr-1.5"></i>
                    Correlativas que te faltan:
                </p>
                <div class="space-y-1">
                    <div
                        v-for="(corr, index) in correlativas.faltantes"
                        :key="index"
                        class="flex items-center text-xs p-2 rounded-md bg-red-50 border border-red-200"
                    >
                        <i class="bx bx-x text-red-500 text-base mr-2 flex-shrink-0"></i>
                        <div class="flex-1">
                            <span class="font-medium text-gray-800">{{ corr.materia_nombre }}</span>
                            <span class="ml-2 px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">
                                {{ corr.estado_requerido === 'aprobada' ? 'Debe estar Aprobada' : 'Debe estar Regular' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Mensaje de resumen -->
                <div class="mt-2 p-2 bg-amber-50 border border-amber-300 rounded-md">
                    <div class="flex items-start">
                        <i class="bx bx-error-circle text-amber-600 text-base mr-2 flex-shrink-0"></i>
                        <p class="text-xs text-amber-900 font-semibold">
                            No puedes inscribirte: te {{ correlativas.faltantes.length === 1 ? 'falta' : 'faltan' }} {{ correlativas.faltantes.length }} correlativa{{ correlativas.faltantes.length > 1 ? 's' : '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkbox de selección (solo si puede inscribirse y NO está inscrito) -->
        <div v-if="puedeInscribirse && !materia.ya_inscrito" class="mt-3 pt-3 border-t border-gray-200">
            <label class="flex items-center cursor-pointer group">
                <input
                    type="checkbox"
                    :checked="seleccionada"
                    @change="emit('toggle-seleccion', materia)"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                    @click.stop
                />
                <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-blue-600">
                    {{ seleccionada ? 'Materia seleccionada' : 'Seleccionar materia' }}
                </span>
            </label>
        </div>

        <!-- Mensaje para materias ya inscritas -->
        <div v-else-if="materia.ya_inscrito" class="mt-3 pt-3 border-t border-green-200">
            <div class="flex items-center text-green-700">
                <i class="bx bx-check-double text-lg mr-2"></i>
                <span class="text-sm font-medium">Ya estás inscrito en esta materia</span>
            </div>
        </div>
    </div>
</template>
