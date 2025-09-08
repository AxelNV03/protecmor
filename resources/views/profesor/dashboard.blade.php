@extends('layouts.app') {{-- Indica que usará el layout app.blade.php --}}

@section('title', 'Página de Inicio') {{-- Reemplaza el título dinámico --}}

@section('content')
    <div class="container">
        <h1>Bienvenido a PROTECMOR 🎉</h1>
        <p>Este es el inicio de tu sistema.</p>
    </div>

    <!-- En tu layout principal -->
    <h1>Bienvenido Profesor, {{ auth()->user()->name }}!</h1>
    <p>Rol: {{ auth()->user()->getRoleNames()->first() }}</p>
    
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar Sesión</button>
    </form> <br>
    <a href="{{ url('/') }}">Ir al Inicio</a>
@endsection
    
