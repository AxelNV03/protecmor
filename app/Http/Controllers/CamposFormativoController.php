<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CamposFormativo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View; // <-- Importar View
use Illuminate\Support\Facades\DB; // <-- ¡IMPORTANTE!
use App\Http\Requests\SaveCampoFormativoRequest; 
use Illuminate\Support\Str; // <-- No olvides importar la clase

class CamposFormativoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function data()
    {
        $camposFormativos = CamposFormativo::withCount('clases')->get();
        return response()->json($camposFormativos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveCampoFormativoRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            // Crear el campo formativo
            CamposFormativo::create([
                'nombre' => Str::upper($validated['nombre']),
                'tipo'          => $validated['tipo'],
                'descripcion'   => $validated['descripcion'] ?? NULL,
            ]);
        });

        // Redireccion
        return redirect()->route('admin.index', [ 'tab' => 'campos' ])
            ->with('success', 'Campo creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(CampoFormativo $campoFormativo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CampoFormativo $campoFormativo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CampoFormativo $campoFormativo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CampoFormativo $campoFormativo)
    {
        //
    }
}
