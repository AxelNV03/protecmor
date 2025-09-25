<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View; // <-- Importar View
use Illuminate\Support\Facades\DB; // <-- ¡IMPORTANTE!
use App\Http\Requests\SaveGrupoRequest; // <-- CAMBIO CLAVE: Usar el request correcto
use Illuminate\Http\RedirectResponse;

class GrupoController extends Controller
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
        $grupos = Grupo::withCount('alumnos')->get();
        return response()->json($grupos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveGrupoRequest $request): RedirectResponse
    {
        $validated =  $request->validated();  // Validar datos y crear grupo

        // Validar datos y crear grupo
        DB::transaction(function () use ($validated) {
            // Crear el grupo
            Grupo::create([
                'clave'         => Grupo::generarClave($validated['name']),
                'nombre'        => $validated['name'],
                'generacion'    => $validated['generacion_inicio'] . '-' . $validated['generacion_fin'],
                'observaciones' => $validated['observaciones'] ?? null,
            ]);
        });

        // Redirigir con mensaje de éxito
        return redirect()->route('admin.index', ['tab' => 'grupos'])
            ->with('success', 'Grupo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grupo $grupo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grupo $grupo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grupo $grupo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grupo $grupo)
    {
        //
    }
}
