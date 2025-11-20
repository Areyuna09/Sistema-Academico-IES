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
            border-bottom: 2px solid #0284c7;
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
            color: #0284c7;
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
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
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

        /* Sección de mesa */
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

        .mesa-box {
            background-color: #f0f9ff;
            border: 2px solid #0284c7;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 15px;
        }
        .mesa-materia {
            font-size: 14px;
            font-weight: bold;
            color: #0c4a6e;
            margin-bottom: 8px;
        }
        .mesa-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .mesa-info-item {
            flex: 1;
        }
        .mesa-info-label {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .mesa-info-value {
            font-size: 11px;
            color: #0c4a6e;
            font-weight: bold;
        }

        /* Tribunal */
        .tribunal-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 10px 12px;
            margin: 12px 0;
            border-radius: 4px;
        }
        .tribunal-box strong {
            color: #92400e;
            font-size: 10px;
            display: block;
            margin-bottom: 4px;
        }
        .tribunal-box p {
            margin: 0;
            font-size: 9px;
            color: #78350f;
            line-height: 1.4;
        }

        /* Caja importante */
        .important-box {
            background-color: #fee2e2;
            border-left: 4px solid #dc2626;
            padding: 10px 12px;
            margin: 12px 0;
            border-radius: 4px;
        }
        .important-box strong {
            color: #991b1b;
            font-size: 10px;
            display: block;
            margin-bottom: 4px;
        }
        .important-box p {
            margin: 0;
            font-size: 9px;
            color: #7f1d1d;
            line-height: 1.4;
        }

        /* Próximos pasos */
        .steps-box {
            background-color: #f0f9ff;
            border-left: 4px solid #0284c7;
            padding: 10px 12px;
            margin: 12px 0;
            border-radius: 4px;
        }
        .steps-box h3 {
            color: #075985;
            font-size: 10px;
            margin: 0 0 6px;
        }
        .steps-box ul {
            margin: 0;
            padding-left: 15px;
        }
        .steps-box li {
            font-size: 9px;
            color: #0c4a6e;
            line-height: 1.4;
            margin-bottom: 3px;
        }

        /* Footer - Fijo abajo */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px 12mm;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            background: white;
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
            @php
                // Para PDFs, DomPDF necesita la ruta absoluta del filesystem
                $logoPath = null;

                // Intentar logo_path (logo oscuro para fondos claros)
                if ($configuracion->logo_path) {
                    $storagePath = storage_path('app/public/' . $configuracion->logo_path);
                    if (file_exists($storagePath)) {
                        $logoPath = $storagePath;
                    }
                }

                // Fallback a logo en public/images
                if (!$logoPath && file_exists(public_path('images/logo-ies-original.png'))) {
                    $logoPath = public_path('images/logo-ies-original.png');
                }
            @endphp
            @if($logoPath)
                <img src="{{ $logoPath }}" alt="Logo {{ $configuracion->nombre_institucion }}" class="logo">
            @endif
            <div class="institution-name">{{ $configuracion->nombre_institucion }}</div>
            <div class="document-title">Comprobante de Inscripción a Mesa de Examen</div>
            <div class="document-subtitle">{{ $mesa->periodo->nombre ?? 'Mesa de Examen' }}</div>
        </div>

        <!-- Número de comprobante -->
        <div class="comprobante-number">
            <strong>N° de Comprobante:</strong> {{ strtoupper(uniqid('MESA-')) }} |
            <strong>Fecha de emisión:</strong> {{ now()->format('d/m/Y H:i') }}hs
        </div>

        <!-- Confirmación -->
        <div class="confirmation-badge">
            <h2>✓ INSCRIPCIÓN A MESA CONFIRMADA</h2>
            <p>Su inscripción ha sido procesada y confirmada exitosamente</p>
        </div>

        <!-- Información del estudiante -->
        <h3 class="section-title">Datos del Estudiante</h3>
        <table class="info-table">
            <tr>
                <td class="label">Apellido y Nombre:</td>
                <td class="value">{{ strtoupper($alumno->nombre_completo) }}</td>
            </tr>
            <tr>
                <td class="label">DNI:</td>
                <td class="value">{{ number_format($alumno->dni, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Legajo:</td>
                <td class="value">{{ $alumno->legajo }}</td>
            </tr>
            <tr>
                <td class="label">Carrera:</td>
                <td class="value">{{ $mesa->materia->getRelation('carrera')->nombre ?? 'Sin especificar' }}</td>
            </tr>
        </table>

        <!-- Información de la Mesa -->
        <h3 class="section-title">Detalles de la Mesa de Examen</h3>
        <div class="mesa-box">
            <div class="mesa-materia">{{ $mesa->materia->nombre }}</div>

            <div class="mesa-info">
                <div class="mesa-info-item">
                    <div class="mesa-info-label">📅 Fecha</div>
                    <div class="mesa-info-value">{{ $mesa->fecha_examen ? $mesa->fecha_examen->format('d/m/Y') : 'A confirmar' }}</div>
                </div>
                <div class="mesa-info-item">
                    <div class="mesa-info-label">🕐 Hora</div>
                    <div class="mesa-info-value">{{ $mesa->hora_examen ?? 'A confirmar' }}</div>
                </div>
                <div class="mesa-info-item">
                    <div class="mesa-info-label">🏫 Aula</div>
                    <div class="mesa-info-value">{{ $mesa->aula ?? 'A confirmar' }}</div>
                </div>
            </div>

            @if($mesa->presidente || $mesa->vocal1 || $mesa->vocal2)
            <div class="tribunal-box">
                <strong>👥 Tribunal Evaluador</strong>
                <p>
                    @if($mesa->presidente)
                        Presidente: {{ $mesa->presidente->nombre_completo }}<br>
                    @endif
                    @if($mesa->vocal1)
                        Vocal 1: {{ $mesa->vocal1->nombre_completo }}<br>
                    @endif
                    @if($mesa->vocal2)
                        Vocal 2: {{ $mesa->vocal2->nombre_completo }}
                    @endif
                </p>
            </div>
            @endif

            @if($mesa->observaciones)
            <div style="margin-top: 8px; font-size: 9px; color: #64748b;">
                <strong>Observaciones:</strong> {{ $mesa->observaciones }}
            </div>
            @endif
        </div>

        <!-- Próximos pasos -->
        <div class="steps-box">
            <h3>📋 Antes del Examen</h3>
            <ul>
                <li>Verificar horarios y aula en el Sistema de Gestión Académica</li>
                <li>Revisar el material de estudio y programa de la materia</li>
                <li>Confirmar que tiene aprobadas todas las correlativas</li>
                <li>Presentarse con 15 minutos de anticipación</li>
                <li>Traer DNI original y este comprobante impreso</li>
                <li>Ante cualquier consulta, comunicarse con Secretaría Académica</li>
            </ul>
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
