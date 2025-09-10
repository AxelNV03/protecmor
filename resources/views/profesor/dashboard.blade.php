<!DOCTYPE html>
<html>
<head>
    <title>PROTECMOR - Profesor Dashboard</title>
</head>

<body>
    <!-- Encabezado -->
    <h1>Bienvenido Profesor, {{ auth()->user()->name }}!</h1>
    <p>Rol: {{ auth()->user()->getRoleNames()->first() }}</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar Sesión</button>
    </form> <br>
    <a href="{{ url('/') }}">Ir al Inicio</a>

    <hr class="my-5">

    <!-- Lista lateral -->
    <h1>Lista lateral</h1>
    <div x-data="{ activeTab: 'mis_clases' }">
        <!-- Menú lateral -->
        @include('profesor.parts.sidebar')

        <!-- Contenido dinámico -->
        <br><hr class="my-5">
        <div class="content">
            @php
                // Secciones que un profesor puede ver
                $sections = [
                    'mis_clases', 
                    'mis_alumnos', 
                    'materiales', 
                    'tareas', 
                    'calificaciones',
                ];
            @endphp

            @foreach($sections as $section)
                @include("profesor.parts.{$section}")
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
