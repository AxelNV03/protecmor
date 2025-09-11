<?php

namespace App\Http-Controllers;

use Illuminate-Http-Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = auth()->user();

        // ✅ CORREGIDO: Usamos hasAnyRole con un array
        if ($user->hasAnyRole(['super admin', 'admin'])) {
            return redirect()->route('admins.index'); // <-- Asegúrate que sea 'admins.index'
        }
        
        if ($user->hasRole('profesor')) {
            return view('profesor.dashboard');
        }
        
        if ($user->hasRole('alumno')) {
            return view('alumno.dashboard');
        }
        
        // ✅ MEJORADO: Si un usuario autenticado no tiene rol,
        // lo mejor es cerrar su sesión y redirigirlo al login
        // para evitar bucles o accesos no deseados.
        auth()->logout();
        
        return redirect('/login')->with('error', 'No tienes un rol asignado. Contacta al administrador.');
    }
}