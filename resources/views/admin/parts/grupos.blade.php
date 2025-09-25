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
                        <button @click="showEdit = true; editgrupo = { ...grupo }" class="px-2 py-1 bg-yellow-500 text-white rounded">
                            Editar
                        </button>
                        <button @click="showConfirmation = true; editgrupo = grupo" class="px-2 py-1 bg-red-500 text-white rounded">
                            Eliminar
                        </button>
                        <button @click="window.location.href = '/admin/alumnos?grupo_id=' + grupo.id" class="px-2 py-1 bg-green-500 text-white rounded">
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
                    <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name') }}">
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



<div>