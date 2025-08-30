<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROTECMOR - Academia Multidisciplinaria</title>
    <style>

    </style>
</head>
<body>
    <!-- separator     -->
    <hr class="my-5">
    <h1>Barra de Navegación top</h1>

    <!-- Navigation-->
    <ul class="navbar-nav me-auto">
        <li class="nav-item">
            <a class="nav-link active" href="{{ url('/') }}">Inicio</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#!">Nosotros</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#!">Diplomados</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#!">Talleres</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#!">Agenda académica</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#!">TACTIKAR ZONE</a>
        </li>
    </ul>

    <!-- Login  -->
    @if (Route::has('login'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">Login</a>
        </li>
    @endif
    @if (Route::has('register'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">Register</a>
        </li>
    @endif

    <!-- separator -->
    <hr class="my-5">
    <h1>Sección de carrete de fotos</h1>

    <h2>Protecmor</h2>
    <p>Diplomado Integral en Urgencias Médicas y Gestión de Riesgos
        Fórmate como Paramédico en sus diferentes niveles Básico, Intermedio y Avanzado 
        con especialidad en gestión integral de riesgos, protección civil y bomberos. 
        Especialidad única en su tipo, con enfoque multidisciplinario.
    </p>
    <button href="#">Ver más</button> <br><br>

    <!-- Imagen 1 -->
    <div>
        <p>Aval Académico UAEM: Diplomado validado por la Facultad de Medicina UAEM con reconocimiento oficial.</p>
        <button>ver temario</button>
    </div>
    <!-- Imagen 2 -->
    <div>
        <p>Docentes Expertos Equipo de médicos, paramédicos y bomberos con amplia experiencia práctica.</p>
        <button>Nuestros Docentes</button>
    </div>
    <!-- Imagen 3 -->
    <div>
        <p>Talleres Prácticos Incluye transportación aeromédica, rescate acuático, combate de incendios y más.</p>
        <button>Ver Talleres</button>
    </div>


    <!-- separator -->
    <hr class="my-5">
    <h1>Carrete de inscribete y talleres</h1>

    <!-- Inscribete -->
    <div>
        <p>Diplomado Integral en Urgencias Médicas y Gestión de Riesgos</p>
        <button>Inscríbete</button>
    </div>

    <!-- ver talleres -->
    <div>
        <p>Explora nuestros talleres prácticos y especialízate en áreas clave.</p>
        <button>Conoce nuestros talleres</button>
    </div>


    <!-- separator -->
    <hr class="my-5">
    <h1>Sección del mapa, y footer de reedes sociales</h1>
</body>
</html>