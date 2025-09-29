<div 
    x-data="{ 
        grupos: [], 
        isLoading: true,
        showModal: false, 
        showEdit: false, 
        showConfirmation: false, 
        editGrupo: {} 
    }" 
    x-show="activeTab === 'grupos'"
    x-init="
        fetch('{{ route('grupos.data') }}')
            .then(response => response.json())
            .then(data => {
                            console.log('Datos de grupos recibidos:', data); 

                grupos = data;
                isLoading = false;
            })
            .catch(error => {
                console.error('Error al cargar los grupos:', error);
                isLoading = false;
            })
    "
>

    <h2>Administración de Grupos - Total de Grupos: <span x-text="grupos.length"></span></h2>
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












    
    <div x-show="isLoading" class="loading-indicator">
        Cargando grupos...
    </div>
    <table x-show="!isLoading">
        <thead>
            <tr>
                <th>Clave</th>
                <th>Nombre</th>
                <th>Generación</th>
                <th>Observaciones</th>
                <th>Número de Alumnos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="grupo in grupos" :key="grupo.id">
                <tr>
                    <td x-text="grupo.clave"></td>
                    <td x-text="grupo.nombre"></td>
                    <td x-text="grupo.generacion"></td>
                    <td x-text="grupo.observaciones"></td>
                    <td x-text="grupo.alumnos_count"></td>
                    
                    <td>
                        <button @click="showEdit = true; editGrupo = { ...grupo }" 
                            class="px-2 py-1 bg-yellow-500 text-white rounded
                            const [inicio, fin] = grupo.generacion.split('-');
                            editAñoInicio = inicio;
                            editAñoFin = fin;
                        ">
                            Editar
                        </button>
                        <button @click="showConfirmation = true; editGrupo = grupo" class="px-2 py-1 bg-red-500 text-white rounded">
                            Eliminar
                        </button>
                        <button @click="window.location.href = `{{ route('grupos.show', '') }}/${grupo.id}`"
                            class="px-2 py-1 bg-green-500 text-white rounded" 
                        >
                            Gestionar Alumnos
                        </button>


                    </td>
                </tr>
            </template>
        </tbody>
    </table>













    <br>
    <button @click="showModal = true" class="px-2 py-1 bg-blue-500 text-white rounded mb-4">
        Agregar Grupo
    </button>
    <div x-show="showModal" x-transition class="fixed inset-0 ...">
        <div class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-lg font-bold mb-4">Nuevo Grupo</h3>
            <form method="POST" action="{{ route('grupos.store') }}">
                {{-- Form fields for creating a new Grupo --}}
                @csrf
                <div class="mb-3">
                    <label class="block text-sm">Nombre del Grupo</label>
                    <input type="text" name="nombre" class="w-full border rounded p-2" value="{{ old('nombre') }}">
                </div>
                
                <div class="flex space-x-4">
                    <div class="w-1/2">
                        <label for="generacion_inicio" class="block text-sm">Generación</glabel>
                        <select name="generacion_inicio" id="generacion_inicio" class="w-full border rounded p-2">
                            @for ($year = 2015; $year <= date('Y'); $year++)
                                <option value="{{ $year }}" {{ old('generacion_inicio') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>

                        <label for="generacion_fin" class="block text-sm"> - </label>
                        <select name="generacion_fin" id="generacion_fin" class="w-full border rounded p-2">
                            @for ($year = 2015; $year <= date('Y') + 10; $year++)
                                <option value="{{ $year }}" {{ old('generacion_fin') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="block text-sm">Observaciones (opcional)</label>
                    <input type="text" name="apeM" class="w-full border rounded p-2" value="{{ old('apeM') }}">
                </div>



                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>












    <!-- Edicion -->
    <div x-show="showEdit" x-transition class="fixed inset-0 ...">
        <div class="bg-white p-6 rounded shadow-md w-96">
            
            {{-- The rest of your edit and delete modals are already well-structured for Alpine.js --}}
            {{-- and don't need significant changes. --}}
            <h3 class="text-lg font-bold mb-4">Editar Grupo</h3>
            @if ($errors->grupos->any())
                <div class="bg-red-100 text-red-600 p-2 mb-3 rounded">
                    <ul>
                        @foreach ($errors->grupos->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form :action="'{{ route('grupos.update', '') }}/' + editGrupo.id" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm">Nombre</label>
                    <input type="text" name="nombre" x-model="editGrupo.nombre" class="w-full border rounded p-2">
                </div>

                <div class="mb-3">
                    <label class="block text-sm">Observaciones (opcional)</label>
                    <textarea name="observaciones" x-model="editGrupo.observaciones" class="w-full border rounded p-2"></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showEdit = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Actualizar</button>
                </div>
            </form>
        </div>
    </div>




    <div x-show="showConfirmation" x-transition class="fixed inset-0 ...">
        <div class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-lg font-bold mb-4">Eliminar Grupo</h3>
            <p>¿Estás seguro de que deseas eliminar a <span x-text="editGrupo.nombre"></span>?</p>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" @click="showConfirmation = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                <form :action="'{{ route('grupos.destroy', '') }}/' + editGrupo.id" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>

<div>