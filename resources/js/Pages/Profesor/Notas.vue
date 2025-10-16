<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';
import axios from 'axios';

const materias = ref([]);
const alumnos = ref([]);
const materiaId = ref('');
const asignaciones = reactive({});

async function cargarMaterias() {
  const res = await axios.get('/profesor/materias');
  materias.value = res.data;
}

async function cargarAlumnos() {
  if(!materiaId.value) return;
  const res = await axios.get('/notas/materia', { params: { materia_id: materiaId.value }});
  alumnos.value = res.data;
  alumnos.value.forEach(a=>{
    asignaciones[a.id] = {
      nota: a.nota ?? '',
      observacion: a.observacion ?? '',
      nota_temporal_id: a.nota_temporal_id ?? null
    };
  });
}

async function guardar() {
  if(!materiaId.value) return alert('Seleccioná una materia');
  const payload = {
    materia_id: materiaId.value,
    asignaciones: Object.keys(asignaciones).map(id => ({
      alumno_id: id,
      nota: asignaciones[id].nota,
      observacion: asignaciones[id].observacion
    }))
  };
  await axios.post('/notas/guardar', payload);
  alert('Notas temporales guardadas');
}

async function aprobar(id) {
  if(!confirm('¿Aprobar y transferir al legajo oficial?')) return;
  const res = await axios.post(`/notas/aprobar/${id}`);
  alert('Nota aprobada y transferida. ID oficial: ' + res.data.oficial_id);
  cargarAlumnos();
}

cargarMaterias();
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Notas Temporales" />
    <div class="p-6">
      <h2 class="text-2xl font-semibold mb-4">Notas Temporales - Panel del Profesor</h2>

      <div class="mb-4 flex gap-4 items-center">
        <select v-model="materiaId" @change="cargarAlumnos" class="p-2 border rounded">
          <option value="">-- Seleccionar materia --</option>
          <option v-for="m in materias" :key="m.id" :value="m.id">{{ m.nombre }}</option>
        </select>
        <button @click="guardar" class="px-4 py-2 rounded bg-blue-500 hover:bg-blue-600 text-white">Guardar</button>
      </div>

      <div v-if="alumnos.length == 0" class="text-gray-500">Seleccioná una materia para ver los alumnos.</div>

      <div v-else>
        <table class="min-w-full bg-white rounded shadow">
          <thead>
            <tr class="text-left bg-gray-100">
              <th class="px-4 py-2">Apellido</th>
              <th class="px-4 py-2">Nombre</th>
              <th class="px-4 py-2">Nota</th>
              <th class="px-4 py-2">Observación</th>
              <th class="px-4 py-2">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="a in alumnos" :key="a.id" class="border-t">
              <td class="px-4 py-2">{{ a.apellido }}</td>
              <td class="px-4 py-2">{{ a.nombre }}</td>
              <td class="px-4 py-2">
                <input v-model="asignaciones[a.id].nota" class="p-1 border rounded w-24"/>
              </td>
              <td class="px-4 py-2">
                <input v-model="asignaciones[a.id].observacion" class="p-1 border rounded w-full"/>
              </td>
              <td class="px-4 py-2">
                <button v-if="asignaciones[a.id].nota_temporal_id" @click="aprobar(asignaciones[a.id].nota_temporal_id)" class="px-3 py-1 bg-green-500 text-white rounded">Aprobar</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
