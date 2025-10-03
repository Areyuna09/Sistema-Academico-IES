<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Inscripción</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px 20px;
        }
        .alert {
            background-color: #dcfce7;
            border-left: 4px solid #16a34a;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert h2 {
            margin: 0 0 10px;
            color: #15803d;
            font-size: 18px;
        }
        .alert p {
            margin: 0;
            color: #166534;
        }
        .info-section {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .info-section h3 {
            margin: 0 0 15px;
            color: #1e293b;
            font-size: 16px;
            font-weight: bold;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #64748b;
        }
        .info-value {
            color: #1e293b;
        }
        .materias-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .materia-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .materia-item:last-child {
            margin-bottom: 0;
        }
        .check-icon {
            width: 24px;
            height: 24px;
            background-color: #16a34a;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .check-icon::before {
            content: '✓';
            color: white;
            font-weight: bold;
        }
        .materia-info {
            flex: 1;
        }
        .materia-name {
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .materia-details {
            font-size: 13px;
            color: #64748b;
        }
        .materia-status {
            background-color: #dcfce7;
            color: #15803d;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
            color: #64748b;
            font-size: 14px;
        }
        .info-box {
            background-color: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            color: #1e40af;
            font-size: 14px;
        }
        .next-steps {
            background-color: #fefce8;
            border: 1px solid #fde047;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        .next-steps h3 {
            margin: 0 0 15px;
            color: #713f12;
            font-size: 16px;
        }
        .next-steps ul {
            margin: 0;
            padding-left: 20px;
        }
        .next-steps li {
            color: #854d0e;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✓ Inscripción Confirmada</h1>
            <p>Comprobante de inscripción académica</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Success Alert -->
            <div class="alert">
                <h2>¡Inscripción procesada exitosamente!</h2>
                <p>Tu inscripción ha sido confirmada. Conserva este comprobante como respaldo.</p>
            </div>

            <!-- Student Info -->
            <div class="info-section">
                <h3>Información del Estudiante</h3>
                <div class="info-row">
                    <span class="info-label">Nombre:</span>
                    <span class="info-value">{{ $alumno->nombre_completo }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">DNI:</span>
                    <span class="info-value">{{ $alumno->dni }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Período:</span>
                    <span class="info-value">{{ $periodo->nombre }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha:</span>
                    <span class="info-value">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <!-- Enrolled Subjects -->
            <div class="info-section">
                <h3>Materias Inscritas ({{ $inscripciones->count() }})</h3>
                <ul class="materias-list">
                    @foreach($inscripciones as $inscripcion)
                    <li class="materia-item">
                        <div class="check-icon"></div>
                        <div class="materia-info">
                            <div class="materia-name">{{ $inscripcion->materia->nombre }}</div>
                            <div class="materia-details">
                                {{ $inscripcion->materia->anno }}° Año -
                                {{ $inscripcion->materia->semestre === 1 ? '1er' : '2do' }} Cuatrimestre
                            </div>
                        </div>
                        <span class="materia-status">Confirmada</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Important Info -->
            <div class="info-box">
                <p><strong>📧 Importante:</strong> Este es tu comprobante oficial de inscripción. Guárdalo para futuras consultas.</p>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>📋 Próximos Pasos</h3>
                <ul>
                    <li>Revisa los horarios de cursada en la plataforma académica</li>
                    <li>Verifica las fechas de inicio de clases</li>
                    <li>Mantén este comprobante como respaldo de tu inscripción</li>
                    <li>Ante cualquier duda, comunícate con la secretaría académica</li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Instituto de Educación Superior</strong></p>
            <p>Este es un mensaje automático, por favor no responder a este correo.</p>
            <p style="margin-top: 15px; font-size: 12px;">© {{ date('Y') }} IES - Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>
