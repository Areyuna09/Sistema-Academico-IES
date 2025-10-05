# API REST - Sistema Académico IES

## Autenticación con Sanctum (Token-based)

El sistema implementa autenticación basada en tokens usando Laravel Sanctum. Los usuarios inician sesión con su **DNI** y **contraseña**, recibiendo un token de acceso que deben incluir en las peticiones subsiguientes.

### Base URL
```
http://localhost:8000/api
```

---

## Endpoints de Autenticación

### 1. Login (Obtener Token)

**POST** `/api/login`

Autentica al usuario y retorna un token de acceso.

#### Request Body
```json
{
  "dni": "46180633",
  "password": "123456",
  "device_name": "web"  // Opcional, por defecto "web"
}
```

#### Response Success (200)
```json
{
  "success": true,
  "token": "1|M5EvM7LkojeY6tSoeISQPIDd1nZa32pPcmFWVm1L87ef4573",
  "user": {
    "id": 19,
    "dni": "46180633",
    "nombre": "Areyuna, Ramon",
    "email": "ramonareyuna09@gmail.com",
    "tipo": 4,
    "role": "alumno"
  }
}
```

#### Response Error (422)
```json
{
  "message": "Las credenciales proporcionadas son incorrectas.",
  "errors": {
    "dni": ["Las credenciales proporcionadas son incorrectas."]
  }
}
```

---

### 2. Obtener Usuario Autenticado

**GET** `/api/me`

Retorna la información del usuario autenticado.

#### Headers
```
Authorization: Bearer {token}
Accept: application/json
```

#### Response Success (200)
```json
{
  "success": true,
  "user": {
    "id": 19,
    "dni": "46180633",
    "nombre": "Areyuna, Ramon",
    "email": "ramonareyuna09@gmail.com",
    "tipo": 4,
    "role": "alumno",
    "alumno_id": 16,
    "profesor_id": null
  }
}
```

---

### 3. Logout (Revocar Token)

**POST** `/api/logout`

Revoca el token de acceso actual.

#### Headers
```
Authorization: Bearer {token}
Accept: application/json
```

#### Response Success (200)
```json
{
  "success": true,
  "message": "Token revocado exitosamente"
}
```

---

### 4. Cambiar Contraseña

**POST** `/api/change-password`

Permite al usuario cambiar su contraseña.

#### Headers
```
Authorization: Bearer {token}
Content-Type: application/json
```

#### Request Body
```json
{
  "current_password": "123456",
  "new_password": "nuevaPassword123",
  "new_password_confirmation": "nuevaPassword123"
}
```

#### Response Success (200)
```json
{
  "success": true,
  "message": "Contraseña actualizada exitosamente"
}
```

#### Response Error (422)
```json
{
  "message": "La contraseña actual es incorrecta.",
  "errors": {
    "current_password": ["La contraseña actual es incorrecta."]
  }
}
```

---

## Tipos de Usuario

- **1** = Admin
- **2** = Usuario (legacy)
- **3** = Profesor
- **4** = Alumno

## Roles Disponibles

- `admin`
- `profesor`
- `alumno`

---

## Ejemplo de Uso con cURL

### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "dni": "46180633",
    "password": "123456",
    "device_name": "mi-dispositivo"
  }'
```

### Obtener Usuario Autenticado
```bash
curl -X GET http://localhost:8000/api/me \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|M5EvM7LkojeY6tSoeISQPIDd1nZa32pPcmFWVm1L87ef4573"
```

### Logout
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|M5EvM7LkojeY6tSoeISQPIDd1nZa32pPcmFWVm1L87ef4573"
```

### Cambiar Contraseña
```bash
curl -X POST http://localhost:8000/api/change-password \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|M5EvM7LkojeY6tSoeISQPIDd1nZa32pPcmFWVm1L87ef4573" \
  -d '{
    "current_password": "123456",
    "new_password": "nuevaPass123",
    "new_password_confirmation": "nuevaPass123"
  }'
```

---

## Ejemplo de Uso con JavaScript (Fetch)

### Login
```javascript
const login = async (dni, password) => {
  const response = await fetch('http://localhost:8000/api/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    body: JSON.stringify({
      dni,
      password,
      device_name: 'web'
    })
  });

  const data = await response.json();

  if (data.success) {
    // Guardar token en localStorage
    localStorage.setItem('auth_token', data.token);
    localStorage.setItem('user', JSON.stringify(data.user));
    return data;
  } else {
    throw new Error(data.message);
  }
};
```

### Petición Autenticada
```javascript
const getUserInfo = async () => {
  const token = localStorage.getItem('auth_token');

  const response = await fetch('http://localhost:8000/api/me', {
    headers: {
      'Accept': 'application/json',
      'Authorization': `Bearer ${token}`
    }
  });

  return await response.json();
};
```

### Logout
```javascript
const logout = async () => {
  const token = localStorage.getItem('auth_token');

  await fetch('http://localhost:8000/api/logout', {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Authorization': `Bearer ${token}`
    }
  });

  // Limpiar localStorage
  localStorage.removeItem('auth_token');
  localStorage.removeItem('user');
};
```

---

## Seguridad

- ✅ Tokens personales de acceso (Sanctum)
- ✅ Hashing seguro de contraseñas (bcrypt)
- ✅ Validación de credenciales
- ✅ Revocación de tokens al logout
- ✅ Autenticación basada en DNI
- ✅ Contraseña mínima de 6 caracteres

---

## Notas Importantes

1. **Contraseña por defecto**: Los usuarios tienen su DNI como contraseña por defecto
2. **Token único por dispositivo**: Al hacer login, se revoca el token anterior del mismo dispositivo
3. **Autenticación Web + API**: El sistema soporta tanto sesiones web tradicionales como tokens API
4. **CORS**: Asegurarse de configurar CORS correctamente en producción

---

## Configuración en Producción

Para producción, configurar las siguientes variables en `.env`:

```env
SANCTUM_STATEFUL_DOMAINS=tudominio.com,www.tudominio.com
SESSION_DOMAIN=.tudominio.com
```

Y configurar CORS en `config/cors.php` para los dominios permitidos.
