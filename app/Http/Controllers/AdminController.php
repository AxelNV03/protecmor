<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\SaveAdminRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View; // <-- Importar View

class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard');
    }

    public function data(): \Illuminate\Http\JsonResponse
    {
        $admins = User::role(['admin', 'super admin'])->get();
        return response()->json($admins);
    }

    public function create(): View
    {
        //
    }

    public function store(SaveAdminRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'telefono' => $validated['telefono'] ?? null,
        ]);
        $admin->assignRole('admin');

        return redirect()->route('admin.index')->with('success', 'Administrador creado correctamente');
    }

    public function edit(User $admin): View // <-- Usando Route Model Binding
    {
        //
    }

    public function update(SaveAdminRequest $request, User $admin): RedirectResponse // <-- Usando Route Model Binding
    {
        // ✅ No necesitas buscar al usuario, Laravel ya lo hizo por ti.
        $validated = $request->validated();

        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? null,
        ]);

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
            $admin->save();
        }

        return redirect()->route('admin.index')->with('success', 'Administrador actualizado correctamente');
    }

    public function destroy(User $admin): RedirectResponse // <-- Usando Route Model Binding
    {
            // ✅ La autorización ahora está en la Policy, más limpio.
        if (!auth()->user()->hasRole('super admin')) {
            // Si no lo tiene, detenemos todo y mostramos un error 403.
            abort(403, 'This action is unauthorized.');
        }

        // 2. Verificamos que no se esté intentando eliminar a sí mismo.
        if (auth()->id() === $admin->id) {
            return redirect()->route('admin.index')
                ->with('error', 'No puedes eliminar tu propia cuenta de super-admin.');
        }

        $admin->delete();

        return redirect()->route('admin.index')->with('success', 'Administrador eliminado correctamente');
    }

    public function activate(User $admin): RedirectResponse // <-- Corregido el Type Hint
    {
        $admin->estatus = 'activo';
        $admin->save();
        return redirect()->back()->with('success', 'Administrador activado');
    }

    public function deactivate(User $admin): RedirectResponse // <-- Corregido el Type Hint
    {
        $admin->estatus = 'inactivo';
        $admin->save();
        return redirect()->back()->with('success', 'Administrador desactivado');
    }
}