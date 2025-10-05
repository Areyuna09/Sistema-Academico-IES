<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Inscripción a Mesa de Examen</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.3;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        .page {
            width: 180mm;
            padding: 12mm;
            margin: 0 auto;
            background: white;
        }

        /* Header con logo */
        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .logo {
            max-width: 70px;
            height: auto;
            margin-bottom: 8px;
        }
        .institution-name {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
            margin: 8px 0 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .document-title {
            font-size: 16px;
            color: #2563eb;
            font-weight: bold;
            margin: 10px 0 4px;
            text-transform: uppercase;
        }
        .document-subtitle {
            font-size: 11px;
            color: #64748b;
        }

        /* Sección de confirmación */
        .confirmation-badge {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            text-align: center;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        .confirmation-badge h2 {
            margin: 0 0 4px;
            font-size: 14px;
            font-weight: bold;
        }
        .confirmation-badge p {
            margin: 0;
            font-size: 10px;
            opacity: 0.95;
        }

        /* Información en tabla */
        .info-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .info-table .label {
            font-weight: bold;
            color: #475569;
            width: 35%;
        }
        .info-table .value {
            color: #1e293b;
        }

        /* Secciones */
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
            margin: 12px 0 8px;
            padding-bottom: 4px;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Información importante */
        .important-box {
            background-color: #fef3c7;
            border-left: 3px solid #f59e0b;
            padding: 8px;
            margin: 10px 0;
            border-radius: 3px;
        }
        .important-box strong {
            color: #92400e;
            display: block;
            margin-bottom: 3px;
            font-size: 9px;
        }
        .important-box p {
            margin: 0;
            color: #78350f;
            font-size: 8px;
            line-height: 1.4;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        .footer-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 8px;
            color: #64748b;
        }
        .footer-col {
            flex: 1;
        }
        .footer-signature {
            margin-top: 15px;
            padding-top: 10px;
        }
        .signature-line {
            border-top: 1px solid #1e293b;
            width: 150px;
            margin: 0 auto 5px;
        }
        .signature-label {
            font-size: 8px;
            color: #475569;
            font-weight: bold;
        }
        .footer-note {
            margin-top: 8px;
            font-size: 7px;
            color: #94a3b8;
            font-style: italic;
        }

        /* Número de comprobante */
        .comprobante-number {
            text-align: right;
            font-size: 9px;
            color: #64748b;
            margin-bottom: 8px;
        }
        .comprobante-number strong {
            color: #1e293b;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            @if($configuracion->logo_path)
                <img src="{{ public_path('storage/' . $configuracion->logo_path) }}" alt="Logo {{ $configuracion->nombre_institucion }}" class="logo">
            @else
                <img src="{{ public_path('images/logo-ies-original.png') }}" alt="Logo IES" class="logo">
            @endif
            <div class="institution-name">{{ $configuracion->nombre_institucion }}</div>
            <div class="document-title">Comprobante de Inscripción a Mesa de Examen</div>
            <div class="document-subtitle">{{ $datos['materia']['nombre'] }}</div>
        </div>

        <!-- Número de comprobante -->
        <div class="comprobante-number">
            <strong>N° de Comprobante:</strong> {{ strtoupper(uniqid('MESA-')) }} |
            <strong>Fecha de emisión:</strong> {{ now()->format('d/m/Y H:i') }}hs
        </div>

        <!-- Confirmación -->
        <div class="confirmation-badge">
            <h2>✓ INSCRIPCIÓN CONFIRMADA</h2>
            <p>Su inscripción a la mesa de examen ha sido confirmada exitosamente</p>
        </div>

        <!-- Información del estudiante -->
        <h3 class="section-title">Datos del Estudiante</h3>
        <table class="info-table">
            <tr>
                <td class="label">Apellido y Nombre:</td>
                <td class="value">{{ strtoupper($datos['alumno']['nombre_completo']) }}</td>
            </tr>
            <tr>
                <td class="label">DNI:</td>
                <td class="value">{{ number_format($datos['alumno']['dni'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Fecha de Inscripción:</td>
                <td class="value">{{ $datos['fecha_inscripcion']->format('d/m/Y H:i') }}hs</td>
            </tr>
        </table>

        <!-- Información de la Mesa -->
        <h3 class="section-title">Datos de la Mesa de Examen</h3>
        <table class="info-table">
            <tr>
                <td class="label">Materia:</td>
                <td class="value">{{ $datos['materia']['nombre'] }}</td>
            </tr>
            <tr>
                <td class="label">Carrera:</td>
                <td class="value">{{ $datos['carrera']['nombre'] }}</td>
            </tr>
            <tr>
                <td class="label">Llamado:</td>
                <td class="value">{{ $datos['mesa']['llamado'] }}° Llamado</td>
            </tr>
            <tr>
                <td class="label">Fecha del Examen:</td>
                <td class="value">{{ $datos['mesa']['fecha_examen']->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Hora:</td>
                <td class="value">{{ $datos['mesa']['hora_examen'] }}</td>
            </tr>
            @if($datos['mesa']['aula'])
            <tr>
                <td class="label">Aula:</td>
                <td class="value">{{ $datos['mesa']['aula'] }}</td>
            </tr>
            @endif
        </table>

        <!-- Tribunal -->
        @if($datos['tribunal']['presidente'] || $datos['tribunal']['vocal1'] || $datos['tribunal']['vocal2'])
        <h3 class="section-title">Tribunal Evaluador</h3>
        <table class="info-table">
            @if($datos['tribunal']['presidente'])
            <tr>
                <td class="label">Presidente:</td>
                <td class="value">{{ $datos['tribunal']['presidente'] }}</td>
            </tr>
            @endif
            @if($datos['tribunal']['vocal1'])
            <tr>
                <td class="label">Vocal 1:</td>
                <td class="value">{{ $datos['tribunal']['vocal1'] }}</td>
            </tr>
            @endif
            @if($datos['tribunal']['vocal2'])
            <tr>
                <td class="label">Vocal 2:</td>
                <td class="value">{{ $datos['tribunal']['vocal2'] }}</td>
            </tr>
            @endif
        </table>
        @endif

        <!-- Información importante -->
        <div class="important-box">
            <strong>📌 IMPORTANTE</strong>
            <p>
                • Debe presentarse 15 minutos antes del horario del examen.<br>
                • Es obligatorio traer el DNI original.<br>
                • Este comprobante debe conservarse como documento oficial.<br>
                • La inscripción está sujeta a las correlativas aprobadas correspondientes.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-row">
                <div class="footer-col">
                    <strong>Secretaría Académica</strong><br>
                    {{ $configuracion->nombre_institucion }}<br>
                    @if($configuracion->direccion)
                        {{ $configuracion->direccion }}
                    @endif
                </div>
                <div class="footer-col">
                    <strong>Contacto</strong><br>
                    @if($configuracion->email)
                        {{ $configuracion->email }}<br>
                    @endif
                    @if($configuracion->telefono)
                        Tel: {{ $configuracion->telefono }}
                    @endif
                </div>
            </div>

            <div class="footer-signature">
                @if($configuracion->firma_digital_path)
                    <img src="{{ public_path('storage/' . $configuracion->firma_digital_path) }}" alt="Firma" style="max-width: 120px; height: auto; margin-bottom: 5px;">
                @else
                    <div class="signature-line"></div>
                @endif
                <div class="signature-label">{{ $configuracion->cargo_firma ?? 'Secretaría Académica' }}</div>
            </div>

            <div class="footer-note">
                @if($configuracion->footer_documentos)
                    {!! nl2br(e($configuracion->footer_documentos)) !!}<br>
                @endif
                Documento generado automáticamente el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}hs.<br>
                Este comprobante tiene validez oficial sin necesidad de firma autógrafa.<br>
                © {{ date('Y') }} {{ $configuracion->nombre_institucion }} - Todos los derechos reservados
            </div>
        </div>
    </div>
</body>
</html>
