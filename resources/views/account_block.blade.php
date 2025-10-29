<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuenta Desactivada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 text-center">
                <div class="card p-4">
                    <h1 class="card-title text-warning">⚠️ Cuenta Desactivada</h1>
                    <p class="card-text">Tu cuenta ha sido desactivada y no puedes acceder al sistema.</p>
                    <p class="card-text">Por favor, contacta a un administrador para solicitar la reactivación.</p>
                    
                    {{-- Opcional: Muestra una forma de contacto --}}
                    <p class="mt-4"><strong>Contacto:</strong> <a href="mailto:admin@tu-escuela.com">admin@tu-escuela.com</a></p>

                    {{-- Formulario para cerrar sesión --}}
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-primary">Cerrar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>