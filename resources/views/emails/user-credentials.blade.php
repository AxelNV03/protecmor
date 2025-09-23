<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Credenciales de {{ ucfirst($userType) }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3b82f6; color: white; padding: 20px; text-align: center; }
        .content { background: white; padding: 20px; }
        .credentials { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Bienvenido al Sistema</h2>
        </div>
        
        <div class="content">
            <p>Hola <strong>{{ $userName }}</strong>,</p>
            
            <p>Se ha creado una cuenta de <strong>{{ $userType }}</strong> para ti. Aquí están tus credenciales:</p>
            
            <div class="credentials">
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Contraseña temporal:</strong> <code style="font-size: 16px;">{{ $password }}</code></p>
                <p><strong>Tipo de usuario:</strong> {{ ucfirst($userType) }}</p>
                <p><strong>URL de acceso:</strong> <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>
            </div>
            
            <p><strong>Recomendaciones de seguridad:</strong></p>
            <ul>
                <li>Cambia tu contraseña después del primer acceso</li>
                <li>No compartas tus credenciales</li>
                <li>Usa una contraseña segura</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>Este email fue enviado automáticamente. Por favor no responder.</p>
        </div>
    </div>
</body>
</html>