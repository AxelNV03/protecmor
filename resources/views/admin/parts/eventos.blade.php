<!-- resources/views/admin/parts/eventos.blade.php -->

<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Calendario de Eventos</h2>

    <!-- Contenedor del calendario -->
    <div id="calendar"></div>
</div>

<!-- Modal para mostrar la información del evento -->
<div id="modalEvento" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative">
        <h2 id="modalTitulo" class="text-xl font-bold mb-2"></h2>
        <p id="modalFecha" class="text-gray-600 mb-2"></p>
        <p id="modalDescripcion" class="mb-4"></p>
        
        <div class="flex justify-between items-center">
            <!-- Botón WhatsApp -->
            <a id="modalWhatsapp" href="#" target="_blank" 
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                Contactar por WhatsApp
            </a>

            <!-- Botón Cerrar -->
            <button onclick="cerrarModal()" 
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                Cerrar
            </button>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es', // Español
        events: '{{ route('admin.eventos.data') }}', // Ruta que devuelve JSON desde EventoController

        eventClick: function(info) {
            // Rellenar datos en el modal
            document.getElementById('modalTitulo').innerText = info.event.title;
            document.getElementById('modalFecha').innerText = 
                new Date(info.event.start).toLocaleDateString('es-MX', {
                    weekday:"long", year:"numeric", month:"long", day:"numeric"
                });
            document.getElementById('modalDescripcion').innerText = info.event.extendedProps.descripcion || "Sin descripción.";

            // Generar link a WhatsApp
            let numero = "5217771234567"; // Cambia este número por el de la escuela
            let mensaje = encodeURIComponent("Hola, me interesa el evento: " + info.event.title);
            document.getElementById('modalWhatsapp').href = "https://wa.me/" + numero + "?text=" + mensaje;

            // Mostrar modal
            document.getElementById('modalEvento').classList.remove('hidden');
        }
    });

    calendar.render();
});

function cerrarModal() {
    document.getElementById('modalEvento').classList.add('hidden');
}
</script>
