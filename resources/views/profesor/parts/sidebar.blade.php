<div class="sidebar">
    <button @click="activeTab = 'mis_clases'" :class="{ 'active': activeTab === 'mis_clases' }">
        Mis Clases
    </button>
    <button @click="activeTab = 'mis_alumnos'" :class="{ 'active': activeTab === 'mis_alumnos' }">
        Mis Alumnos
    </button>
    <button @click="activeTab = 'materiales'" :class="{ 'active': activeTab === 'materiales' }">
        Materiales
    </button>
    <button @click="activeTab = 'tareas'" :class="{ 'active': activeTab === 'tareas' }">
        Tareas / Talleres
    </button>
    <button @click="activeTab = 'calificaciones'" :class="{ 'active': activeTab === 'calificaciones' }">
        Calificaciones
    </button>
</div>