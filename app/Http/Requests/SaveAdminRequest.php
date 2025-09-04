<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NombreValido;
use App\Rules\EmailUnico;
use App\Rules\PasswordSegura;
use App\Rules\TelefonoValido;

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
        // Usamos las reglas de "creación" como base.
        $rules = [
            'name'     => ['required', new NombreValido],
            'password' => ['required', 'confirmed', new PasswordSegura],
            'telefono' => ['nullable', new TelefonoValido],
        ];

        // Si es update, usamos el ID del admin en la ruta. En create, será null.
        $adminId = $this->route('admin')?->id;
        
        //    - Si $adminId es null (creando), busca el email en toda la tabla.
        //    - Si $adminId tiene un valor (actualizando), ignora ese ID en la búsqueda.
        $rules['email'] = ['required', 'email', new EmailUnico($adminId)];

        // 4. Si el método es PUT o PATCH (estamos actualizando),
        //    hacemos las reglas de contraseña y teléfono menos estrictas.
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // La contraseña es opcional al actualizar.
            $rules['password'][0] = 'nullable';
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