<div class="nav nav-tabs">
    <button class="nav-link" @click="activeTab = 'inicio'" :class="{ 'active': activeTab === 'inicio' }">
        Inicio
    </button>
    <button class="nav-link" @click="activeTab = 'material'" :class="{ 'active': activeTab === 'material' }">
        Material
    </button>

    @hasanyrole('profesor')
        <button class="nav-link" @click="activeTab = 'asistencias'" :class="{ 'active': activeTab === 'asistencias' }">
            Asistencias
        </button>
    @endhasanyrole
    <button class="nav-link" @click="activeTab = 'alumnos'" :class="{ 'active': activeTab === 'alumnos' }">
        Alumnos
    </button>
    @hasanyrole('superadmin|profesores')
    <button class="nav-link" @click="activeTab = 'chat'" :class="{ 'active': activeTab === 'chat' }">
        Chat
    </button>
    @endhasanyrole
</div>