import { ref } from 'vue'

export function useValidation() {
    const errors = ref({})

    // Validadores básicos
    const validators = {
        required: (value, fieldName) => {
            if (!value || value.toString().trim() === '') {
                return `${fieldName} es obligatorio.`
            }
            return null
        },

        minLength: (value, min, fieldName) => {
            if (value && value.length < min) {
                return `${fieldName} debe tener al menos ${min} caracteres.`
            }
            return null
        },

        maxLength: (value, max, fieldName) => {
            if (value && value.length > max) {
                return `${fieldName} no puede exceder los ${max} caracteres.`
            }
            return null
        },

        lettersOnly: (value, fieldName) => {
            if (value && !/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/.test(value)) {
                return `${fieldName} solo puede contener letras y espacios.`
            }
            return null
        },

        numbersOnly: (value, fieldName) => {
            if (value && !/^\d+$/.test(value)) {
                return `${fieldName} solo puede contener números.`
            }
            return null
        },

        email: (value, fieldName) => {
            if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                return `${fieldName} debe ser un email válido.`
            }
            return null
        },

        dni: (value, fieldName) => {
            if (value) {
                const dniStr = value.toString()
                if (!/^\d{7,8}$/.test(dniStr)) {
                    return `${fieldName} debe tener entre 7 y 8 dígitos.`
                }
            }
            return null
        },

        phone: (value, fieldName) => {
            if (value && !/^\d{10}$/.test(value.toString())) {
                return `${fieldName} debe tener 10 dígitos.`
            }
            return null
        },

        password: (value, fieldName) => {
            if (value) {
                if (value.length < 8) {
                    return `${fieldName} debe tener al menos 8 caracteres.`
                }
                if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(value)) {
                    return `${fieldName} debe contener al menos una mayúscula, una minúscula y un número.`
                }
            }
            return null
        },

        alphanumeric: (value, fieldName) => {
            if (value && !/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s\-]+$/.test(value)) {
                return `${fieldName} solo puede contener letras, números, espacios y guiones.`
            }
            return null
        },

        noSpecialChars: (value, fieldName) => {
            if (value && /[<>\"';&\\]/.test(value)) {
                return `${fieldName} contiene caracteres no permitidos.`
            }
            return null
        },

        year: (value, fieldName) => {
            if (value) {
                const year = parseInt(value)
                const currentYear = new Date().getFullYear()
                if (year < 1900 || year > currentYear + 10) {
                    return `${fieldName} debe estar entre 1900 y ${currentYear + 10}.`
                }
            }
            return null
        },

        positiveNumber: (value, fieldName) => {
            if (value !== null && value !== undefined && value !== '') {
                const num = parseFloat(value)
                if (isNaN(num) || num < 0) {
                    return `${fieldName} debe ser un número positivo.`
                }
            }
            return null
        },

        range: (value, min, max, fieldName) => {
            if (value !== null && value !== undefined && value !== '') {
                const num = parseFloat(value)
                if (isNaN(num) || num < min || num > max) {
                    return `${fieldName} debe estar entre ${min} y ${max}.`
                }
            }
            return null
        }
    }

    // Función para validar campos individuales
    const validateField = (value, rules, fieldName) => {
        for (const rule of rules) {
            const error = rule(value, fieldName)
            if (error) {
                return error
            }
        }
        return null
    }

    // Función para validar formularios completos
    const validateForm = (formData, validationRules) => {
        errors.value = {}
        let isValid = true

        for (const [field, rules] of Object.entries(validationRules)) {
            const error = validateField(formData[field], rules.validators, rules.label)
            if (error) {
                errors.value[field] = error
                isValid = false
            }
        }

        return isValid
    }

    // Limpiar errores
    const clearErrors = () => {
        errors.value = {}
    }

    // Limpiar error de un campo específico
    const clearFieldError = (field) => {
        delete errors.value[field]
    }

    // Reglas de validación predefinidas para casos comunes
    const commonRules = {
        nombre: {
            label: 'El nombre',
            validators: [
                validators.required,
                (v) => validators.minLength(v, 2, 'El nombre'),
                (v) => validators.maxLength(v, 100, 'El nombre'),
                (v) => validators.lettersOnly(v, 'El nombre'),
            ]
        },
        apellido: {
            label: 'El apellido',
            validators: [
                validators.required,
                (v) => validators.minLength(v, 2, 'El apellido'),
                (v) => validators.maxLength(v, 100, 'El apellido'),
                (v) => validators.lettersOnly(v, 'El apellido'),
            ]
        },
        email: {
            label: 'El email',
            validators: [
                validators.required,
                (v) => validators.email(v, 'El email'),
                (v) => validators.maxLength(v, 255, 'El email'),
            ]
        },
        dni: {
            label: 'El DNI',
            validators: [
                validators.required,
                (v) => validators.dni(v, 'El DNI'),
            ]
        },
        telefono: {
            label: 'El teléfono',
            validators: [
                (v) => validators.phone(v, 'El teléfono'),
            ]
        },
        password: {
            label: 'La contraseña',
            validators: [
                validators.required,
                (v) => validators.password(v, 'La contraseña'),
            ]
        },
        passwordOptional: {
            label: 'La contraseña',
            validators: [
                (v) => v ? validators.password(v, 'La contraseña') : null,
            ]
        }
    }

    return {
        errors,
        validators,
        validateField,
        validateForm,
        clearErrors,
        clearFieldError,
        commonRules
    }
}
