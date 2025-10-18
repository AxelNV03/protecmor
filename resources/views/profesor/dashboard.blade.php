<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>PROTECMOR - Panel de Profesor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Panel de Profesor</h1>
        <div class="flex items-center gap-4">
            <span class="text-gray-600">Bienvenido, {{ auth()->user()->name }}</span>
            <span class="text-xs rounded-full bg-blue-100 text-blue-700 px-2 py-1">
                {{ Str::title(auth()->user()->getRoleNames()->first() ?? 'profesor') }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-red-500">Cerrar Sesión</button>
            </form>
        </div>
    </nav>

    <!-- Layout -->
    <div class="container mx-auto p-6 flex flex-col md:flex-row">

        <!-- Alpine state with URL sync -->
        <div
            x-data="{
                activeTab: new URLSearchParams(window.location.search).get('tab') || 'mi-perfil',
                changeTab(tab) {
                    this.activeTab = tab;
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tab);
                    history.pushState({}, '', url);
                }
            }"
            class="flex w-full flex-col md:flex-row gap-6"
        >

            <!-- Sidebar -->
            @include('profesor.parts.sidebar')

            <!-- Main content -->
            <main class="bg-white p-6 rounded-lg shadow-md w-full md:w-3/4">
                <!-- Mi Perfil -->
                <div x-show="activeTab === 'mi-perfil'">
                    @includeWhen(view()->exists('profesor.parts.mi-perfil'), 'profesor.parts.mi-perfil')
                </div>

                <!-- Mis Clases (listado + acceso al panel de clase) -->
                <div x-show="activeTab === 'clases'">
                    @include('clases.dashboard')
                </div>

                <!-- Material Didáctico (acceso resumido por clase) -->
                <div x-show="activeTab === 'materiales'">
                    @includeWhen(view()->exists('profesor.parts.materiales'), 'profesor.parts.materiales')
                </div>

                <!-- Asistencias (módulo de carga vía Excel por clase) -->
                <div x-show="activeTab === 'asistencias'">
                    @includeWhen(view()->exists('profesor.parts.asistencias'), 'profesor.parts.asistencias')
                </div>

                <!-- Calificaciones (captura/edición por clase) -->
                <div x-show="activeTab === 'calificaciones'">
                    @includeWhen(view()->exists('profesor.parts.calificaciones'), 'profesor.parts.calificaciones')
                </div>

                <!-- Chat Interno (por clase) -->
                <div x-show="activeTab === 'chat-interno'">
                    @includeWhen(view()->exists('profesor.parts.chat-interno'), 'profesor.parts.chat-interno')
                </div>

                <!-- (Opcional) Reportes rápidos del profesor -->
                <div x-show="activeTab === 'reportes'">
                    @includeWhen(view()->exists('profesor.parts.reportes'), 'profesor.parts.reportes')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
