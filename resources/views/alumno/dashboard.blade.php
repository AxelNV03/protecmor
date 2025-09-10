<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROTECMOR - Panel de Alumno</title>
    <!-- Incluir Tailwind CSS desde CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Incluir Alpine.js desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Navbar superior -->
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Panel de Alumno</h1>
        <div class="flex items-center space-x-4">
            <span class="text-gray-600">Bienvenido, {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-red-500">Cerrar Sesión</button>
            </form>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mx-auto p-6 flex flex-col md:flex-row">
        <!-- Contenedor principal con Alpine.js para la navegación de pestañas -->
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
            class="flex flex-col md:flex-row w-full"
        >
            <!-- Barra lateral de navegación -->
            @include('alumno.parts.sidebar')

            <!-- Contenido dinámico de las pestañas -->
            <main class="bg-white p-6 rounded-lg shadow-md w-full md:w-3/4 md:ml-6 mt-6 md:mt-0">
                <div x-show="activeTab === 'mi-perfil'">
                    @include('alumno.parts.mi-perfil')
                </div>
                
                <div x-show="activeTab === 'clases-y-materiales'">
                    @include('alumno.parts.clases-y-materiales')
                </div>
                
                <div x-show="activeTab === 'calificaciones'">
                    @include('alumno.parts.calificaciones')
                </div>

                <div x-show="activeTab === 'mis-pagos'">
                    @include('alumno.parts.mis-pagos')
                </div>
                
                <div x-show="activeTab === 'talleres-y-constancias'">
                    @include('alumno.parts.talleres-y-constancias')
                </div>
                
                <div x-show="activeTab === 'chat-interno'">
                    @include('alumno.parts.chat-interno')
                </div>
            </main>
        </div>
    </div>
</body>
</html>