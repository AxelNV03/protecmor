<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NombreValido;
use App\Rules\EmailUnico;
use App\Rules\PasswordSegura;
use App\Rules\TelefonoValido;
use Illuminate\Validation\Rule; // <-- Importante para validaciones avanzadas

class SaveAdminRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        // La autorización es la misma para ambos casos.
        return auth()->check() && auth()->user()->hasRole('super admin');
    }

    /**
     * Obtiene las reglas de validación que aplican a la petición.
     */
    public function rules(): array
    {
        // Obtenemos el ID del admin que se está actualizando. Será null al crear.
        $adminId = $this->route('admin')?->id;
        
        // Usamos las reglas de "creación" como base.
        $rules = [
            'name'          => ['required', new NombreValido],
            'email'         => ['required', 'email',    Rule::unique('users')->ignore($adminId)],          
            'estatus'       => ['nullable', Rule::in(['activo', 'inactivo'])],            
            'telefono'      => ['nullable', new TelefonoValido, Rule::unique('users', 'telefono')->ignore($adminId)],
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
        ];
    }
}