# Sistema de Validaciones - Sistema Académico IES

Este documento describe el sistema de validaciones implementado en el sistema para prevenir la entrada de datos inválidos o basura (como "asjnhjsabxhj").

## 📋 Tabla de Contenidos
1. [Validaciones Backend (Laravel)](#validaciones-backend-laravel)
2. [Validaciones Frontend (Vue)](#validaciones-frontend-vue)
3. [Guía de Implementación](#guía-de-implementación)
4. [Ejemplos de Uso](#ejemplos-de-uso)

---

## 🔒 Validaciones Backend (Laravel)

### Form Requests Creados

Se han creado Form Requests personalizados para cada CRUD principal:

#### Usuarios
- `App\Http\Requests\StoreUsuarioRequest`
- `App\Http\Requests\UpdateUsuarioRequest`

**Validaciones implementadas:**
- ✅ Nombre: mínimo 2 caracteres, máximo 100, debe contener al menos una letra
- ✅ Email: formato válido RFC, único en la base de datos
- ✅ DNI: solo números, entre 7-10 dígitos, único
- ✅ Tipo de usuario: debe ser 1 (Admin), 2 (Bedel), 3 (Profesor) o 4 (Alumno)
- ✅ Contraseña: mínimo 6 caracteres, confirmación requerida
- ✅ Teléfono: solo números, espacios, guiones, paréntesis y símbolo +

#### Alumnos
- `App\Http\Requests\StoreAlumnoRequest`
- `App\Http\Requests\UpdateAlumnoRequest`

#### Profesores
- `App\Http\Requests\StoreProfesorRequest`
- `App\Http\Requests\UpdateProfesorRequest`

#### Carreras
- `App\Http\Requests\StoreCarreraRequest`
- `App\Http\Requests\UpdateCarreraRequest`

#### Materias
- `App\Http\Requests\StoreMateriaRequest`
- `App\Http\Requests\UpdateMateriaRequest`

#### Planes de Estudio
- `App\Http\Requests\StorePlanEstudioRequest`
- `App\Http\Requests\UpdatePlanEstudioRequest`

### Cómo Usar Form Requests

**1. En el controlador:**

```php
use App\Http\Requests\StoreUsuarioRequest;

public function store(StoreUsuarioRequest $request)
{
    // Los datos ya están validados aquí
    $validated = $request->validated();

    // Crear el registro
    Usuario::create($validated);

    return redirect()->back()->with('success', 'Registro creado exitosamente');
}
```

**2. Estructura de un Form Request:**

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Autorizar solo a admin y bedel
        return in_array($this->user()?->tipo, [1, 2]);
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^.*[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ]+.*$/', // Al menos una letra
            ],
            'dni' => [
                'required',
                'string',
                'regex:/^[0-9]+$/',
                'unique:tbl_usuarios,dni',
            ],
            // ... más reglas
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre debe contener al menos una letra.',
            'dni.regex' => 'El DNI debe contener solo números.',
            // ... más mensajes personalizados
        ];
    }
}
```

---

## 🎨 Validaciones Frontend (Vue)

### Composable useValidation

Se creó un composable reutilizable en `resources/js/Composables/useValidation.js`

### Validadores Disponibles

```javascript
import { useValidation } from '@/Composables/useValidation'

const { validators, validateForm, errors, commonRules } = useValidation()

// Validadores disponibles:
validators.required(value, fieldName)
validators.minLength(value, min, fieldName)
validators.maxLength(value, max, fieldName)
validators.lettersOnly(value, fieldName)       // Solo letras y espacios
validators.numbersOnly(value, fieldName)       // Solo números
validators.email(value, fieldName)
validators.dni(value, fieldName)               // DNI argentino (7-8 dígitos)
validators.phone(value, fieldName)             // Teléfono (10 dígitos)
validators.password(value, fieldName)          // Contraseña segura
validators.alphanumeric(value, fieldName)      // Letras, números, espacios y guiones
validators.noSpecialChars(value, fieldName)    // Previene inyección
validators.year(value, fieldName)              // Año válido (1900 - actualYear+10)
validators.positiveNumber(value, fieldName)
validators.range(value, min, max, fieldName)
```

### Ejemplo de Uso en Componente Vue

```vue
<script setup>
import { ref } from 'vue'
import { useValidation } from '@/Composables/useValidation'
import { router } from '@inertiajs/vue3'

const { validateForm, errors, clearErrors, commonRules, validators } = useValidation()

const form = ref({
    nombre: '',
    email: '',
    dni: '',
    password: ''
})

const validationRules = {
    nombre: commonRules.nombre,
    email: commonRules.email,
    dni: commonRules.dni,
    password: commonRules.password
}

const submitForm = () => {
    // Validar antes de enviar
    if (!validateForm(form.value, validationRules)) {
        return // Si hay errores, no enviar
    }

    // Si pasa las validaciones, enviar
    router.post('/admin/usuarios', form.value)
}
</script>

<template>
    <form @submit.prevent="submitForm">
        <div>
            <label>Nombre</label>
            <input v-model="form.nombre" type="text" />
            <p v-if="errors.nombre" class="text-red-600 text-sm">
                {{ errors.nombre }}
            </p>
        </div>

        <div>
            <label>Email</label>
            <input v-model="form.email" type="email" />
            <p v-if="errors.email" class="text-red-600 text-sm">
                {{ errors.email }}
            </p>
        </div>

        <div>
            <label>DNI</label>
            <input v-model="form.dni" type="text" />
            <p v-if="errors.dni" class="text-red-600 text-sm">
                {{ errors.dni }}
            </p>
        </div>

        <button type="submit">Guardar</button>
    </form>
</template>
```

### Validación en Tiempo Real

```vue
<script setup>
import { watch } from 'vue'

// Validar campo mientras el usuario escribe
watch(() => form.value.nombre, (newValue) => {
    const error = validators.lettersOnly(newValue, 'El nombre')
    if (error) {
        errors.value.nombre = error
    } else {
        clearFieldError('nombre')
    }
})
</script>
```

---

## 📝 Guía de Implementación

### Para Agregar Validaciones a un Nuevo CRUD

#### 1. Backend (Laravel)

**Paso 1: Crear Form Requests**
```bash
php artisan make:request StoreNombreRequest
php artisan make:request UpdateNombreRequest
```

**Paso 2: Configurar las Reglas**
```php
public function rules(): array
{
    return [
        'nombre' => [
            'required',
            'string',
            'min:2',
            'max:100',
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
        ],
        // ... más reglas
    ];
}
```

**Paso 3: Usar en el Controlador**
```php
use App\Http\Requests\StoreNombreRequest;

public function store(StoreNombreRequest $request)
{
    $validated = $request->validated();
    Modelo::create($validated);
}
```

#### 2. Frontend (Vue)

**Paso 1: Importar Composable**
```javascript
import { useValidation } from '@/Composables/useValidation'
```

**Paso 2: Definir Reglas**
```javascript
const validationRules = {
    nombre: {
        label: 'El nombre',
        validators: [
            validators.required,
            (v) => validators.minLength(v, 2, 'El nombre'),
            (v) => validators.lettersOnly(v, 'El nombre'),
        ]
    }
}
```

**Paso 3: Validar antes de Enviar**
```javascript
const submitForm = () => {
    if (!validateForm(form.value, validationRules)) {
        return
    }
    // Enviar formulario
}
```

---

## 🔍 Ejemplos de Uso

### Ejemplo 1: Validar Nombre de Carrera

**Backend:**
```php
'nombre' => [
    'required',
    'string',
    'min:3',
    'max:200',
    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-]+$/', // Letras, espacios y guiones
    'unique:tbl_carreras,nombre'
],
```

**Frontend:**
```javascript
{
    nombre: {
        label: 'El nombre de la carrera',
        validators: [
            validators.required,
            (v) => validators.minLength(v, 3, 'El nombre'),
            (v) => validators.maxLength(v, 200, 'El nombre'),
            (v) => validators.alphanumeric(v, 'El nombre'),
        ]
    }
}
```

### Ejemplo 2: Validar Año Académico

**Backend:**
```php
'anio' => [
    'required',
    'integer',
    'min:1900',
    'max:' . (date('Y') + 10)
],
```

**Frontend:**
```javascript
{
    anio: {
        label: 'El año',
        validators: [
            validators.required,
            (v) => validators.year(v, 'El año'),
        ]
    }
}
```

### Ejemplo 3: Validar Nota (0-10)

**Backend:**
```php
'nota' => [
    'required',
    'numeric',
    'min:0',
    'max:10'
],
```

**Frontend:**
```javascript
{
    nota: {
        label: 'La nota',
        validators: [
            validators.required,
            (v) => validators.range(v, 0, 10, 'La nota'),
        ]
    }
}
```

---

## 🛡️ Prevención de Inyecciones y Ataques

El sistema incluye validaciones específicas para prevenir:

### 1. Inyección SQL
- Uso de Eloquent ORM y consultas parametrizadas
- Validación de tipos de datos
- Escapado automático de Laravel

### 2. XSS (Cross-Site Scripting)
- Sanitización automática de Blade/Vue
- Validador `noSpecialChars` que bloquea `< > " ' ; & \`
- Uso de `v-text` en lugar de `v-html` cuando sea posible

### 3. Datos Basura
- Regex estrictos para cada tipo de campo
- Validación de longitudes mínimas y máximas
- Listas blancas (whitelist) para campos con valores predefinidos

---

## ✅ Checklist de Validación para Nuevos CRUDs

- [ ] Form Request creado para store
- [ ] Form Request creado para update
- [ ] Reglas de validación definidas
- [ ] Mensajes de error personalizados
- [ ] Validación de unicidad (si aplica)
- [ ] Validación de relaciones (foreign keys)
- [ ] Validaciones frontend con useValidation
- [ ] Mensajes de error mostrados en UI
- [ ] Validación en tiempo real (opcional)
- [ ] Pruebas con datos inválidos

---

## 📚 Recursos Adicionales

- [Laravel Validation Docs](https://laravel.com/docs/validation)
- [Form Request Validation](https://laravel.com/docs/validation#form-request-validation)
- [Vue Composables](https://vuejs.org/guide/reusability/composables.html)

---

**Última actualización:** $(date)
**Versión del sistema:** 1.0
