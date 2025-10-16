import { ref, watch } from 'vue';

/**
 * Composable para validar y sanitizar entrada numérica en inputs
 * Previene la entrada de caracteres no numéricos en tiempo real
 */
export function useNumericInput(initialValue = '', options = {}) {
    const {
        allowDecimals = false,
        allowNegative = false,
        max = null,
        min = null,
        maxDecimals = 2
    } = options;

    const value = ref(initialValue);
    const rawValue = ref(initialValue);

    /**
     * Sanitiza el valor ingresado eliminando caracteres no permitidos
     */
    const sanitize = (input) => {
        if (!input) return '';

        let sanitized = String(input);

        // Permitir solo números, punto decimal si está habilitado, y signo negativo si está habilitado
        let pattern = '[^0-9';
        if (allowDecimals) pattern += '.';
        if (allowNegative) pattern += '-';
        pattern += ']';

        sanitized = sanitized.replace(new RegExp(pattern, 'g'), '');

        // Asegurar que el signo negativo solo esté al inicio
        if (allowNegative) {
            const hasNegative = sanitized.includes('-');
            sanitized = sanitized.replace(/-/g, '');
            if (hasNegative) sanitized = '-' + sanitized;
        }

        // Asegurar que solo haya un punto decimal
        if (allowDecimals) {
            const parts = sanitized.split('.');
            if (parts.length > 2) {
                sanitized = parts[0] + '.' + parts.slice(1).join('');
            }
            // Limitar decimales
            if (parts.length === 2 && parts[1].length > maxDecimals) {
                sanitized = parts[0] + '.' + parts[1].substring(0, maxDecimals);
            }
        }

        // Aplicar límites min/max
        if (sanitized !== '' && sanitized !== '-' && sanitized !== '.') {
            const numValue = parseFloat(sanitized);
            if (!isNaN(numValue)) {
                if (max !== null && numValue > max) {
                    sanitized = String(max);
                }
                if (min !== null && numValue < min) {
                    sanitized = String(min);
                }
            }
        }

        return sanitized;
    };

    /**
     * Maneja el evento input
     */
    const handleInput = (event) => {
        const input = event.target.value;
        const sanitized = sanitize(input);

        rawValue.value = input;
        value.value = sanitized;

        // Actualizar el input del DOM si el valor fue sanitizado
        if (sanitized !== input) {
            event.target.value = sanitized;
        }
    };

    /**
     * Maneja el evento paste
     */
    const handlePaste = (event) => {
        event.preventDefault();
        const pastedText = event.clipboardData.getData('text');
        const sanitized = sanitize(pastedText);
        value.value = sanitized;
        event.target.value = sanitized;
    };

    /**
     * Obtiene el valor como número
     */
    const getNumericValue = () => {
        if (value.value === '' || value.value === '-' || value.value === '.') {
            return null;
        }
        return allowDecimals ? parseFloat(value.value) : parseInt(value.value);
    };

    /**
     * Valida si el valor actual es válido
     */
    const isValid = () => {
        const numValue = getNumericValue();
        if (numValue === null) return false;
        if (isNaN(numValue)) return false;
        if (max !== null && numValue > max) return false;
        if (min !== null && numValue < min) return false;
        return true;
    };

    /**
     * Establece un nuevo valor
     */
    const setValue = (newValue) => {
        value.value = sanitize(newValue);
    };

    /**
     * Resetea el valor
     */
    const reset = () => {
        value.value = initialValue;
        rawValue.value = initialValue;
    };

    return {
        value,
        rawValue,
        handleInput,
        handlePaste,
        getNumericValue,
        isValid,
        setValue,
        reset,
        sanitize
    };
}

/**
 * Directiva Vue para inputs numéricos
 * Uso: v-numeric="{ allowDecimals: true, min: 0, max: 100 }"
 */
export const vNumeric = {
    mounted(el, binding) {
        const options = binding.value || {};
        const {
            allowDecimals = false,
            allowNegative = false,
            max = null,
            min = null,
            maxDecimals = 2
        } = options;

        const sanitize = (input) => {
            if (!input) return '';

            let sanitized = String(input);

            let pattern = '[^0-9';
            if (allowDecimals) pattern += '.';
            if (allowNegative) pattern += '-';
            pattern += ']';

            sanitized = sanitized.replace(new RegExp(pattern, 'g'), '');

            if (allowNegative) {
                const hasNegative = sanitized.includes('-');
                sanitized = sanitized.replace(/-/g, '');
                if (hasNegative) sanitized = '-' + sanitized;
            }

            if (allowDecimals) {
                const parts = sanitized.split('.');
                if (parts.length > 2) {
                    sanitized = parts[0] + '.' + parts.slice(1).join('');
                }
                if (parts.length === 2 && parts[1].length > maxDecimals) {
                    sanitized = parts[0] + '.' + parts[1].substring(0, maxDecimals);
                }
            }

            if (sanitized !== '' && sanitized !== '-' && sanitized !== '.') {
                const numValue = parseFloat(sanitized);
                if (!isNaN(numValue)) {
                    if (max !== null && numValue > max) {
                        sanitized = String(max);
                    }
                    if (min !== null && numValue < min) {
                        sanitized = String(min);
                    }
                }
            }

            return sanitized;
        };

        const handleInput = (event) => {
            const input = event.target.value;
            const sanitized = sanitize(input);

            if (sanitized !== input) {
                event.target.value = sanitized;
                // Disparar evento input para que Vue detecte el cambio
                event.target.dispatchEvent(new Event('input', { bubbles: true }));
            }
        };

        const handlePaste = (event) => {
            event.preventDefault();
            const pastedText = event.clipboardData.getData('text');
            const sanitized = sanitize(pastedText);
            event.target.value = sanitized;
            event.target.dispatchEvent(new Event('input', { bubbles: true }));
        };

        el.addEventListener('input', handleInput);
        el.addEventListener('paste', handlePaste);

        // Guardar referencias para poder removerlas después
        el._numericHandlers = { handleInput, handlePaste };
    },

    unmounted(el) {
        if (el._numericHandlers) {
            el.removeEventListener('input', el._numericHandlers.handleInput);
            el.removeEventListener('paste', el._numericHandlers.handlePaste);
            delete el._numericHandlers;
        }
    }
};
