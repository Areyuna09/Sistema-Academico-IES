<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';

const props = defineProps({
    configuracion: Object,
});

const form = useForm({
    nombre_institucion: props.configuracion.nombre_institucion,
    direccion: props.configuracion.direccion,
    telefono: props.configuracion.telefono,
    email: props.configuracion.email,
    sitio_web: props.configuracion.sitio_web,
    footer_documentos: props.configuracion.footer_documentos,
    cargo_firma: props.configuracion.cargo_firma,
    horarios_atencion: props.configuracion.horarios_atencion,
    logo: null,
    firma_digital: null,
});

const logoPreview = ref(props.configuracion.logo_url);
const firmaPreview = ref(props.configuracion.firma_digital_url);

const onLogoChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.logo = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            logoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const onFirmaChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.firma_digital = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            firmaPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const deleteLogo = () => {
    if (confirm('¿Está seguro de eliminar el logo?')) {
        router.delete(route('admin.configuracion.delete-logo'), {
            onSuccess: () => {
                logoPreview.value = null;
            }
        });
    }
};

const deleteFirma = () => {
    if (confirm('¿Está seguro de eliminar la firma digital?')) {
        router.delete(route('admin.configuracion.delete-firma'), {
            onSuccess: () => {
                firmaPreview.value = null;
            }
        });
    }
};

const submit = () => {
    form.post(route('admin.configuracion.update'), {
        forceFormData: true,
    });
};
</script>

<template>
    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Configuración General</h1>
                <p class="text-xs text-gray-400 mt-0.5">Configurar datos de la institución</p>
            </div>
        </template>

        <div class="p-8 max-w-5xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <form @submit.prevent="submit">
                    <!-- Información de la Institución -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Información de la Institución</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nombre de la Institución <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.nombre_institucion"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                />
                                <p v-if="form.errors.nombre_institucion" class="text-red-600 text-sm mt-1">{{ form.errors.nombre_institucion }}</p>
                            </div>

                            <!-- Logo -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Logo de la Institución</label>
                                <div class="flex items-start gap-4">
                                    <div v-if="logoPreview" class="flex-shrink-0">
                                        <img :src="logoPreview" alt="Logo" class="h-24 w-24 object-contain border rounded-lg p-2">
                                        <button
                                            type="button"
                                            @click="deleteLogo"
                                            class="mt-2 text-sm text-red-600 hover:text-red-800"
                                        >
                                            Eliminar logo
                                        </button>
                                    </div>
                                    <div class="flex-1">
                                        <input
                                            type="file"
                                            @change="onLogoChange"
                                            accept="image/jpeg,image/png,image/jpg,image/svg+xml"
                                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        />
                                        <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, SVG. Tamaño máximo: 2MB</p>
                                    </div>
                                </div>
                                <p v-if="form.errors.logo" class="text-red-600 text-sm mt-1">{{ form.errors.logo }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Datos de Contacto -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Datos de Contacto</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                                <textarea
                                    v-model="form.direccion"
                                    rows="2"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="Calle, número, ciudad, provincia"
                                ></textarea>
                                <p v-if="form.errors.direccion" class="text-red-600 text-sm mt-1">{{ form.errors.direccion }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                <input
                                    v-model="form.telefono"
                                    type="text"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="264-1234567"
                                />
                                <p v-if="form.errors.telefono" class="text-red-600 text-sm mt-1">{{ form.errors.telefono }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="info@instituto.edu.ar"
                                />
                                <p v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sitio Web</label>
                                <input
                                    v-model="form.sitio_web"
                                    type="url"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="https://www.instituto.edu.ar"
                                />
                                <p v-if="form.errors.sitio_web" class="text-red-600 text-sm mt-1">{{ form.errors.sitio_web }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información para Documentos -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Información para Documentos</h3>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Footer para Certificados/Comprobantes</label>
                                <textarea
                                    v-model="form.footer_documentos"
                                    rows="3"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="Texto que aparecerá al pie de los documentos oficiales..."
                                ></textarea>
                                <p v-if="form.errors.footer_documentos" class="text-red-600 text-sm mt-1">{{ form.errors.footer_documentos }}</p>
                            </div>

                            <!-- Firma Digital -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Firma Digital</label>
                                <div class="flex items-start gap-4">
                                    <div v-if="firmaPreview" class="flex-shrink-0">
                                        <img :src="firmaPreview" alt="Firma" class="h-20 w-auto object-contain border rounded-lg p-2 bg-white">
                                        <button
                                            type="button"
                                            @click="deleteFirma"
                                            class="mt-2 text-sm text-red-600 hover:text-red-800"
                                        >
                                            Eliminar firma
                                        </button>
                                    </div>
                                    <div class="flex-1">
                                        <input
                                            type="file"
                                            @change="onFirmaChange"
                                            accept="image/jpeg,image/png,image/jpg"
                                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        />
                                        <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG. Tamaño máximo: 2MB</p>
                                    </div>
                                </div>
                                <p v-if="form.errors.firma_digital" class="text-red-600 text-sm mt-1">{{ form.errors.firma_digital }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cargo de quien firma</label>
                                <input
                                    v-model="form.cargo_firma"
                                    type="text"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="Ej: Director/a, Rector/a"
                                />
                                <p v-if="form.errors.cargo_firma" class="text-red-600 text-sm mt-1">{{ form.errors.cargo_firma }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Horarios de Atención -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Horarios de Atención</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Horarios</label>
                            <textarea
                                v-model="form.horarios_atencion"
                                rows="4"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                placeholder="Lunes a Viernes: 8:00 a 20:00&#10;Sábados: 8:00 a 12:00"
                            ></textarea>
                            <p v-if="form.errors.horarios_atencion" class="text-red-600 text-sm mt-1">{{ form.errors.horarios_atencion }}</p>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 disabled:opacity-50"
                        >
                            <i class="bx bx-save mr-1"></i>
                            {{ form.processing ? 'Guardando...' : 'Guardar Configuración' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </SidebarLayout>
</template>
