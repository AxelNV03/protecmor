<aside class="bg-gray-800 text-white p-4 rounded-lg shadow-md w-full md:w-1/4 h-fit">
    <h2 class="text-lg font-semibold mb-4">Menú</h2>
    <nav class="flex flex-col space-y-2">
        <button
            @click="changeTab('mi-perfil')"
            :class="{ 'bg-blue-600 text-white': activeTab === 'mi-perfil', 'hover:bg-gray-700': activeTab !== 'mi-perfil' }"
            class="py-2 px-4 rounded-md text-left transition-colors duration-200"
        >Mi Perfil</button>

        <button
            @click="changeTab('clases')"
            :class="{ 'bg-blue-600 text-white': activeTab === 'clases', 'hover:bg-gray-700': activeTab !== 'clases' }"
            class="py-2 px-4 rounded-md text-left transition-colors duration-200"
        >Mis Clases</button>

        <button
            @click="changeTab('materiales')"
            :class="{ 'bg-blue-600 text-white': activeTab === 'materiales', 'hover:bg-gray-700': activeTab !== 'materiales' }"
            class="py-2 px-4 rounded-md text-left transition-colors duration-200"
        >Material Didáctico</button>

        <button
            @click="changeTab('asistencias')"
            :class="{ 'bg-blue-600 text-white': activeTab === 'asistencias', 'hover:bg-gray-700': activeTab !== 'asistencias' }"
            class="py-2 px-4 rounded-md text-left transition-colors duration-200"
        >Asistencias</button>

        <button
            @click="changeTab('calificaciones')"
            :class="{ 'bg-blue-600 text-white': activeTab === 'calificaciones', 'hover:bg-gray-700': activeTab !== 'calificaciones' }"
            class="py-2 px-4 rounded-md text-left transition-colors duration-200"
        >Calificaciones</button>

        <button
            @click="changeTab('chat-interno')"
            :class="{ 'bg-blue-600 text-white': activeTab === 'chat-interno', 'hover:bg-gray-700': activeTab !== 'chat-interno' }"
            class="py-2 px-4 rounded-md text-left transition-colors duration-200"
        >Chat Interno</button>

        <button
            @click="changeTab('reportes')"
            :class="{ 'bg-blue-600 text-white': activeTab === 'reportes', 'hover:bg-gray-700': activeTab !== 'reportes' }"
            class="py-2 px-4 rounded-md text-left transition-colors duration-200"
        >Reportes</button>
    </nav>
</aside>
