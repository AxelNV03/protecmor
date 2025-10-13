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
<div x-data="{ showModal: false, alumnoSeleccionado: null }">

    <h1>Clase: {{ $clase->nombre }}</h1>
    <p>Profesor: {{ $clase->profesor->user->name }}</p>
    <p>Estatus: {{ $clase->estado }}</p>

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
                                    <a href="{{-- route('calificaciones.edit', $calificacion->id) --}}" class="btn btn-warning btn-sm">
                                        Editar
                                    </a>
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

                    {{-- 👇 AÑADE ESTE BLOQUE PARA VER LOS ERRORES 👇 --}}
    @if ($errors->any())
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
            <strong>¡Error de validación!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
                
                
                <input type="hidden" name="campo_id" value="{{ $clase->campo_formativo_id }}">
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

</div> {{-- Fin del div de x-data --}}

</body>
</html>