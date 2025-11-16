<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import Dialog from '@/Components/Dialog.vue';
import { useDialog } from '@/Composables/useDialog';

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
    logo_dark: null,
    firma_digital: null,
});

const logoPreview = ref(props.configuracion.logo_url);
const logoDarkPreview = ref(props.configuracion.logo_dark_url);
const firmaPreview = ref(props.configuracion.firma_digital_url);

const { confirm: showConfirm } = useDialog();

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

const onLogoDarkChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.logo_dark = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            logoDarkPreview.value = e.target.result;
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

const deleteLogo = async () => {
    const confirmed = await showConfirm(
        '¿Está seguro de eliminar el logo? Esta acción no se puede deshacer.',
        'Confirmar eliminación'
    );

    if (confirmed) {
        router.delete(route('admin.configuracion.delete-logo'), {
            onSuccess: () => {
                logoPreview.value = null;
            }
        });
    }
};

const deleteLogoDark = async () => {
    const confirmed = await showConfirm(
        '¿Está seguro de eliminar el logo oscuro? Esta acción no se puede deshacer.',
        'Confirmar eliminación'
    );

    if (confirmed) {
        router.delete(route('admin.configuracion.delete-logo-dark'), {
            onSuccess: () => {
                logoDarkPreview.value = null;
            }
        });
    }
};

const deleteFirma = async () => {
    const confirmed = await showConfirm(
        '¿Está seguro de eliminar la firma digital? Esta acción no se puede deshacer.',
        'Confirmar eliminación'
    );

    if (confirmed) {
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
        onSuccess: () => {
            // Resetear los archivos del form después de guardar
            form.logo = null;
            form.logo_dark = null;
            form.firma_digital = null;

            // Actualizar previews con los nuevos valores de la DB
            logoPreview.value = props.configuracion.logo_url;
            logoDarkPreview.value = props.configuracion.logo_dark_url;
            firmaPreview.value = props.configuracion.firma_digital_url;
        },
    });
};
</script>

<template>
    <Head title="Editar Configuración" />

    <SidebarLayout :user="$page.props.auth.user">
        <template #header>
            <div>
                <h1 class="text-xl font-semibold text-white">Configuración General</h1>
                <p class="text-xs text-gray-400 mt-0.5">Configurar datos de la institución</p>
            </div>
        </template>

        <div class="p-8 max-w-5xl mx-auto">
            <!-- Mensaje de éxito -->
            <div v-if="$page.props.flash?.success" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                <i class="bx bx-check-circle text-2xl text-green-600"></i>
                <div>
                    <p class="text-sm font-medium text-green-800">{{ $page.props.flash.success }}</p>
                    <p class="text-xs text-green-600 mt-0.5">Los cambios se han guardado correctamente en la base de datos.</p>
                </div>
            </div>

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
                                    maxlength="100"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                />
                                <p v-if="form.errors.nombre_institucion" class="text-red-600 text-sm mt-1">{{ form.errors.nombre_institucion }}</p>
                            </div>

                            <!-- Logo Claro (para fondos oscuros) -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Logo de la Institución (Claro)
                                    <span class="text-xs text-gray-500 font-normal ml-1">- Para fondos oscuros (con letras blancas)</span>
                                </label>
                                <div class="flex items-start gap-4">
                                    <div v-if="logoPreview" class="flex-shrink-0">
                                        <div class="bg-gray-800 p-3 rounded-lg">
                                            <img :src="logoPreview" alt="Logo Claro" class="h-24 w-24 object-contain">
                                        </div>
                                        <button
                                            type="button"
                                            @click="deleteLogo"
                                            class="mt-2 text-sm text-red-600 hover:text-red-800"
                                        >
                                            Eliminar logo
                                        </button>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="bx bx-check-circle text-green-600"></i>
                                            Logo guardado en DB
                                        </p>
                                    </div>
                                    <div v-else class="flex-shrink-0">
                                        <div class="bg-gray-100 p-3 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center" style="width: 120px; height: 120px;">
                                            <i class="bx bx-image text-4xl text-gray-400"></i>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 text-center">Sin logo</p>
                                    </div>
                                    <div class="flex-1">
                                        <input
                                            type="file"
                                            @change="onLogoChange"
                                            accept="image/jpeg,image/png,image/jpg,image/svg+xml"
                                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        />
                                        <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, SVG. Tamaño máximo: 2MB</p>
                                        <p v-if="form.logo" class="text-xs text-blue-600 mt-2 flex items-center gap-1">
                                            <i class="bx bx-info-circle"></i>
                                            <span>Archivo seleccionado: {{ form.logo.name }}</span>
                                        </p>
                                    </div>
                                </div>
                                <p v-if="form.errors.logo" class="text-red-600 text-sm mt-1">{{ form.errors.logo }}</p>
                            </div>

                            <!-- Logo Oscuro (para fondos claros) -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Logo de la Institución (Oscuro)
                                    <span class="text-xs text-gray-500 font-normal ml-1">- Para fondos claros (con letras negras)</span>
                                </label>
                                <div class="flex items-start gap-4">
                                    <div v-if="logoDarkPreview" class="flex-shrink-0">
                                        <div class="bg-white p-3 rounded-lg border-2 border-gray-200">
                                            <img :src="logoDarkPreview" alt="Logo Oscuro" class="h-24 w-24 object-contain">
                                        </div>
                                        <button
                                            type="button"
                                            @click="deleteLogoDark"
                                            class="mt-2 text-sm text-red-600 hover:text-red-800"
                                        >
                                            Eliminar logo oscuro
                                        </button>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="bx bx-check-circle text-green-600"></i>
                                            Logo oscuro guardado en DB
                                        </p>
                                    </div>
                                    <div v-else class="flex-shrink-0">
                                        <div class="bg-gray-100 p-3 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center" style="width: 120px; height: 120px;">
                                            <i class="bx bx-image text-4xl text-gray-400"></i>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 text-center">Sin logo oscuro</p>
                                    </div>
                                    <div class="flex-1">
                                        <input
                                            type="file"
                                            @change="onLogoDarkChange"
                                            accept="image/jpeg,image/png,image/jpg,image/svg+xml"
                                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        />
                                        <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, SVG. Tamaño máximo: 2MB</p>
                                        <p v-if="form.logo_dark" class="text-xs text-blue-600 mt-2 flex items-center gap-1">
                                            <i class="bx bx-info-circle"></i>
                                            <span>Archivo seleccionado: {{ form.logo_dark.name }}</span>
                                        </p>
                                        <p class="text-xs text-blue-600 mt-2">
                                            <i class="bx bx-info-circle"></i>
                                            El sistema elegirá automáticamente el logo apropiado según el color de fondo
                                        </p>
                                    </div>
                                </div>
                                <p v-if="form.errors.logo_dark" class="text-red-600 text-sm mt-1">{{ form.errors.logo_dark }}</p>
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
                                    maxlength="200"
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
                                    placeholder="381-5123456 o +54 381 5123456"
                                    pattern="[0-9\s\-\+\(\)]+"
                                    inputmode="tel"
                                    maxlength="20"
                                    title="Se permiten números, espacios, guiones, paréntesis y signo +"
                                />
                                <p v-if="form.errors.telefono" class="text-red-600 text-sm mt-1">{{ form.errors.telefono }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    maxlength="100"
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
                                    maxlength="100"
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
                                    maxlength="500"
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
                                    maxlength="100"
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
                                maxlength="500"
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

        <!-- Dialog component -->
        <Dialog />
    </SidebarLayout>
</template>
