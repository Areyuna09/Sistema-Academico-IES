# 🎓 Sistema Académico IES

Sistema de gestión académica completo para el Instituto de Educación Superior General Manuel Belgrano.

Plataforma integral que permite la administración de inscripciones, mesas de examen, expedientes académicos, gestión de correlativas y seguimiento del rendimiento estudiantil.

## 🚀 Instalación

### Requisitos
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/MariaDB

### Pasos

```bash
# 1. Clonar repositorio
git clone https://github.com/Areyuna09/Sistema-Academico-IES.git
cd Sistema-Academico-IES

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Configurar base de datos en .env
# 5. Importar expediente_ies.sql
# 6. Ejecutar migraciones
php artisan migrate

# 7. Iniciar servidores
php artisan serve          # Terminal 1
npm run dev               # Terminal 2
```

**Acceso**: http://127.0.0.1:8000

## 🔧 Stack Tecnológico

- **Backend**: Laravel 12 + MySQL
- **Frontend**: Vue.js 3 + Inertia.js + Tailwind CSS
- **Herramientas**: Sanctum, DomPDF, Vite

## 👥 Equipo de Desarrollo

- Ramón Areyuna
- Alan Rodriguez
- Agustin Godoy
- Amilcar Fernandez
- Luis Gomez
- Fabricio Antunez
