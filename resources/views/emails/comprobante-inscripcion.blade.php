<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Inscripción</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f3f4f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <!-- Container principal -->
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">

                    <!-- Header con gradiente -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); padding: 40px 30px; text-align: center;">
                            <div style="background-color: rgba(255,255,255,0.2); display: inline-block; padding: 15px 30px; border-radius: 50px; margin-bottom: 15px;">
                                <span style="font-size: 32px; color: #ffffff;">🎓</span>
                            </div>
                            <h1 style="margin: 0 0 10px; font-size: 28px; color: #ffffff; font-weight: 700; letter-spacing: -0.5px;">
                                {{ $configuracion->nombre_institucion ?? 'IES G.M. Belgrano' }}
                            </h1>
                            <p style="margin: 0; font-size: 14px; color: rgba(255,255,255,0.9); text-transform: uppercase; letter-spacing: 1px; font-weight: 500;">
                                Comprobante de Inscripción
                            </p>
                        </td>
                    </tr>

                    <!-- Badge de confirmación -->
                    <tr>
                        <td style="padding: 30px 30px 20px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 20px; border-radius: 8px; text-align: center;">
                                        <div style="font-size: 18px; color: #ffffff; font-weight: 700; margin-bottom: 5px;">
                                            ✓ INSCRIPCIÓN CONFIRMADA
                                        </div>
                                        <div style="font-size: 13px; color: rgba(255,255,255,0.95);">
                                            Tu inscripción ha sido procesada exitosamente
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Número de comprobante -->
                    <tr>
                        <td style="padding: 0 30px 20px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f9fafb; border-radius: 6px; padding: 12px 15px;">
                                <tr>
                                    <td style="font-size: 12px; color: #6b7280;">
                                        <strong style="color: #111827;">N° Comprobante:</strong> {{ strtoupper(uniqid('INS-')) }}
                                    </td>
                                    <td align="right" style="font-size: 12px; color: #6b7280;">
                                        <strong style="color: #111827;">Fecha:</strong> {{ now()->format('d/m/Y H:i') }}hs
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Datos del estudiante -->
                    <tr>
                        <td style="padding: 0 30px 25px;">
                            <h2 style="margin: 0 0 15px; font-size: 16px; color: #111827; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 3px solid #3b82f6; padding-bottom: 8px;">
                                📋 Datos del Estudiante
                            </h2>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                                        <span style="font-size: 13px; color: #6b7280; font-weight: 600;">Apellido y Nombre:</span><br>
                                        <span style="font-size: 15px; color: #111827; font-weight: 700;">{{ strtoupper($alumno->nombre_completo) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                                        <span style="font-size: 13px; color: #6b7280; font-weight: 600;">DNI:</span><br>
                                        <span style="font-size: 15px; color: #111827;">{{ number_format($alumno->dni, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                                        <span style="font-size: 13px; color: #6b7280; font-weight: 600;">Año de Cursada:</span><br>
                                        <span style="font-size: 15px; color: #111827;">{{ $alumno->curso }}° Año</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0;">
                                        <span style="font-size: 13px; color: #6b7280; font-weight: 600;">Período Académico:</span><br>
                                        <span style="font-size: 15px; color: #111827; font-weight: 600;">{{ $periodo->nombre }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Materias inscritas -->
                    <tr>
                        <td style="padding: 0 30px 25px;">
                            <h2 style="margin: 0 0 15px; font-size: 16px; color: #111827; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 3px solid #3b82f6; padding-bottom: 8px;">
                                📚 Materias Inscritas ({{ $inscripciones->count() }})
                            </h2>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden;">
                                <thead>
                                    <tr style="background-color: #1e40af;">
                                        <th style="padding: 12px 10px; text-align: left; font-size: 12px; color: #ffffff; font-weight: 700; text-transform: uppercase; width: 8%;">#</th>
                                        <th style="padding: 12px 10px; text-align: left; font-size: 12px; color: #ffffff; font-weight: 700; text-transform: uppercase;">Materia</th>
                                        <th style="padding: 12px 10px; text-align: center; font-size: 12px; color: #ffffff; font-weight: 700; text-transform: uppercase; width: 25%;">Año/Cuatrim.</th>
                                        <th style="padding: 12px 10px; text-align: center; font-size: 12px; color: #ffffff; font-weight: 700; text-transform: uppercase; width: 18%;">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inscripciones as $index => $inscripcion)
                                    <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f9fafb' }};">
                                        <td style="padding: 12px 10px; font-size: 14px; color: #6b7280; font-weight: 600; border-bottom: 1px solid #e5e7eb;">
                                            {{ $index + 1 }}
                                        </td>
                                        <td style="padding: 12px 10px; border-bottom: 1px solid #e5e7eb;">
                                            <div style="font-size: 14px; color: #111827; font-weight: 600; margin-bottom: 3px;">
                                                {{ $inscripcion->materia->nombre }}
                                            </div>
                                        </td>
                                        <td style="padding: 12px 10px; text-align: center; border-bottom: 1px solid #e5e7eb;">
                                            <div style="font-size: 13px; color: #6b7280;">
                                                {{ $inscripcion->materia->anno }}° Año - {{ $inscripcion->materia->semestre === 1 ? '1er' : '2do' }} Cuatr.
                                            </div>
                                        </td>
                                        <td style="padding: 12px 10px; text-align: center; border-bottom: 1px solid #e5e7eb;">
                                            <span style="background-color: #d1fae5; color: #065f46; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; display: inline-block;">
                                                Confirmada
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <!-- Información importante -->
                    <tr>
                        <td style="padding: 0 30px 20px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 6px; padding: 15px;">
                                <tr>
                                    <td>
                                        <div style="font-size: 13px; color: #92400e; font-weight: 700; margin-bottom: 8px;">
                                            📌 IMPORTANTE
                                        </div>
                                        <div style="font-size: 13px; color: #78350f; line-height: 1.6;">
                                            Este comprobante certifica tu inscripción a las materias detalladas.
                                            Conservalo como documento oficial y presentalo cuando sea requerido.
                                            La inscripción está sujeta a la aprobación de las correlatividades correspondientes.
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Próximos pasos -->
                    <tr>
                        <td style="padding: 0 30px 30px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; padding: 15px;">
                                <tr>
                                    <td>
                                        <div style="font-size: 13px; color: #1e40af; font-weight: 700; margin-bottom: 10px; text-transform: uppercase;">
                                            📋 Próximos Pasos
                                        </div>
                                        <ul style="margin: 0; padding-left: 20px; color: #1e40af; font-size: 13px; line-height: 1.8;">
                                            <li>Verificá los horarios de cursada en el Sistema de Gestión Académica</li>
                                            <li>Consultá las fechas de inicio de clases para cada materia</li>
                                            <li>Presentá este comprobante en Secretaría Académica si es requerido</li>
                                            <li>Ante cualquier consulta, comunicate con Secretaría en horario de atención</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 25px 30px; border-top: 1px solid #e5e7eb;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding-bottom: 15px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td width="50%" style="font-size: 12px; color: #6b7280; line-height: 1.6;">
                                                    <strong style="color: #111827; font-size: 13px;">Secretaría Académica</strong><br>
                                                    {{ $configuracion->nombre_institucion ?? 'IES G.M. Belgrano' }}<br>
                                                    @if($configuracion->direccion ?? false)
                                                        {{ $configuracion->direccion }}
                                                    @endif
                                                </td>
                                                <td width="50%" align="right" style="font-size: 12px; color: #6b7280; line-height: 1.6;">
                                                    <strong style="color: #111827; font-size: 13px;">Contacto</strong><br>
                                                    @if($configuracion->email ?? false)
                                                        {{ $configuracion->email }}<br>
                                                    @endif
                                                    @if($configuracion->telefono ?? false)
                                                        Tel: {{ $configuracion->telefono }}
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 15px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 11px; color: #9ca3af; line-height: 1.6;">
                                        Documento generado automáticamente el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}hs<br>
                                        Este comprobante tiene validez oficial sin necesidad de firma autógrafa<br>
                                        © {{ date('Y') }} {{ $configuracion->nombre_institucion ?? 'IES G.M. Belgrano' }} - Todos los derechos reservados
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
