<div x-data="{ showModal: false, showEdit: false, showConfirmation: false, editProfe: {} }" x-show="activeTab === 'profesores'">
    <h2>Administración de Profesores</h2>

    <!-- Botón para agregar profesor -->
    <button @click="showModal = true" class="px-2 py-1 bg-blue-500 text-white rounded mb-4">
        Agregar Profesor
    </button>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Especialidad</th>
                <th>Fecha ingreso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($profes as $profe)
            <tr>
                <td>{{ $profe->user->name }}</td>
                <td>{{ $profe->user->email }}</td>
                <td>{{ $profe->especialidad }}</td>
                <td>{{ $profe->fecha_ingreso }}</td>
                <td>
                    <button @click="showEdit = true; editProfe = {{ json_encode($profe) }}" class="px-2 py-1 bg-yellow-500 text-white rounded">
                        Editar
                    </button>
                    <button @click="showConfirmation = true; editProfe = {{ json_encode($profe) }}" class="px-2 py-1 bg-red-500 text-white rounded">
                        Eliminar
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Crear Profesor -->
    <div x-show="showModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-lg font-bold mb-4">Nuevo Profesor</h3>
            {{-- Errores de validación para "profesores" --}}
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
                    <label class="block text-sm">Contraseña</label>
                    <input type="password" name="password" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Especialidad</label>
                    <input type="text" name="especialidad" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Fecha ingreso</label>
                    <input type="date" name="fecha_ingreso" class="w-full border rounded p-2">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Profesor -->
    <div x-show="showEdit" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-lg font-bold mb-4">Editar Profesor</h3>
             {{-- Errores de validación para "profesores" --}}
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

    <!-- Modal Eliminar Profesor -->
    <div x-show="showConfirmation" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
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