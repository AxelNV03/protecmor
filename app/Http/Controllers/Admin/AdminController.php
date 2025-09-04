<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;  // ← Para Hash::make()
use Illuminate\Validation\Rule;       // ← Para Rule::unique()
use App\Http\Requests\SaveAdminRequest; // ← Importar tu FormRequest


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
    public function store(SaveAdminRequest $request): RedirectResponse // ← Usar FormRequest
    {
        // ✅ Los datos YA están validados automáticamente
        $validated = $request->validated();

        // Crear el usuario con los datos validados
        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // ← Hashear la contraseña
            'telefono' => $validated['telefono'] ?? null,
        ]);

        // Asignar rol de admin
        $admin->assignRole('admin');

        return redirect()->route('admin.index')
            ->with('success', 'Administrador creado correctamente');
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
    public function update(SaveAdminRequest $request, string $id) // ← Cambiar Request por UpdateAdminRequest
    {        
        // Encontrar el administrador
        $admin = User::findOrFail($id);

        // ✅ Los datos YA están validados - usamos validated() en lugar de all()
        $validated = $request->validated();

        // Actualizar campos (ahora más explícito y seguro)
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->telefono = $validated['telefono'] ?? null; // ← Mejor que fill()

        // Actualizar password solo si se proporcionó
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
       // Verificar que el usuario autenticado es super-admin 
        if (auth()->user()->role !== 'super-admin') {
            abort(403, 'Acción no autorizada.');
        }

        // Prevenir que un super-admin se elimine a sí mismo
        $admin = User::findOrFail($id);
        if ($admin->id === auth()->id()) {
            return redirect()->route('admins.index')
                ->with('error', 'No puedes eliminar tu propia cuenta de super-admin.');
        }

        // Encontrar y eliminar el administrador
        $admin->delete();

        return redirect()->route('admin.index')
            ->with('success', 'Administrador eliminado correctamente');
    }

        // Activar un administrador
    public function activate(Admin $admin)
    {
        $admin->estatus = 'activo';
        $admin->save();
        
        return redirect()->back()->with('success', 'Administrador activado');
    }

    // Desactivar un administrador  
    public function deactivate(Admin $admin)
    {
        $admin->estatus = 'inactivo';
        $admin->save();
        
        return redirect()->back()->with('success', 'Administrador desactivado');
    }
}
