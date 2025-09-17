document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        selectable: true,
        editable: true,
        events: "{{ route('admin.eventos.data') }}",

        dateClick: function(info) {
            limpiarFormulario();
            document.getElementById('fecha').value = info.dateStr;
            document.getElementById('formTitle').innerText = 'Crear Evento';
            abrirModalForm();
        },

        eventClick: function(info) {
            const evento = info.event;
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
            const mensaje = encodeURIComponent(`Hola, me interesa el evento: ${evento.title}`);
            document.getElementById('modalWhatsapp').href = `https://wa.me/${numero}?text=${mensaje}`;

            document.getElementById('btnEditar').onclick = function () {
                cerrarModalEvento();
                cargarEventoEnFormulario(evento);
            };

            document.getElementById('btnEliminar').onclick = function () {
                eliminarEvento(evento.id);
            };

            abrirModalEvento();
        },
        
        eventDrop: function(info) {
            const evento = info.event;
            const data = new FormData();
            data.append('_method', 'PUT');
            data.append('_token', document.querySelector('input[name=_token]').value);
            data.append('nombre', evento.title);
            data.append('start', evento.startStr);

            fetch(`/admin/eventos/${evento.id}`, {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    calendar.refetchEvents();
                } else {
                    alert('Error al actualizar el evento.');
                    info.revert();
                }
            });
        }
    });

    calendar.render();

    // Lógica para el formulario de Creación/Edición
    document.getElementById('eventoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('eventoId').value;
        const data = new FormData(this);

        if (!data.has('incluido_mensualidad')) {
            data.append('incluido_mensualidad', 0);
        }

        let url = id ? `/admin/eventos/${id}` : `/admin/eventos`;
        if (id) {
            data.append('_method', 'PUT');
        }

        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value },
            body: data
        })
        .then(res => {
            if (!res.ok) {
                // Si la respuesta no es exitosa, lanza un error para el bloque catch
                throw new Error('Error en la respuesta del servidor.');
            }
            return res.json();
        })
        .then(response => {
            if (response.success) {
                alert(response.message);
                calendar.refetchEvents();
                cerrarModalForm();
            } else {
                alert(response.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al guardar el evento. Por favor, revisa la consola para más detalles.');
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
        if (!confirm("¿Seguro que deseas eliminar este evento?")) return;

        const data = new FormData();
        data.append('_method', 'DELETE');
        data.append('_token', document.querySelector('input[name=_token]').value);

        fetch(`/admin/eventos/${id}`, {
            method: 'POST',
            body: data
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                alert(response.message);
                calendar.refetchEvents();
                cerrarModalEvento();
            } else {
                alert(response.message);
            }
        });
    };

    function limpiarFormulario() {
        document.getElementById('eventoForm').reset();
        document.getElementById('eventoId').value = '';
    }

    function abrirModalForm() {
        document.getElementById('modalForm').classList.add('active');
    }
    window.cerrarModalForm = function() {
        document.getElementById('modalForm').classList.remove('active');
    }
    function abrirModalEvento() {
        document.getElementById('modalEvento').classList.add('active');
    }
    window.cerrarModalEvento = function() {
        document.getElementById('modalEvento').classList.remove('active');
    }
});