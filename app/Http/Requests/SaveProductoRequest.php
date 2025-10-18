<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Producto;
use Illuminate\Validation\Rule;

class SaveProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Asumiendo que solo el administrador puede guardar/actualizar productos
        // Se puede requerir un middleware de rol más estricto si se desea.
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'costo' => ['nullable', 'numeric', 'min:0'],
            'proveedor' => ['nullable', 'string', 'max:255'],
            'categoria' => ['required', 'string', Rule::in(Producto::getCategorias())],
            'disponible' => ['required', 'boolean'],
            'visible' => ['required', 'boolean'],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'], // 2MB max
        ];

        // La imagen es obligatoria al crear (POST), opcional al editar (PUT)
        if ($this->isMethod('POST')) {
            $rules['imagen'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];
        }

        return $rules;
    }

    /**
     * Prepara los datos para la validación (casting de booleanos).
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'disponible' => $this->boolean('disponible'),
            'visible' => $this->boolean('visible'),
        ]);
    }
}