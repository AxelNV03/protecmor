<div 
    x-show="activeTab === 'respaldos'" 
    x-data="{ respaldos: [], isLoading: true }" 
    x-init="
        fetch('{{ route('respaldos.data') }}')
            .then(res => res.json())
            .then(data => { respaldos = data; isLoading = false; })
    "
>
    <h2>Gestión de Respaldo</h2>

    {{-- Formulario para generar --}}
    <form action="{{ route('respaldos.generar') }}" method="POST" class="mb-4">
        @csrf
        <input type="text" name="nombre_personalizado" placeholder="Nombre personalizado (opcional)">
        <button type="submit">Generar Nuevo Respaldo</button>
    </form> 
    <hr>

    {{-- Tabla de historial --}}
    <div x-show="isLoading">Cargando historial de respaldos...</div>
    <table x-show="!isLoading">
        <thead>
            <tr>
                <th>Nombre del Archivo</th>
                <th>Generado por</th>
                <th>Fecha</th>
                <th>Tamaño</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="respaldo in respaldos" :key="respaldo.id">
                <tr>
                    <td x-text="respaldo.nombre_archivo"></td>
                    {{-- 👇 Mostramos el nombre del usuario --}}
                    <td x-text="respaldo.usuario.name"></td>
                    <td x-text="respaldo.fecha_creacion"></td>
                    <td x-text="respaldo.tamano_formateado"></td>
                    <td>
                        <a :href="`{{ route('respaldos.descargar') }}?path=${respaldo.ruta}`">
                            Descargar
                        </a>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</div>