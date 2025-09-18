document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        selectable: true,
        editable: true,
        events: '/admin/eventos/data',
        displayEventTime: false,
        eventContent: function(arg) {
            // Devuelve solo el nombre del evento, eliminando el número
            return { html: `<div class="fc-daygrid-event-dot" style="border-color: transparent;"></div><div class="fc-event-title">${arg.event.title}</div>` };
        },

        // Mapeo de colores pastel para cada tipo de evento
        eventDidMount: function(info) {
            const eventColors = {
                'Talleres prácticos': '#A5D6A7', // Verde pastel
                'Diplomados/cursos': '#90CAF9',  // Azul pastel
                'Simulacros': '#FFCC80',         // Naranja pastel
                'Seminarios/conferencias': '#B39DDB', // Púrpura pastel
                'Campañas comunitarias': '#FFAB91', // Rosa pastel
            };
            const tipoEvento = info.event.extendedProps.tipo;
            info.el.style.backgroundColor = eventColors[tipoEvento] || '#cce5ff';
            info.el.style.color = '#000';
            info.el.style.padding = '2px 4px';
            info.el.style.borderRadius = '4px';
            info.el.style.fontSize = '0.9em';
            info.el.style.border = 'none';
        },

        dateClick: function(info) {
            limpiarFormulario();
            // Corregido: La fecha ahora se asigna directamente
            document.getElementById('fecha').value = info.dateStr;
            document.getElementById('formTitle').innerText = 'Crear Evento';
            abrirModalForm();
        },

        eventClick: function(info) {
            const evento = info.event;
            document.getElementById('detalleId').value = evento.id;
            document.getElementById('detalleTitulo').innerText = evento.title;
            document.getElementById('detalleTipo').innerText = evento.extendedProps.tipo || 'No especificado';
            document.getElementById('detalleFecha').innerText = evento.start.toLocaleDateString('es-MX', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });
            document.getElementById('detalleHora').innerText = evento.extendedProps.hora || 'No especificado';
            document.getElementById('detalleDuracion').innerText = evento.extendedProps.duracion || 'No especificado';
            document.getElementById('detalleCosto').innerText = evento.extendedProps.costo || 'No especificado';
            document.getElementById('detalleLugar').innerText = evento.extendedProps.lugar || 'No especificado';
            document.getElementById('detalleDescripcion').innerText = evento.extendedProps.descripcion || 'Sin descripción';
            document.getElementById('detallePublico').innerText = evento.extendedProps.publico || 'No especificado';
            document.getElementById('detalleMensualidad').innerText = evento.extendedProps.incluido_mensualidad ? 'Sí' : 'No';

            const numero = "5217771234567";
            const mensaje = encodeURIComponent(`Hola, me interesa apartar un cupo para el evento: ${evento.title}`);
            document.getElementById('modalWhatsapp').href = `https://wa.me/${numero}?text=${mensaje}`;

            document.getElementById('btnEditar').onclick = function() {
                cerrarModalEvento();
                cargarEventoEnFormulario(evento);
            };

            document.getElementById('btnEliminar').onclick = function() {
                eliminarEvento(evento.id);
            };

            abrirModalEvento();
        },

        eventDrop: function(info) {
            const evento = info.event;
            const data = new FormData();
            data.append('_method', 'PUT');
            data.append('nombre', evento.title);
            data.append('fecha', evento.startStr);

            fetch(`/admin/eventos/${evento.id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: data
            })
            .then(res => res.json())
            .then(response => {
                if (!response.success) {
                    Swal.fire('Error', 'No se pudo actualizar el evento.', 'error');
                    info.revert();
                }
            });
        }
    });

    calendar.render();

    document.getElementById('eventoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('eventoId').value;
        const data = new FormData(this);

        if (!data.has('incluido_mensualidad')) {
            data.append('incluido_mensualidad', 0);
        }

        const url = id ? `/admin/eventos/${id}` : `/admin/eventos`;
        if (id) data.append('_method', 'PUT');

        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: data
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                Swal.fire('Éxito', response.message, 'success');
                calendar.refetchEvents();
                cerrarModalForm();
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', 'Ocurrió un error al guardar el evento.', 'error');
        });
    });

    window.cargarEventoEnFormulario = function(evento) {
        limpiarFormulario();
        document.getElementById('eventoId').value = evento.id;
        document.getElementById('nombre').value = evento.title;
        document.getElementById('tipo').value = evento.extendedProps.tipo || '';
        document.getElementById('fecha').value = evento.startStr.slice(0, 10);
        document.getElementById('hora').value = evento.extendedProps.hora || '';
        document.getElementById('duracion').value = evento.extendedProps.duracion || '';
        document.getElementById('costo').value = evento.extendedProps.costo || '';
        document.getElementById('lugar').value = evento.extendedProps.lugar || '';
        document.getElementById('descripcion').value = evento.extendedProps.descripcion || '';
        document.getElementById('publico').value = evento.extendedProps.publico || 'alumnos';
        document.getElementById('incluido_mensualidad').checked = evento.extendedProps.incluido_mensualidad;
        document.getElementById('formTitle').innerText = 'Editar Evento';
        abrirModalForm();
    };

    window.eliminarEvento = function(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const data = new FormData();
                data.append('_method', 'DELETE');

                fetch(`/admin/eventos/${id}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: data
                })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        Swal.fire('Eliminado', response.message, 'success');
                        calendar.refetchEvents();
                        cerrarModalEvento();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                });
            }
        });
    };

    // --- FUNCIONES AUXILIARES ---
    function limpiarFormulario() {
        document.getElementById('eventoForm').reset();
        document.getElementById('eventoId').value = '';
        document.getElementById('formTitle').innerText = 'Crear Evento';
    }

    function abrirModalForm() { document.getElementById('modalForm').classList.add('active'); }
    window.cerrarModalForm = function() { document.getElementById('modalForm').classList.remove('active'); }
    function abrirModalEvento() { document.getElementById('modalEvento').classList.add('active'); }
    window.cerrarModalEvento = function() { document.getElementById('modalEvento').classList.remove('active'); }
});
