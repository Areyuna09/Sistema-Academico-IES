<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';
import axios from 'axios';

const materias = ref([]);
const alumnos = ref([]);
const fecha = ref(new Date().toISOString().slice(0,10));
const materiaId = ref('');
const asistenciasMap = reactive({});

async function cargarMaterias() {
  const res = await axios.get('/profesor/materias');
  materias.value = res.data;
}

async function cargarAlumnos() {
  if(!materiaId.value) return;
  const res = await axios.get(`/materias/${materiaId.value}/alumnos`);
  alumnos.value = res.data;
  alumnos.value.forEach(a=>{
    asistenciasMap[a.id] = asistenciasMap[a.id] || { estado:'ausente', observacion:'' };
  });
}

async function cargarAsistencias(){
  if(!materiaId.value || !fecha.value) return;
  const res = await axios.get('/asistencias', { params: { materia_id: materiaId.value, fecha: fecha.value }});
  const list = res.data;
  list.forEach(s=>{
    if(!asistenciasMap[s.alumno_id]) asistenciasMap[s.alumno_id] = { estado:'ausente', observacion:'' };
    asistenciasMap[s.alumno_id].estado = s.estado;
    asistenciasMap[s.alumno_id].observacion = s.observacion;
  });
}

async function guardar(){
  if(!materiaId.value) return alert('Seleccioná una materia');
  const payload = {
    materia_id: materiaId.value,
    fecha: fecha.value,
    asistencias: alumnos.value.map(a=>({
      alumno_id: a.id,
      estado: asistenciasMap[a.id].estado,
      observacion: asistenciasMap[a.id].observacion
    }))
  };
  await axios.post('/asistencias/guardar', payload);
  alert('Asistencias guardadas correctamente ✅');
}

cargarMaterias();
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Panel del Profesor" />

    <div class="p-6">
      <h2 class="text-2xl font-semibold mb-4">Panel del Profesor</h2>

      <div class="mb-4 flex flex-wrap gap-4 items-center">
        <select v-model="materiaId" @change="cargarAlumnos" class="p-2 border rounded">
          <option value="">-- Seleccionar materia --</option>
          <option v-for="m in materias" :key="m.id" :value="m.id">{{ m.nombre }}</option>
        </select>

        <input type="date" v-model="fecha" class="p-2 border rounded"/>
        <button @click="cargarAsistencias" class="px-4 py-2 rounded bg-green-500 hover:bg-green-600 text-white">Cargar</button>
        <button @click="guardar" class="px-4 py-2 rounded bg-blue-500 hover:bg-blue-600 text-white">Guardar</button>
      </div>

      <div v-if="alumnos.length == 0" class="text-gray-500">
        Seleccioná una materia para ver los alumnos.
      </div>

      <div v-else>
        <table class="min-w-full bg-white rounded shadow">
          <thead>
            <tr class="text-left bg-gray-100">
              <th class="px-4 py-2">Apellido</th>
              <th class="px-4 py-2">Nombre</th>
              <th class="px-4 py-2">Asistencia</th>
              <th class="px-4 py-2">Observación</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="a in alumnos" :key="a.id" class="border-t">
              <td class="px-4 py-2">{{ a.apellido }}</td>
              <td class="px-4 py-2">{{ a.nombre }}</td>
              <td class="px-4 py-2">
                <select v-model="asistenciasMap[a.id].estado" class="p-1 border rounded">
                  <option value="presente">Presente</option>
                  <option value="ausente">Ausente</option>
                  <option value="justificada">Justificada</option>
                </select>
              </td>
              <td class="px-4 py-2">
                <input v-model="asistenciasMap[a.id].observacion" placeholder="opcional" class="p-1 border rounded w-full"/>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
