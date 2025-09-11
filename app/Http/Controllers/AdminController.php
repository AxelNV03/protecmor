<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\SaveAdminRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View; // <-- Importar View
use Illuminate\Support\Facades\DB; // <-- ¡IMPORTANTE!
use Illuminate\Support\Facades\Mail; // ← Agregar esta línea
use App\Mail\UserCredentialsMail;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.dashboard');
    }

    /**
     * Return JSON data for DataTables.
     */
    public function data(): \Illuminate\Http\JsonResponse
    {
        $admins = User::role(['admin', 'super admin'])->get();
        return response()->json($admins);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveAdminRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $password = User::generatePassword(); // Generate password outside
        
        // Declare the variable before the transaction
        $admin = null;

        DB::transaction(function () use ($validated, $password, &$admin) { // Pass by reference
            // Create and assign the admin inside
            $admin = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($password),
                'telefono'  => $validated['telefono'] ?? null,
                'estatus'   => 'activo',
            ]);
            $admin->assignRole('admin');
        });

        // Now $admin is accessible here
        if ($admin) {
            Mail::to($admin->email)->send(new UserCredentialsMail(
                $admin->name,
                $admin->email,
                $password,
                'administrador'
            ));
        }

        return redirect()->route('admin.index', ['tab' => 'admins'])
            ->with('success', 'Administrador creado y credenciales enviadas.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveAdminRequest $request, User $admin): RedirectResponse
    {
        $validated = $request->validated();  // Validar datos

        // Actualizamos los datos del modelo User dentro de una transacción
        DB::transaction(function () use ($validated, $admin) {
            $admin->update([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'telefono'  => $validated['telefono'] ?? null,
                'estatus'   => $validated['estatus'], // Asegurarse de que 'estatus' venga del formulario
            ]);       

            if (!empty($validated['password'])) {
                $admin->password = Hash::make($validated['password']);
                $admin->save();  
            }
        });

        // Si se actualiza enviar la nueva contraseña por email
        if (!empty($validated['password'])) {
            // Enviar email con la nueva contraseña
            Mail::to($validated['email'])->send(new UserCredentialsMail(
                $validated['name'],
                $validated['email'],
                $validated['password'], // La contraseña en texto plano
                'administrador'
            ));
        }

        return redirect()->route('admin.index')
            ->with('success', 'Administrador actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $admin): RedirectResponse
    {
        // 1. Verificación de permisos (se mantiene igual)
        if (!auth()->user()->hasRole('super admin')) {
            abort(403, 'This action is unauthorized.');
        }

        // 2. Verificación de auto-eliminación (se mantiene igual)
        if (auth()->id() === $admin->id) {
            return redirect()->route('admin.index', ['tab' => 'admins'])
                ->with('error', 'No puedes eliminar tu propia cuenta de super-admin.');
        }

        // 3. Ejecutamos el borrado lógico
        $admin->delete();

        // Opcional: Cambiar el estatus
        // $admin->update(['estatus' => 'inactivo']);

        return redirect()->route('admin.index', ['tab' => 'admins'])
            ->with('success', 'Administrador archivado correctamente.');
    }
}

