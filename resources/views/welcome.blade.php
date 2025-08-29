{{-- welcome.blade.php --}}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'PROTECMOR'))</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <style>
        /* Estilos personalizados para PROTECMOR */
        .btn-disabled {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
        }
        .navbar-brand {
            font-weight: bold;
            color: #2c3e50 !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <strong>PROTECMOR</strong>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
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
            
            <!-- Botones de login/register o perfil de usuario -->
            <ul class="navbar-nav ms-auto">
                @guest
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
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                Cerrar Sesión
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section" style="background: url('https://protecmor.com.mx/wp-content/uploads/2025/06/IMG_0235-1-scaled.jpg') no-repeat center center; background-size: cover; min-height: 50vh; position: relative;">
    <div style="background: rgba(0,0,0,0.55); position: absolute; top: 0; left: 0; right: 0; bottom: 0;"></div>
    <div class="container" style="position: relative; z-index: 1; color: white; padding: 100px 0; text-align: center;">
        <h1 style="font-family: 'Times New Roman', serif; font-size: 40px;">PROTECMOR</h1>
        <p style="font-family: 'Times New Roman', serif; font-size: 20px; margin: 40px 0;">
            Academia Multidisciplinaria en Protección Civil Morelos<br>
            Transformamos tu pasión en acción a través del liderazgo en la formación innovadora de paramédicos básico, intermedio y avanzado con la especialidad en la gestión integral de riesgos protección civil y bomberos.
        </p>
        <a href="#!" class="btn btn-primary btn-disabled">Ver más →</a>
    </div>
</section>

<!-- Features Section -->
<section class="features-section" style="padding: 50px 0;">
    <div class="container">
        <div class="row">
            <!-- Aval Académico -->
            <div class="col-md-4 text-center">
                <div class="feature-item">
                    <svg style="width: 50px; height: 50px; margin-bottom: 20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256zM371.8 211.8C382.7 200.9 382.7 183.1 371.8 172.2C360.9 161.3 343.1 161.3 332.2 172.2L224 280.4L179.8 236.2C168.9 225.3 151.1 225.3 140.2 236.2C129.3 247.1 129.3 264.9 140.2 275.8L204.2 339.8C215.1 350.7 232.9 350.7 243.8 339.8L371.8 211.8z"></path>
                    </svg>
                    <h3 style="font-family: Arial, sans-serif; font-size: 20px;">Aval Académico UAEM</h3>
                    <p style="font-family: Arial, sans-serif; font-size: 15px;">
                        Diplomado validado por la Facultad de Medicina UAEM con reconocimiento oficial.
                    </p>
                    <a href="#!" class="btn btn-outline-dark btn-disabled">Ver temario</a>
                </div>
            </div>

            <!-- Docentes Expertos -->
            <div class="col-md-4 text-center">
                <div class="feature-item">
                    <img src="https://protecmor.com.mx/wp-content/uploads/2025/06/profesor.png" alt="Docentes" style="width: 50px; height: 50px; margin-bottom: 20px;">
                    <h3 style="font-family: Arial, sans-serif; font-size: 20px;">Docentes Expertos</h3>
                    <p style="font-family: Arial, sans-serif; font-size: 15px;">
                        Equipo de médicos, paramédicos y bomberos con amplia experiencia práctica.
                    </p>
                    <a href="#!" class="btn btn-outline-dark btn-disabled">Nuestros docentes</a>
                </div>
            </div>

            <!-- Talleres Prácticos -->
            <div class="col-md-4 text-center">
                <div class="feature-item">
                    <img src="https://protecmor.com.mx/wp-content/uploads/2025/06/talleres-de-trabajo.png" alt="Talleres" style="width: 50px; height: 50px; margin-bottom: 20px;">
                    <h3 style="font-family: Arial, sans-serif; font-size: 20px;">Talleres Prácticos</h3>
                    <p style="font-family: Arial, sans-serif; font-size: 15px;">
                        Incluye transportación aeromédica, rescate acuático, combate de incendios y más.
                    </p>
                    <a href="#!" class="btn btn-outline-dark btn-disabled">Ver talleres</a>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="my-5">

<!-- Sección Diplomado + Carrusel -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <!-- Texto -->
            <div class="col-md-6">
                <h2 class="mb-4">Diplomado Integral en Urgencias Médicas y Gestión de Riesgos</h2>
                <p class="lead mb-4">
                    Fórmate como Paramédico en sus diferentes niveles Básico, Intermedio y Avanzado 
                    con especialidad en gestión integral de riesgos, protección civil y bomberos. 
                    Especialidad única en su tipo, con enfoque multidisciplinario.
                </p>
                <a href="#" class="btn btn-success btn-lg">INSCRIBETE →</a>
            </div>
            
            <!-- Carrusel de Fotos -->
            <div class="col-md-6">
                <div id="protecmorCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner rounded shadow">
                        <!-- Imagen 1 -->
                        <div class="carousel-item active">
                            <img src="https://via.placeholder.com/600x400/2c3e50/ffffff?text=Imagen+1" 
                                 class="d-block w-100" 
                                 alt="Formación en paramedicina">
                        </div>
                        <!-- Imagen 2 -->
                        <div class="carousel-item">
                            <img src="https://via.placeholder.com/600x400/e74c3c/ffffff?text=Imagen+2" 
                                 class="d-block w-100" 
                                 alt="Talleres prácticos">
                        </div>
                        <!-- Imagen 3 -->
                        <div class="carousel-item">
                            <img src="https://via.placeholder.com/600x400/3498db/ffffff?text=Imagen+3" 
                                 class="d-block w-100" 
                                 alt="Equipo docente">
                        </div>
                    </div>
                    <!-- Controles del carrusel -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#protecmorCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#protecmorCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="my-5">


<!-- Sección Talleres + Carrusel -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            
            <!-- Carrusel de Fotos de Talleres -->
            <div class="col-md-7">
                <div id="talleresCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner rounded shadow">
                        <!-- Imagen 1 -->
                        <div class="carousel-item active">
                            <img src="https://via.placeholder.com/600x400/2c3e50/ffffff?text=Transportación+Aeromédica" 
                                 class="d-block w-100" 
                                 alt="Transportación aeromédica">
                        </div>
                        <!-- Imagen 2 -->
                        <div class="carousel-item">
                            <img src="https://via.placeholder.com/600x400/e74c3c/ffffff?text=STOP+THE+BLEED" 
                                 class="d-block w-100" 
                                 alt="STOP THE BLEED">
                        </div>
                        <!-- Imagen 3 -->
                        <div class="carousel-item">
                            <img src="https://via.placeholder.com/600x400/3498db/ffffff?text=BLS" 
                                 class="d-block w-100" 
                                 alt="BLS">
                        </div>
                        <!-- Imagen 4 -->
                        <div class="carousel-item">
                            <img src="https://via.placeholder.com/600x400/27ae60/ffffff?text=Rescate+Acuático" 
                                 class="d-block w-100" 
                                 alt="Rescate acuático">
                        </div>
                        <!-- Imagen 5 -->
                        <div class="carousel-item">
                            <img src="https://via.placeholder.com/600x400/f39c12/ffffff?text=Combate+Incendios" 
                                 class="d-block w-100" 
                                 alt="Combate contra incendios">
                        </div>
                    </div>
                    <!-- Controles del carrusel -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#talleresCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#talleresCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </div>

            <!-- Texto -->
            <div class="col-md-5">
                <h2 class="mb-4">Talleres</h2>
                <p class="mb-4">
                    Explora todos nuestros talleres:<br>
                    – Transportación aeromédica<br>
                    – STOP THE BLEED<br>
                    – BLS<br>
                    – Preservación del lugar de la escena y la intervención del servicio médico prehospitalario<br>
                    – Elaboración de programas internos<br>
                    – Rescate acuático<br>
                    – Rapeel<br>
                    – Operadores de vehículos de emergencia<br>
                    – Combate contra incendios
                </p>
                <a href="#" class="btn btn-primary">Conoce nuestros talleres →</a>
            </div>
        </div>
    </div>
</section>

<hr class="my-5">

<!-- Sección Ubicación - Google Maps -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2>Facultad de Medicina | UAEM</h2>
                <p class="lead">Lefíceros S/N, Los Volcanes, 62350 Cuernavaca, Mor.</p>
                <div class="mt-2">
                    <span class="badge bg-warning text-dark">4.6 ★★★★☆</span>
                    <span class="text-muted ms-2">158 opiniones</span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Mapa de Google -->
            <div class="col-md-8 mb-4">
                <div class="rounded shadow overflow-hidden">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3770.835036045312!2d-99.212394!3d19.072999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85ce03d3c68e40b1%3A0x368285efcec32145!2sFacultad%20de%20Medicina%20%7C%20UAEM!5e0!3m2!1ses!2smx!4v1690840000000!5m2!1ses!2smx" 
                        width="100%" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="mt-2 text-end">
                    <small class="text-muted">Datos del mapa ©2025 INEGI</small>
                </div>
            </div>

            <!-- Información de ubicación -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Áreas cercanas</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Mercado Adolfo López Mateos</li>
                            <li class="list-group-item">AMATITLAN</li>
                            <li class="list-group-item">TEOFANZOLCO</li>
                            <li class="list-group-item">Ciprés</li>
                            <li class="list-group-item">SANTA VERACRUZ</li>
                            <li class="list-group-item">Cuernavaca Centro</li>
                            <li class="list-group-item">Av Plan de Ayala</li>
                            <li class="list-group-item">Gran Outlet Cuernavaca</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="https://www.google.com/maps/place/Facultad+de+Medicina+%7C+UAEM/@19.072999,-99.212394,15z/data=!4m6!3m5!1s0x85ce03d3c68e40b1:0x368285efcec32145!8m2!3d19.072999!4d-99.212394!16s%2Fg%2F11g9v5z5y2?entry=ttu" 
                       target="_blank" 
                       class="btn btn-outline-primary w-100">
                        <i class="fas fa-map-marked-alt me-2"></i>Ampliar el mapa
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de contacto -->
<footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
        <div class="row align-items-center">
            <!-- Menú del footer -->
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="footer-menu">
                    <a href="{{ url('/') }}" class="text-light text-decoration-none me-3">Inicio</a>
                    <a href="#!" class="text-light text-decoration-none me-3">Nosotros</a>
                    <a href="#!" class="text-light text-decoration-none me-3">Diplomados</a>
                    <a href="#!" class="text-light text-decoration-none me-3">Talleres</a>
                    <a href="#!" class="text-light text-decoration-none me-3">Agenda académica</a>
                    <a href="#!" class="text-light text-decoration-none">TACTIKAR ZONE</a>
                </div>
            </div>
            
            <!-- Iconos de redes sociales -->
            <div class="col-md-3 mb-3 mb-md-0 text-center">
                <div class="social-icons">
                    <a href="#" class="text-light me-3" title="Facebook">
                        <i class="fab fa-facebook-f fa-lg"></i>
                    </a>
                    <a href="#" class="text-light me-3" title="Instagram">
                        <i class="fab fa-instagram fa-lg"></i>
                    </a>
                    <a href="#" class="text-light me-3" title="Twitter">
                        <i class="fab fa-twitter fa-lg"></i>
                    </a>
                    <a href="#" class="text-light" title="YouTube">
                        <i class="fab fa-youtube fa-lg"></i>
                    </a>
                </div>
            </div>
            
            <!-- Derechos reservados -->
            <div class="col-md-3 text-md-end">
                <p class="mb-0">Todos los derechos © 2025 PROTECMOR</p>
            </div>
        </div>
    </div>
</footer>

<!-- Sección de inscripción flotante -->
<div class="position-fixed bottom-0 end-0 m-3 p-3 bg-light rounded shadow-lg">
    <div class="d-flex align-items-center">
        <span class="me-2 fw-bold">¿Quieres inscribirte?</span>
        <a href="https://wa.me/5211234567890?text=Hola,%20me%20quiero%20inscribir%20en%20PROTECMOR" 
           class="btn btn-success p-2" 
           target="_blank">
            <i class="fab fa-whatsapp fa-2x"></i>
        </a>
    </div>
</div>
</body>
</html>