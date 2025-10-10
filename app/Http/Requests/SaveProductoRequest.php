<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Si el usuario ya está autenticado en la ruta 'admin', puedes devolver true.
        // O puedes añadir lógica más específica de permisos: auth()->user()->can('manage-products')
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'precio' => 'required|numeric|min:0', 
            'costo' => 'nullable|numeric|min:0', 
            'categoria' => 'required|string|max:255',
            'disponible' => 'required|in:1,0', 
            'imagen' => 'nullable|image|max:2048', 
        ];
    }
}