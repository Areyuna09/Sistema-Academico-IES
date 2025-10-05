# 🎓 Sistema Académico IES

Sistema de Gestión Académica para inscripciones, mesas de examen y expedientes académicos.

**Repositorio**: https://github.com/Areyuna09/Sistema-Academico-IES.git

## 🚀 Instalación Rápida

### 1. Requisitos Previos

- **PHP**: 8.2 o superior
- **Composer**: Última versión
- **Node.js**: 18 o superior
- **MySQL**: 5.7 o superior (o MariaDB)
- **XAMPP** (recomendado para desarrollo local)

```bash
php --version    # Verificar PHP 8.2+
composer --version
node --version   # Verificar Node.js 18+
npm --version
```

### 2. Clonar e Instalar Dependencias

```bash
git clone https://github.com/Areyuna09/Sistema-Academico-IES.git
cd Sistema-Academico-IES
composer install
npm install
cp .env.example .env
php artisan key:generate
```


### 3. Base de Datos

```bash
# 1. Abrir XAMPP → Iniciar MySQL
# 2. Acceder a phpMyAdmin (http://localhost/phpmyadmin)
# 3. Crear base de datos 'expediente_ies'
# 4. Importar el archivo SQL
```

Importar el archivo: `expediente_ies_compatible.sql`

### 4. Configurar Variables de Entorno (.env)

```env
APP_NAME="Sistema Académico IES"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=expediente_ies
DB_USERNAME=root
DB_PASSWORD=

# Para emails de prueba (Mailtrap recomendado)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username
MAIL_PASSWORD=tu_password
```

### 5. Ejecutar Migraciones Pendientes

```bash
php artisan migrate
```

### 6. Iniciar el Servidor

**Opción 1 - Script automático (Windows):**
```bash
./iniciar.bat
```

**Opción 2 - Manual:**
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

### 7. Acceder al Sistema

- **Aplicación Web**: http://127.0.0.1:8000
- **Documentación API**: http://127.0.0.1:8000/docs/api

---

## 📚 Documentación

- **API REST**: Ver [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md) para endpoints y ejemplos
- **API Docs Interactiva**: http://127.0.0.1:8000/docs/api (Scramble - tipo Swagger)

## 🔐 Credenciales

- **Usuario**: DNI del alumno
- **Contraseña por defecto**: DNI (modificable desde el perfil)

## 🔧 Stack

- **Backend**: Laravel 12 + MySQL + Sanctum
- **Frontend**: Vue.js 3 + Inertia.js + Tailwind CSS
- **Herramientas**: Scramble, DomPDF, Vite

## 👥 Equipo

- **Ramón Areyuna**
- **Alan Rodriguez**
- **Agustin Godoy**
- **Amilcar Fernandez**
- **Rodrigo Castillo**
- **Luis Gomez**
- **Fabricio Antunez**
