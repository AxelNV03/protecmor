<!-- resources/views/admin/dashboard.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>PROTECMOR - Admin Dashboard</title>
</head>
<body>
    <h1>Bienvenido Admin, {{ auth()->user()->name }}!</h1>
    <p>Rol: {{ auth()->user()->getRoleNames()->first() }}</p>
    
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar Sesión</button>
    </form> <br>
    <a href="{{ url('/') }}">Ir al Inicio</a>

    
    <!-- Lista lateral -->
    <hr class="my-5">
    <h1>Lista lateral</h1>
    <hr>
        @role('super admin')<li><a href="">Administradores</a></li>@endrole
        <li><a href="">Alumnos</a></li>
        <li><a href="">Profesores</a></li>
        <li><a href="">Grupos</a></li>
        <li><a href="">Clases</a></li>
        <li><a href="">Materiales</a></li>
        <li><a href="">Talleres</a></li>
        <li><a href="">Materias</a></li>
        <li><a href="">Eventos</a></li>
        <li><a href="">Pases de lista</a></li>
        <li><a href="">Pagos</a></li>
        <li><a href="">Calificaciones</a></li>
        <li><a href="">Reportes</a></li>
        <li><a href="">Respaldo de Base de Datos</a></li>
    <hr class="my-5">

</body>
</html>

