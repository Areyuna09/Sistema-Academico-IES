<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Inscriptos - Mesa de Examen</title>
    <style>
        @page {
            margin: 15mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.3;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-size: 10px;
        }

        /* Header con logo */
        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .logo {
            max-width: 60px;
            height: auto;
            margin-bottom: 6px;
        }
        .institution-name {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            margin: 6px 0 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .document-title {
            font-size: 13px;
            color: #2563eb;
            font-weight: bold;
            margin: 8px 0 4px;
            text-transform: uppercase;
        }
        .document-subtitle {
            font-size: 10px;
            color: #64748b;
        }

        /* Información de la mesa */
        .mesa-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .mesa-info-grid {
            display: table;
            width: 100%;
        }
        .mesa-info-row {
            display: table-row;
        }
        .mesa-info-cell {
            display: table-cell;
            padding: 3px 8px;
            vertical-align: top;
        }
        .mesa-info-label {
            font-weight: bold;
            color: #475569;
            width: 25%;
        }
        .mesa-info-value {
            color: #1e293b;
        }

        /* Tabla de inscriptos */
        .inscriptos-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .inscriptos-table th {
            background: #2563eb;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .inscriptos-table td {
            padding: 6px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9px;
        }
        .inscriptos-table tr:nth-child(even) {
            background: #f8fafc;
        }
        .inscriptos-table tr:hover {
            background: #f1f5f9;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

        /* Estado badge */
        .estado {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .estado-inscripto { background: #dbeafe; color: #1d4ed8; }
        .estado-presente { background: #cffafe; color: #0891b2; }
        .estado-ausente { background: #fed7aa; color: #c2410c; }
        .estado-aprobado { background: #dcfce7; color: #16a34a; }
        .estado-desaprobado { background: #fee2e2; color: #dc2626; }

        /* Tribunal */
        .tribunal {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
        }
        .tribunal-title {
            font-weight: bold;
            color: #475569;
            margin-bottom: 6px;
            font-size: 9px;
        }
        .tribunal-member {
            font-size: 9px;
            margin-bottom: 3px;
        }

        /* Footer - Fijo abajo */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px 15mm;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            background: white;
        }
        .footer-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 7px;
            color: #64748b;
        }
        .footer-col {
            flex: 1;
        }
        .footer-note {
            margin-top: 6px;
            font-size: 6px;
            color: #94a3b8;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        @if($configuracion && $configuracion->logo_url)
            <img src="{{ public_path('storage/' . str_replace('/storage/', '', $configuracion->logo_url)) }}" alt="Logo" class="logo">
        @else
            <img src="{{ public_path('images/logo-ies-original.png') }}" alt="Logo" class="logo">
        @endif
        <div class="institution-name">
            {{ $configuracion->nombre_institucion ?? 'IES Gral. Manuel Belgrano' }}
        </div>
        <div class="document-title">Listado de Inscriptos a Mesa de Examen</div>
        <div class="document-subtitle">Generado el {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <!-- Información de la mesa -->
    <div class="mesa-info">
        <div class="mesa-info-grid">
            <div class="mesa-info-row">
                <div class="mesa-info-cell mesa-info-label">Materia:</div>
                <div class="mesa-info-cell mesa-info-value">{{ $mesa->materia->nombre }}</div>
                <div class="mesa-info-cell mesa-info-label">Carrera:</div>
                <div class="mesa-info-cell mesa-info-value">{{ $carrera->nombre ?? 'Sin carrera' }}</div>
            </div>
            <div class="mesa-info-row">
                <div class="mesa-info-cell mesa-info-label">Fecha:</div>
                <div class="mesa-info-cell mesa-info-value">{{ \Carbon\Carbon::parse($mesa->fecha_examen)->format('d/m/Y') }}</div>
                <div class="mesa-info-cell mesa-info-label">Hora:</div>
                <div class="mesa-info-cell mesa-info-value">{{ \Carbon\Carbon::parse($mesa->hora_examen)->format('H:i') }}</div>
            </div>
            <div class="mesa-info-row">
                <div class="mesa-info-cell mesa-info-label">Llamado:</div>
                <div class="mesa-info-cell mesa-info-value">{{ $mesa->llamado ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Tabla de inscriptos -->
    @if($inscripciones->count() > 0)
        <table class="inscriptos-table">
            <thead>
                <tr>
                    <th style="width: 15%">DNI</th>
                    <th style="width: 30%">Alumno</th>
                    <th style="width: 8%">Año</th>
                    <th style="width: 32%">Email</th>
                    <th style="width: 15%">Fecha Insc.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inscripciones as $index => $inscripcion)
                    <tr>
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
        <div style="text-align: center; padding: 30px; color: #64748b;">
            <p>No hay alumnos inscriptos en esta mesa.</p>
        </div>
    @endif

    <!-- Tribunal -->
    @if($mesa->presidente || $mesa->vocal1 || $mesa->vocal2)
        <div class="tribunal">
            <div class="tribunal-title">TRIBUNAL EVALUADOR</div>
            @if($mesa->presidente)
                <div class="tribunal-member"><strong>Presidente:</strong> {{ $mesa->presidente->apellido }}, {{ $mesa->presidente->nombre }}</div>
            @endif
            @if($mesa->vocal1)
                <div class="tribunal-member"><strong>Vocal 1:</strong> {{ $mesa->vocal1->apellido }}, {{ $mesa->vocal1->nombre }}</div>
            @endif
            @if($mesa->vocal2)
                <div class="tribunal-member"><strong>Vocal 2:</strong> {{ $mesa->vocal2->apellido }}, {{ $mesa->vocal2->nombre }}</div>
            @endif
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="footer-row">
            <div class="footer-col">
                <strong>Secretaría Académica</strong><br>
                {{ $configuracion->nombre_institucion ?? 'IES Gral. Manuel Belgrano' }}<br>
                @if($configuracion && $configuracion->direccion)
                    {{ $configuracion->direccion }}
                @endif
            </div>
            <div class="footer-col">
                <strong>Contacto</strong><br>
                @if($configuracion && $configuracion->email)
                    {{ $configuracion->email }}<br>
                @endif
                @if($configuracion && $configuracion->telefono)
                    Tel: {{ $configuracion->telefono }}
                @endif
            </div>
        </div>

        <div class="footer-note">
            @if($configuracion && $configuracion->footer_documentos)
                {!! nl2br(e($configuracion->footer_documentos)) !!}<br>
            @endif
            Documento generado automáticamente el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}hs.<br>
            © {{ date('Y') }} {{ $configuracion->nombre_institucion ?? 'IES Gral. Manuel Belgrano' }} - Todos los derechos reservados
        </div>
    </div>
</body>
</html>
