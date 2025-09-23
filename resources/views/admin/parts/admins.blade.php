<div 
    x-data="{ 
        admins: [], 
        isLoading: true,
        showModal: false, 
        showEdit: false, 
        showConfirmation: false,
        editAdmin: {} 
    }"
    x-show="activeTab === 'admins'"
    x-init="
        fetch('{{ route('admin.data') }}')
            .then(response => response.json())
            .then(data => {
                admins = data;
                isLoading = false;
            })
            .catch(error => {
                console.error('Error al cargar los administradores:', error);
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

    <h2>Administración de administradores</h2>

    <div x-show="isLoading" class="text-center p-4">
        Cargando datos de administradores...
    </div>

    <table x-show="!isLoading" class="min-w-full bg-white">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Fecha de Creación</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="admin in admins" :key="admin.id">
                <tr>
                    <td x-text="admin.name"></td>
                    <td x-text="admin.email"></td>
                    <td x-text="admin.telefono"></td>
                    <td x-text="new Date(admin.created_at).toLocaleDateString('es-ES')"></td>
                    <td x-text="admin.estatus"></td>
                    <td>
                        <button 
                            @click="showEdit = true; editAdmin = { ...admin }" 
                            class="px-2 py-1 bg-yellow-500 text-white rounded"
                        >
                            Editar
                        </button>
                        
                        <button
                            @click="showConfirmation = true; editAdmin = admin" 
                            class="px-2 py-1 bg-red-500 text-white rounded"
                        >
                            Eliminar
                        </button>
                    </td>
                </tr>
            </template>
            </tbody>
    </table>

    <br><br>
    <button @click="showModal = true" class="px-4 py-2 bg-green-500 text-white rounded">Agregar Administrador</button>

    <div x-show="showModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div @click.away="showModal = false" class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-lg font-bold mb-4">Nuevo Administrador</h3>
            <form method="POST" action="{{ route('admin.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm">Nombre</label>
                    <input type="text" name="name" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Email</label>
                    <input type="email" name="email" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Teléfono</label>
                    <input type="text" name="telefono" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Contraseña</label>
                    <input type="password" name="password" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>



    <div x-show="showEdit" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div @click.away="showEdit = false" class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-lg font-bold mb-4">Editar Administrador</h3>
            <form :action="`{{ route('admin.update', '') }}/${editAdmin.id}`" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm">Nombre</label>
                    <input type="text" name="name" x-model="editAdmin.name" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Email</label>
                    <input type="email" name="email" x-model="editAdmin.email" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Teléfono</label>
                    <input type="text" name="telefono" x-model="editAdmin.telefono" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Contraseña (dejar vacío para no cambiar)</label>
                    <input type="password" name="password" class="w-full border rounded p-2" placeholder="Nueva contraseña">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded p-2" placeholder="Confirmar nueva contraseña">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showEdit = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    

    <div x-show="showConfirmation" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div @click.away="showConfirmation = false" class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-lg font-bold mb-4">Eliminar Administrador</h3>
            <p>¿Estás seguro de que deseas eliminar a <strong x-text="editAdmin.name"></strong>?</p>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" @click="showConfirmation = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                <form :action="`{{ route('admin.destroy', '') }}/${editAdmin.id}`" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>