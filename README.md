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

### 3. Base de Datos

-   Abrir XAMPP → Iniciar MySQL
-   phpMyAdmin → Crear BD `expediente_ies`
-   Importar: `expediente_ies_compatible.sql`

### 4. Configurar .env

```env
DB_CONNECTION=mysql
DB_DATABASE=expediente_ies
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Iniciar

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
