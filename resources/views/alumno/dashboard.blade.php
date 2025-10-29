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
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1/dist/echo.iife.js"></script>
    <script>
    window.Pusher = Pusher;

    const wsHost = window.location.hostname; // ← 127.0.0.1 o localhost según abras el sitio
    const wsPort = {{ config('reverb.default.port', 8080) }}; // ← el que pusiste en .env
    Pusher.logToConsole = true; // ← para depuración en consola (quítalo en producción)
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ config('reverb.apps.0.key') }}',
        wsHost: wsHost,
        wsPort: wsPort,
        wssPort: wsPort,
        forceTLS: window.location.protocol === 'https:', // http false, https true
        enabledTransports: ['ws', 'wss'],
        authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
            fetch('/broadcasting/auth', {
                method: 'POST',
                headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                socket_id: socketId,
                channel_name: channel.name
                }),
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(data => callback(false, data))
            .catch(err => callback(true, err));
            }
        };
        },
    });
    </script>
    
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
                
                <div x-show="activeTab === 'clases">
                    @include('clases.dashboard')
                </div>
                
                @include('alumno.parts.calificaciones')

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