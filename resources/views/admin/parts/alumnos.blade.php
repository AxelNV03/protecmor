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
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Sexo</th>
                <th>Edad</th>
                <th>Matricula</th>
                <th>grupo</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Teléfono de Emergencia</th>
                <th>Fecha de Ingreso</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="alumno in alumnos" :key="alumno.id">
                <tr>
                    <td x-text="alumno.user.name"></td>
                    <td x-text="alumno.apeP"></td>
                    <td x-text="alumno.apeM"></td>
                    <td x-text="alumno.sexo"></td>
                    <td x-text="alumno.matricula"></td>
                    <td x-text="alumno.grupo?.nombre || 'Sin grupo asignado'"></td>
                    <td x-text="alumno.user.email"></td>
                    <td x-text="alumno.user.telefono"></td>
                    <td x-text="alumno.direccion"></td>
                    <td x-text="alumno.telefono_emergencia"></td>
                    <td x-text="new Date(alumno.user.created_at).toLocaleDateString()"></td>
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
                    <label class="block text-sm">Nombre(s)</label>
                    <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name') }}">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Apellido Paterno</label>
                    <input type="text" name="apeP" class="w-full border rounded p-2" value="{{ old('apeP') }}">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Apellido Materno</label>
                    <input type="text" name="apeM" class="w-full border rounded p-2" value="{{ old('apeM') }}">
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
                    <label class="block text-sm">Dirección</label>
                    <input type="text" name="direccion" class="w-full border rounded p-2" value="{{ old('direccion') }}">
                </div>

                <div class="mb-3">
                    <label class="block text-sm">Fecha Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="w-full border rounded p-2" value="{{ old('fecha_ingreso') }}">
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
            <h3 class="text-lg font-bold mb-4">Editar Alumno</h3>
            @if ($errors->alumnos->any())
                <div class="bg-red-100 text-red-600 p-2 mb-3 rounded">
                    <ul>
                        @foreach ($errors->alumnos->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form :action="'{{ route('alumnos.update', '') }}/' + editAlumno.id" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm">Nombre</label>
                    <input type="text" name="name" x-model="editAlumno.user.name" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Apellido Paterno</label>
                    <input type="text" name="apeP" x-model="editAlumno.apeP" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Apellido Materno</label>
                    <input type="text" name="apeM" x-model="editAlumno.apeM" class="w-full border rounded p-2">
                </div>




                <div>
                    <label class="block text-sm">Genero</label>
                    <select name="sexo" class="w-full border rounded p-2" x-model="editAlumno.sexo">
                        <option value="Masculino" {{ old('sexo') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="Femenino" {{ old('sexo') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        <option value="Otro" {{ old('sexo') == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
                
                
                
                
                <!-- <div class="mb-3">
                    <label class="block text-sm">feca_nacimiento</label>
                    <input type="text" name="name" x-model="editAlumno.user.name" class="w-full border rounded p-2">
                </div> -->


                <div class="mb-3">
                    <label class="block text-sm">Email</label>
                    <input type="email" name="email" x-model="editAlumno.user.email" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Teléfono</label>
                    <input type="text" name="telefono" class="w-full border rounded p-2" x-model="editAlumno.user.telefono">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Teléfono de Emergencia</label>
                    <input type="text" name="telefono_emergencia" class="w-full border rounded p-2" x-model="editAlumno.telefono_emergencia">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Dirección</label>
                    <input type="text" name="direccion" x-model="editAlumno.direccion" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-sm">Estatus</label>
                    <select name="estatus" x-model="editAlumno.user.estatus" class="w-full border rounded p-2">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Contraseña (dejar vacío para no cambiar)</label>
                    <input type="password" name="password" class="w-full border rounded p-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded p-2">
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
            <p>¿Estás seguro de que deseas eliminar a <span x-text="editAlumno.user.name"></span>?</p>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" @click="showConfirmation = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                <form :action="'{{ route('alumnos.destroy', '') }}/' + editAlumno.id" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>








</div>
