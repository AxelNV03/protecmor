<div 
    x-data="{ 
        campos: [], 
        isLoading: true,
        showModal: false, 
        showEdit: false, 
        showConfirmation: false, 
        editCampo: {} 
    }" 
    x-show="activeTab === 'campos'"
    x-init="
        fetch('{{ route('campos.data') }}')
            .then(response => response.json())
            .then(data => {
                console.log('Datos de campos recibidos:', data); 
                campos = data;
                isLoading = false;
            })
            .catch(error => {
                console.error('Error al cargar los campos:', error);
                isLoading = false;
            })
    "
>

    <h2>Administración de Campos Formativos</h2>
    <!-- Errores -->
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



    <!-- Tabla de datos -->
    <div x-show="campos.length === 0" class="alert alert-info my-3">
        No hay campos registrados en la base de datos.
    </div>
    
    <table x-show="campos.length > 0">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Descripcion</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="campo in campos" :key="campo.id">
                <tr>
                    <td x-text="campo.nombre"></td>
                    <td x-text="campo.tipo"></td>
                    <td x-text="campo.descripcion"></td>
                    <td>
                        <button @click="showEdit = true; editCampo = { ...campo }" class="px-2 py-1 bg-yellow-500 text-white rounded">
                            Editar
                        </button>
                        <button @click="showConfirmation = true; editCampo = campo" class="px-2 py-1 bg-red-500 text-white rounded">
                            Eliminar
                        </button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
    


    <!-- Seccion de Creacion -->
    <br><button @click="showModal = true" class="px-2 py-1 bg-blue-500 text-white rounded mb-4">
        Agregar Campo Formativo
    </button><br>
    <div x-show="showModal" x-transition class="fixed inset-0 ...">
        <div class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-lg font-bold mb-4">Nuevo Grupo</h3>
            <form method="POST" action="{{ route('campos.store') }}">
                {{-- Form fields for creating a new Grupo --}}

                    {{-- 👇 AÑADE ESTE BLOQUE PARA VER LOS ERRORES 👇 --}}
    @if ($errors->any())
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
            <strong>¡Error de validación!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


                @csrf
                <div class="mb-3">
                    <label class="block text-sm">Nombre del campo</label>
                    <input type="text" name="nombre" class="w-full border rounded p-2" value="{{ old('nombre') }}">
                </div>
                <div>
                    <label class="block text-sm">Tipo</label>
                    <select name="tipo" class="w-full border rounded p-2">
                        <option value="materia" >Materia</option>
                        <option value="taller" >Taller</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Descripción (opcional)</label>
                    <input type="text" name="descripcion" class="w-full border rounded p-2" value="{{ old('apeM') }}">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Seccion de Edicion -->
    <div x-show="showEdit" x-transition class="fixed inset-0 ...">
        <div class="bg-white p-6 rounded shadow-md w-96">            
            <h3 class="text-lg font-bold mb-4">Editar Campo</h3>
            <form :action="'{{ route('campos.update', '') }}/' + editCampo.id" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm">Nombre</label>
                    <input type="text" name="nombre" x-model="editCampo.nombre" class="w-full border rounded p-2">
                </div>

                <div class="mb-3">
                    <label class="block text-sm">Descripción (opcional)</label>
                    <textarea name="descripcion" x-model="editCampo.descripcion" class="w-full border rounded p-2"></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showEdit = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Actualizar</button>
                </div>
            </form>
        </div>
    </div>



    <!-- Borrar de la BD -->
    <div x-show="showConfirmation" x-transition class="fixed inset-0 ...">
        <div class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-lg font-bold mb-4">Eliminar Campo</h3>
            <p>¿Estás seguro de que deseas eliminar el campo: <span x-text="editCampo.nombre"></span>?</p>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" @click="showConfirmation = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                <form :action="'{{ route('campos.destroy', '') }}/' + editCampo.id" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>



</div>
