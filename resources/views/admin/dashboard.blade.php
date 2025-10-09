<!-- resources/views/admin/dashboard.blade.php -->
<!DOCTYPE html>
<html>
    <head>
        <title>PROTECMOR - Admin Dashboard</title>
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
                
                @php
                    $sections = [
                        'alumnos', 'profesores', 'grupos', 'campos', 'clases', 'materiales', 'eventos', 'pases_lista', 'pagos', 'calificaciones', 'reportes', 'respaldos'
                    ];
                @endphp

                @role('super admin')
                <div x-show="activeTab === 'admins'">
                    @include('admin.parts.admins')
                </div>
                @endrole

                <div x-show="activeTab === 'alumnos'">
                    @include('admin.parts.alumnos')
                </div>

                <div x-show="activeTab === 'profesores'">
                    @include('admin.parts.profesores')
                </div>

                <div x-show="activeTab === 'grupos'">
                    @include('admin.parts.grupos')
                </div>

                <div x-show="activeTab === 'campos'">
                    @include('admin.parts.campos')
                </div>

                <div x-show="activeTab === 'clases'">
                    @include('admin.parts.clases')
                </div>

                <div x-show="activeTab === 'materiales'">
                    @include('admin.parts.materiales')
                </div>

                <div x-show="activeTab === 'eventos'">
                    @include('admin.parts.eventos')
                </div>

                <div x-show="activeTab === 'pases_lista'">
                    @include('admin.parts.pases_lista')
                </div>

                <div x-show="activeTab === 'pagos'">
                    @include('admin.parts.pagos')
                </div>

                <div x-show="activeTab === 'calificaciones'">
                    @include('admin.parts.calificaciones')
                </div>

                <div x-show="activeTab === 'reportes'">
                    @include('admin.parts.reportes')
                </div>

                <div x-show="activeTab === 'respaldo_bd'">
                    @include('admin.parts.respaldos')
                </div>

            </div>
        </div>
    </body>
</html>

