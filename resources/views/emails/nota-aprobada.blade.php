<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Aprobada</title>
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
            border-bottom: 3px solid #16a34a;
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
        .success-badge {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            text-align: center;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .success-badge h2 {
            margin: 0 0 5px;
            font-size: 20px;
            font-weight: bold;
        }
        .success-badge p {
            margin: 0;
            font-size: 14px;
            opacity: 0.95;
        }
        .info-section {
            background-color: #f8fafc;
            border-left: 4px solid #16a34a;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-section h3 {
            margin: 0 0 10px 0;
            color: #16a34a;
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
        .nota-destacada {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border: 2px solid #16a34a;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .nota-destacada .label {
            font-size: 14px;
            color: #15803d;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .nota-destacada .valor {
            font-size: 48px;
            color: #15803d;
            font-weight: bold;
            line-height: 1;
        }
        .info-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            color: #1e40af;
            font-size: 16px;
        }
        .info-box p {
            margin: 5px 0;
            color: #1e40af;
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

        <!-- Success Badge -->
        <div class="success-badge">
            <h2>✅ NOTA APROBADA</h2>
            <p>La nota que cargaste fue aprobada y transferida al legajo oficial</p>
        </div>

        <!-- Información de la nota -->
        <div class="info-section">
            <h3>📋 Detalles de la Nota Aprobada</h3>
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
                <div class="info-label">Fecha:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($nota->fecha)->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Aprobado por:</div>
                <div class="info-value">{{ $nota->aprobadoPor->nombre ?? 'Sistema Automático' }}</div>
            </div>
        </div>

        <!-- Nota destacada -->
        <div class="nota-destacada">
            <div class="label">CALIFICACIÓN</div>
            <div class="valor">{{ $nota->nota }}</div>
        </div>

        <!-- Información adicional -->
        <div class="info-box">
            <h3>📌 Información Importante</h3>
            <p>✓ La nota ha sido transferida exitosamente al legajo académico oficial del alumno</p>
            <p>✓ El alumno podrá consultar esta calificación en Secretaría Académica</p>
            <p>✓ La nota ya forma parte del historial académico permanente</p>
            @if(strtolower($nota->tipo_evaluacion) === 'final')
                <p>✓ Como es una nota final, se actualizó el estado de la materia en el legajo</p>
            @else
                <p>✓ Como es una nota de cursada, se registró en el sistema académico</p>
            @endif
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
