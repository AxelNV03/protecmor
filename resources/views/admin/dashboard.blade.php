<!-- resources/views/admin/dashboard.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROTECMOR - Admin Dashboard</title>

    {{-- Estilos (ejemplo con Bootstrap para que se vea ordenado) --}}
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    {{-- Script de Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</head>

<body>
    <h1>Bienvenido Admin, {{ auth()->user()->name }}!</h1>
    <p>Rol: {{ auth()->user()->getRoleNames()->first() }}</p>
    
    <form method="POST" action="{{ route('logout') }}">
        @csrf <button type="submit">Cerrar Sesión</button>
    </form> <br>
    <a href="{{ url('/') }}">Ir al Inicio</a>
    
    <!-- Lista lateral -->
    <hr class="my-5">
    <h1>Lista lateral</h1>
    
    <div x-data="{ activeTab: new URLSearchParams(window.location.search).get('tab') || 'admins',
        changeTab(tab) {
            this.activeTab = tab;
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            history.pushState({}, '', url);
        }
    }">
        <h3 style="background: yellow;">
            Pestaña activa actual: <span x-text="activeTab"></span>
        </h3>

        <!-- Menú lateral -->
        @include('admin.parts.sidebar')
            
        <!-- Contenido dinámico -->
        <br><hr class="my-5">
        <div class="content">
            @role('super admin')
                @include('admin.parts.admins')
            @endrole
            
            @include('clases.dashboard')

            @php
                $sections = [
                    'alumnos', 'profesores', 'grupos', 'campos', 'eventos', 'pases_lista', 'pagos', 'calificaciones', 'reportes', 'respaldos'
                ];
            @endphp

            @foreach($sections as $section)
                @include('admin.parts.' . $section)
            @endforeach

        </div>
    </div>
</body>
</html>

