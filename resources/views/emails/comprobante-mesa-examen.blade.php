<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Inscripción a Mesa de Examen</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f3f4f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <!-- Container principal -->
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">

                    <!-- Header con gradiente -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%); padding: 40px 30px; text-align: center;">
                            <div style="background-color: rgba(255,255,255,0.2); display: inline-block; padding: 15px 30px; border-radius: 50px; margin-bottom: 15px;">
                                <span style="font-size: 32px; color: #ffffff;">📝</span>
                            </div>
                            <h1 style="margin: 0 0 10px; font-size: 28px; color: #ffffff; font-weight: 700; letter-spacing: -0.5px;">
                                {{ $configuracion->nombre_institucion ?? 'IES G.M. Belgrano' }}
                            </h1>
                            <p style="margin: 0; font-size: 14px; color: rgba(255,255,255,0.9); text-transform: uppercase; letter-spacing: 1px; font-weight: 500;">
                                Comprobante de Inscripción a Mesa de Examen
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
                                            Tu inscripción a la mesa de examen ha sido procesada exitosamente
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
                                        <strong style="color: #111827;">N° Inscripción:</strong> {{ $datos['inscripcion_id'] }}
                                    </td>
                                    <td align="right" style="font-size: 12px; color: #6b7280;">
                                        <strong style="color: #111827;">Fecha:</strong> {{ $datos['fecha_inscripcion']->format('d/m/Y H:i') }}hs
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Información de la Mesa -->
                    <tr>
                        <td style="padding: 0 30px 25px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); border-radius: 8px; padding: 20px; border: 2px solid #a78bfa;">
                                <tr>
                                    <td>
                                        <div style="text-align: center; margin-bottom: 15px;">
                                            <div style="font-size: 14px; color: #5b21b6; font-weight: 700; text-transform: uppercase; margin-bottom: 5px;">
                                                Mesa de Examen
                                            </div>
                                            <div style="font-size: 20px; color: #4c1d95; font-weight: 700;">
                                                {{ $datos['materia']['nombre'] }}
                                            </div>
                                        </div>
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td width="50%" style="padding: 8px 0;">
                                                    <div style="font-size: 12px; color: #6b21a8; font-weight: 600;">Llamado:</div>
                                                    <div style="font-size: 16px; color: #4c1d95; font-weight: 700;">{{ $datos['mesa']['llamado'] }}° Llamado</div>
                                                </td>
                                                <td width="50%" align="right" style="padding: 8px 0;">
                                                    <div style="font-size: 12px; color: #6b21a8; font-weight: 600;">Fecha del Examen:</div>
                                                    <div style="font-size: 16px; color: #4c1d95; font-weight: 700;">{{ $datos['mesa']['fecha_examen']->format('d/m/Y') }}</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <div style="font-size: 12px; color: #6b21a8; font-weight: 600;">Hora:</div>
                                                    <div style="font-size: 16px; color: #4c1d95; font-weight: 700;">{{ $datos['mesa']['hora_examen'] }} hs</div>
                                                </td>
                                                @if($datos['mesa']['aula'])
                                                <td align="right" style="padding: 8px 0;">
                                                    <div style="font-size: 12px; color: #6b21a8; font-weight: 600;">Aula:</div>
                                                    <div style="font-size: 16px; color: #4c1d95; font-weight: 700;">{{ $datos['mesa']['aula'] }}</div>
                                                </td>
                                                @endif
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Datos del Estudiante -->
                    <tr>
                        <td style="padding: 0 30px 25px;">
                            <h2 style="margin: 0 0 15px; font-size: 16px; color: #111827; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 3px solid #7c3aed; padding-bottom: 8px;">
                                👤 Datos del Estudiante
                            </h2>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                                        <span style="font-size: 13px; color: #6b7280; font-weight: 600;">Apellido y Nombre:</span><br>
                                        <span style="font-size: 15px; color: #111827; font-weight: 700;">{{ strtoupper($datos['alumno']['nombre_completo']) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                                        <span style="font-size: 13px; color: #6b7280; font-weight: 600;">DNI:</span><br>
                                        <span style="font-size: 15px; color: #111827;">{{ number_format($datos['alumno']['dni'], 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                                @if($datos['alumno']['legajo'])
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                                        <span style="font-size: 13px; color: #6b7280; font-weight: 600;">Legajo:</span><br>
                                        <span style="font-size: 15px; color: #111827;">{{ $datos['alumno']['legajo'] }}</span>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding: 10px 0;">
                                        <span style="font-size: 13px; color: #6b7280; font-weight: 600;">Carrera:</span><br>
                                        <span style="font-size: 15px; color: #111827; font-weight: 600;">{{ $datos['carrera']['nombre'] }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Tribunal Examinador -->
                    @if($datos['tribunal']['presidente'] || $datos['tribunal']['vocal1'] || $datos['tribunal']['vocal2'])
                    <tr>
                        <td style="padding: 0 30px 25px;">
                            <h2 style="margin: 0 0 15px; font-size: 16px; color: #111827; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 3px solid #7c3aed; padding-bottom: 8px;">
                                👥 Tribunal Examinador
                            </h2>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                @if($datos['tribunal']['presidente'])
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                                        <span style="font-size: 13px; color: #6b7280; font-weight: 600;">Presidente:</span><br>
                                        <span style="font-size: 14px; color: #111827;">{{ $datos['tribunal']['presidente'] }}</span>
                                    </td>
                                </tr>
                                @endif
                                @if($datos['tribunal']['vocal1'])
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                                        <span style="font-size: 13px; color: #6b7280; font-weight: 600;">Vocal 1:</span><br>
                                        <span style="font-size: 14px; color: #111827;">{{ $datos['tribunal']['vocal1'] }}</span>
                                    </td>
                                </tr>
                                @endif
                                @if($datos['tribunal']['vocal2'])
                                <tr>
                                    <td style="padding: 10px 0;">
                                        <span style="font-size: 13px; color: #6b7280; font-weight: 600;">Vocal 2:</span><br>
                                        <span style="font-size: 14px; color: #111827;">{{ $datos['tribunal']['vocal2'] }}</span>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                    @endif

                    <!-- Información importante -->
                    <tr>
                        <td style="padding: 0 30px 20px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 6px; padding: 15px;">
                                <tr>
                                    <td>
                                        <div style="font-size: 13px; color: #92400e; font-weight: 700; margin-bottom: 8px;">
                                            ⚠️ IMPORTANTE
                                        </div>
                                        <div style="font-size: 13px; color: #78350f; line-height: 1.6;">
                                            <strong>Debes presentarte el día del examen con:</strong>
                                            <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                                                <li>DNI original</li>
                                                <li>Este comprobante de inscripción (impreso o digital)</li>
                                                <li>Libreta universitaria (si corresponde)</li>
                                            </ul>
                                            Presentate <strong>15 minutos antes</strong> del horario establecido.
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
                                            📋 Recordatorios
                                        </div>
                                        <ul style="margin: 0; padding-left: 20px; color: #1e40af; font-size: 13px; line-height: 1.8;">
                                            <li>Verificá la fecha y hora del examen</li>
                                            <li>Si necesitás cancelar la inscripción, hacelo con al menos 48hs de anticipación</li>
                                            <li>Ante cualquier inconveniente, comunicarte con Secretaría Académica</li>
                                            <li>Conservá este comprobante hasta finalizar el examen</li>
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
