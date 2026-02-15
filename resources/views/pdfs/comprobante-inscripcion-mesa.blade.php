<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Inscripción a Mesa de Examen</title>
    <style>
        @page {
            margin: 12mm 12mm 20mm 12mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.4;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            font-size: 11px;
        }

        /* --- Cabecera institucional --- */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #333;
        }
        .header-table td {
            border: 1px solid #333;
            vertical-align: middle;
        }
        .header-logo {
            width: 80px;
            padding: 8px;
            text-align: center;
        }
        .header-logo img {
            max-width: 65px;
            max-height: 65px;
        }
        .header-info {
            padding: 8px 12px;
        }
        .header-info .inst-name {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a1a1a;
            margin-bottom: 3px;
        }
        .header-info .inst-detail {
            font-size: 9px;
            color: #555;
            line-height: 1.4;
        }
        .header-title-row td {
            padding: 6px 12px;
            border: 1px solid #333;
        }
        .header-doc-title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a1a1a;
        }
        .header-doc-subtitle {
            font-size: 10px;
            color: #555;
        }
        .header-meta {
            text-align: right;
            font-size: 10px;
            color: #333;
        }

        /* --- Grilla de datos --- */
        .data-grid {
            width: 100%;
            border-collapse: collapse;
            margin: 14px 0 10px;
        }
        .data-grid td {
            padding: 5px 8px;
            border: 1px solid #ccc;
            font-size: 11px;
        }
        .data-grid .lbl {
            font-weight: bold;
            color: #333;
            background-color: #f5f5f5;
            width: 22%;
            white-space: nowrap;
        }
        .data-grid .val {
            color: #1a1a1a;
        }

        /* --- Sección título --- */
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
            margin: 14px 0 6px;
            padding: 4px 8px;
            background-color: #f0f0f0;
            border-left: 3px solid #333;
            text-transform: uppercase;
        }

        /* --- Caja importante --- */
        .important-box {
            border: 1px solid #c9a227;
            background-color: #fefce8;
            padding: 8px 10px;
            margin: 12px 0;
        }
        .important-box strong {
            font-size: 10px;
            color: #92400e;
            display: block;
            margin-bottom: 3px;
        }
        .important-box p {
            margin: 0;
            font-size: 10px;
            color: #78350f;
            line-height: 1.4;
        }

        /* --- Pie de página --- */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 6px 12mm;
            border-top: 1px solid #999;
            text-align: center;
            font-size: 8px;
            color: #666;
            background: #fff;
        }
    </style>
</head>
<body>
    @php
        $logoPath = null;
        if ($configuracion->logo_path) {
            $storagePath = storage_path('app/public/' . $configuracion->logo_path);
            if (file_exists($storagePath)) {
                $logoPath = $storagePath;
            }
        }
        if (!$logoPath && file_exists(public_path('images/logo-letras-oscuras.png'))) {
            $logoPath = public_path('images/logo-letras-oscuras.png');
        }
    @endphp

    <!-- Cabecera institucional -->
    <table class="header-table">
        <tr>
            <td class="header-logo" rowspan="2">
                @if($logoPath)
                    <img src="{{ $logoPath }}" alt="Logo">
                @endif
            </td>
            <td class="header-info" colspan="2">
                <div class="inst-name">{{ $configuracion->nombre_institucion }}</div>
                <div class="inst-detail">
                    @if($configuracion->direccion){{ $configuracion->direccion }}@endif
                    @if($configuracion->telefono) &middot; Tel: {{ $configuracion->telefono }}@endif
                    @if($configuracion->email) &middot; {{ $configuracion->email }}@endif
                </div>
            </td>
        </tr>
        <tr class="header-title-row">
            <td>
                <div class="header-doc-title">Comprobante de Inscripción a Mesa de Examen</div>
                <div class="header-doc-subtitle">{{ $mesa->periodo?->nombre ?? 'Mesa de Examen' }}</div>
            </td>
            <td class="header-meta">
                Fecha: {{ now()->format('d/m/Y H:i') }}hs<br>
                N° Comprobante: {{ strtoupper(uniqid('MESA-')) }}
            </td>
        </tr>
    </table>

    <!-- Datos del estudiante -->
    <div class="section-title">Datos del Estudiante</div>
    <table class="data-grid">
        <tr>
            <td class="lbl">Apellido y Nombre</td>
            <td class="val">{{ strtoupper($alumno->nombre_completo) }}</td>
            <td class="lbl">DNI</td>
            <td class="val">{{ number_format($alumno->dni, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="lbl">Legajo</td>
            <td class="val">{{ $alumno->legajo }}</td>
            <td class="lbl">Carrera</td>
            <td class="val">{{ $carrera?->nombre ?? 'Sin especificar' }}</td>
        </tr>
    </table>

    <!-- Datos de la mesa -->
    <div class="section-title">Detalles de la Mesa de Examen</div>
    <table class="data-grid">
        <tr>
            <td class="lbl">Materia</td>
            <td class="val">{{ $mesa->materia->nombre }}</td>
            <td class="lbl">Carrera</td>
            <td class="val">{{ $carrera?->nombre ?? 'Sin especificar' }}</td>
        </tr>
        <tr>
            <td class="lbl">Fecha</td>
            <td class="val">{{ $mesa->fecha_examen ? $mesa->fecha_examen->format('d/m/Y') : 'A confirmar' }}</td>
            <td class="lbl">Hora</td>
            <td class="val">{{ $mesa->hora_examen ?? 'A confirmar' }}</td>
        </tr>
        @if($mesa->presidente || $mesa->vocal1 || $mesa->vocal2)
        <tr>
            <td class="lbl">Tribunal</td>
            <td class="val" colspan="3">
                @if($mesa->presidente)Presidente: {{ $mesa->presidente?->nombre_completo }}@endif
                @if($mesa->vocal1) &middot; Vocal 1: {{ $mesa->vocal1?->nombre_completo }}@endif
                @if($mesa->vocal2) &middot; Vocal 2: {{ $mesa->vocal2?->nombre_completo }}@endif
            </td>
        </tr>
        @endif
        @if($mesa->observaciones)
        <tr>
            <td class="lbl">Observaciones</td>
            <td class="val" colspan="3">{{ $mesa->observaciones }}</td>
        </tr>
        @endif
    </table>

    <!-- Importante -->
    <div class="important-box">
        <strong>IMPORTANTE</strong>
        <p>
            Presentarse con 15 minutos de anticipación con DNI original y este comprobante.
            Confirmar que tiene aprobadas todas las correlativas requeridas.
            Ante cualquier consulta, comunicarse con Secretaría Académica.
        </p>
    </div>

    <!-- Pie de página -->
    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i') }}hs
        | {{ $configuracion->nombre_institucion }}
        @if($configuracion->footer_documentos)
            | {{ $configuracion->footer_documentos }}
        @endif
    </div>
</body>
</html>
