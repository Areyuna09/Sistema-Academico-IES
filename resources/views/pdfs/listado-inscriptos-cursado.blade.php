<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Inscriptos - Cursado</title>
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

        /* --- Cabecera institucional --- */
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

        /* --- Filtros aplicados --- */
        .filtros-bar {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .filtros-bar td {
            padding: 4px 8px;
            border: 1px solid #ccc;
            font-size: 10px;
            background-color: #f5f5f5;
        }
        .filtros-bar .flbl {
            font-weight: bold;
            color: #333;
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

        /* --- Resumen compacto --- */
        .resumen-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .resumen-table td {
            border: 1px solid #ccc;
            padding: 6px 10px;
            text-align: center;
        }
        .resumen-table .num {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a5f;
        }
        .resumen-table .rlbl {
            font-size: 9px;
            color: #666;
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
                <div class="header-doc-title">Listado de Inscriptos a Cursado</div>
                <div class="header-doc-subtitle">
                    @if($periodo){{ $periodo->nombre }}@endif
                </div>
            </td>
            <td class="header-meta">
                Fecha: {{ now()->format('d/m/Y H:i') }}hs
            </td>
        </tr>
    </table>

    <!-- Tabla de inscriptos -->
    @if($inscripciones->count() > 0)
        <table class="inscriptos-table">
            <thead>
                <tr>
                    <th style="width: 12%">DNI</th>
                    <th style="width: 28%">Alumno</th>
                    <th style="width: 25%">Materia</th>
                    <th style="width: 22%">Carrera</th>
                    <th style="width: 8%">Año</th>
                    <th style="width: 5%">Div.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inscripciones as $index => $inscripcion)
                    @php
                        $carreraNombre = '-';
                        if ($inscripcion->materia && $inscripcion->materia->carrera) {
                            if (is_object($inscripcion->materia->carrera)) {
                                $carreraNombre = $inscripcion->materia->carrera->nombre;
                            } else {
                                $carreraObj = \App\Models\Carrera::find($inscripcion->materia->carrera);
                                $carreraNombre = $carreraObj->nombre ?? '-';
                            }
                        }
                    @endphp
                    <tr>
                        <td>{{ $inscripcion->alumno->dni }}</td>
                        <td>{{ $inscripcion->alumno->apellido }}, {{ $inscripcion->alumno->nombre }}</td>
                        <td>{{ $inscripcion->materia->nombre }}</td>
                        <td>{{ $carreraNombre }}</td>
                        <td class="text-center">{{ $inscripcion->materia->anno ?? '-' }}°</td>
                        <td class="text-center">{{ $inscripcion->alumno->division ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @else
        <div style="text-align: center; padding: 30px; color: #666;">
            <p>No se encontraron inscripciones con los filtros aplicados.</p>
        </div>
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
