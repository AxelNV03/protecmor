<!-- resources/views/admin/dashboard.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
</head>
<body>





    <h1>Bienvenido Admin, {{ auth()->user()->name }}!</h1>
    <p>Rol: {{ auth()->user()->getRoleNames()->first() }}</p>
    
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar Sesión</button>
    </form> <br>
    <a href="{{ url('/') }}">Ir al Inicio</a>
</body>
</html>