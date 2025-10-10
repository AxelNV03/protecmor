<div class="sidebar">
    @role('super admin')
    <button @click="changeTab('admins')" :class="{ 'active': activeTab === 'admins' }">
        Administradores
    </button>
    @endrole
    <button @click="changeTab('alumnos')" :class="{ 'active': activeTab === 'alumnos' }">
        Alumnos
    </button>
    <button @click="changeTab('profesores')" :class="{ 'active': activeTab === 'profesores' }">
        Profesores
    </button>
    <button @click="changeTab('grupos')" :class="{ 'active': activeTab === 'grupos' }">
        Grupos
    </button>
        <button @click="changeTab('campos')" :class="{ 'active': activeTab === 'campos' }">
        Campos Formativos
    </button>  
    <button @click="changeTab('clases')" :class="{ 'active': activeTab === 'clases' }">
        Clases
    </button>
    <button @click="changeTab('eventos')" :class="{ 'active': activeTab === 'eventos' }">
        Eventos
    </button>
    <button @click="changeTab('pases_lista')" :class="{ 'active': activeTab === 'pases_lista' }">
        Pases de lista
    </button>
    <button @click="changeTab('pagos')" :class="{ 'active': activeTab === 'pagos' }">
        Pagos
    </button>
    <button @click="changeTab('calificaciones')" :class="{ 'active': activeTab === 'calificaciones' }">
        Calificaciones
    </button>
    <button @click="changeTab('productos')" :class="{ 'active': activeTab === 'productos' }">
        Productos
    </button>
    <button @click="changeTab('reportes')" :class="{ 'active': activeTab === 'reportes' }">
        Reportes
    </button>
    <button @click="changeTab('respaldo_bd')" :class="{ 'active': activeTab === 'respaldo_bd' }">
        Respaldo de Base de Datos
    </button>
</div>