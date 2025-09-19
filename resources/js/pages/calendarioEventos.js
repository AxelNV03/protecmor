document.addEventListener('DOMContentLoaded', function () {
    const isAdmin = window.IS_ADMIN === true || window.IS_ADMIN === 'true';
    const eventsUrl = window.EVENTS_URL || '/admin/eventos/data';
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        selectable: window.IS_ADMIN,  // true para admin, false para público
        editable: window.IS_ADMIN,    // true para admin, false para público
        events: window.EVENTS_URL,    // URL de datos
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

            // Solo asigna los botones si existen (solo admin)
            const btnEditar = document.getElementById('btnEditar');
            const btnEliminar = document.getElementById('btnEliminar');

            if (btnEditar) {
                btnEditar.onclick = function() {
                    cerrarModalEvento();
                    cargarEventoEnFormulario(evento);
                };
            }

            if (btnEliminar) {
                btnEliminar.onclick = function() {
                    eliminarEvento(evento.id);
                };
            }

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
    // --- FILTROS POR TIPO ---
    const filtros = document.querySelectorAll('.btn-filtro');
    const listaEventos = document.getElementById('listaEventos');

    filtros.forEach(boton => {
        boton.addEventListener('click', () => {
            const tipo = boton.dataset.tipo;
            // Solo eventos futuros
            const hoy = new Date();
            const eventos = calendar.getEvents().filter(ev => 
                ev.extendedProps.tipo === tipo && ev.start >= hoy
            );

            listaEventos.innerHTML = `
                <div class="evento-container">
                    <button class="cerrar-eventos" onclick="document.getElementById('listaEventos').innerHTML='';" style="float:right; background:none; border:none; font-size:18px; cursor:pointer;">&times;</button>
                    <h3>Eventos de tipo: ${tipo}</h3>
                    ${eventos.length ? `
                        <div class="swiper mySwiper">
                            <div class="swiper-wrapper">
                                ${eventos.map(ev => `
                                    <div class="swiper-slide">
                                        <div class="evento-item">
                                            <h4 style="text-align:center; font-weight:bold; font-size:1.2em;">${ev.title}</h4>
                                            <p><strong>Tipo:</strong> ${ev.extendedProps.tipo}</p>
                                            <p><strong>Fecha:</strong> ${ev.start.toLocaleDateString('es-MX')}</p>
                                            <p><strong>Hora:</strong> ${ev.extendedProps.hora || 'No especificado'}</p>
                                            <p><strong>Duración:</strong> ${ev.extendedProps.duracion || 'No especificado'}</p>
                                            <p><strong>Costo:</strong> ${ev.extendedProps.costo || 'No especificado'}</p>
                                            <p><strong>Lugar:</strong> ${ev.extendedProps.lugar || 'No especificado'}</p>
                                            <p><strong>Descripción:</strong> ${ev.extendedProps.descripcion || 'Sin descripción'}</p>
                                            <p><strong>Público:</strong> ${ev.extendedProps.publico || 'No especificado'}</p>
                                            <p><strong>Incluido mensualidad:</strong> ${ev.extendedProps.incluido_mensualidad ? 'Sí' : 'No'}</p>
                                            <div style="text-align:center; margin-top:10px;">
                                                <a href="https://tuchat.com?mensaje=me%20interesa%20participar%20en%20${encodeURIComponent(ev.title)}"
                                                class="btn-contactar" target="_blank">
                                                    Contactar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                `).join("")}
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                    ` : '<p>No hay eventos próximos de este tipo.</p>'}
                </div>
            `;

            // Inicializar Swiper
            new Swiper(".mySwiper", {
                slidesPerView: 3,
                spaceBetween: 20,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev"
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true
                },
                breakpoints: {
                    0: { slidesPerView: 1 },
                    768: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 }
                }
            });
        });
    });


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
