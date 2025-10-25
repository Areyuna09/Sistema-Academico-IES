<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Inscripción</title>
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

        /* Lista de materias */
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
        .materias-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .materias-table thead {
            background-color: #2563eb;
            color: white;
        }
        .materias-table th {
            padding: 7px 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .materias-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
            line-height: 1.3;
        }
        .materias-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .materia-name {
            font-weight: 600;
            color: #1e293b;
        }
        .materia-details {
            color: #64748b;
            font-size: 8px;
        }
        .status-badge {
            background-color: #dcfce7;
            color: #15803d;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }

        /* Caja importante */
        .important-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 10px 12px;
            margin: 12px 0;
            border-radius: 4px;
        }
        .important-box strong {
            color: #92400e;
            font-size: 10px;
            display: block;
            margin-bottom: 4px;
        }
        .important-box p {
            margin: 0;
            font-size: 9px;
            color: #78350f;
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
            <div class="document-title">Comprobante de Inscripción</div>
            <div class="document-subtitle">{{ $periodo->nombre }}</div>
        </div>

        <!-- Número de comprobante -->
        <div class="comprobante-number">
            <strong>N° de Comprobante:</strong> {{ strtoupper(uniqid('INS-')) }} |
            <strong>Fecha de emisión:</strong> {{ now()->format('d/m/Y H:i') }}hs
        </div>

        <!-- Confirmación -->
        <div class="confirmation-badge">
            <h2>✓ INSCRIPCIÓN CONFIRMADA</h2>
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
                <td class="label">Año de Cursada:</td>
                <td class="value">{{ $alumno->curso }}° Año</td>
            </tr>
            <tr>
                <td class="label">Período Académico:</td>
                <td class="value">{{ $periodo->nombre }}</td>
            </tr>
        </table>

        <!-- Materias inscritas -->
        <h3 class="section-title">Materias Inscritas ({{ $inscripciones->count() }})</h3>
        <table class="materias-table">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 55%">Materia</th>
                    <th style="width: 20%">Año / Cuatrimestre</th>
                    <th style="width: 20%">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inscripciones as $index => $inscripcion)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="materia-name">{{ $inscripcion->materia->nombre }}</div>
                    </td>
                    <td>
                        <div class="materia-details">
                            {{ $inscripcion->materia->anno }}° Año - {{ $inscripcion->materia->semestre === 1 ? '1er' : '2do' }} Cuatr.
                        </div>
                    </td>
                    <td>
                        <span class="status-badge">Confirmada</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Información importante -->
        <div class="important-box">
            <strong>📌 IMPORTANTE</strong>
            <p>
                Este comprobante certifica su inscripción a las materias detalladas.
                Debe conservarlo como documento oficial y presentarlo cuando sea requerido.
                La inscripción está sujeta a la aprobación de las correlatividades correspondientes.
            </p>
        </div>

        <!-- Próximos pasos -->
        <div class="steps-box">
            <h3>📋 Próximos Pasos</h3>
            <ul>
                <li>Verificar los horarios de cursada en el Sistema de Gestión Académica</li>
                <li>Consultar las fechas de inicio de clases para cada materia</li>
                <li>Presentar este comprobante en Secretaría Académica si es requerido</li>
                <li>Ante cualquier consulta, comunicarse con Secretaría en horario de atención</li>
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
