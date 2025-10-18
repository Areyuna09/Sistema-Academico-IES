<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExcepcionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'estado' => 'required|in:aprobada,rechazada',
            'justificacion_administrativa' => 'required|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'estado.required' => 'Debe seleccionar un estado (aprobar o rechazar).',
            'estado.in' => 'El estado debe ser "aprobada" o "rechazada".',
            'justificacion_administrativa.required' => 'La justificación administrativa es obligatoria.',
            'justificacion_administrativa.max' => 'La justificación no puede superar los 1000 caracteres.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'aprobado_por' => auth()->id(),
            'fecha_resolucion' => now(),
        ]);
    }
}
