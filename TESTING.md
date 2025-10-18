# 📘 Sistema de Testing Automatizado - Sistema Académico IES

## 🎯 ¿Qué es Pest PHP?

**Pest PHP** es un framework de testing moderno construido **SOBRE** PHPUnit. Es como una "capa amigable" encima de PHPUnit.

**Analogía**: Si PHPUnit es el motor de un auto, Pest es el interior moderno y cómodo que hace que manejar sea más fácil.

**Ventajas de Pest sobre PHPUnit puro:**
- Sintaxis más simple y legible
- Menos código boilerplate
- Tests más expresivos
- 100% compatible con PHPUnit

---

## 📁 Archivos Clave de Configuración

### A) `phpunit.xml` (Raíz del proyecto)

Este es el archivo de configuración principal de PHPUnit. Define cómo corren los tests.

**Configuración de Base de Datos:**
```xml
<server name="DB_CONNECTION" value="sqlite"/>
<server name="DB_DATABASE" value=":memory:"/>
```
- Usa **SQLite en memoria** (no toca tu DB real)
- La DB se crea al inicio y se destruye al terminar
- Es súper rápida (todo en RAM)

**Dónde buscar los tests:**
```xml
<testsuites>
    <testsuite name="Unit">
        <directory>tests/Unit</directory>
    </testsuite>
    <testsuite name="Feature">
        <directory>tests/Feature</directory>
    </testsuite>
</testsuites>
```

---

### B) `tests/Pest.php` (Configuración de Pest)

Configura cómo se comportan los tests en cada directorio.

```php
uses(Tests\TestCase::class)->in('Feature');
```
- Tests en Feature usan TestCase y RefreshDatabase
- Necesitan base de datos

```php
pest()->extend(Tests\TestCase::class)->in('Unit');
```
- Tests en Unit extienden TestCase pero NO usan DB
- Solo valida lógica, súper rápidos

---

## 🔍 Patrones de Búsqueda

**Por Directorio:**
```bash
php artisan test tests/Unit/
```

**Por Archivo:**
```bash
php artisan test tests/Unit/ValidationRulesTest.php
```

**Por Nombre:**
```bash
php artisan test --filter="DNI validation"
```

**Por Suite:**
```bash
php artisan test --testsuite=Unit
```

---

## 📝 Sintaxis de Tests

### Patrón AAA (Arrange, Act, Assert)

```php
test('DNI validation rule works correctly', function () {
    // Arrange (Preparar)
    $rules = [
        'dni' => ['required', 'string', 'max:10', 'regex:/^[0-9]+$/'],
    ];

    // Act (Ejecutar)
    $validator = Validator::make(['dni' => '12345678'], $rules);

    // Assert (Verificar)
    expect($validator->passes())->toBeTrue();
});
```

---

## 🎯 Criterios y Buenas Prácticas

### Nombres Descriptivos

✅ **BIEN:**
```php
test('DNI validation rule works correctly')
test('nombre validation requires at least one letter')
```

❌ **MAL:**
```php
test('test1')
test('dni')
```

### Un Test = Una Cosa

Cada test debe validar UN comportamiento específico.

### Tests Independientes

Cada test debe poder ejecutarse solo sin depender de otros.

---

## 📊 Estructura de Archivos

```
tests/
├── Feature/               # Tests que usan DB
│   ├── AuthorizationTest.php
│   ├── ModelRelationshipsTest.php
│   └── ...
├── Unit/                  # Tests sin DB
│   ├── ValidationRulesTest.php
│   ├── DemoErrorTest.php
│   └── ...
└── Pest.php
```

| Tipo | Ubicación | Usa DB | Velocidad | Ejemplo |
|------|-----------|--------|-----------|---------|
| **Unit** | tests/Unit/ | ❌ No | ⚡ Muy rápido | Validaciones, helpers |
| **Feature** | tests/Feature/ | ✅ Sí | 🐢 Más lento | Rutas, modelos, auth |

---

## 🔬 Tests Unitarios (Unit)

```php
test('DNI validation rule works correctly', function () {
    $rules = ['dni' => ['required', 'string', 'max:10', 'regex:/^[0-9]+$/']];

    // ✅ DNI válido
    $validator = Validator::make(['dni' => '12345678'], $rules);
    expect($validator->passes())->toBeTrue();

    // ❌ DNI con letras
    $validator = Validator::make(['dni' => 'abc12345'], $rules);
    expect($validator->fails())->toBeTrue();
});
```

**Métodos útiles:**
- `passes()`: Retorna true si validación pasa
- `fails()`: Retorna true si validación falla
- `errors()->has('campo')`: Verifica si hay error en campo

---

## 🏗️ Tests Feature

```php
test('only admin users can access admin routes', function () {
    $alumno = User::factory()->create(['tipo' => 2]);
    $response = $this->actingAs($alumno)->get(route('admin.usuarios.index'));
    $response->assertForbidden();
});
```

**Requiere DB para:**
- Crear usuarios en tabla users
- Ejecutar middleware de autenticación
- Verificar políticas de autorización

---

## 🏭 Factories

```php
public function definition(): array
{
    return [
        'dni' => $this->faker->unique()->numerify('########'),
        'nombre' => $this->faker->firstName(),
        'apellido' => $this->faker->lastName(),
    ];
}
```

**Uso:**
```php
// Crear con datos random
$alumno = Alumno::factory()->create();

// Crear con valores específicos
$alumno = Alumno::factory()->create([
    'nombre' => 'Juan',
    'apellido' => 'Pérez'
]);
```

---

## ⚙️ Comandos Útiles

```bash
# Ejecutar todos los tests
php artisan test

# Solo Unit (rápidos)
php artisan test tests/Unit/

# Solo Feature (con DB)
php artisan test tests/Feature/

# Un archivo específico
php artisan test tests/Unit/ValidationRulesTest.php

# Con cobertura
php artisan test --coverage

# Parar al primer error
php artisan test --stop-on-failure

# Filtrar por nombre
php artisan test --filter="DNI validation"
```

---

## 📊 Interpretando Resultados

```
PASS  Tests\Unit\ValidationRulesTest
✓ DNI validation rule works correctly           0.05s

Tests:    11 passed (38 assertions)
Duration: 1.78s
```

**Significado:**
- `PASS`: Todos los tests pasaron
- `✓`: Test individual pasó
- `11 passed`: 11 tests exitosos
- `38 assertions`: 38 verificaciones totales
- `Duration: 1.78s`: Tiempo total

---

## 🎓 Resumen

1. **Pest PHP** = Sintaxis moderna sobre PHPUnit
2. **phpunit.xml** = Configuración principal
3. **tests/Pest.php** = Configuración por directorio
4. **tests/Unit/** = Tests sin DB (rápidos)
5. **tests/Feature/** = Tests con DB (lentos)
6. **Factories** = Generadores de datos
7. **Patrón AAA** = Arrange, Act, Assert
8. **1 test = 1 comportamiento**
9. **Tests independientes**
10. **Rápido** = ~2 segundos para 11 tests

---

## 📁 Archivos del Sistema

### Tests Implementados:
- `tests/Unit/ValidationRulesTest.php` - 9 tests de validaciones
- `tests/Unit/DemoErrorTest.php` - Demostración
- `tests/Feature/AuthorizationTest.php` - Tests de autorización
- `tests/Feature/ModelRelationshipsTest.php` - Tests de relaciones

### Factories:
- `database/factories/AlumnoFactory.php`
- `database/factories/ProfesorFactory.php`
- `database/factories/CarreraFactory.php`
- `database/factories/MateriaFactory.php`

---

**Fecha**: 2025-10-18
**Tests**: 11 passed, 1 skipped
**Tiempo**: ~2 segundos
