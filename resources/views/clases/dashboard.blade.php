<div 
    x-data="{ 
        grupos: [], 
        campos: [],
        profesores: [],
        clases: [],
        showModal: false, 
        showEdit: false, 
        showConfirmation: false, 
        editClase: {} 
    }" 
    x-show="activeTab === 'clases'"
    x-init="
        // Inicia una función asíncrona autoejecutable
        (async () => {
            // Promise.all ejecuta todas las peticiones en paralelo
            const [clasesRes, gruposRes, profesRes, camposRes] = await Promise.all([
                fetch('{{ route('clases.data') }}'),
                fetch('{{ route('grupos.data') }}'),
                fetch('{{ route('profesores.data') }}'),
                fetch('{{ route('campos.data') }}') 
            ]);

            // Una vez que todas han respondido, las convertimos a JSON
            clases = await clasesRes.json();
            grupos = await gruposRes.json();
            profesores = await profesRes.json();
            campos = await camposRes.json();
        })();
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
        No hay clases registrados en la base de datos.
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



    <!--  Formulario para crear -->
    <br>
    <button @click="showModal = true" class="px-2 py-1 bg-blue-500 text-white rounded mb-4">
        Agregar Clase
    </button>
    <div x-show="showModal" x-transition class="fixed inset-0 ...">
        <div class="bg-white p-6 rounded shadow-md w-96">
            <h3 class="text-lg font-bold mb-4">Nuevo Grupo</h3>
                <form action="{{ route('clases.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="nombre" class="block mb-2">Nombre de la Clase</label>
                    <input type="text" name="nombre" id="nombre" class="form-input w-full" required>
                </div>

                <div class="mb-4">
                    <label for="grupo_id" class="block mb-2">Grupo</label>
                    <select name="grupo_id" id="grupo_id" class="form-select w-full" required>
                        <option value="">-- Selecciona un grupo --</option>
                        {{-- 👇 Usa directamente la variable 'grupos' del x-data principal --}}
                        <template x-for="grupo in grupos" :key="grupo.id">
                            <option :value="grupo.id" x-text="grupo.nombre"></option>
                        </template>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="profesor_id" class="block mb-2">Profesor</label>
                    <select name="profesor_id" id="profesor_id" class="form-select w-full" required>
                        <option value="">-- Selecciona un profesor --</option>
                        {{-- 👇 Usa directamente la variable 'profesores' --}}
                        <template x-for="profesor in profesores.filter(p => p.user.estatus === 'activo')" :key="profesor.id">
                             <option :value="profesor.id" x-text="profesor.full_name"></option>
                        </template>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="campo_id" class="block mb-2">Campo Formativo</label>
                    <select name="campo_id" id="campo_id" class="form-select w-full" required>
                        <option value="">-- Selecciona un campo --</option>
                        {{-- 👇 Usa directamente la variable 'campos' --}}
                        <template x-for="campo in campos" :key="campo.id">
                            <option :value="campo.id" x-text="campo.nombre"></option>
                        </template>
                    </select>
                </div>
                
                {{-- ... (otros campos como descripción) ... --}}

                <br>
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
            @if ($errors->clases->any())
                <div class="bg-red-100 text-red-600 p-2 mb-3 rounded">
                    <ul>
                        @foreach ($errors->clases->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form :action="'{{ route('clases.update', '') }}/' + editClase.id" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="edit_nombre" class="block mb-2">Nombre de la Clase</label>
                    <input type="text" name="nombre" id="edit_nombre" x-model="editClase.nombre" class="form-input w-full" required>
                </div>

                <div class="mb-4">
                    <label for="edit_profesor_id" class="block mb-2">Profesor</label>
                    {{-- x-model enlaza el <select> al 'profesor_id' de la clase que estás editando --}}
                    <select name="profesor_id" id="edit_profesor_id" x-model="editClase.profesor_id" class="form-select w-full" required>
                        <option value="">-- Selecciona un profesor --</option>
                        <template x-for="profesor in profesores" :key="profesor.id">
                            <option :value="profesor.id" x-text="profesor.full_name"></option>
                        </template>
                    </select>
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
            <h3 class="text-lg font-bold mb-4">Eliminar Clase</h3>
            <p>¿Estás seguro de que deseas eliminar a <span x-text="editClase.nombre"></span>?</p>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" @click="showConfirmation = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                <form :action="'{{ route('clases.destroy', '') }}/' + editClase.id" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>






</div>