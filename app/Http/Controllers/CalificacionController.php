<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calificacione;
use Illuminate\Http\Response;
use Illuminate\View\View; // <-- Importar View
use Illuminate\Support\Facades\Auth; // <-- ¡IMPORTANTE!
use App\Http\Requests\SaveClaseRequest; // <-- CAMBIO CLAVE: Usar el request correcto
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;


class CalificacionController extends Controller
{
    public function store(Request $request)
    {
        // Validación de los datos
        $validated = $request->validate([
            'campo_id' => 'required|exists:clases,id',
            'alumno_id' => 'required|exists:alumnos,id',
            'calificacion' => 'nullable|numeric|between:0,10',
            'nivel_desempeno' => 'nullable|string',
        ]);

        // Crear la calificación
        Calificacione::create($validated);

        return back()->with('success', 'Calificación asignada correctamente.');
    }

    public function update(Request $request, Calificacione $calificacion): RedirectResponse
    {
        // 1. Determinamos el tipo de clase para saber qué validar
        $tipoClase = $calificacion->campoFormativo->tipo;

        $rules = [];
        if ($tipoClase === 'materia') {
            $rules['calificacion'] = ['required', 'integer', 'between:0,10'];
        } else {
            $rules['nivel_desempeno'] = ['required', Rule::in(['Bajo', 'Regular', 'Bueno', 'Excelente'])];
        }

        // 2. Validamos la petición con las reglas que acabamos de definir
        $validated = $request->validate($rules);

        // 3. Actualizamos la calificación con los datos validados
        $calificacion->update($validated);

        // 4. Redirigimos a la página anterior con un mensaje de éxito
        return back()->with('success', 'Calificación actualizada correctamente.');
    }
}
