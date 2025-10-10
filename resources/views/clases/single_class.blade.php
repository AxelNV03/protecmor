<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de la Clase</title>
    
    {{-- Estilos (ejemplo con Bootstrap para que se vea ordenado) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Script de Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- <style>
        .active {
            font-weight: bold;
            text-decoration: underline;
            color: blue;
        }
    </style> -->
</head>
<body class="container mt-4">
    {{-- Componente principal de Alpine.js --}}
    <div x-data="{ activeTab: 'inicio' }">
        
        {{-- Este @if solo se evaluará si el usuario tiene el rol correcto --}}
        @hasanyrole('super admin|profesor')
            @if($clase->estado === 'en curso')
                <a href="{{-- tu ruta para finalizar --}}" class="btn btn-primary mb-3">
                    Finalizar Clase
                </a>
            @endif
        @endhasanyrole

        {{-- Barra de Pestañas --}}
        @include('clases.pestanias')
        <hr>


        {{-- Contenido de las Pestañas --}}
        <div class="tab-content p-3 border">
            @include('clases.inicio')
            @include('clases.material')            
            @hasanyrole('profesor')
                @include('clases.asistencias')           
            @endhasanyrole 
            @include('clases.alumnos')            
            @include('clases.chat')            



            {{-- Botón para regresar a la lista de administración --}}
            <a href="{{ route('admin.index', ['tab' => 'clases']) }}" class="btn btn-primary mb-3">
                &larr; Regresar al Listado
            </a>
        </div>
    </div>
</body>
</html>