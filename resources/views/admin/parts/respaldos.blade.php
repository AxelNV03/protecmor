<div 
    x-show="activeTab === 'respaldos'" 
    x-data="{
        showRestoreModal: false,
        respaldos: [],
        isLoading: true,
        respaldoARestaurar: null,
        password: '',
        errorMessage: ''
    }"
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


    @if($errors->any())
    <div class="alert alert-danger">
        <h6>Por favor corrige los siguientes errores:</h6>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
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
                <button @click="showRestoreModal = true; respaldoARestaurar = respaldo">
                    Restaurar
                </button>
                </td>
            </tr>
        </template>
    </tbody>
</table>


    {{-- Formulario de confirmacion --}}
        <div x-show="showRestoreModal" class="px-4 py-2 bg-gray-300 rounded">
        <div @click.away="showRestoreModal = false">
            <h3>Confirmar Restauración</h3>
            <p>Esta acción es irreversible. Por favor, ingresa tu contraseña para continuar.</p>
            
            <form :action="`/respaldos/${respaldoARestaurar?.id}/restaurar`" method="POST"                         onsubmit="return confirm('ADVERTENCIA: Vas a reemplazar TODA la base de datos. ¿Estás seguro?');"
>
                @csrf
                <div class="my-3">
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="showRestoreModal = false">Cancelar</button>
                    <button type="submit">Confirmar y Restaurar</button>
                </div>
            </form>
        </div>
    </div>


    
</div>