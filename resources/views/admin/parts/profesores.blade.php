<div 
    x-data="{ 
        profes: [], 
        isLoading: true,
        showModal: false, 
        showEdit: false, 
        showConfirmation: false, 
        editProfe: {} 
    }" 
    x-show="activeTab === 'profesores'"
    x-init="
        fetch('{{ route('profesores.data') }}')
            .then(response => response.json())
            .then(data => {
                            console.log('Datos de profesores recibidos:', data); 

                profes = data;
                isLoading = false;
            })
            .catch(error => {
                console.error('Error al cargar los profesores:', error);
                isLoading = false;
            })
    "
>

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

    <h2>Administración de Profesores</h2>

    <button @click="showModal = true" class="px-2 py-1 bg-blue-500 text-white rounded mb-4">
        Agregar Profesor
    </button>

    <div x-show="isLoading" class="text-center p-4">
        Cargando datos de profesores...
    </div>

    <table x-show="!isLoading">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Teléfono de Emergencia</th>
                <th>Especialidad</th>
                <th>Fecha ingreso</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="profe in profes" :key="profe.id">
                <tr>
                    <td x-text="profe.user.name"></td>
                    <td x-text="profe.user.email"></td>
                    
                    <td x-text="profe.user.telefono"></td>
                    <td x-text="profe.telefono_emergencia"></td>
                    <td x-text="profe.especialidad"></td>
                    <td x-text="profe.fecha_ingreso"></td>
                    <td x-text="profe.user.estatus"></td>
                    
                    <td>
                        <button @click="showEdit = true; editProfe = { ...profe }" class="px-2 py-1 bg-yellow-500 text-white rounded">
                            Editar
                        </button>
                        <button @click="showConfirmation = true; editProfe = profe" class="px-2 py-1 bg-red-500 text-white rounded">
                            Eliminar
                        </button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>

    <div x-show="showModal" x-transition class="fixed inset-0 ...">
        <div class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-lg font-bold mb-4">Nuevo Profesor</h3>
            {{-- This part for errors is excellent! It uses a "Named Error Bag" --}}
            @if ($errors->profesores->any())
                <div class="bg-red-100 text-red-600 p-2 mb-3 rounded">
                    <ul>
                        @foreach ($errors->profesores->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('profesores.store') }}">
                {{-- Form fields for creating a new professor --}}
                @csrf
                <div class="mb-3">
                    <label class="block text-sm">Nombre</label>
                    <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name') }}">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Email</label>
                    <input type="email" name="email" class="w-full border rounded p-2" value="{{ old('email') }}">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Contraseña</label>
                    <input type="password" name="password" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Especialidad</label>
                    <input type="text" name="especialidad" class="w-full border rounded p-2" value="{{ old('especialidad') }}">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Fecha ingreso</label>
                    <input type="date" name="fecha_ingreso" class="w-full border rounded p-2" value="{{ old('fecha_ingreso') }}">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showEdit" x-transition class="fixed inset-0 ...">
        <div class="bg-white p-6 rounded shadow-md w-96">
            {{-- The rest of your edit and delete modals are already well-structured for Alpine.js --}}
            {{-- and don't need significant changes. --}}
            <h3 class="text-lg font-bold mb-4">Editar Profesor</h3>
            @if ($errors->profesores->any())
                <div class="bg-red-100 text-red-600 p-2 mb-3 rounded">
                    <ul>
                        @foreach ($errors->profesores->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form :action="'{{ route('profesores.update', '') }}/' + editProfe.id" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm">Nombre</label>
                    <input type="text" name="name" x-model="editProfe.user.name" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Email</label>
                    <input type="email" name="email" x-model="editProfe.user.email" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Contraseña (dejar vacío para no cambiar)</label>
                    <input type="password" name="password" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Especialidad</label>
                    <input type="text" name="especialidad" x-model="editProfe.especialidad" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Fecha ingreso</label>
                    <input type="date" name="fecha_ingreso" x-model="editProfe.fecha_ingreso" class="w-full border rounded p-2">
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
            <h3 class="text-lg font-bold mb-4">Eliminar Profesor</h3>
            <p>¿Estás seguro de que deseas eliminar a <span x-text="editProfe.user.name"></span>?</p>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" @click="showConfirmation = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                <form :action="'{{ route('profesores.destroy', '') }}/' + editProfe.id" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>