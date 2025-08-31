<div class="sidebar">
    <button @click="activeTab = 'admins'" :class="{ 'active': activeTab === 'admins' }">
        Administradores
    </button>
    <button @click="activeTab = 'alumnos'" :class="{ 'active': activeTab === 'alumnos' }">
        Alumnos
    </button>
    <button @click="activeTab = 'profesores'" :class="{ 'active': activeTab === 'profesores' }">
        Profesores
    </button>
    <button @click="activeTab = 'grupos'" :class="{ 'active': activeTab === 'grupos' }">
        Grupos
    </button>
    <button @click="activeTab = 'clases'" :class="{ 'active': activeTab === 'clases' }">
        Clases
    </button>
    <button @click="activeTab = 'materiales'" :class="{ 'active': activeTab === 'materiales' }">
        Materiales
    </button>
    <button @click="activeTab = 'talleres'" :class="{ 'active': activeTab === 'talleres' }">
        Talleres
    </button>
    <button @click="activeTab = 'materias'" :class="{ 'active': activeTab === 'materias' }">
        Materias
    </button>
    <button @click="activeTab = 'eventos'" :class="{ 'active': activeTab === 'eventos' }">
        Eventos
    </button>
    <button @click="activeTab = 'pases_lista'" :class="{ 'active': activeTab === 'pases_lista' }">
        Pases de lista
    </button>
    <button @click="activeTab = 'pagos'" :class="{ 'active': activeTab === 'pagos' }">
        Pagos
    </button>
    <button @click="activeTab = 'calificaciones'" :class="{ 'active': activeTab === 'calificaciones' }">
        Calificaciones
    </button>
    <button @click="activeTab = 'reportes'" :class="{ 'active': activeTab === 'reportes' }">
        Reportes
    </button>
    <button @click="activeTab = 'respaldo_bd'" :class="{ 'active': activeTab === 'respaldo_bd' }">
        Respaldo de Base de Datos
    </button>
</div>
