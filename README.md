# Sistema Academico IES

Sistema de gestion academica integral para el Instituto de Educacion Superior General Manuel Belgrano.

Plataforma fullstack que permite la administracion de inscripciones, mesas de examen, expedientes academicos, gestion de correlativas, seguimiento del rendimiento estudiantil y control de asistencias.

## Descripcion del Sistema

El sistema esta diseñado para centralizar y digitalizar la gestion academica de un instituto de educacion superior, abarcando:

- **Gestion de inscripciones** a cursado y mesas de examen
- **Expedientes academicos** con legajos digitales
- **Carga y aprobacion de notas** con flujo de revision
- **Control de asistencias** por materia
- **Mesas de examen** con inscripciones y actas
- **Planes de estudio** y correlativas
- **Sistema de roles y permisos** configurable desde la interfaz
- **Generacion de PDFs** (constancias, actas, reportes)
- **Panel administrativo** con estadisticas y configuracion de modulos
- **Importacion masiva** desde archivos Excel

## Tecnologias

### Backend

- **PHP** 8.2+
- **Laravel** 12.x
- **MySQL** / MariaDB
- **Inertia.js** 2.x (server-side)

### Frontend

- **Vue.js** 3.4+ (Composition API con `<script setup>`)
- **Inertia.js** 2.x (client-side)
- **Tailwind CSS** 3.x
- **Vite** 7.x

### Dependencias Backend

| Paquete | Version | Descripcion |
|---|---|---|
| `laravel/framework` | ^12.0 | Framework principal |
| `inertiajs/inertia-laravel` | ^2.0 | Adaptador Inertia para Laravel |
| `laravel/sanctum` | ^4.0 | Autenticacion SPA y API tokens |
| `barryvdh/laravel-dompdf` | ^3.1 | Generacion de PDFs |
| `maatwebsite/excel` | ^3.1 | Importacion/exportacion Excel |
| `tightenco/ziggy` | ^2.6 | Rutas de Laravel en JavaScript |
| `dedoc/scramble` | * | Documentacion API automatica |
| `guzzlehttp/guzzle` | * | Cliente HTTP |

### Dependencias Frontend

| Paquete | Version | Descripcion |
|---|---|---|
| `vue` | ^3.4.0 | Framework reactivo |
| `@inertiajs/vue3` | ^2.0.0 | Adaptador Inertia para Vue 3 |
| `tailwindcss` | ^3.2.1 | Framework CSS utility-first |
| `@tailwindcss/forms` | ^0.5.3 | Plugin de estilos para formularios |
| `@headlessui/vue` | ^1.7.23 | Componentes UI accesibles |
| `axios` | ^1.11.0 | Cliente HTTP |
| `boxicons` | ^2.1.4 | Libreria de iconos |
| `driver.js` | ^1.3.6 | Tours guiados interactivos |
| `laravel-vite-plugin` | ^2.0.0 | Integracion Vite con Laravel |
| `@vitejs/plugin-vue` | ^6.0.1 | Plugin Vue para Vite |

## Instalacion

### Requisitos Previos

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL / MariaDB
- XAMPP, Laragon o similar

### Pasos

1. **Clonar el repositorio**

```bash
git clone https://github.com/Areyuna09/Sistema-Academico-IES.git
cd Sistema-Academico-IES
```

2. **Instalar dependencias**

```bash
composer install
npm install
```

3. **Configurar entorno**

```bash
cp .env.example .env
php artisan key:generate
```

Editar `.env` con las credenciales de la base de datos.

4. **Base de datos y migraciones**

```bash
# Importar esquema base: expediente_ies.sql
php artisan migrate
php artisan db:seed
php artisan storage:link
```

5. **Iniciar servidores**

```bash
# Terminal 1 - Backend
php artisan serve

# Terminal 2 - Frontend
npm run dev
```

**Acceso**: http://127.0.0.1:8000
