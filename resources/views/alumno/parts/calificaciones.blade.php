<div 
    x-data="{ 
        activeTab: 'calificaciones',
        materias: [],
        talleres: [],
        isLoading: true
    }"
    x-init="
        fetch('{{ route('calificaciones.calificacionesAlumno') }}')
            .then(response => response.json())
            .then(data => {
                materias = data.materias;
                talleres = data.talleres;
                isLoading = false;
            })
    "
>
    <h1>Mis Calificaciones</h1>

    <div>
        <button @click="activeTab = 'materias'" :class="{ 'active': activeTab === 'materias' }">Materias</button>
        <button @click="activeTab = 'talleres'" :class="{ 'active': activeTab === 'talleres' }">Talleres</button>
    </div>

    <hr>

    <div x-show="isLoading">Cargando calificaciones...</div>

    <div x-show="!isLoading">
        <div x-show="activeTab === 'materias'">
            <h3>Materias</h3>
            <table>
                <tbody>
                    <template x-for="calificacion in materias" :key="calificacion.id">
                        <tr>
                            <td x-text="calificacion.campo_formativo.nombre"></td>
                            <td x-text="calificacion.calificacion"></td>
                        </tr>
                    </template>
                    {{-- Mensaje si no hay materias --}}
                    <template x-if="materias.length === 0">
                        <tr><td colspan="2">Aún no tienes calificaciones en materias.</td></tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div x-show="activeTab === 'talleres'">
            <h3>Talleres</h3>
            <table>
                <tbody>
                    <template x-for="calificacion in talleres" :key="calificacion.id">
                        <tr>
                            <td x-text="calificacion.campo_formativo.nombre"></td>
                            <td x-text="calificacion.nivel_desempeno"></td>
                        </tr>
                    </template>
                    {{-- Mensaje si no hay talleres --}}
                    <template x-if="talleres.length === 0">
                        <tr><td colspan="2">Aún no tienes calificaciones en talleres.</td></tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>