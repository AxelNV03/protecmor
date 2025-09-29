
<div 
    x-data="{ 
        campos: [], 
        isLoading: true,
        showModal: false, 
        showEdit: false, 
        showConfirmation: false, 
        editCampo: {} 
    }" 
    x-show="activeTab === 'campos'"
    x-init="
        fetch('{{ route('campos.data') }}')
            .then(response => response.json())
            .then(data => {
                console.log('Datos de campos Formativos recibidos:', data); 
                campos = data;
                isLoading = false;
            })
            .catch(error => {
                console.error('Error al cargar los campos Formativos:', error);
                isLoading = false;
            }
        )
    "
>


<h1>Administración de Campos Formativos</h1>


<div>
