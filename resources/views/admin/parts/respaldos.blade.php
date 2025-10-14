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


    <!-- Mostrar mensajes de sesión -->
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif



    {{-- Formulario para generar --}}
    <form 
        action="{{ route('respaldos.generar') }}" 
        method="POST"
        onsubmit="return confirm('¿Estás seguro de que deseas generar una nueva copia de seguridad?');"
    >
        @csrf
        <div>
            <label for="nombre_personalizado">Nombre Personalizado (opcional):</label>
            <input 
                type="text" 
                name="nombre_personalizado" 
                id="nombre_personalizado" 
                placeholder="Ej: respaldo_fin_de_mes"
            >
        </div>

        <button type="submit">
            Generar Respaldo
        </button>
    </form>


    {{-- Tabla de historial --}}
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
                <td x-text="respaldo.usuario.name"></td>
                <td x-text="respaldo.fecha_creacion"></td>
                <td x-text="respaldo.tamano_formateado"></td>
                <td>
                    <a :href="`{{ route('respaldos.descargar') }}?path=${respaldo.ruta}`" class="btn btn-info">
                        Descargar
                    </a>

                    {{-- 👇 NUEVO BOTÓN DE RESTAURAR 👇 --}}
                    <form 
                        :action="`/respaldos/${respaldo.id}/restaurar`" 
                        method="POST" 
                        class="d-inline"
                        onsubmit="return confirm('ADVERTENCIA: Vas a reemplazar TODA la base de datos. ¿Estás seguro?');"
                    >
                        @csrf
                        <button type="submit" class="submit">Restaurar</button>
                    </form>
                </td>
            </tr>
        </template>
    </tbody>
</table>



    
</div>