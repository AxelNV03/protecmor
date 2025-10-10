<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View; // <-- Importar View
use Illuminate\Support\Facades\DB; // <-- ¡IMPORTANTE!
use App\Http\Requests\SaveClaseRequest; // <-- CAMBIO CLAVE: Usar el request correcto
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str; // <-- No olvides importar la clase

class ClaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function data(): \Illuminate\Http\JsonResponse
    {
        $clases = Clase::with(['grupo', 'profesor.user', 'campoFormativo'])->get();
        return response()->json($clases);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveClaseRequest $request): RedirectResponse
    {
        // 1. Los datos ya vienen validados gracias a SaveClaseRequest
        $validated = $request->validated();

        // 2. Creamos la nueva clase
        Clase::create([
            'clave'              => Clase::generarClave($validated['nombre']),
            'nombre'             => $validated['nombre'],
            'grupo_id'           => $validated['grupo_id'],
            'profesor_id'        => $validated['profesor_id'],
            'campo_formativo_id' => $validated['campo_formativo_id'],
            'estado'             => 'en curso', // Asignado automáticamente
            'fecha_inicio'       => now(),      // Asignado automáticamente
        ]);

        // 3. Redirigimos con un mensaje de éxito
        return redirect()->route('admin.index', ['tab' => 'clases'])
            ->with('success', 'Clase creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Clase $clase): View
    {
        // Carga todas las relaciones necesarias de forma eficiente
        $clase->load(['profesor.user', 'grupo', 'campoFormativo', 'materiales']);
    
        return view('clases.single_class', [
            'clase' => $clase
        ], [ 
            'tab' => 'inicio' 
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clase $clase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clase $clase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clase $clase)
    {
        //
    }
}
