# 🎓 Sistema Académico IES

Sistema de validación de correlativas para inscripciones académicas.

**Repositorio**: https://github.com/Areyuna09/Sistema-Academico-IES.git

## 🚀 Instalación Rápida

### 1. Dependencias

```bash
php --version    # Necesitas PHP 8.2+
composer --version
node --version   # Necesitas Node.js 18+
npm --version
```

### 2. Instalar

```bash
git clone https://github.com/Areyuna09/Sistema-Academico-IES.git
cd SistemaAcademicoIES
composer install
npm install
cp .env.example .env
php artisan key:generate
```

### 3. Configurar Base de Datos MySQL por Defecto

Editar `config/database.php` línea 19:

```php
'default' => env('DB_CONNECTION', 'mysql'),
```

### 4. Base de Datos

-   Abrir XAMPP → Iniciar MySQL
-   phpMyAdmin → Crear BD `expediente_ies`
-   Importar: `expediente_ies_compatible.sql`

### 5. Configurar .env

```env
DB_CONNECTION=mysql
DB_DATABASE=expediente_ies
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Ejecutar Migraciones

```bash
php artisan migrate
```

Esto creará las tablas necesarias de Laravel (users, sessions, cache, jobs).

### 7. Crear Usuario de Prueba

```bash
php artisan tinker --execute="App\Models\User::create(['name' => 'Admin Test', 'email' => 'test@ies.edu', 'password' => bcrypt('123456')]); echo 'Usuario creado';"
```

**Credenciales:** `test@ies.edu` / `123456`

### 8. Iniciar

```bash
./iniciar.bat
```

**URLs:**

-   Laravel: http://127.0.0.1:8000
-   Vue: http://localhost:5173

## 👥 Equipo

-   **Ramón Areyuna**
-   **Alan Rodriguez**
-   **Agustin Godoy**
-   **Amilcar Fernandez**
-   **Rodrigo Castillo**
-   **Luis Gomez**
-   **Fabricio Antunez**

## 🔧 Stack

Laravel 12 + Vue.js 3 + MySQL + Tailwind CSS
