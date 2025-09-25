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

    <h2>Administración de Grupos</h2>
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
                <th>Clave</th>
                <th>Nombre</th>
                <th>Generación</th>
                <th>Observaciones</th>
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
                    <td x-text="alumno.edad"></td>
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




<div>