# 📧 Guía de Configuración SMTP con Gmail

Esta guía te ayudará a configurar el envío de correos electrónicos usando Gmail en el Sistema Académico IES.

## 📋 Requisitos Previos

1. Una cuenta de Gmail activa
2. Verificación en 2 pasos activada en tu cuenta de Google
3. Generar una "Contraseña de aplicación" de Google

---

## 🔐 Paso 1: Activar la Verificación en 2 Pasos

Si aún no tienes activada la verificación en 2 pasos:

1. Ve a tu cuenta de Google: https://myaccount.google.com/
2. En el menú lateral, selecciona **"Seguridad"**
3. En "Cómo accedes a Google", selecciona **"Verificación en 2 pasos"**
4. Sigue las instrucciones para activarla (necesitarás tu teléfono)

---

## 🔑 Paso 2: Generar una Contraseña de Aplicación

⚠️ **IMPORTANTE**: NO uses tu contraseña normal de Gmail. Debes generar una contraseña especial para aplicaciones.

### Pasos para generar la contraseña:

1. Ve a tu cuenta de Google: https://myaccount.google.com/
2. En el menú lateral, selecciona **"Seguridad"**
3. Busca la sección **"Cómo accedes a Google"**
4. Haz clic en **"Contraseñas de aplicaciones"**
   - Si no ves esta opción, asegúrate de tener activada la verificación en 2 pasos
5. Selecciona:
   - **Aplicación**: Correo
   - **Dispositivo**: Windows Computer (o el que prefieras)
6. Haz clic en **"Generar"**
7. Google te mostrará una contraseña de 16 caracteres (sin espacios)
   - Ejemplo: `abcd efgh ijkl mnop` → Cópiala como: `abcdefghijklmnop`

⚠️ **Guarda esta contraseña en un lugar seguro**, no podrás volver a verla.

---

## ⚙️ Paso 3: Configurar el Archivo .env

Abre el archivo `.env` en la raíz del proyecto y actualiza estas líneas:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-correo@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicacion-aqui
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="tu-correo@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Ejemplo real:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=sistemaiestdf@gmail.com
MAIL_PASSWORD=abcdefghijklmnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="sistemaiestdf@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🧪 Paso 4: Probar la Configuración

He creado un comando de Artisan para probar el envío de correos:

```bash
php artisan email:test tu-correo@ejemplo.com
```

### Ejemplo:

```bash
php artisan email:test ramon@gmail.com
```

### Resultado esperado:

```
Enviando correo de prueba a: ramon@gmail.com
Usando host: smtp.gmail.com
Puerto: 587

✅ Correo enviado exitosamente!
Revisa la bandeja de entrada de: ramon@gmail.com

Nota: Si no lo ves, revisa la carpeta de spam.
```

---

## 🔧 Solución de Problemas

### Error: "Invalid credentials"

- ✅ Verifica que estés usando la **contraseña de aplicación**, no tu contraseña normal
- ✅ Asegúrate de copiar toda la contraseña sin espacios
- ✅ Confirma que el correo en `MAIL_USERNAME` sea correcto

### Error: "Could not authenticate"

- ✅ Activa la verificación en 2 pasos en tu cuenta de Google
- ✅ Genera una nueva contraseña de aplicación
- ✅ Reinicia el servidor de Laravel después de cambiar el .env

### El correo llega a spam

- Es normal en desarrollo. En producción, considera:
  - Configurar registros SPF, DKIM y DMARC en tu dominio
  - Usar un servicio profesional como SendGrid o Amazon SES
  - Verificar tu dominio en Google Workspace

### Error: "Connection timeout"

- ✅ Verifica que el puerto 587 no esté bloqueado por tu firewall
- ✅ Intenta cambiar el puerto a 465 y `MAIL_ENCRYPTION=ssl`

---

## 📊 Límites de Gmail

Gmail tiene límites de envío:

- **Cuentas personales**: 500 correos/día
- **Google Workspace**: 2000 correos/día

Para un sistema en producción con alto volumen, considera:
- **SendGrid** (100 correos/día gratis)
- **Amazon SES** (muy económico)
- **Mailgun** (5000 correos/mes gratis)

---

## 🚀 Uso en el Sistema

Una vez configurado, el sistema enviará correos automáticamente para:

1. ✅ Confirmación de inscripciones a materias
2. ✅ Confirmación de inscripciones a mesas de examen
3. ✅ Notificaciones de notas aprobadas
4. ✅ Notificaciones de excepciones aprobadas/rechazadas
5. ✅ Recordatorios del sistema

Todos estos correos usan el sistema de **colas de Laravel** para no bloquear las respuestas HTTP.

---

## 🔄 Iniciar el Worker de Colas

**IMPORTANTE**: El sistema usa colas para enviar correos en segundo plano. Debes tener el worker corriendo para que los correos se envíen.

### Opción 1: Usando el archivo .bat (Recomendado)

Simplemente haz doble click en el archivo:
```
iniciar-worker.bat
```

### Opción 2: Desde la terminal

```bash
php artisan queue:work --tries=3 --timeout=90
```

**Nota**: El worker debe estar corriendo mientras el sistema esté en uso. En producción, usa un administrador de procesos como `supervisor` o `pm2`.

---

## ✅ Sistema Completamente Funcional

Una vez que el worker esté corriendo, el sistema enviará correos automáticamente cuando:

1. ✅ Un alumno se inscriba a materias
2. ✅ Un alumno se inscriba a una mesa de examen
3. ✅ Una excepción sea aprobada/rechazada
4. ✅ Una nota sea aprobada

Todos los correos se procesan en segundo plano sin bloquear la respuesta al usuario.

---

## 🔧 Comandos Útiles

### Ver jobs pendientes en la cola
```bash
php artisan queue:work --once
```

### Ver jobs fallidos
```bash
php artisan queue:failed
```

### Limpiar jobs fallidos
```bash
php artisan queue:flush
```

### Reintentar jobs fallidos
```bash
php artisan queue:retry all
```

---

## 📧 Contacto

Si tienes problemas con la configuración, revisa:
- Logs de Laravel: `storage/logs/laravel.log`
- Documentación oficial de Laravel Mail: https://laravel.com/docs/11.x/mail
- Documentación de Laravel Queues: https://laravel.com/docs/11.x/queues

---

**Última actualización**: 16 de octubre de 2025
**Estado**: ✅ Completamente funcional y probado
