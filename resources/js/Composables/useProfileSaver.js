import { onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useProfileSaver() {
    onMounted(() => {
        const page = usePage();
        const user = page.props.auth?.user;

        // Verificar si hay datos de perfil para guardar
        const saveData = sessionStorage.getItem('save_profile_data');

        if (saveData && user) {
            try {
                const { dni, password } = JSON.parse(saveData);

                console.log('=== Guardando perfil ===');
                console.log('DNI:', dni);
                console.log('Tiene contraseña?:', !!password);
                console.log('Usuario:', user);

                // Obtener perfiles guardados
                let savedProfiles = [];
                const stored = localStorage.getItem('saved_profiles');
                if (stored) {
                    try {
                        savedProfiles = JSON.parse(stored);
                    } catch (e) {
                        console.error('Error parsing saved profiles:', e);
                        savedProfiles = [];
                    }
                }

                // Verificar si el perfil ya existe
                const existingIndex = savedProfiles.findIndex(p => p.dni === dni);

                const profileData = {
                    dni: dni,
                    password: password, // Guardamos la contraseña
                    name: user.name,
                    email: user.email,
                    avatar: user.avatar,
                    tipo: user.tipo,
                    lastLogin: new Date().toISOString(),
                };

                console.log('Datos del perfil a guardar:', profileData);

                if (existingIndex >= 0) {
                    // Actualizar perfil existente
                    savedProfiles[existingIndex] = profileData;
                    console.log('Perfil actualizado en índice:', existingIndex);
                } else {
                    // Agregar nuevo perfil
                    savedProfiles.push(profileData);
                    console.log('Nuevo perfil agregado');
                }

                // Guardar en localStorage (máximo 5 perfiles)
                if (savedProfiles.length > 5) {
                    savedProfiles = savedProfiles.slice(-5);
                }

                localStorage.setItem('saved_profiles', JSON.stringify(savedProfiles));

                console.log('Perfiles guardados en localStorage:', savedProfiles);

                // Limpiar datos temporales
                sessionStorage.removeItem('save_profile_data');

                console.log('Perfil guardado exitosamente');
            } catch (e) {
                console.error('Error saving profile:', e);
                sessionStorage.removeItem('save_profile_data');
            }
        }
    });
}
