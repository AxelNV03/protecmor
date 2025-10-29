<div x-show="activeTab == 'inicio'">
    {{-- Información General --}}

    <h1>{{ $clase->nombre }}</h1>
    <h2><strong>Clave:</strong> {{ $clase->clave }}</h2>
    <hr>

    {{-- Detalles --}}
    <ul>
        <li><strong>Grupo:</strong> {{ $clase->grupo->nombre ?? 'Sin grupo' }}</li>
        <li>
            <strong>Campo Formativo:</strong> {{ $clase->campoFormativo->nombre ?? 'N/A' }} 
            (<em>Tipo: {{ $clase->campoFormativo->tipo ?? 'N/A' }}</em>)
        </li>
        <li><strong>Profesor:</strong> {{ $clase->profesor->full_name ?? 'Sin Asignar' }}</li>
        <li><strong>Estado:</strong> {{ Str::ucfirst($clase->estado) }} {{-- 'ucfirst' pone la primera letra en mayúscula --}}</li>
        <li><strong>Inicio:</strong> {{ $clase->fecha_inicio ? \Carbon\Carbon::parse($clase->fecha_inicio)->format('d/m/Y') : 'N/A' }}</li>
        <li>
            <strong>Fin:</strong>
            {{-- Condicional para mostrar la fecha de fin o 'N/A' --}}
            @if($clase->estado === 'finalizada' && $clase->fecha_fin)
                {{ \Carbon\Carbon::parse($clase->fecha_fin)->format('d/m/Y') }}
            @else
                N/A
            @endif
        </li>
    </ul>
    <hr>
</div>