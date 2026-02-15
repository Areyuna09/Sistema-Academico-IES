<script setup>
import { computed } from 'vue';

const props = defineProps({
    asistencias: {
        type: Object,
        default: () => ({})
    },
    alumnos: {
        type: Array,
        default: () => []
    },
    mes: {
        type: String,
        required: true // formato 'YYYY-MM'
    }
});

const emit = defineEmits(['cambiar-mes']);

const nombresMeses = [
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];

const mesLabel = computed(() => {
    const [anio, mesNum] = props.mes.split('-').map(Number);
    return `${nombresMeses[mesNum - 1]} ${anio}`;
});

// Generar los dias hábiles (Lun-Vie) del mes
const diasDelMes = computed(() => {
    const [anio, mesNum] = props.mes.split('-').map(Number);
    const dias = [];
    const totalDias = new Date(anio, mesNum, 0).getDate();

    for (let d = 1; d <= totalDias; d++) {
        const fecha = new Date(anio, mesNum - 1, d);
        const diaSemana = fecha.getDay(); // 0=Dom, 1=Lun...6=Sab
        if (diaSemana >= 1 && diaSemana <= 5) {
            const fechaStr = `${anio}-${String(mesNum).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            dias.push({
                dia: d,
                fecha: fechaStr,
                diaSemana: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'][diaSemana]
            });
        }
    }
    return dias;
});

// Obtener el estado de un alumno en una fecha
const obtenerEstado = (alumnoId, fecha) => {
    if (!props.asistencias[fecha]) return null;
    const registro = props.asistencias[fecha].find(a => a.alumno_id === alumnoId);
    return registro ? registro.estado : null;
};

// Badge label y color según estado
const badgeConfig = (estado) => {
    switch (estado) {
        case 'presente': return { label: 'P', bg: 'bg-green-100', text: 'text-green-700', border: 'border-green-300' };
        case 'ausente': return { label: 'A', bg: 'bg-red-100', text: 'text-red-700', border: 'border-red-300' };
        case 'tarde': return { label: 'T', bg: 'bg-yellow-100', text: 'text-yellow-700', border: 'border-yellow-300' };
        default: return null;
    }
};

// Resumen por alumno
const resumenAlumno = (alumnoId) => {
    let presentes = 0, ausentes = 0, tardes = 0;

    for (const fecha of diasDelMes.value.map(d => d.fecha)) {
        const estado = obtenerEstado(alumnoId, fecha);
        if (estado === 'presente') presentes++;
        else if (estado === 'ausente') ausentes++;
        else if (estado === 'tarde') tardes++;
    }

    const total = presentes + ausentes + tardes;
    const porcentaje = total > 0 ? Math.round((presentes / total) * 100) : 0;

    return { presentes, ausentes, tardes, total, porcentaje };
};
</script>

<template>
    <div>
        <!-- Navegación del mes -->
        <div class="flex items-center justify-center gap-4 mb-4">
            <button
                @click="emit('cambiar-mes', -1)"
                class="p-2 rounded-lg hover:bg-gray-100 transition-colors"
            >
                <i class="bx bx-chevron-left text-2xl text-gray-600"></i>
            </button>
            <h3 class="text-lg font-semibold text-gray-800 min-w-[200px] text-center">
                {{ mesLabel }}
            </h3>
            <button
                @click="emit('cambiar-mes', 1)"
                class="p-2 rounded-lg hover:bg-gray-100 transition-colors"
            >
                <i class="bx bx-chevron-right text-2xl text-gray-600"></i>
            </button>
        </div>

        <!-- Grilla del calendario -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 border border-gray-200 sticky left-0 bg-gray-50 z-10 min-w-[160px]">
                            Alumno
                        </th>
                        <th
                            v-for="dia in diasDelMes"
                            :key="dia.fecha"
                            class="px-1 py-2 text-center text-xs font-semibold text-gray-600 border border-gray-200 min-w-[36px]"
                        >
                            <div>{{ dia.diaSemana }}</div>
                            <div class="font-bold">{{ dia.dia }}</div>
                        </th>
                        <th class="px-2 py-2 text-center text-xs font-semibold text-gray-600 border border-gray-200 min-w-[40px]">P</th>
                        <th class="px-2 py-2 text-center text-xs font-semibold text-gray-600 border border-gray-200 min-w-[40px]">A</th>
                        <th class="px-2 py-2 text-center text-xs font-semibold text-gray-600 border border-gray-200 min-w-[40px]">T</th>
                        <th class="px-2 py-2 text-center text-xs font-semibold text-gray-600 border border-gray-200 min-w-[50px]">%</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="alumno in alumnos" :key="alumno.id" class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-gray-900 font-medium border border-gray-200 sticky left-0 bg-white z-10">
                            {{ alumno.apellido }}, {{ alumno.nombre }}
                        </td>
                        <td
                            v-for="dia in diasDelMes"
                            :key="dia.fecha"
                            class="px-1 py-1 text-center border border-gray-200"
                        >
                            <template v-if="obtenerEstado(alumno.id, dia.fecha)">
                                <span
                                    :class="[
                                        'inline-flex items-center justify-center w-6 h-6 rounded text-xs font-bold border',
                                        badgeConfig(obtenerEstado(alumno.id, dia.fecha)).bg,
                                        badgeConfig(obtenerEstado(alumno.id, dia.fecha)).text,
                                        badgeConfig(obtenerEstado(alumno.id, dia.fecha)).border
                                    ]"
                                >
                                    {{ badgeConfig(obtenerEstado(alumno.id, dia.fecha)).label }}
                                </span>
                            </template>
                        </td>
                        <!-- Resumen -->
                        <td class="px-2 py-2 text-center text-xs font-semibold text-green-700 border border-gray-200 bg-green-50">
                            {{ resumenAlumno(alumno.id).presentes }}
                        </td>
                        <td class="px-2 py-2 text-center text-xs font-semibold text-red-700 border border-gray-200 bg-red-50">
                            {{ resumenAlumno(alumno.id).ausentes }}
                        </td>
                        <td class="px-2 py-2 text-center text-xs font-semibold text-yellow-700 border border-gray-200 bg-yellow-50">
                            {{ resumenAlumno(alumno.id).tardes }}
                        </td>
                        <td class="px-2 py-2 text-center text-xs font-bold border border-gray-200"
                            :class="resumenAlumno(alumno.id).porcentaje >= 75 ? 'text-green-700 bg-green-50' : 'text-red-700 bg-red-50'"
                        >
                            {{ resumenAlumno(alumno.id).porcentaje }}%
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mensaje si no hay datos -->
        <div v-if="alumnos.length === 0" class="text-center py-8 text-gray-500">
            No hay alumnos para mostrar
        </div>
        <div v-else-if="Object.keys(asistencias).length === 0" class="text-center py-4 text-gray-500 text-sm mt-2">
            No hay registros de asistencia para este mes
        </div>

        <!-- Leyenda -->
        <div class="flex items-center gap-4 mt-4 text-xs text-gray-600">
            <div class="flex items-center gap-1">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded bg-green-100 text-green-700 border border-green-300 font-bold text-xs">P</span>
                <span>Presente</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded bg-red-100 text-red-700 border border-red-300 font-bold text-xs">A</span>
                <span>Ausente</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded bg-yellow-100 text-yellow-700 border border-yellow-300 font-bold text-xs">T</span>
                <span>Tarde</span>
            </div>
        </div>
    </div>
</template>
