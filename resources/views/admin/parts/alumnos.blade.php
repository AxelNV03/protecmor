<div 
    x-data="{ 
        alumnos: [], 
        isLoading: true,
        showModal: false, 
        showEdit: false, 
        showConfirmation: false, 
        editProfe: {} 
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





    <table x-show="!isLoading">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Matricula</th>
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







</div>
