<div x-data="{ user: {{ auth()->user()->toJson() }} }">
    <h2 class="text-2xl font-bold mb-4">Mi Perfil</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-600">Nombre</label>
            <p class="mt-1 text-gray-800" x-text="user.name"></p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-600">Correo Electrónico</label>
            <p class="mt-1 text-gray-800" x-text="user.email"></p>
        </div>
        <!-- Campos que el alumno puede modificar (FN.5) -->
        <div>
            <label class="block text-sm font-semibold text-gray-600">Teléfono</label>
            <p class="mt-1 text-gray-800" x-text="user.telefono"></p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-600">Teléfono de Emergencia</label>
            <p class="mt-1 text-gray-800" x-text="user.telefono_emergencia"></p>
        </div>
        <!-- Agrega los demás campos del modelo Alumno que quieras mostrar -->
    </div>
</div>