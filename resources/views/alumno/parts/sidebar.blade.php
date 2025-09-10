<aside class="bg-gray-800 text-white p-4 rounded-lg shadow-md w-full md:w-1/4 h-fit">
    <h2 class="text-lg font-semibold mb-4">Menú</h2>
    <nav class="flex flex-col space-y-2">
        <button 
            @click="changeTab('mi-perfil')" 
            :class="{ 'bg-blue-600 text-white': activeTab === 'mi-perfil', 'hover:bg-gray-700': activeTab !== 'mi-perfil' }" 
            class="py-2 px-4 rounded-md text-left transition-colors duration-200"
        >
            Mi Perfil
        </button>
        <button 
            @click="changeTab('clases-y-materiales')" 
            :class="{ 'bg-blue-600 text-white': activeTab === 'clases-y-materiales', 'hover:bg-gray-700': activeTab !== 'clases-y-materiales' }" 
            class="py-2 px-4 rounded-md text-left transition-colors duration-200"
        >
            Clases y Materiales
        </button>
        <button 
            @click="changeTab('calificaciones')" 
            :class="{ 'bg-blue-600 text-white': activeTab === 'calificaciones', 'hover:bg-gray-700': activeTab !== 'calificaciones' }" 
            class="py-2 px-4 rounded-md text-left transition-colors duration-200"
        >
            Calificaciones
        </button>
        <button 
            @click="changeTab('mis-pagos')" 
            :class="{ 'bg-blue-600 text-white': activeTab === 'mis-pagos', 'hover:bg-gray-700': activeTab !== 'mis-pagos' }" 
            class="py-2 px-4 rounded-md text-left transition-colors duration-200"
        >
            Mis Pagos
        </button>
        <button 
            @click="changeTab('talleres-y-constancias')" 
            :class="{ 'bg-blue-600 text-white': activeTab === 'talleres-y-constancias', 'hover:bg-gray-700': activeTab !== 'talleres-y-constancias' }" 
            class="py-2 px-4 rounded-md text-left transition-colors duration-200"
        >
            Talleres y Constancias
        </button>
        <button 
            @click="changeTab('chat-interno')" 
            :class="{ 'bg-blue-600 text-white': activeTab === 'chat-interno', 'hover:bg-gray-700': activeTab !== 'chat-interno' }" 
            class="py-2 px-4 rounded-md text-left transition-colors duration-200"
        >
            Chat Interno
        </button>
    </nav>
</aside>