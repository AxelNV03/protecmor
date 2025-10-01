<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NombreValido;
use App\Rules\PasswordSegura;
use App\Rules\TelefonoValido;
use Illuminate\Validation\Rule; // <-- Importante para validaciones avanzadas


class SaveProfeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['super admin', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Obtenemos el ID del usuario del profesor. Será null al crear.
        $userId = $this->profesor?->user_id;

        $rules = [
            'name'                => ['required', new NombreValido],
            'apeP'                => ['required', new NombreValido],
            'apeM'                => ['required', new NombreValido],
            'direccion'           => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento'    => ['nullable', 'date'],
            'email'               => ['required', 'email', Rule::unique('users')->ignore($userId)],
            'estatus'             => ['nullable', Rule::in(['activo', 'inactivo'])],
            'telefono' => [
                'nullable', 
                new TelefonoValido, 
                Rule::unique('users', 'telefono')->ignore($userId)
            ],
            'especialidad'        => ['required', 'string', 'max:255'],
            'sexo'                => ['required', Rule::in(['Masculino', 'Femenino', 'Otro'])],
            'telefono_emergencia' => ['nullable', new TelefonoValido],
        ];

        // Si es UPDATE, agregar regla de password opcional
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return array_merge($rules, [
                'password' => ['nullable', 'confirmed', new PasswordSegura]
            ]);
        }

        return $rules;
    }

    /**
        * Obtiene los mensajes de error personalizados.
    */
    public function messages(): array
    {
        return [
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'sexo.in' => 'El sexo seleccionado no es válido.',
        ];
    }
}


