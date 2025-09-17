<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Eventos</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/pages/calendarioEventos.css'])
</head>
<body>
<div class="container">
    <h1>Calendario de Eventos</h1>
    <div id="calendar"></div>
</div>

<!-- Modal Crear/Editar -->
<div id="modalForm" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalForm()">&times;</span>
        <h2><span id="formTitle">Crear Evento</span></h2>
        <form id="eventoForm">
            @csrf
            <input type="hidden" id="eventoId" name="id">
            <label>Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <label>Tipo:</label>
            <input type="text" id="tipo" name="tipo" required>
            <label>Fecha:</label>
            <input type="date" id="fecha" name="fecha" required>
            <label>Hora:</label>
            <input type="time" id="hora" name="hora">
            <label>Duración:</label>
            <input type="text" id="duracion" name="duracion">
            <label>Costo:</label>
            <input type="number" id="costo" name="costo">
            <label>Lugar:</label>
            <input type="text" id="lugar" name="lugar">
            <label>Descripción:</label>
            <textarea id="descripcion" name="descripcion"></textarea>
            <label>Público:</label>
            <select id="publico" name="publico" required>
                <option value="alumnos">Alumnos</option>
                <option value="general">General</option>
            </select>
            <div class="checkbox-container">
                <input type="checkbox" id="incluido_mensualidad" name="incluido_mensualidad" value="1">
                <label for="incluido_mensualidad">Incluido en mensualidad</label>
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">Guardar Evento</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detalles -->
<div id="modalEvento" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalEvento()">&times;</span>
        <input type="hidden" id="detalleId">
        <h2>Detalles del Evento</h2>
        <p><strong>Título:</strong> <span id="detalleTitulo"></span></p>
        <p><strong>Tipo:</strong> <span id="detalleTipo"></span></p>
        <p><strong>Fecha:</strong> <span id="detalleFecha"></span></p>
        <p><strong>Hora:</strong> <span id="detalleHora"></span></p>
        <p><strong>Duración:</strong> <span id="detalleDuracion"></span></p>
        <p><strong>Costo:</strong> <span id="detalleCosto"></span></p>
        <p><strong>Lugar:</strong> <span id="detalleLugar"></span></p>
        <p><strong>Descripción:</strong> <span id="detalleDescripcion"></span></p>
        <p><strong>Público:</strong> <span id="detallePublico"></span></p>
        <p><strong>Incluido mensualidad:</strong> <span id="detalleMensualidad"></span></p>
        <div class="modal-actions">
            <a id="modalWhatsapp" href="#" target="_blank" class="btn btn-primary">Contactar por WhatsApp</a>
            <button id="btnEditar" class="btn btn-warning">Editar</button>
            <button id="btnEliminar" class="btn btn-danger">Eliminar</button>
            <button onclick="cerrarModalEvento()" class="btn btn-secondary">Cerrar</button>
        </div>
    </div>
</div>

@vite('resources/js/pages/calendarioEventos.js')
</body>
</html>
