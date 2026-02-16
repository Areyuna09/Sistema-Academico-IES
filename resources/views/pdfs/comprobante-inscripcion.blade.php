<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Inscripción</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ies-original.png') }}">
    <style>
        @page {
            margin: 12mm 12mm 12mm 12mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.4;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            font-size: 11px;
        }

        /* --- Cabecera institucional tipo historia clínica --- */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #bbb;
        }
        .header-table td {
            border: 1px solid #bbb;
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
            border: 1px solid #bbb;
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

        /* --- Datos del estudiante en grilla --- */
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
            border-left: 3px solid #bbb;
            text-transform: uppercase;
        }

        /* --- Tabla de materias --- */
        .materias-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .materias-table thead th {
            background-color: #1e3a5f;
            color: #fff;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #1e3a5f;
        }
        .materias-table tbody td {
            padding: 5px 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        .materias-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
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
            border-top: 1px solid #bbb;
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
                <div class="header-doc-title">Comprobante de Inscripción</div>
                <div class="header-doc-subtitle">{{ $periodo->nombre }}</div>
            </td>
            <td class="header-meta">
                Fecha: {{ now()->format('d/m/Y H:i') }}hs<br>
                N° Comprobante: {{ strtoupper(uniqid('INS-')) }}
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
            <td class="lbl">Carrera</td>
            <td class="val">{{ $alumno->carreraRelacion->nombre ?? 'Sin especificar' }}</td>
            <td class="lbl">Año de Cursada</td>
            <td class="val">{{ $alumno->curso }}° Año</td>
        </tr>
        <tr>
            <td class="lbl">División</td>
            <td class="val">{{ $alumno->division ?? '-' }}</td>
            <td class="lbl">Período Académico</td>
            <td class="val">{{ $periodo->nombre }}</td>
        </tr>
    </table>

    <!-- Materias inscritas -->
    <div class="section-title">Materias Inscritas ({{ $inscripciones->count() }})</div>
    <table class="materias-table">
        <thead>
            <tr>
                <th style="width: 8%">#</th>
                <th style="width: 55%">Materia</th>
                <th style="width: 37%">Año / Cuatrimestre</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inscripciones as $index => $inscripcion)
            <tr>
                <td style="text-align:center">{{ $index + 1 }}</td>
                <td style="font-weight:600">{{ $inscripcion->materia->nombre }}</td>
                <td>{{ $inscripcion->materia->anno }}° Año - {{ $inscripcion->materia->semestre === 1 ? '1er' : '2do' }} Cuatr.</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Importante -->
    <div class="important-box">
        <strong>IMPORTANTE</strong>
        <p>
            Este comprobante certifica su inscripción a las materias detalladas.
            Debe conservarlo como documento oficial y presentarlo cuando sea requerido.
            La inscripción está sujeta a la aprobación de las correlatividades correspondientes.
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
