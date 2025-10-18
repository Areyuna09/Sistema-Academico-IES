<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Rechazada</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #dc2626;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        .institution-name {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
            margin: 10px 0;
        }
        .alert-badge {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
            text-align: center;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-badge h2 {
            margin: 0 0 5px;
            font-size: 20px;
            font-weight: bold;
        }
        .alert-badge p {
            margin: 0;
            font-size: 14px;
            opacity: 0.95;
        }
        .info-section {
            background-color: #f8fafc;
            border-left: 4px solid #dc2626;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-section h3 {
            margin: 0 0 10px 0;
            color: #dc2626;
            font-size: 16px;
        }
        .info-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #475569;
            min-width: 150px;
        }
        .info-value {
            color: #1e293b;
        }
        .motivo-box {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .motivo-box h3 {
            margin: 0 0 10px 0;
            color: #92400e;
            font-size: 16px;
        }
        .motivo-box p {
            margin: 0;
            color: #78350f;
            font-style: italic;
        }
        .action-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .action-box h3 {
            margin: 0 0 10px 0;
            color: #1e40af;
            font-size: 16px;
        }
        .action-box ul {
            margin: 0;
            padding-left: 20px;
        }
        .action-box li {
            color: #1e40af;
            margin-bottom: 8px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            @if($configuracion->logo_path)
                <img src="{{ asset('storage/' . $configuracion->logo_path) }}" alt="Logo {{ $configuracion->nombre_institucion }}" class="logo">
            @else
                <img src="{{ asset('images/logo-ies-original.png') }}" alt="Logo IES" class="logo">
            @endif
            <div class="institution-name">{{ $configuracion->nombre_institucion }}</div>
        </div>

        <!-- Alert Badge -->
        <div class="alert-badge">
            <h2>❌ NOTA RECHAZADA</h2>
            <p>La nota que cargaste fue rechazada por Secretaría Académica</p>
        </div>

        <!-- Información de la nota -->
        <div class="info-section">
            <h3>📋 Detalles de la Nota Rechazada</h3>
            <div class="info-row">
                <div class="info-label">Alumno:</div>
                <div class="info-value">{{ $nota->alumno->apellido }}, {{ $nota->alumno->nombre }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">DNI:</div>
                <div class="info-value">{{ number_format($nota->alumno->dni, 0, ',', '.') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Materia:</div>
                <div class="info-value">{{ $nota->profesorMateria->materiaRelacion->nombre ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tipo de Evaluación:</div>
                <div class="info-value">{{ $nota->tipo_evaluacion }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nota Cargada:</div>
                <div class="info-value">{{ $nota->nota }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($nota->fecha)->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Rechazado por:</div>
                <div class="info-value">{{ $nota->aprobadoPor->nombre ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Motivo del rechazo -->
        <div class="motivo-box">
            <h3>🔍 Motivo del Rechazo</h3>
            <p>{{ $nota->observaciones ?? 'No se especificó un motivo.' }}</p>
        </div>

        <!-- Acciones a seguir -->
        <div class="action-box">
            <h3>📌 ¿Qué hacer ahora?</h3>
            <ul>
                <li>Revisa el motivo del rechazo indicado arriba</li>
                <li>Corrige la nota según las observaciones de Secretaría</li>
                <li>Vuelve a cargar la nota correcta en el sistema</li>
                <li>Si tienes dudas, comunícate con Secretaría Académica</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ $configuracion->nombre_institucion }}</strong></p>
            @if($configuracion->direccion)
                <p>{{ $configuracion->direccion }}</p>
            @endif
            @if($configuracion->email)
                <p>Email: {{ $configuracion->email }}</p>
            @endif
            @if($configuracion->telefono)
                <p>Tel: {{ $configuracion->telefono }}</p>
            @endif
            <p style="margin-top: 15px; font-style: italic;">
                Este es un mensaje automático del Sistema Académico. Por favor, no respondas a este correo.
            </p>
        </div>
    </div>
</body>
</html>
