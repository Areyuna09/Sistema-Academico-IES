<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Cambio de Contraseña</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .alert-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .alert-box p {
            margin: 0;
            color: #92400e;
        }
        .info-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box h3 {
            margin-top: 0;
            color: #1e40af;
            font-size: 16px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #4b5563;
        }
        .info-value {
            color: #6b7280;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .footer a {
            color: #2563eb;
            text-decoration: none;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="icon">🔐</div>
            <h1>Contraseña Actualizada</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p style="font-size: 16px; color: #111827;">Hola <strong>{{ $userName }}</strong>,</p>

            <p>Tu contraseña ha sido <strong>restablecida exitosamente</strong> en el sistema {{ config('app.name') }}.</p>

            <!-- Alert Box -->
            <div class="alert-box">
                <p><strong>⚠️ Si no realizaste este cambio:</strong></p>
                <p>Por favor, contacta inmediatamente al administrador del sistema para proteger tu cuenta.</p>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <h3>📋 Detalles del Cambio</h3>
                <div class="info-item">
                    <span class="info-label">Fecha y Hora:</span>
                    <span class="info-value">{{ $timestamp }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Dirección IP:</span>
                    <span class="info-value">{{ $ipAddress }}</span>
                </div>
            </div>

            <p style="margin-top: 30px;">Ya puedes iniciar sesión con tu nueva contraseña.</p>

            <center>
                <a href="{{ route('login') }}" class="button">Iniciar Sesión</a>
            </center>

            <p style="font-size: 14px; color: #6b7280; margin-top: 30px;">
                <strong>Consejos de seguridad:</strong>
            </p>
            <ul style="font-size: 14px; color: #6b7280;">
                <li>Nunca compartas tu contraseña con nadie</li>
                <li>Usa contraseñas únicas para cada servicio</li>
                <li>Considera usar un gestor de contraseñas</li>
                <li>Cambia tu contraseña periódicamente</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p>© {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
