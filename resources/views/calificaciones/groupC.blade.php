<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificaciones de {{ $clase->nombre }}</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body>

{{-- 👇 El x-data ahora envuelve toda la página --}}
<div 
    x-data="{ 
        showModal: false, 
        showEditModal: false, // <-- Añadir para el nuevo modal
        alumnoSeleccionado: null,
        calificacionAEditar: {} // <-- Añadir para guardar los datos de la calificación
    }"
>
    <h1>Clase: {{ $clase->nombre }}</h1>
    <p>Profesor: {{ $clase->profesor->user->name }}</p>
    <p>Estatus: {{ $clase->estado }}</p>

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

    <h2>Alumnos Inscritos</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Matrícula</th>
                <th>Nombre Completo</th>
                <th>Calificación</th>
                @role('profesor|super admin')
                    <th>Acciones</th>
                @endrole
            </tr>
        </thead>
        <tbody>
            @foreach ($clase->grupo->alumnos as $alumno)
                @php
                    $calificacion = $alumno->calificaciones->first();
                @endphp
                <tr>
                    <td>{{ $alumno->matricula }}</td>
                    <td>{{ $alumno->user->name }} {{ $alumno->apeP }} {{ $alumno->apeM }}</td>
                    <td>
                        @if ($calificacion)
                            {{ $clase->campoFormativo->tipo === 'materia' ? $calificacion->calificacion : $calificacion->nivel_desempeno }}
                        @else
                            <span class="text-muted">Sin Calificación</span>
                        @endif
                    </td>

                    @role('profesor|super admin')
                        <td>
                            @if ($clase->estado === 'en curso')
                                @if ($calificacion)
                                    <button 
                                        @click="
                                            showEditModal = true; 
                                            calificacionAEditar = {{ json_encode($calificacion) }};
                                        " 
                                        class="btn btn-warning btn-sm"
                                    >
                                        Editar
                                    </button>

                                @else
                                    {{-- El botón ahora guarda el ID del alumno y abre el modal --}}
                                    <button @click="showModal = true; alumnoSeleccionado = {{ $alumno->id }}" class="btn btn-primary btn-sm">
                                        Asignar
                                    </button>
                                @endif
                            @else
                                <span class="text-muted">Clase Finalizada</span>
                            @endif
                        </td>
                    @endrole
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- El modal ahora está fuera del bucle, definido una sola vez --}}
    <div x-show="showModal" @click.away="showModal = false" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Asignar Calificación</h3>
            <form action="{{ route('calificaciones.store') }}" method="POST">
                @csrf

                <input type="hidden" name="campo_id" value="{{ $clase->campo_id }}">
                <input type="hidden" name="alumno_id" :value="alumnoSeleccionado">

                <div>
                    <label>Calificación:</label>
                    @if ($clase->campoFormativo->tipo === 'materia')
                        <input type="number" name="calificacion" min="0" max="10" step="1" required>
                    @else
                        <select name="nivel_desempeno" required>
                            <option value="Bajo">Bajo</option>
                            <option value="Regular">Regular</option>
                            <option value="Bueno">Bueno</option>
                            <option value="Excelente">Excelente</option>
                        </select>
                    @endif
                </div>

                <button type="button" @click="showModal = false">Cancelar</button>
                <button type="submit">Guardar Calificación</button>
            </form>
        </div>
    </div>

    <div 
    x-show="showEditModal" 
    @click.away="showEditModal = false" 
    class="modal" 
    style="display: none;"
>
    <div class="modal-content">
        <h3>Editar Calificación</h3>

        <form :action="`{{ url('/calificaciones') }}/${calificacionAEditar.id}`" method="POST">            
            @csrf
            @method('PUT')
            
            <div>
                <label>Calificación:</label>
                @if ($clase->campoFormativo->tipo === 'materia')
                    <input 
                        type="number" 
                        name="calificacion" 
                        min="0" max="10" step="1" 
                        x-model="calificacionAEditar.calificacion" 
                        required
                    >
                @else
                    <select 
                        name="nivel_desempeno" 
                        x-model="calificacionAEditar.nivel_desempeno" 
                        required
                    >
                        <option value="Bajo">Bajo</option>
                        <option value="Regular">Regular</option>
                        <option value="Bueno">Bueno</option>
                        <option value="Excelente">Excelente</option>
                    </select>
                @endif
            </div>

            <button type="button" @click="showEditModal = false">Cancelar</button>
            <button type="submit">Actualizar Calificación</button>
        </form>
    </div>
</div>

    
</div> {{-- Fin del div de x-data --}}




</body>
</html>