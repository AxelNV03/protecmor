<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;        // <--- Agregar esto
use App\Models\Profesore;   // <--- Si no lo agregaste todavía
use Illuminate\Support\Facades\Hash; // <--- Para Hash::make

class ProfeController extends Controller
{
    public function index()
    {
         // Profesores
        $profes = Profesore::with('user')->get();

        // Administradores (para la sección de admins)
        $admins = User::role('admin')->get();

        return view('admin.dashboard', compact('profes', 'admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|string|min:6|confirmed',
            'especialidad'=> 'required|string|max:255',
            'fecha_ingreso' => 'nullable|date',
        ]);

        // 1. Crear usuario
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('profesor');

        // 2. Crear profesor vinculado al usuario
        Profesore::create([
            'user_id'       => $user->id,
            'especialidad'  => $request->especialidad,
            'fecha_ingreso' => $request->fecha_ingreso,
        ]);

        return redirect()->route('profesores.index')->with('success', 'Profesor creado correctamente');
    }

    public function update(Request $request, Profesore $profesor)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,'.$profesor->user_id,
            'password'    => 'nullable|string|min:6|confirmed',
            'especialidad'=> 'required|string|max:255',
            'fecha_ingreso' => 'nullable|date',
        ]);

        // Actualizar usuario
        $profesor->user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $profesor->user->password = Hash::make($request->password);
            $profesor->user->save();
        }

        // Actualizar profesor
        $profesor->update([
            'especialidad'  => $request->especialidad,
            'fecha_ingreso' => $request->fecha_ingreso,
        ]);

        return redirect()->route('profesores.index')->with('success', 'Profesor actualizado correctamente');
    }

    public function destroy(Profesore $profesor)
    {
        $profesor->user->delete(); // Borra también al usuario
        $profesor->delete();

        return redirect()->route('profesores.index')->with('success', 'Profesor eliminado correctamente');
    }
}
