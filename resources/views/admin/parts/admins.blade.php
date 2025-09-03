<!-- admin/parts/admins.blade.php -->
<!-- <div x-data="{ showModal: false }" x-show="activeTab === 'admins'"> -->
<div x-data="{ showModal: false, showEdit: false, editAdmin: {} }" x-show="activeTab === 'admins'">

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

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $admin)
            <tr>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
                <td>{{ $admin->telefono }}</td>
                <td>{{ $admin->estatus }}</td>
                <td>
                    
                    <!-- Boton de editar -->
                    <button 
                        @click="showEdit = true; editAdmin = {{ json_encode($admin) }}" 
                        class="px-2 py-1 bg-yellow-500 text-white rounded"
                    >
                        Editar
                    </button>


                    <button>Eliminar</button>


                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br><br>
    <button @click="showModal = true">Agregar Administrador</button>



    <!-- Modal CREAR-->
    <div 
        x-show="showModal" 
        x-transition 
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
        <div class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-lg font-bold mb-4">Nuevo Administrador</h3>

            <form method="POST" action="{{ route('admin.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm">Nombre</label>
                    <input type="text" name="name" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Email</label>
                    <input type="email" name="email" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Teléfono</label>
                    <input type="text" name="telefono" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">contraseña</label>
                    <input type="password" name="password" class="w-full border rounded p-2">
                </div>
                </div>
                    <div class="mb-3">
                    <label class="block text-sm">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded p-2">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>




    <!-- Modal EDITAR -->
    <div 
        x-show="showEdit" 
        x-transition 
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
        <div class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-lg font-bold mb-4">Editar Administrador</h3>

            <form :action="`{{ route('admin.update', '') }}/${editAdmin.id}`" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm">Nombre</label>
                    <input type="text" name="name" x-model="editAdmin.name" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Email</label>
                    <input type="email" name="email" x-model="editAdmin.email" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Teléfono</label>
                    <input type="text" name="telefono" x-model="editAdmin.telefono" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Contraseña</label>
                    <input type="password" name="password" x-model="editAdmin.password" class="w-full border rounded p-2">
                </div>
                    <div class="mb-3">
                    <label class="block text-sm">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded p-2">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showEdit = false" class="px-4 py-2 bg-gray-300 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>




</div>
