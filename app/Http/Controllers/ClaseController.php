<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View; // <-- Importar View
use Illuminate\Support\Facades\DB; // <-- ¡IMPORTANTE!
use Illuminate\Support\Facades\Auth; // <-- ¡IMPORTANTE!
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
        $user = auth()->user();

        $clases = \App\Models\Clase::forUser($user)
            ->with([
                'grupo:id,nombre',
                'campoFormativo:id,nombre,tipo',
                'profesor:id,user_id',
                'profesor.user:id,name',
            ])
            ->orderBy('fecha_inicio', 'desc')
            ->get();

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
            'campo_id'           => $validated['campo_id'],
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
    public function show(\App\Models\Clase $clase): \Illuminate\View\View
    {
        // Autoriza (si usas policy, ver punto 4)
        // $this->authorize('view', $clase);

        $clase->load([
            'profesor.user',
            'grupo.alumnos.user',  // 👈 alumnos del grupo
            'campoFormativo',
            'materiales',
        ]);

        return view('clases.single_class', [
            'clase' => $clase,
        ], ['tab' => 'inicio']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clase $clase)
    {
        //
    }

   public function panelCalificaciones(Clase $clase): View
    {
        $clase->load([
            'grupo.alumnos' => function ($query) use ($clase) {
                $query->with(['user', 'calificaciones' => function($q) use ($clase) {
                    // 👇 CORRECCIÓN AQUÍ: Usamos 'campo_id'
                    $q->where('campo_id', $clase->campo_id);
                }]);
            },
            'profesor.user',
            'campoFormativo'
        ]);

        return view('calificaciones.groupC', ['clase' => $clase]);
    }


    /**
     * Update the specified resource in storage.
     */
// En app/Http/Controllers/ClaseController.php

    public function update(SaveClaseRequest $request, Clase $clase): RedirectResponse
    {
        // Los datos ya están validados
        $validated = $request->validated();

        // Actualizamos únicamente los campos permitidos
        $clase->update([
            'nombre'      => $validated['nombre'],
            'profesor_id' => $validated['profesor_id'],
        ]);

        return redirect()->route('admin.index', ['tab' => 'clases'])
            ->with('success', 'Clase actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clase $clase)
    {
        // 1. Autorización
        if (!auth()->user()->hasAnyRole(['super admin', 'admin'])) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. Usar una transacción para la operación completa
        DB::transaction(function () use ($clase) {
            // Paso B: Eliminar el grupo ahora que está vacío
            $clase->delete();
        });

        return redirect()->route('admin.index', ['tab' => 'clases'])
            ->with('success', 'Grupo eliminado y alumnos desvinculados correctamente.');
    }
}
