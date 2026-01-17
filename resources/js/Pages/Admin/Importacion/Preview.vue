<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    preview: Object,
    tipo: String,
});

const duplicadosSeleccionados = ref([]);
const crearUsuarios = ref(true);
const importando = ref(false);

const getColor = (tipo) => {
    const colores = {
        'alumnos': 'blue',
        'profesores': 'green',
        'materias': 'purple',
    };
    return colores[tipo] || 'gray';
};

const toggleDuplicado = (dni) => {
    const index = duplicadosSeleccionados.value.indexOf(dni);
    if (index === -1) {
        duplicadosSeleccionados.value.push(dni);
    } else {
        duplicadosSeleccionados.value.splice(index, 1);
    }
};

const seleccionarTodosDuplicados = () => {
    if (duplicadosSeleccionados.value.length === props.preview.datos.duplicados.length) {
        duplicadosSeleccionados.value = [];
    } else {
        duplicadosSeleccionados.value = props.preview.datos.duplicados.map(d => d.datos.dni);
    }
};

const totalAImportar = computed(() => {
    return props.preview.resumen.nuevos + duplicadosSeleccionados.value.length;
});

const confirmarImportacion = () => {
    importando.value = true;

    router.post(route('admin.importacion.importar'), {
        tipo: props.tipo,
        actualizar_duplicados: duplicadosSeleccionados.value,
        crear_usuarios: crearUsuarios.value,
    }, {
        onFinish: () => {
            importando.value = false;
        },
    });
};
</script>

<template>
    <Head :title="`Preview Importacion - ${preview.entidad}`" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex items-center space-x-2">
                <Link :href="route('admin.importacion.create', tipo)" class="text-gray-400 hover:text-white">
                    <i class="bx bx-arrow-back text-xl"></i>
                </Link>
                <div>
                    <h1 class="text-xl font-semibold text-white">Vista Previa de Importacion</h1>
                    <p class="text-xs text-gray-400 mt-0.5">{{ preview.archivo }}</p>
                </div>
            </div>
        </template>

        <div class="p-8 max-w-6xl mx-auto">
            <!-- Resumen -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total en archivo</p>
                            <p class="text-2xl font-bold text-gray-800">{{ preview.resumen.total }}</p>
                        </div>
                        <div class="p-3 bg-gray-100 rounded-lg">
                            <i class="bx bx-file text-2xl text-gray-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Nuevos registros</p>
                            <p class="text-2xl font-bold text-green-600">{{ preview.resumen.nuevos }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="bx bx-plus-circle text-2xl text-green-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Duplicados</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ preview.resumen.duplicados }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="bx bx-copy text-2xl text-yellow-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Errores</p>
                            <p class="text-2xl font-bold text-red-600">{{ preview.resumen.errores }}</p>
                        </div>
                        <div class="p-3 bg-red-100 rounded-lg">
                            <i class="bx bx-error text-2xl text-red-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registros nuevos -->
            <div v-if="preview.datos.nuevos.length > 0" class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bx bx-check-circle text-green-500 mr-2"></i>
                    Registros Nuevos ({{ preview.datos.nuevos.length }})
                </h3>
                <p class="text-sm text-gray-500 mb-4">Estos registros se importaran directamente</p>

                <div class="overflow-x-auto max-h-64 overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fila</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">DNI</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th v-if="tipo !== 'materias'" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Carrera</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in preview.datos.nuevos" :key="item.fila" class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-sm text-gray-500">{{ item.fila }}</td>
                                <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ item.datos.dni || item.datos.nombre }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900">
                                    {{ tipo === 'materias' ? item.datos.nombre : `${item.datos.apellido}, ${item.datos.nombre}` }}
                                </td>
                                <td v-if="tipo !== 'materias'" class="px-4 py-2 text-sm text-gray-500">{{ item.datos.email || '-' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-500">{{ item.datos.carrera }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Duplicados -->
            <div v-if="preview.datos.duplicados.length > 0" class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="bx bx-copy text-yellow-500 mr-2"></i>
                        Duplicados Encontrados ({{ preview.datos.duplicados.length }})
                    </h3>
                    <button
                        @click="seleccionarTodosDuplicados"
                        class="text-sm text-blue-600 hover:text-blue-800"
                    >
                        {{ duplicadosSeleccionados.length === preview.datos.duplicados.length ? 'Deseleccionar todos' : 'Seleccionar todos' }}
                    </button>
                </div>
                <p class="text-sm text-gray-500 mb-4">Seleccione los registros que desea actualizar con los nuevos datos</p>

                <div class="space-y-3 max-h-80 overflow-y-auto">
                    <div
                        v-for="item in preview.datos.duplicados"
                        :key="item.datos.dni"
                        :class="[
                            'border rounded-lg p-4 cursor-pointer transition-colors duration-200',
                            duplicadosSeleccionados.includes(item.datos.dni) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'
                        ]"
                        @click="toggleDuplicado(item.datos.dni)"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input
                                    type="checkbox"
                                    :checked="duplicadosSeleccionados.includes(item.datos.dni)"
                                    class="h-4 w-4 text-blue-600 rounded border-gray-300 mr-3"
                                    @click.stop
                                    @change="toggleDuplicado(item.datos.dni)"
                                />
                                <div>
                                    <p class="font-medium text-gray-800">
                                        Fila {{ item.fila }}: DNI {{ item.datos.dni }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ tipo === 'materias' ? item.datos.nombre : `${item.datos.apellido}, ${item.datos.nombre}` }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400">Registro existente:</p>
                                <p class="text-sm text-gray-600">{{ item.existente.nombre }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Errores -->
            <div v-if="preview.datos.errores.length > 0" class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bx bx-error text-red-500 mr-2"></i>
                    Errores ({{ preview.datos.errores.length }})
                </h3>
                <p class="text-sm text-gray-500 mb-4">Estos registros no se pueden importar debido a errores</p>

                <div class="space-y-2 max-h-64 overflow-y-auto">
                    <div
                        v-for="item in preview.datos.errores"
                        :key="item.fila"
                        class="border border-red-200 bg-red-50 rounded-lg p-3"
                    >
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-medium text-red-800">Fila {{ item.fila }}</p>
                                <p class="text-sm text-red-600">{{ item.datos.dni || item.datos.nombre || 'Sin identificador' }}</p>
                            </div>
                            <div class="text-right">
                                <ul class="text-sm text-red-700">
                                    <li v-for="(err, idx) in item.errores" :key="idx">{{ err }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Opciones -->
            <div v-if="tipo !== 'materias'" class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Opciones de Importacion</h3>

                <label class="flex items-center">
                    <input
                        type="checkbox"
                        v-model="crearUsuarios"
                        class="h-4 w-4 text-blue-600 rounded border-gray-300"
                    />
                    <span class="ml-3 text-gray-700">
                        Crear usuarios automaticamente (contrasena = DNI)
                    </span>
                </label>
            </div>

            <!-- Botones de accion -->
            <div class="flex justify-between items-center">
                <Link
                    :href="route('admin.importacion.create', tipo)"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200"
                >
                    <i class="bx bx-arrow-back mr-2"></i>
                    Volver a Cargar
                </Link>

                <div class="flex items-center space-x-4">
                    <p class="text-sm text-gray-600">
                        Se importaran <strong class="text-lg">{{ totalAImportar }}</strong> registros
                    </p>

                    <button
                        @click="confirmarImportacion"
                        :disabled="totalAImportar === 0 || importando"
                        :class="[
                            'inline-flex items-center px-6 py-3 font-semibold rounded-lg transition-colors duration-200',
                            totalAImportar > 0 && !importando
                                ? `bg-${getColor(tipo)}-600 hover:bg-${getColor(tipo)}-700 text-white`
                                : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                        ]"
                    >
                        <i :class="importando ? 'bx bx-loader-alt bx-spin' : 'bx bx-check'" class="text-xl mr-2"></i>
                        {{ importando ? 'Importando...' : 'Confirmar Importacion' }}
                    </button>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>
