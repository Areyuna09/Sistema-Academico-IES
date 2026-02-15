<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Inscriptos - Mesa de Examen</title>
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

        /* --- Grilla de datos de mesa --- */
        .data-grid {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
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
            width: 18%;
            white-space: nowrap;
        }
        .data-grid .val {
            color: #1a1a1a;
        }

        /* --- Tabla de inscriptos --- */
        .inscriptos-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .inscriptos-table thead th {
            background-color: #1e3a5f;
            color: #fff;
            padding: 6px 6px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #1e3a5f;
        }
        .inscriptos-table tbody td {
            padding: 4px 6px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        .inscriptos-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }

        /* --- Tribunal al pie --- */
        .tribunal-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }
        .tribunal-table td {
            padding: 5px 8px;
            border: 1px solid #ccc;
            font-size: 10px;
        }
        .tribunal-table .tlbl {
            font-weight: bold;
            color: #333;
            background-color: #f5f5f5;
            width: 15%;
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
        if ($configuracion && $configuracion->logo_path) {
            $storagePath = storage_path('app/public/' . $configuracion->logo_path);
            if (file_exists($storagePath)) {
                $logoPath = $storagePath;
            }
        }
        if (!$logoPath && $configuracion && $configuracion->logo_url) {
            $altPath = public_path('storage/' . str_replace('/storage/', '', $configuracion->logo_url));
            if (file_exists($altPath)) {
                $logoPath = $altPath;
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
                <div class="inst-name">{{ $configuracion->nombre_institucion ?? 'IES Gral. Manuel Belgrano' }}</div>
                <div class="inst-detail">
                    @if($configuracion && $configuracion->direccion){{ $configuracion->direccion }}@endif
                    @if($configuracion && $configuracion->telefono) &middot; Tel: {{ $configuracion->telefono }}@endif
                    @if($configuracion && $configuracion->email) &middot; {{ $configuracion->email }}@endif
                </div>
            </td>
        </tr>
        <tr class="header-title-row">
            <td>
                <div class="header-doc-title">Listado de Inscriptos a Mesa de Examen</div>
                <div class="header-doc-subtitle">{{ $mesa->materia->nombre }}</div>
            </td>
            <td class="header-meta">
                Fecha: {{ now()->format('d/m/Y H:i') }}hs
            </td>
        </tr>
    </table>

    <!-- Datos de la mesa -->
    <table class="data-grid">
        <tr>
            <td class="lbl">Materia</td>
            <td class="val">{{ $mesa->materia->nombre }}</td>
            <td class="lbl">Carrera</td>
            <td class="val">{{ $carrera->nombre ?? 'Sin carrera' }}</td>
        </tr>
        <tr>
            <td class="lbl">Fecha</td>
            <td class="val">{{ \Carbon\Carbon::parse($mesa->fecha_examen)->format('d/m/Y') }}</td>
            <td class="lbl">Hora</td>
            <td class="val">{{ \Carbon\Carbon::parse($mesa->hora_examen)->format('H:i') }}</td>
        </tr>
        <tr>
            <td class="lbl">Llamado</td>
            <td class="val">{{ $mesa->llamado ?? 'N/A' }}</td>
            <td class="lbl">Inscriptos</td>
            <td class="val">{{ $inscripciones->count() }}</td>
        </tr>
    </table>

    <!-- Tabla de inscriptos -->
    @if($inscripciones->count() > 0)
        <table class="inscriptos-table">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 15%">DNI</th>
                    <th style="width: 28%">Alumno</th>
                    <th style="width: 7%">Año</th>
                    <th style="width: 30%">Email</th>
                    <th style="width: 15%">Fecha Insc.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inscripciones as $index => $inscripcion)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $inscripcion->alumno->dni }}</td>
                        <td>{{ $inscripcion->alumno->apellido }}, {{ $inscripcion->alumno->nombre }}</td>
                        <td class="text-center">{{ $inscripcion->alumno->curso ?? '-' }}°</td>
                        <td>{{ $inscripcion->alumno->email ?? '-' }}</td>
                        <td class="text-center">{{ $inscripcion->created_at ? $inscripcion->created_at->format('d/m/Y') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 30px; color: #666;">
            <p>No hay alumnos inscriptos en esta mesa.</p>
        </div>
    @endif

    <!-- Tribunal -->
    @if($mesa->presidente || $mesa->vocal1 || $mesa->vocal2)
        <table class="tribunal-table">
            <tr>
                <td class="tlbl" colspan="4" style="text-align:center; font-weight:bold; text-transform:uppercase;">Tribunal Evaluador</td>
            </tr>
            <tr>
                @if($mesa->presidente)
                    <td class="tlbl">Presidente</td>
                    <td>{{ $mesa->presidente->apellido }}, {{ $mesa->presidente->nombre }}</td>
                @endif
                @if($mesa->vocal1)
                    <td class="tlbl">Vocal 1</td>
                    <td>{{ $mesa->vocal1->apellido }}, {{ $mesa->vocal1->nombre }}</td>
                @endif
            </tr>
            @if($mesa->vocal2)
            <tr>
                <td class="tlbl">Vocal 2</td>
                <td>{{ $mesa->vocal2->apellido }}, {{ $mesa->vocal2->nombre }}</td>
                <td colspan="2"></td>
            </tr>
            @endif
        </table>
    @endif

    <!-- Pie de página -->
    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i') }}hs
        | {{ $configuracion->nombre_institucion ?? 'IES Gral. Manuel Belgrano' }}
        @if($configuracion && $configuracion->footer_documentos)
            | {{ $configuracion->footer_documentos }}
        @endif
    </div>
</body>
</html>
