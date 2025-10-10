<div 
    x-data="{ 
        clases: [], 
        isLoading: true,
        showModal: false, 
        showEdit: false, 
        showConfirmation: false, 
        editClase: {} 
    }" 
    x-show="activeTab === 'clases'"
    x-init="
        fetch('{{ route('clases.data') }}')
            .then(response => response.json())
            .then(data => {
                console.log('Datos de clases recibidos:', data); 
                clases = data;
                isLoading = false;
            })
            .catch(error => {
                console.error('Error al cargar las clases:', error);
                isLoading = false;
            })
    "
>

    <h1>Clases</h1>
    

    <h2>
        @hasanyrole('super admin|admin')
            Administración de Clases
        @else
            Clases
        @endhasanyrole
        - Total: <span x-text="clases.length"></span>
    </h2>


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





    <!-- Mostrar -->
    <div x-show="clases.length === 0" class="alert alert-info my-3">
        No hay grupos registrados en la base de datos.
    </div>
    <table x-show="clases.length > 0">
        <thead>
            <tr>
                <th>Clave</th>
                <th>Nombre</th>
                <th>Tipo de Campo Formativo</th>
                <th>Grupo</th>
                <th>Docente encargado</th>
                <th>Estado</th>
                <th>Fecha de inicio</th>
                <th>Fecha de fin</th>
                @hasanyrole('super admin|admin')
                    <th>Acciones</th>
                @endhasanyrole
            </tr>
        </thead>
        <tbody>
            <template x-for="clase in clases" :key="clase.id">
                <tr>
                    <td x-text="clase.clave"></td>  
                    <td><a :href="`{{ route('clases.show', '') }}/${clase.id}`" x-text="clase.nombre" class="text-blue-600 hover:underline"></a></td>
                    <td x-text="clase.campo_formativo.tipo"></td>
                    <td x-text="clase.grupo.nombre"></td>
                    <td x-text="clase.profesor.user.name"></td>
                    <td x-text="clase.estado"></td>
                    <td x-text="clase.fecha_inicio"></td>
                    <td x-text="clase?.fecha_fin || 'N/A' "></td>
                    
                    @hasanyrole('super admin|admin')
                    <td>
                        <button @click="showEdit = true; editClase = { ...clase }" 
                            class="px-2 py-1 bg-yellow-500 text-white rounded
                        ">
                            Editar
                        </button>
                    
                    
                        <button @click="showConfirmation = true; editClase = clase" class="px-2 py-1 bg-red-500 text-white rounded">
                            Eliminar
                        </button>                    
                    </td>
                    @endhasanyrole

                </tr>
            </template>
        </tbody>
    </table>
</div>