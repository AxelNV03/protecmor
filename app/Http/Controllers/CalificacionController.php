<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calificacione;
use Illuminate\Http\Response;
use Illuminate\View\View; // <-- Importar View
use Illuminate\Support\Facades\Auth; // <-- ¡IMPORTANTE!
use App\Http\Requests\SaveClaseRequest; // <-- CAMBIO CLAVE: Usar el request correcto
use Illuminate\Http\RedirectResponse;

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
}
