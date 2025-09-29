<div 
    x-data="{ 
        campos: [], 
        isLoading: true,
        showModal: false, 
        showEdit: false, 
        showConfirmation: false, 
        editCampo: {} 
    }" 
    x-show="activeTab === 'camposF'"
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
            }
        )
    "
>

    <h2>Administración de Campos - Total de Grupos:</h2>



<div>