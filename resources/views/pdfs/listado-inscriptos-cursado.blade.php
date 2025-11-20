<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Inscriptos - Cursado</title>
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

        /* Filtros aplicados */
        .filtros-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 8px 10px;
            margin-bottom: 15px;
            font-size: 9px;
        }
        .filtros-info span {
            margin-right: 15px;
        }
        .filtros-label {
            font-weight: bold;
            color: #475569;
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
            padding: 6px 4px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .inscriptos-table td {
            padding: 5px 4px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 8px;
        }
        .inscriptos-table tr:nth-child(even) {
            background: #f8fafc;
        }
        .text-center {
            text-align: center;
        }

        /* Estado badge */
        .estado {
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .estado-confirmada { background: #dcfce7; color: #16a34a; }
        .estado-pendiente { background: #fef3c7; color: #d97706; }
        .estado-cancelada { background: #fee2e2; color: #dc2626; }

        /* Resumen */
        .resumen {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 4px;
            padding: 10px;
            margin-top: 15px;
        }
        .resumen-title {
            font-weight: bold;
            color: #0369a1;
            margin-bottom: 6px;
        }
        .resumen-grid {
            display: table;
            width: 100%;
        }
        .resumen-item {
            display: table-cell;
            text-align: center;
            padding: 4px;
        }
        .resumen-numero {
            font-size: 16px;
            font-weight: bold;
            color: #0369a1;
        }
        .resumen-label {
            font-size: 8px;
            color: #64748b;
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
        <div class="document-title">Listado de Inscriptos a Cursado</div>
        <div class="document-subtitle">Generado el {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <!-- Filtros aplicados -->
    @if($periodo || !empty($filtros['carrera_id']) || !empty($filtros['estado']))
        <div class="filtros-info">
            <strong>Filtros aplicados:</strong>
            @if($periodo)
                <span><span class="filtros-label">Período:</span> {{ $periodo->nombre }}</span>
            @endif
            @if(!empty($filtros['estado']))
                <span><span class="filtros-label">Estado:</span> {{ ucfirst($filtros['estado']) }}</span>
            @endif
        </div>
    @endif

    <!-- Tabla de inscriptos -->
    @if($inscripciones->count() > 0)
        <table class="inscriptos-table">
            <thead>
                <tr>
                    <th style="width: 4%">N°</th>
                    <th style="width: 10%">DNI</th>
                    <th style="width: 20%">Alumno</th>
                    <th style="width: 20%">Materia</th>
                    <th style="width: 16%">Carrera</th>
                    <th style="width: 8%">Año</th>
                    <th style="width: 10%">División</th>
                    <th style="width: 12%">Turno</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inscripciones as $index => $inscripcion)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $inscripcion->alumno->dni }}</td>
                        <td>{{ $inscripcion->alumno->apellido }}, {{ $inscripcion->alumno->nombre }}</td>
                        <td>{{ $inscripcion->materia->nombre }}</td>
                        <td>{{ $inscripcion->materia->carrera->nombre ?? '-' }}</td>
                        <td class="text-center">{{ $inscripcion->alumno->curso ?? '-' }}°</td>
                        <td class="text-center">{{ $inscripcion->division ?? '-' }}</td>
                        <td class="text-center">{{ $inscripcion->cursado ? ucfirst($inscripcion->cursado) : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Resumen -->
        <div class="resumen">
            <div class="resumen-title">Resumen de Inscripciones</div>
            <div class="resumen-grid">
                <div class="resumen-item">
                    <div class="resumen-numero">{{ $inscripciones->count() }}</div>
                    <div class="resumen-label">Total Inscriptos</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-numero">{{ $inscripciones->unique('alumno_id')->count() }}</div>
                    <div class="resumen-label">Alumnos</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-numero">{{ $inscripciones->unique('materia_id')->count() }}</div>
                    <div class="resumen-label">Materias</div>
                </div>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 30px; color: #64748b;">
            <p>No se encontraron inscripciones con los filtros aplicados.</p>
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
