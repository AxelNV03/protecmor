<div 
    x-data="{ 
        alumnos: [], 
        isLoading: true,
        showModal: false, 
        showEdit: false, 
        showConfirmation: false, 
        editAlumno: {} 
    }" 
    x-show="activeTab === 'alumnos'"
    x-init="
        fetch('{{ route('alumnos.data') }}')
            .then(response => response.json())
            .then(data => {
                            console.log('Datos de alumnos recibidos:', data); 

                alumnos = data;
                isLoading = false;
            })
            .catch(error => {
                console.error('Error al cargar los alumnos:', error);
                isLoading = false;
            })
    "
>
        
    <h2>Administración de alumnos</h2>
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
        Cargando alumnos...
    </div>

    <table x-show="!isLoading">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Matricula</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Teléfono de Emergencia</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="alumno in alumnos" :key="alumno.id">
                <tr>
                    <td x-text="alumno.user.name"></td>
                    <td x-text="alumno.matricula"></td>
                    <td x-text="alumno.user.email"></td>
                    <td x-text="alumno.user.telefono"></td>
                    <td x-text="alumno.telefono_emergencia"></td>
                    <td x-text="alumno.user.estatus"></td>
                    
                    <td>
                        <button @click="showEdit = true; editAlumno = { ...alumno }" class="px-2 py-1 bg-yellow-500 text-white rounded">
                            Editar
                        </button>
                        <button @click="showConfirmation = true; editAlumno = alumno" class="px-2 py-1 bg-red-500 text-white rounded">
                            Eliminar
                        </button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>






    <br>
    <button @click="showModal = true" class="px-2 py-1 bg-blue-500 text-white rounded mb-4">
        Agregar Alumno
    </button>
    <div x-show="showModal" x-transition class="fixed inset-0 ...">
        <div class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-lg font-bold mb-4">Nuevo Alumno</h3>
            <form method="POST" action="{{ route('alumnos.store') }}">
                {{-- Form fields for creating a new Alumno --}}
                @csrf
                <div class="mb-3">
                    <label class="block text-sm">Nombre</label>
                    <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name') }}">
                </div>
                <div>
                    <label class="block text-sm">Sexo</label>
                    <select name="sexo" class="w-full border rounded p-2">
                        <option value="Masculino" {{ old('sexo') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="Femenino" {{ old('sexo') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        <option value="Otro" {{ old('sexo') == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Email</label>
                    <input type="email" name="email" class="w-full border rounded p-2" value="{{ old('email') }}">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Teléfono</label>
                    <input type="text" name="telefono" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Teléfono de Emergencia</label>
                    <input type="text" name="telefono_emergencia" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Fecha Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="w-full border rounded p-2" value="{{ old('fecha_ingreso') }}">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Contraseña</label>
                    <input type="password" name="password" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded p-2">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>











</div>
