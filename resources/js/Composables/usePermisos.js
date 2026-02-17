import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

/**
 * Composable para verificar permisos del usuario
 * 
 * Uso:
 * const { soloLectura, puedeCrear, puedeModificar, puedeEliminar } = usePermisos();
 * 
 * <button v-if="puedeCrear()">Crear</button>
 * <button v-if="puedeModificar()">Editar</button>
 */
export function usePermisos() {
    const page = usePage();
    
    // Obtener tipo de usuario actual
    const tipoUsuario = computed(() => page.props.auth?.user?.tipo || null);
    
    /**
     * Constantes de tipos de usuario
     */
    const TIPOS = {
        ADMIN: 1,
        USUARIO: 2, // Bedel legacy
        PROFESOR: 3,
        ALUMNO: 4,
        DIRECTIVO: 5,
        SUPERVISOR: 6,
        BEDEL: 7,
        PRECEPTOR: 8,
    };
    
    /**
     * Verificar si el usuario tiene permisos de solo lectura
     * @returns {boolean}
     */
    const soloLectura = () => {
        return [TIPOS.DIRECTIVO, TIPOS.SUPERVISOR].includes(tipoUsuario.value);
    };
    
    /**
     * Verificar si el usuario puede crear registros
     * @returns {boolean}
     */
    const puedeCrear = () => {
        if (soloLectura()) return false;
        return [TIPOS.ADMIN, TIPOS.BEDEL, TIPOS.USUARIO, TIPOS.PRECEPTOR].includes(tipoUsuario.value);
    };
    
    /**
     * Verificar si el usuario puede modificar registros
     * @returns {boolean}
     */
    const puedeModificar = () => {
        if (soloLectura()) return false;
        return [TIPOS.ADMIN, TIPOS.BEDEL, TIPOS.USUARIO, TIPOS.PRECEPTOR].includes(tipoUsuario.value);
    };
    
    /**
     * Verificar si el usuario puede eliminar registros
     * @returns {boolean}
     */
    const puedeEliminar = () => {
        if (soloLectura()) return false;
        return [TIPOS.ADMIN, TIPOS.BEDEL, TIPOS.USUARIO].includes(tipoUsuario.value);
    };
    
    /**
     * Verificar si el usuario es admin
     * @returns {boolean}
     */
    const esAdmin = () => {
        return tipoUsuario.value === TIPOS.ADMIN;
    };
    
    /**
     * Verificar si el usuario es profesor
     * @returns {boolean}
     */
    const esProfesor = () => {
        return tipoUsuario.value === TIPOS.PROFESOR;
    };
    
    /**
     * Verificar si el usuario es alumno
     * @returns {boolean}
     */
    const esAlumno = () => {
        return tipoUsuario.value === TIPOS.ALUMNO;
    };
    
    /**
     * Verificar si el usuario es directivo
     * @returns {boolean}
     */
    const esDirectivo = () => {
        return tipoUsuario.value === TIPOS.DIRECTIVO;
    };
    
    /**
     * Verificar si el usuario es supervisor
     * @returns {boolean}
     */
    const esSupervisor = () => {
        return tipoUsuario.value === TIPOS.SUPERVISOR;
    };
    
    /**
     * Verificar si el usuario es bedel (nuevo o legacy)
     * @returns {boolean}
     */
    const esBedel = () => {
        return [TIPOS.BEDEL, TIPOS.USUARIO].includes(tipoUsuario.value);
    };
    
    /**
     * Verificar si el usuario es preceptor
     * @returns {boolean}
     */
    const esPreceptor = () => {
        return tipoUsuario.value === TIPOS.PRECEPTOR;
    };
    
    /**
     * Verificar si el usuario puede aprobar notas finales
     * @returns {boolean}
     */
    const puedeAprobarNotas = () => {
        return [TIPOS.ADMIN, TIPOS.BEDEL, TIPOS.USUARIO].includes(tipoUsuario.value);
    };
    
    /**
     * Verificar si el usuario puede tomar asistencias
     * @returns {boolean}
     */
    const puedeTomarAsistencias = () => {
        return [TIPOS.ADMIN, TIPOS.PROFESOR, TIPOS.PRECEPTOR].includes(tipoUsuario.value);
    };
    
    /**
     * Verificar si el usuario puede cargar notas
     * @returns {boolean}
     */
    const puedeCargarNotas = () => {
        return [TIPOS.ADMIN, TIPOS.PROFESOR].includes(tipoUsuario.value);
    };
    
    /**
     * Verificar si el usuario puede gestionar usuarios
     * @returns {boolean}
     */
    const puedeGestionarUsuarios = () => {
        return [TIPOS.ADMIN, TIPOS.BEDEL, TIPOS.USUARIO].includes(tipoUsuario.value);
    };
    
    /**
     * Verificar si el usuario puede gestionar inscripciones
     * @returns {boolean}
     */
    const puedeGestionarInscripciones = () => {
        return [TIPOS.ADMIN, TIPOS.BEDEL, TIPOS.USUARIO, TIPOS.PRECEPTOR].includes(tipoUsuario.value);
    };
    
    /**
     * Verificar si el usuario puede gestionar mesas de examen
     * @returns {boolean}
     */
    const puedeGestionarMesas = () => {
        return [TIPOS.ADMIN, TIPOS.BEDEL, TIPOS.USUARIO, TIPOS.PRECEPTOR].includes(tipoUsuario.value);
    };
    
    /**
     * Verificar si el usuario puede exportar PDF
     * @returns {boolean}
     */
    const puedeExportar = () => {
        // Todos excepto alumnos pueden exportar
        return tipoUsuario.value !== TIPOS.ALUMNO;
    };
    
    /**
     * Obtener el nombre del rol actual
     * @returns {string}
     */
    const nombreRol = () => {
        const nombres = {
            [TIPOS.ADMIN]: 'Administrador',
            [TIPOS.USUARIO]: 'Bedel (Legacy)',
            [TIPOS.PROFESOR]: 'Profesor',
            [TIPOS.ALUMNO]: 'Alumno',
            [TIPOS.DIRECTIVO]: 'Directivo',
            [TIPOS.SUPERVISOR]: 'Supervisor',
            [TIPOS.BEDEL]: 'Bedel',
            [TIPOS.PRECEPTOR]: 'Preceptor',
        };
        return nombres[tipoUsuario.value] || 'Desconocido';
    };
    
    return {
        // Permisos generales
        soloLectura,
        puedeCrear,
        puedeModificar,
        puedeEliminar,
        puedeExportar,
        
        // Verificadores de rol
        esAdmin,
        esProfesor,
        esAlumno,
        esDirectivo,
        esSupervisor,
        esBedel,
        esPreceptor,
        
        // Permisos específicos
        puedeAprobarNotas,
        puedeTomarAsistencias,
        puedeCargarNotas,
        puedeGestionarUsuarios,
        puedeGestionarInscripciones,
        puedeGestionarMesas,
        
        // Utilidades
        nombreRol,
        tipoUsuario,
        TIPOS,
    };
}