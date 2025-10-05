# Configuración de Email para Comprobantes de Inscripción

El sistema está configurado para enviar comprobantes de inscripción por email. Para que funcione correctamente, necesitas configurar el archivo `.env` con los datos de tu servidor SMTP.

## Opciones de Configuración

### 1. Gmail (Recomendado para desarrollo/testing)

**Pasos para configurar Gmail:**

1. Ve a tu cuenta de Google: https://myaccount.google.com/
2. Activa la verificación en 2 pasos si no la tienes activa
3. Genera una "Contraseña de aplicación":
   - Ve a https://myaccount.google.com/apppasswords
   - Selecciona "Correo" y "Otro (nombre personalizado)"
   - Escribe "Sistema Académico IES" y genera
   - Copia la contraseña de 16 caracteres que te da

4. Actualiza el archivo `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=xxxx xxxx xxxx xxxx  # La contraseña de aplicación de 16 dígitos
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@ies.edu.ar"
MAIL_FROM_NAME="Sistema Académico IES"
```

### 2. Outlook / Hotmail

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@outlook.com
MAIL_PASSWORD=tu_contraseña
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@ies.edu.ar"
MAIL_FROM_NAME="Sistema Académico IES"
```

### 3. Yahoo Mail

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@yahoo.com
MAIL_PASSWORD=contraseña_de_aplicacion
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@ies.edu.ar"
MAIL_FROM_NAME="Sistema Académico IES"
```

### 4. Mailtrap (Para testing - no envía emails reales)

Ideal para desarrollo sin enviar emails a usuarios reales:

1. Regístrate en https://mailtrap.io (gratis)
2. Crea un inbox
3. Copia las credenciales SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username_mailtrap
MAIL_PASSWORD=tu_password_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@ies.edu.ar"
MAIL_FROM_NAME="Sistema Académico IES"
```

### 5. Modo Log (Solo para desarrollo - no envía emails)

Si solo quieres probar sin enviar emails reales:

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@ies.edu.ar"
MAIL_FROM_NAME="Sistema Académico IES"
```

Los emails se guardarán en: `storage/logs/laravel.log`

## Probar la configuración

Después de configurar el email, puedes probar con este comando:

```bash
php artisan tinker
```

Y ejecuta:

```php
Mail::raw('Test email desde Sistema Académico IES', function ($message) {
    $message->to('tu_email@example.com')
            ->subject('Prueba de Email');
});
```

## Troubleshooting

### Error: "Failed to authenticate on SMTP server"
- Verifica que el username y password sean correctos
- Si usas Gmail, asegúrate de usar una "Contraseña de aplicación" y no tu contraseña normal
- Verifica que la verificación en 2 pasos esté activa (Gmail)

### Error: "Connection could not be established"
- Verifica que el MAIL_HOST y MAIL_PORT sean correctos
- Verifica tu firewall o antivirus
- Verifica tu conexión a internet

### Los emails no llegan
- Revisa la carpeta de spam/correo no deseado
- Verifica que MAIL_FROM_ADDRESS sea un email válido
- Si usas Mailtrap, los emails solo aparecen en el inbox de Mailtrap

## Funcionalidad Actual

Una vez configurado, el sistema enviará automáticamente un email con:

✓ Comprobante de inscripción en HTML profesional
✓ Detalles del estudiante (nombre, DNI)
✓ Lista de materias inscritas
✓ Información del período académico
✓ Próximos pasos y recomendaciones

El email se envía a la dirección asociada a la cuenta del usuario que realizó la inscripción.
