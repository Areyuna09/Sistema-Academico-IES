<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Solo admin y bedel pueden actualizar usuarios
        return in_array($this->user()?->tipo, [1, 2]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('usuario'); // Obtener ID del usuario siendo editado

        return [
            'nombre' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            ],
            'apellido' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                Rule::unique('tbl_users', 'email')->ignore($userId),
            ],
            'dni' => [
                'required',
                'numeric',
                'digits_between:7,8',
                Rule::unique('tbl_users', 'dni')->ignore($userId),
            ],
            'tipo' => [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4]),
            ],
            'password' => [
                'nullable', // Password es opcional en actualización
                'string',
                'min:8',
                'max:255',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
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
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',

            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.min' => 'El apellido debe tener al menos 2 caracteres.',
            'apellido.max' => 'El apellido no puede exceder los 100 caracteres.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',

            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe ser una dirección válida.',
            'email.unique' => 'Este email ya está registrado en el sistema.',

            'dni.required' => 'El DNI es obligatorio.',
            'dni.numeric' => 'El DNI debe contener solo números.',
            'dni.digits_between' => 'El DNI debe tener entre 7 y 8 dígitos.',
            'dni.unique' => 'Este DNI ya está registrado en el sistema.',

            'tipo.required' => 'El tipo de usuario es obligatorio.',
            'tipo.in' => 'El tipo de usuario seleccionado no es válido.',

            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula y un número.',
        ];
    }
}
