<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreExcepcionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // El middleware admin ya valida el acceso
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'alumno_id' => 'required|exists:tbl_alumnos,id',
            'tipo' => 'required|in:correlativa,reingreso,cambio_carrera,cambio_plan,justificacion_inasistencia,otra',
            'descripcion' => 'required|string|max:500',
            'materia_id' => 'nullable|exists:tbl_materias,id',
            'mesa_examen_id' => 'nullable|exists:tbl_mesas_examen,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'alumno_id.required' => 'Debe seleccionar un alumno.',
            'alumno_id.exists' => 'El alumno seleccionado no existe.',
            'tipo.required' => 'Debe seleccionar el tipo de excepción.',
            'tipo.in' => 'El tipo de excepción no es válido.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max' => 'La descripción no puede superar los 500 caracteres.',
            'materia_id.exists' => 'La materia seleccionada no existe.',
            'mesa_examen_id.exists' => 'La mesa de examen seleccionada no existe.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'solicitado_por' => auth()->id(),
            'fecha_solicitud' => now(),
            'estado' => 'pendiente',
        ]);
    }
}
