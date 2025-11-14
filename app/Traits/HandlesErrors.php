<?php

namespace App\Traits;

trait HandlesErrors
{
    /**
     * Convierte errores de base de datos a mensajes amigables
     */
    protected function getFriendlyErrorMessage(\Exception $e, string $defaultMessage = 'Error inesperado'): string
    {
        // Si es un error de base de datos
        if ($e instanceof \Illuminate\Database\QueryException) {
            $sqlMessage = $e->getMessage();

            // Campos nulos
            if (str_contains($sqlMessage, "cannot be null")) {
                if (str_contains($sqlMessage, "Column 'nombre'")) {
                    return 'El nombre es obligatorio. Por favor completa este campo.';
                }
                if (str_contains($sqlMessage, "Column 'apellido'")) {
                    return 'El apellido es obligatorio. Por favor completa este campo.';
                }
                if (str_contains($sqlMessage, "Column 'dni'")) {
                    return 'El DNI es obligatorio. Por favor completa este campo.';
                }
                if (str_contains($sqlMessage, "Column 'carrera'")) {
                    return 'La carrera es obligatoria. Por favor selecciona una carrera.';
                }
                return 'Faltan campos obligatorios. Por favor completa todos los campos requeridos.';
            }

            // Duplicados
            if (str_contains($sqlMessage, "Duplicate entry")) {
                if (str_contains($sqlMessage, "for key 'dni'") || str_contains($sqlMessage, "tbl_alumnos.dni") || str_contains($sqlMessage, "tbl_profesores.dni")) {
                    return 'Ya existe un registro con este DNI.';
                }
                if (str_contains($sqlMessage, "nombre") && str_contains($sqlMessage, "carrera")) {
                    return 'Ya existe una materia con este nombre en esta carrera.';
                }
                return 'Ya existe un registro con esos datos.';
            }

            // Claves foráneas
            if (str_contains($sqlMessage, "foreign key constraint")) {
                if (str_contains($sqlMessage, "FOREIGN KEY (`alumno_id`)") || str_contains($sqlMessage, "FOREIGN KEY (`alumno`)")) {
                    return 'El alumno seleccionado no existe o no es válido.';
                }
                if (str_contains($sqlMessage, "FOREIGN KEY (`materia_id`)") || str_contains($sqlMessage, "FOREIGN KEY (`materia`)")) {
                    return 'La materia seleccionada no es válida.';
                }
                if (str_contains($sqlMessage, "FOREIGN KEY (`carrera_id`)") || str_contains($sqlMessage, "FOREIGN KEY (`carrera`)")) {
                    return 'La carrera seleccionada no es válida.';
                }
                if (str_contains($sqlMessage, "FOREIGN KEY (`profesor_id`)") || str_contains($sqlMessage, "FOREIGN KEY (`profesor`)")) {
                    return 'El profesor seleccionado no es válido.';
                }
                return 'Los datos seleccionados no son válidos. Verifica la información ingresada.';
            }

            // Datos demasiado largos
            if (str_contains($sqlMessage, "Data too long")) {
                if (str_contains($sqlMessage, "column 'nombre'")) {
                    return 'El nombre es demasiado largo. Reduce la cantidad de caracteres.';
                }
                if (str_contains($sqlMessage, "column 'apellido'")) {
                    return 'El apellido es demasiado largo. Reduce la cantidad de caracteres.';
                }
                if (str_contains($sqlMessage, "column 'dni'")) {
                    return 'El DNI es demasiado largo. Verifica que sea correcto.';
                }
                return 'Uno de los campos excede el tamaño máximo permitido. Reduce la cantidad de caracteres.';
            }

            // Tipo de dato incorrecto
            if (str_contains($sqlMessage, "Incorrect integer value")) {
                return 'Uno de los campos numéricos contiene un valor inválido. Verifica los datos ingresados.';
            }

            if (str_contains($sqlMessage, "Incorrect string value")) {
                return 'Uno de los campos de texto contiene caracteres no válidos. Verifica la información ingresada.';
            }

            if (str_contains($sqlMessage, "doesn't have a default value")) {
                return 'Faltan campos obligatorios. Por favor completa el formulario correctamente.';
            }

            // Error SQL genérico pero más específico
            return 'Error al procesar los datos. Verifica que la información sea correcta y completa.';
        }

        // Si es un error de validación de Laravel
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return 'Los datos proporcionados no son válidos.';
        }

        // Si es un error de autorización
        if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return 'No tienes permisos para realizar esta acción.';
        }

        // Si es un error de modelo no encontrado
        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return 'El registro solicitado no fue encontrado.';
        }

        // Error genérico
        return $defaultMessage . '. Por favor intenta nuevamente.';
    }

    /**
     * Maneja errores y los registra en el log
     */
    protected function handleError(\Exception $e, string $context = 'operación', array $logData = []): void
    {
        $logData['error'] = $e->getMessage();
        $logData['file'] = $e->getFile();
        $logData['line'] = $e->getLine();

        if ($e instanceof \Illuminate\Database\QueryException) {
            $logData['sql_code'] = $e->getCode();
            \Log::error("Error de base de datos en {$context}", $logData);
        } else {
            \Log::error("Error en {$context}", $logData);
        }
    }
}
