<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Solo admin y bedel pueden crear usuarios
        return in_array($this->user()?->tipo, [1, 2]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^.*[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ]+.*$/', // Al menos una letra
            ],
            'email' => [
                'required',
                'email:rfc',
                'max:100',
                'unique:tbl_usuarios,email',
            ],
            'dni' => [
                'required',
                'string',
                'max:10',
                'regex:/^[0-9]+$/',
                'unique:tbl_usuarios,dni',
            ],
            'tipo' => [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4, 5, 6,7, 8]), // 1=Admin, 2=Bedel, 3=Profesor, 4=Alumno, 5=Directivo, 6=Supervisor, 7=Bedel, 8=Preceptor
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
            'telefono' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9\s\-\+\(\)]+$/',
            ],
            'alumno_id' => 'nullable|exists:tbl_alumnos,id',
            'profesor_id' => 'nullable|exists:tbl_profesores,id',
            'pais' => 'nullable|exists:tbl_paises,id',
            'provincia' => 'nullable|exists:tbl_provincias,id',
            'sexo' => 'nullable|exists:tbl_sexos,id',
            'activo' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'nombre.regex' => 'El nombre debe contener al menos una letra.',

            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe ser una dirección válida.',
            'email.unique' => 'Este email ya está registrado en el sistema.',

            'dni.required' => 'El DNI es obligatorio.',
            'dni.regex' => 'El DNI debe contener solo números.',
            'dni.unique' => 'Este DNI ya está registrado en el sistema.',

            'tipo.required' => 'El tipo de usuario es obligatorio.',
            'tipo.in' => 'El tipo de usuario seleccionado no es válido.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',

            'telefono.regex' => 'El teléfono solo puede contener números, espacios, guiones, paréntesis y signo +.',
        ];
    }
}
