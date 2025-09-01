<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = User::role('admin')->with('roles')->get();
        return view('admin.dashboard',[
            'admins' => $admins
        ]);
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
    public function store(Request $request): RedirectResponse
    {
        // dd($request->all()); // Muestra todos los datos y detiene la ejecución
        
        $admin=User::create($request->all());
        $admin->assignRole('admin');
        return redirect()->route('admin.index');   
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Encontrar el administrador o fallar
        $admin = Admin::findOrFail($id);

        // Validar los datos del request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->ignore($admin->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'estatus' => 'required|in:activo,inactivo',
        ]);

        // Actualizar los campos
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->telefono = $validated['telefono'] ?? null;
        $admin->estatus = $validated['estatus'];

        // Actualizar la contraseña si se proporciona
        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        // Guardar los cambios
        $admin->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('admin.index')
            ->with('success', 'Administrador actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
