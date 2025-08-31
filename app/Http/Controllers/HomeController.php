<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Lógica de redirección por roles
        if (auth()->user()->hasRole('admin|super admin')) {
            return redirect()->route('admin.dashboard'); 
        } elseif (auth()->user()->hasRole('profesor')) {
            return view('profesor.dashboard');
        } elseif (auth()->user()->hasRole('alumno')) {
            return view('alumno.dashboard');
        }
        
        // Redirigir a inicio si no tiene rol asignado
        return redirect('/');
    }
}
