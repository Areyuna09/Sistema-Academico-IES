/**
 * Emitir evento de nueva notificación
 * Este evento será escuchado por el Navbar para actualizar las notificaciones en tiempo real
 */
export function emitirNuevaNotificacion() {
    const evento = new CustomEvent('nueva-notificacion', {
        bubbles: true,
        detail: { timestamp: Date.now() }
    });
    window.dispatchEvent(evento);
}

/**
 * Mostrar notificación visual temporal (toast)
 */
export function mostrarNotificacionToast(titulo, mensaje, tipo = 'success') {
    // Si quieres agregar toasts en el futuro, aquí sería el lugar
    console.log(`[Notificación ${tipo}] ${titulo}: ${mensaje}`);
}
