<!-- resources/views/admin/dashboard.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>PROTECMOR - Admin Dashboard</title>
</head>

<body>
    <!-- En tu layout principal -->
    <h1>Bienvenido Admin, {{ auth()->user()->name }}!</h1>
    <p>Rol: {{ auth()->user()->getRoleNames()->first() }}</p>
    
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar Sesión</button>
    </form> <br>
    <a href="{{ url('/') }}">Ir al Inicio</a>




    
    <!-- Lista lateral -->
    <hr class="my-5">
    <h1>Lista lateral</h1>
    
    <div
        x-data="{ 
            activeTab: new URLSearchParams(window.location.search).get('tab') || 'admins',

            changeTab(tab) {
                this.activeTab = tab;
                
                const url = new URL(window.location);
                url.searchParams.set('tab', tab);
                history.pushState({}, '', url);
            }
        }"
    >
        <!-- Menú lateral -->
        @include('admin.parts.sidebar')
        
        <!-- Contenido dinámico -->
        <br><hr class="my-5">
        <div class="content">
            @role('super admin')
                @include('admin.parts.admins')
            @endrole

            @php
                $sections = [
                    'alumnos', 'profesores', 'grupos', 'clases', 'materiales', 'talleres', 'materias', 'eventos','pases_lista','pagos', 'calificaciones', 'reportes', 'respaldos'
                ];
            @endphp

            @foreach($sections as $section)
                @include("admin.parts.{$section}")
            @endforeach
        
        </div>
    </div>





    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>

