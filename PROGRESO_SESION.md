# Progreso de Desarrollo - Sistema Académico IES

**Fecha:** 1 de Octubre, 2025
**Rama:** ramon
**Estado:** En desarrollo (NO commitear hasta que todo funcione)

---

## 🎯 Objetivos Completados

### 1. Mejoras en UI/UX - Sidebar y Navbar

#### Sidebar Responsive con Overlay
- ✅ Sidebar colapsado por defecto (16px de ancho)
- ✅ Botón hamburguesa en navbar (visible en todos los modos)
- ✅ En móviles: sidebar aparece como overlay con fondo borroso
- ✅ Overlay oscuro con `backdrop-blur-sm` en móviles
- ✅ Click en overlay cierra el sidebar
- ✅ En desktop: sidebar se expande/colapsa normalmente
- ✅ Transiciones suaves y profesionales
- ✅ Título dinámico según módulo actual

**Archivos modificados:**
- `resources/js/Layouts/SidebarLayout.vue`

#### Paleta de Colores Final
- **Sidebar/Navbar:** Gray-800 (dark)
- **Fondo:** Gray-50
- **Cards:** Blanco con bordes Gray-200
- **Acentos:** Blue-600, Green-600, Red-600

---

### 2. Sistema de Períodos de Inscripción

#### Base de Datos
Se creó la tabla `tbl_periodos_inscripcion` con:
- Nombre del período (ej: "2° Cuatrimestre 2025")
- Cuatrimestre (1 o 2)
- Año
- Fechas de inicio/fin de inscripción
- Fechas de inicio/fin de cursada
- Estado activo (solo uno puede estar activo)

#### Datos Reales Cargados
```
1° Cuatrimestre 2025:
  - Inscripción: 01/04 al 03/04
  - Cursada: 07/04 al 31/07

2° Cuatrimestre 2025 (ACTIVO):
  - Inscripción: 20/08 al 23/08
  - Cursada: 26/08 al 15/12

1° Cuatrimestre 2026:
  - Inscripción: 01/04 al 03/04
  - Cursada: 06/04 al 31/07
```

#### Funcionalidades
- ✅ Modelo `PeriodoInscripcion` con métodos:
  - `activo()` - obtener período activo
  - `inscripcionesAbiertas()` - verificar si inscripciones están abiertas
  - `diasRestantes()` - calcular días restantes
- ✅ Validación automática de períodos
- ✅ Vista muestra alerta según estado (abierto/cerrado)

**Archivos creados:**
- `database/migrations/2025_10_01_020019_create_tbl_periodos_inscripcion_table.php`
- `app/Models/PeriodoInscripcion.php`
- `database/seeders/PeriodosInscripcionSeeder.php`

---

### 3. Sistema de Inscripciones Mejorado

#### Datos Reales del Alumno Autenticado
- ✅ Controlador obtiene alumno del usuario autenticado
- ✅ Se usa `tbl_alumnos_materias` como historial académico (legajo)
- ✅ Motor de correlativas valida con datos reales
- ✅ Eliminados todos los datos simulados

#### Profesores Reales
- ✅ Agregada relación `profesores()` en modelo Materia
- ✅ Se obtienen de `tbl_profesor_tiene_materias`
- ✅ Cards muestran profesores reales

#### Cards de Materias Rediseñadas
Las cards ahora muestran:
1. **Nombre de la materia** + Badge de estado
2. **Año y cuatrimestre**
3. **Profesores que dictan la materia**
4. **Correlativas necesarias con estado:**
   - Badge azul: "Regular" (necesita estar regularizada)
   - Badge morado: "Aprobada" (necesita estar aprobada)
   - ✅ Icono verde: Correlativa cumplida
   - ❌ Icono rojo: Correlativa pendiente
5. **Checkbox de selección** (solo si puede inscribirse)

**Características:**
- Diseño compacto y móvil-friendly
- Click en toda la card para seleccionar
- Validación por ID (más confiable que por nombre)
- Información clara del estado de correlativas

#### Vista de Inscripciones
- ✅ Eliminada barra de progreso innecesaria
- ✅ Header compacto con info del estudiante + contador
- ✅ Alerta de período con fechas y días restantes
- ✅ Grid responsive optimizado (gap-4)
- ✅ Botón flotante de confirmación (esquina inferior derecha)

**Archivos modificados:**
- `app/Http/Controllers/InscripcionesController.php`
- `app/Models/Materia.php`
- `resources/js/Components/MateriaCard.vue`
- `resources/js/Pages/Inscripciones/Index.vue`

---

## 📊 Base de Datos

### Tablas Principales Utilizadas
1. **`users`** - Usuarios del sistema
   - dni, role (admin/profesor/alumno)
   - alumno_id, profesor_id (relaciones)

2. **`tbl_alumnos`** - Información de alumnos
   - dni, nombre, apellido, carrera

3. **`tbl_alumnos_materias`** - Historial académico (LEGAJO)
   - alumno, materia, carrera
   - cursada ('0' o '1') - si está regularizada
   - rendida ('0' o '1') - si está aprobada
   - nota, fecha

4. **`tbl_materias`** - Materias del instituto
   - nombre, anno, semestre, carrera
   - paracursar_regular - IDs de materias que deben estar regulares
   - paracursar_rendido - IDs de materias que deben estar aprobadas
   - pararendir - IDs de materias para rendir

5. **`tbl_profesores`** - Profesores del instituto

6. **`tbl_profesor_tiene_materias`** - Relación profesor-materia

7. **`tbl_periodos_inscripcion`** - Períodos de inscripción (NUEVA)

### Motor de Correlativas
El sistema usa el servicio `MotorCorrelativasService` que:
1. Consulta el historial académico del alumno (`tbl_alumnos_materias`)
2. Obtiene las correlativas necesarias de la materia
3. Valida si cada correlativa está cumplida:
   - Regular: `cursada = '1'`
   - Aprobada: `rendida = '1'`
4. Retorna lista de correlativas cumplidas y faltantes

---

## 🎨 Diseño y UX

### Paleta de Colores
```
Sidebar/Navbar: bg-gray-800 (Dark)
Fondo principal: bg-gray-50
Cards: bg-white con border-gray-200

Estados de materias:
- Disponible: border-gray-200, badge verde
- Seleccionada: border-blue-500, bg-blue-50
- No disponible: border-red-200, bg-red-50

Badges de correlativas:
- Regular: bg-blue-100, text-blue-700
- Aprobada: bg-purple-100, text-purple-700
- Cumplida: text-green-600
- Pendiente: text-red-600
```

### Iconos (Boxicons)
- `bx-menu` - Hamburguesa
- `bx-home-alt` - Inicio
- `bx-clipboard` - Inscripciones
- `bx-folder-open` - Expediente
- `bx-calendar-event` - Mesas
- `bx-user` - Usuario/Profesor
- `bx-book` - Año
- `bx-time` - Cuatrimestre
- `bx-check-circle` - Cumplida
- `bx-x-circle` - Pendiente
- `bx-bell` - Notificaciones

### Responsive Design
- **Móvil:** 1 columna, sidebar overlay
- **Tablet (md):** 2 columnas
- **Desktop (lg):** 3 columnas, sidebar fixed

---

## 🔧 Tecnologías y Stack

### Backend
- **Laravel 12** - Framework PHP
- **MySQL** - Base de datos
- **Eloquent ORM** - Manejo de datos
- **Inertia.js** - Bridge Laravel-Vue

### Frontend
- **Vue.js 3** - Framework JavaScript (Composition API)
- **Tailwind CSS** - Estilos utility-first
- **Boxicons** - Iconos
- **Vite** - Build tool

### Servicios Personalizados
- `MotorCorrelativasService` - Validación de correlativas
- `ValidacionCursadoService` - Validación para cursar
- `CorrelativasParserService` - Parser de correlativas

---

## 📝 Pendientes para Próxima Sesión

### Funcionalidades
1. Implementar confirmación de inscripción con modal
2. Guardar inscripciones en base de datos
3. Implementar módulo de Expediente
4. Implementar módulo de Mesas de Examen
5. Sistema de notificaciones
6. Obtener horarios reales de materias

### Base de Datos
1. Crear tabla de inscripciones confirmadas
2. Tabla de horarios por materia
3. Sistema de cupos por materia

### UI/UX
1. Modal de confirmación de inscripción
2. Mensajes de éxito/error con Toasts
3. Logo del instituto en sidebar

---

## 🚀 Comandos Útiles

### Desarrollo
```bash
# Backend
php artisan serve

# Frontend
npm run dev

# Base de datos
php artisan migrate
php artisan db:seed --class=PeriodosInscripcionSeeder
php artisan db:seed --class=UserAlumnoSeeder
```

### Testing
```bash
# Login de prueba
DNI: 20829266
Password: 20829266
```

---

## 📂 Estructura de Archivos Modificados/Creados

```
app/
├── Http/Controllers/
│   └── InscripcionesController.php (MODIFICADO)
├── Models/
│   ├── Materia.php (MODIFICADO)
│   └── PeriodoInscripcion.php (NUEVO)
└── Services/
    ├── MotorCorrelativasService.php
    └── ValidacionCursadoService.php

database/
├── migrations/
│   └── 2025_10_01_020019_create_tbl_periodos_inscripcion_table.php (NUEVO)
└── seeders/
    └── PeriodosInscripcionSeeder.php (NUEVO)

resources/js/
├── Components/
│   └── MateriaCard.vue (REDISEÑADO)
├── Layouts/
│   └── SidebarLayout.vue (MEJORADO)
└── Pages/
    ├── Dashboard.vue (MEJORADO)
    └── Inscripciones/
        └── Index.vue (REDISEÑADO)
```

---

## 💡 Notas Importantes

1. **NO COMMITEAR** hasta que todo funcione completamente
2. El sistema usa autenticación por DNI (no email)
3. Contraseña predeterminada = DNI del alumno
4. Solo hay un período activo a la vez
5. Las inscripciones tienen fechas muy cortas (3-4 días)
6. El motor de correlativas usa la tabla `tbl_alumnos_materias` como fuente de verdad

---

## 🎉 Logros del Día

- ✅ Sidebar responsive con overlay perfecto
- ✅ Sistema de períodos de inscripción funcional
- ✅ Cards de materias con información completa y real
- ✅ Validación de correlativas con datos del legajo
- ✅ UI limpia, moderna y móvil-friendly
- ✅ Botón flotante de confirmación
- ✅ Integración completa con base de datos existente

**Total de archivos modificados:** 8
**Total de archivos creados:** 3
**Líneas de código aproximadas:** ~1500

---

**Próxima sesión:** Implementar funcionalidad de confirmación de inscripción y módulo de Expediente.
