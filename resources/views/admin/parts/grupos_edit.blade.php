<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Grupo Dashboard</title>
    
    {{-- Tus otros scripts y estilos --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<div 
    x-data='{
        grupo: @json($grupo),
        alumnosInscritos: @json($alumnosInscritos),
        alumnosDisponibles: @json($alumnosDisponibles),

        detach: async function(alumno) {
            try {
                const response = await fetch(`/grupos/${this.grupo.id}/detach-alumno/${alumno.id}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
                    }
                });

                if (!response.ok) {
                    throw new Error("La respuesta del servidor no fue exitosa.");
                }
                
                const data = await response.json();
                if (data.success) {
                    this.alumnosInscritos = this.alumnosInscritos.filter(a => a.id !== alumno.id);
                    this.alumnosDisponibles.push(alumno);
                }
            } catch (error) {
                console.error("Error al desinscribir al alumno:", error);
                alert("Ocurrió un error al desinscribir al alumno.");
            }
        },



        // Array para los checkboxes
        alumnosSeleccionados: [], // <-- Coma aquí

        attachSeleccionados: async function() {
            if (this.alumnosSeleccionados.length === 0) return;

            const response = await fetch(`/grupos/${this.grupo.id}/attach-alumnos`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content,
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ alumnos_ids: this.alumnosSeleccionados })
            });

            if (response.ok) {
                const selectedIdsAsNumbers = this.alumnosSeleccionados.map(Number);

                let aMover = this.alumnosDisponibles.filter(a => selectedIdsAsNumbers.includes(a.id));
                this.alumnosInscritos.push(...aMover);

                // 3. 👇 Usamos el mismo array de números aquí para filtrar correctamente.
                this.alumnosDisponibles = this.alumnosDisponibles.filter(a => !selectedIdsAsNumbers.includes(a.id));
                
                this.grupo.alumnos_count += aMover.length;
                this.alumnosSeleccionados = [];
            }
        }


    }'
>
    <div>
        <h1 class="text-2xl font-bold" x-text="grupo.nombre"></h1>
        <p>
            <strong>Clave:</strong> <span x-text="grupo.clave"></span> | 
            <strong>Generación:</strong> <span x-text="grupo.generacion"></span>
        </p>

        <h2>Observaciones</h2>
        <p x-text="grupo.observaciones"></p>
    </div>

    <hr class="my-4">

    <div>
        <h3>Alumnos en este Grupo (<span x-text="alumnosInscritos.length"></span>)</h3>


        <div x-show="alumnosInscritos.length === 0" class="alert alert-info my-3">
            No hay alumnos registrados en el grupo.
        </div>

        <table x-show="alumnosInscritos.length > 0">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Matrícula</th>
                    <th>Correo</th>
                    <th>Telefono</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <template x-for="alumno in alumnosInscritos" :key="alumno.id">
                    <tr>
                        <td x-text="alumno.user.name"></td>
                        <td x-text="alumno.apeP"></td>
                        <td x-text="alumno.apeM"></td>
                        <td x-text="alumno.matricula"></td>
                        <td x-text="alumno.user.email"></td>
                        <td x-text="alumno.user.telefono"></td>
                        <td>
                            <button @click="detach(alumno)" class="btn-danger"> Eliminar del Grupo</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>










    <hr class="my-4">
    <div>
        <h3>Alumnos Disponibles para Inscribir (<span x-text="alumnosDisponibles.length"></span>)</h3>

        <button @click="window.location.href = '{{ route('admin.index', ['tab' => 'grupos']) }}'"class="btn">
            Regresar a la pagina anterior
        </button>
        <button @click="attachSeleccionados" :disabled="alumnosSeleccionados.length === 0"class="btn btn-primary mb-3">
            Agregar al grupo los Alumnos seleccionados (<span x-text="alumnosSeleccionados.length"></span>)
        </button>
        
        
        <br><br>
        {{-- 1. Mensaje que se muestra si no hay alumnos --}}
        <div x-show="alumnosDisponibles.length === 0" class="alert alert-info my-3">
            No hay alumnos sin grupo disponibles para inscribir.
        </div>

        {{-- 2. La tabla que se muestra si SÍ hay alumnos --}}
        <table x-show="alumnosDisponibles.length > 0" >
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Matrícula</th>
                    <th>Correo</th>
                    <th>Telefono</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <template x-for="alumno in alumnosDisponibles" :key="alumno.id">
                    <tr>
                        <td x-text="alumno.user.name"></td>
                        <td x-text="alumno.apeP"></td>
                        <td x-text="alumno.apeM"></td>
                        <td x-text="alumno.matricula"></td>
                        <td x-text="alumno.user.email"></td>
                        <td x-text="alumno.user.telefono"></td>
                        <td><input type="checkbox" :value="alumno.id" x-model="alumnosSeleccionados"></td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Aquí irían tus funciones 'attach' y 'detach' de Alpine.js --}}

    <br>



</div>