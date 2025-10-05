# Solución para Error de Envío de Email (SSL Certificate)

## ❌ Error Actual
```
Unable to connect with STARTTLS: failed loading cafile stream: `C:\php\extras\ssl\cacert.pem'
```

El sistema está intentando enviar emails pero falla por certificados SSL faltantes.

---

## ✅ Solución 1: Descargar Certificados SSL (RECOMENDADO)

### Paso 1: Descargar certificados
1. Ve a: https://curl.se/docs/caextract.html
2. Descarga el archivo `cacert.pem`
3. Guárdalo en: `C:\xampp\php\extras\ssl\cacert.pem`
   - Si la carpeta `extras\ssl` no existe, créala

### Paso 2: Configurar PHP
1. Abre el archivo: `C:\xampp\php\php.ini`
2. Busca la línea que dice:
   ```
   ;curl.cainfo =
   ```
3. Reemplázala por (sin el punto y coma):
   ```
   curl.cainfo = "C:\xampp\php\extras\ssl\cacert.pem"
   ```

4. Busca también:
   ```
   ;openssl.cafile=
   ```
5. Reemplázala por:
   ```
   openssl.cafile="C:\xampp\php\extras\ssl\cacert.pem"
   ```

### Paso 3: Reiniciar servicios
1. Detén los servidores actuales (Ctrl+C en ambas terminales)
2. Reinicia Apache desde XAMPP Control Panel
3. Vuelve a ejecutar:
   ```bash
   npm run dev
   php artisan serve
   ```

---

## ✅ Solución 2: Deshabilitar Verificación SSL (SOLO DESARROLLO)

**⚠️ ADVERTENCIA:** Esta solución es SOLO para desarrollo local. NO usar en producción.

### Opción A: Configurar en .env

Agrega esta línea al archivo `.env`:
```env
MAIL_VERIFY_PEER=false
```

### Opción B: Modificar configuración de mail

1. Abre: `config/mail.php`
2. Busca la sección 'smtp' en 'mailers'
3. Agrega estas opciones:

```php
'smtp' => [
    'transport' => 'smtp',
    'url' => env('MAIL_URL'),
    'host' => env('MAIL_HOST', '127.0.0.1'),
    'port' => env('MAIL_PORT', 2525),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username' => env('MAIL_USERNAME'),
    'password' => env('MAIL_PASSWORD'),
    'timeout' => null,
    'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url(env('APP_URL', 'localhost'), PHP_URL_HOST)),
    'verify_peer' => false,  // ← Agregar esta línea
],
```

---

## ✅ Solución 3: Usar Mailtrap (Para Testing - MÁS FÁCIL)

Si solo quieres probar sin configurar SSL ni Gmail:

1. Ve a https://mailtrap.io y crea una cuenta gratis
2. Crea un nuevo "Inbox"
3. Copia las credenciales SMTP
4. Actualiza tu `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username_de_mailtrap
MAIL_PASSWORD=tu_password_de_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@ies.edu.ar"
MAIL_FROM_NAME="Sistema Académico IES"
```

**Ventajas de Mailtrap:**
- ✅ No necesita configurar SSL
- ✅ No envía emails reales (perfecto para testing)
- ✅ Puedes ver los emails en una interfaz web bonita
- ✅ Gratis para desarrollo

---

## 🧪 Probar que funciona

Después de aplicar cualquier solución, prueba con:

```bash
php artisan tinker
```

Luego ejecuta:
```php
Mail::raw('Email de prueba desde Sistema Académico IES', function ($message) {
    $message->to('ramonareyuna09@gmail.com')
            ->subject('Prueba de Email');
});
```

Si no muestra errores, el email se envió correctamente.

---

## 📧 Cómo buscar el email en Gmail

Si usas la Solución 1 (SSL) o configuraste Gmail correctamente:

### En la bandeja principal:
1. Busca en el buscador de Gmail: `from:noreply@ies.edu.ar`
2. O busca: `Comprobante de Inscripción`

### En Spam:
1. Ve a la carpeta "Spam" (izquierda del menú)
2. Busca emails de "Sistema Académico IES" o "noreply@ies.edu.ar"
3. Si está ahí, márcalo como "No es spam"

### En Todos los mensajes:
1. Haz clic en "Más" en el menú izquierdo
2. Selecciona "Todos los mensajes"
3. Busca: `Comprobante de Inscripción`

### Filtros adicionales:
- `subject:Comprobante de Inscripción`
- `newer_than:1d` (emails del último día)
- `is:unread` (emails no leídos)

---

## 📊 Estado Actual

Según los logs, el sistema:
- ✅ Guardó correctamente en `inscripciones`
- ✅ Guardó correctamente en `tbl_alumnos_cursa`
- ✅ Intentó enviar email a: `ramonareyuna09@gmail.com`
- ❌ Falló por certificados SSL

Una vez que apliques cualquiera de las 3 soluciones, el email se enviará correctamente.

---

## 🎯 Recomendación

Para desarrollo rápido y fácil: **Usa Solución 3 (Mailtrap)**

Para producción real: **Usa Solución 1 (Certificados SSL)**
