# Informe de Calidad de Código - Sistema Académico IES

**Fecha:** 16 de Octubre de 2025
**Proyecto:** Sistema Académico IES - Laravel 11 + Vue 3
**Evaluador:** Equipo de Desarrollo

---

## 1. Objetivo del Informe

Este documento analiza la calidad del código desarrollado por cuatro miembros del equipo (Alan, Amilcar, Agustín y Luis), evaluando aspectos técnicos, arquitectura, seguridad y mantenibilidad para determinar qué código integrar al sistema principal.

---

## 2. Metodología de Evaluación

Se evaluaron los siguientes criterios técnicos:

1. **Arquitectura y Patrones**: Uso correcto de MVC, separación de responsabilidades
2. **Seguridad**: Validación de datos, autorización, prevención de vulnerabilidades
3. **Base de Datos**: Uso de Eloquent ORM, migraciones, optimización de queries
4. **Frontend**: Calidad del código Vue.js, componentes reutilizables
5. **Mantenibilidad**: Legibilidad, documentación, código limpio
6. **Buenas Prácticas**: Manejo de errores, transacciones, logging

Escala de calificación: 1-10 (donde 10 es excelente)

---

## 3. Resumen de Resultados

| Desarrollador | Líneas de Código | Calificación | Recomendación |
|---------------|------------------|--------------|---------------|
| Alan | 7,515 | 9.2/10 | Integrar al proyecto |
| Amilcar | 2,300 | 9.5/10 | Ya integrado exitosamente |
| Agustín | 350 | 2.5/10 | No integrar - código rehecho |
| Luis | 691 | 3.5/10 | No integrar - funcionalidad duplicada |

---

## 4. Análisis Detallado por Desarrollador

### 4.1 Alan - Calificación: 9.2/10

**Trabajo realizado:**
- Panel completo de profesores con 24 vistas Vue.js
- Sistema de asistencias con registro por fecha
- Sistema de notas temporales con aprobación
- 9 controladores backend
- 2 migraciones de base de datos
- Middleware personalizado para profesores

**Puntos fuertes:**

1. **Uso correcto de Eloquent ORM**
```php
$profesorMateria = ProfesorMateria::with(['materiaRelacion', 'carreraRelacion'])
    ->where('id', $profesorMateriaId)
    ->where('profesor', $profesorId)
    ->firstOrFail();
```
Utiliza eager loading para evitar el problema N+1 y optimizar las consultas.

2. **Validaciones robustas**
```php
$validated = $request->validate([
    'tipo_evaluacion' => 'required|string|max:100',
    'fecha_evaluacion' => 'required|date',
    'notas' => 'required|array',
    'notas.*.alumno_id' => 'required|exists:tbl_alumnos,id',
    'notas.*.nota' => 'required|numeric|min:0|max:10',
    'notas.*.observaciones' => 'nullable|string|max:500',
]);
```
Las validaciones son específicas y completas.

3. **Seguridad en múltiples capas**
```php
// Verificación de tipo de usuario
if ($request->user()->tipo != 3) {
    abort(403, 'No autorizado');
}

// Verificación de que el profesor solo acceda a sus materias
$profesorMateria = ProfesorMateria::where('profesor', $profesorId)->firstOrFail();
```

4. **Modelos con relaciones y scopes**
```php
class NotaTemporal extends Model
{
    public function scopeDeMateria($query, $profesorMateriaId)
    {
        return $query->where('profesor_materia_id', $profesorMateriaId);
    }

    public function getEstadoAttribute(): string
    {
        return $this->nota >= 6 ? 'Aprobado' : 'Desaprobado';
    }
}
```

5. **Migraciones bien estructuradas**
```php
Schema::create('tbl_notas_temporales', function (Blueprint $table) {
    $table->integer('id')->autoIncrement();
    $table->decimal('nota', 4, 2)->comment('Nota de 0 a 10');

    $table->foreign('profesor_materia_id')
          ->references('id')
          ->on('tbl_profesor_tiene_materias')
          ->onDelete('cascade');

    $table->index(['profesor_materia_id', 'fecha_evaluacion']);
});
```
Incluye foreign keys, índices para optimización y comentarios descriptivos.

6. **Frontend profesional con Vue 3**
```vue
<script setup>
const estadisticas = computed(() => {
  const notasValidas = form.notas.filter(n => n.nota !== null);
  const total = notasValidas.length;
  const aprobados = notasValidas.filter(n => parseFloat(n.nota) >= 6).length;
  const promedio = total > 0
    ? (notasValidas.reduce((sum, n) => sum + parseFloat(n.nota), 0) / total).toFixed(2)
    : '0.00';

  return { total, aprobados, promedio };
});
</script>
```
Utiliza computed properties y código reactivo correctamente.

**Áreas de mejora:**
- No implementó transacciones de base de datos para operaciones batch
- No utilizó Form Requests (validaciones en clases separadas)
- Falta documentación en algunos métodos

**Desglose por criterios:**

| Criterio | Calificación | Justificación |
|----------|--------------|---------------|
| Arquitectura | 9/10 | Sigue patrones MVC correctamente |
| Seguridad | 10/10 | Validación y autorización en múltiples capas |
| Base de Datos | 10/10 | Eloquent con relaciones, migraciones bien hechas |
| Frontend | 9/10 | Vue 3 Composition API usado correctamente |
| Mantenibilidad | 9/10 | Código limpio y legible |
| Buenas Prácticas | 8/10 | Falta usar transacciones DB |

**Conclusión:** El código de Alan es de calidad profesional y está listo para producción. Se recomienda su integración completa al proyecto.

---

### 4.2 Amilcar - Calificación: 9.5/10

**Trabajo realizado:**
- Implementación completa de autenticación JWT desde cero
- API REST para visualización de plan de estudio
- Sistema de autorización con Laravel Policies
- Controlador con logging estratégico
- Middleware de autenticación JWT personalizado

**Puntos fuertes:**

1. **Implementación correcta de JWT**
```php
class JwtService
{
    public function issueToken(User $user, ?string $deviceName = 'api'): array
    {
        $jti = (string) Str::uuid();

        $payload = [
            'iss' => Config::get('jwt.issuer'),
            'aud' => Config::get('jwt.audience'),
            'iat' => $now->timestamp,
            'exp' => $exp->timestamp,
            'jti' => $jti,
            'sub' => (int) $user->id,
        ];

        $token = $this->encode($header, $payload, $secret);
        return ['token' => $token, 'expires_at' => $exp->toIso8601String()];
    }

    public function verify(string $token): ?array
    {
        // Verifica firma con hash_equals (previene timing attacks)
        if (!hash_equals($expectedSig, $signatureB64)) return null;

        // Verifica expiración
        if (($payload['exp'] ?? 0) <= time()) return null;

        // Verifica blacklist
        if (Cache::get($this->blacklistKey($jti))) return null;

        return $payload;
    }
}
```
Implementa JWT con sistema de blacklist, verificación de firma segura y manejo de expiración.

2. **Authorization Policies bien diseñadas**
```php
class AlumnoPolicy
{
    public function viewPlan(User $user, Alumno $alumno): bool
    {
        // Admin puede ver cualquier plan
        if ($user->tipo === 1) return true;

        // Profesor puede ver si tiene al alumno en una materia
        if ($user->tipo === 3) {
            return ProfesorMateria::where('profesor', $user->profesor_id)
                ->whereHas('inscripciones', function ($q) use ($alumno) {
                    $q->where('alumno_id', $alumno->id);
                })->exists();
        }

        // Alumno solo puede ver su propio plan
        return $user->alumno_id === $alumno->id;
    }
}
```
Autorización granular y declarativa.

3. **Logging estratégico**
```php
public function showAlumnoPlan(Alumno $alumno)
{
    Log::info('Mostrar plan de estudio', [
        'alumno_id' => $alumno->id,
        'user_id' => optional(request()->user())->id,
    ]);

    Gate::authorize('viewPlan', $alumno);

    // ... código ...

    Log::debug('Materias obtenidas', ['count' => $materias->count()]);

    return response()->json($payload);
}
```
Logging útil para debugging y auditoría sin ser excesivo.

4. **Manejo correcto de base de datos legacy**
```php
$lista = $materias->map(function ($m) {
    // Convierte el formato legacy a formato moderno
    $estado = 'pendiente';
    if ($m->rendida === '1') {
        $estado = 'aprobada';
    } elseif ($m->cursada === '1') {
        $estado = 'regularizada';
    }

    return [
        'materia' => [
            'id' => (int) $m->id,
            'nombre' => $m->nombre,
            'anio' => $m->anno,
            'cuatrimestre' => $m->semestre,
        ],
        'estado' => $estado,
        'nota' => is_numeric($m->nota) ? (float) $m->nota : $m->nota,
    ];
});
```

5. **API REST bien estructurada**
```php
$payload = [
    'alumno' => [
        'id' => $alumno->id,
        'nombre' => $alumno->nombre_completo,
        'carrera' => [
            'id' => $alumno->carreraRelacion->Id,
            'nombre' => $alumno->carreraRelacion->nombre,
        ],
    ],
    'resumen' => [
        'totalMaterias' => $total,
        'aprobadas' => $aprobadas,
        'avancePorcentaje' => $avance,
    ],
    'materias' => $lista,
];
```
Respuestas JSON consistentes y bien estructuradas.

**Áreas de mejora:**
- No incluyó tests unitarios
- Podría agregar más documentación en los métodos complejos

**Desglose por criterios:**

| Criterio | Calificación | Justificación |
|----------|--------------|---------------|
| Arquitectura | 10/10 | API REST bien diseñada |
| Seguridad | 10/10 | JWT + Policies impecable |
| Base de Datos | 10/10 | Optimización con joins y eager loading |
| Frontend | 8/10 | Frontend básico pero funcional |
| Mantenibilidad | 10/10 | Código limpio y documentado |
| Buenas Prácticas | 9/10 | Logging y manejo de errores correcto |

**Conclusión:** El código de Amilcar demuestra un nivel senior de arquitectura de software. Ya está integrado exitosamente al proyecto.

---

### 4.3 Agustín - Calificación: 2.5/10

**Trabajo realizado:**
- Sistema básico de excepciones (350 líneas)
- Sistema básico de notificaciones
- Modelos y controladores simples

**Problemas críticos identificados:**

1. **Vulnerabilidad de seguridad: Mass Assignment**
```php
// VULNERABLE
public function store(Request $request)
{
    $excepcion = Excepcion::create($request->all());
}
```
Problema: `$request->all()` permite que un atacante envíe campos adicionales no esperados.

Solución correcta:
```php
public function store(StoreExcepcionRequest $request)
{
    $excepcion = Excepcion::create($request->validated());
}
```

2. **Sin validación de datos**
```php
// Sin validaciones
public function update(Request $request, Excepcion $excepcion)
{
    $excepcion->update($request->all());
}
```

3. **Sin paginación**
```php
// Carga TODOS los registros en memoria
$excepciones = Excepcion::all();
```
Problema: Si hay 10,000 excepciones, carga todas en memoria.

Solución correcta:
```php
$excepciones = Excepcion::with(['alumno', 'materia'])
    ->orderBy('created_at', 'desc')
    ->paginate(15);
```

4. **Sin transacciones de base de datos**
```php
// Si falla alguna operación, queda inconsistente
Notificacion::crear($userId, ...);
$excepcion->update(['estado' => 'aprobada']);
HistorialExcepcion::create([...]);
```

Solución correcta:
```php
DB::transaction(function () use ($request, $excepcion) {
    $excepcion->update($request->validated());
    Notificacion::crear($excepcion->alumno->user_id, ...);
    HistorialExcepcion::create([...]);
});
```

5. **Frontend básico con alert()**
```javascript
// No profesional
if (confirm('¿Aprobar?')) {
    await axios.post(`/excepciones/${id}`);
    alert('Aprobada');
}
```

**Desglose por criterios:**

| Criterio | Calificación | Justificación |
|----------|--------------|---------------|
| Arquitectura | 3/10 | No sigue patrones establecidos |
| Seguridad | 1/10 | Vulnerabilidad mass assignment crítica |
| Base de Datos | 3/10 | Sin transacciones ni optimización |
| Frontend | 3/10 | Básico, usa alert() nativo |
| Mantenibilidad | 3/10 | Difícil de mantener |
| Buenas Prácticas | 2/10 | No sigue estándares de Laravel |

**Decisión:** El código fue descartado y reescrito profesionalmente con:
- Form Requests para validación
- Transacciones de base de datos
- Componentes Vue reutilizables
- Sistema de notificaciones automático con polling

**Recomendación para Agustín:** Requiere capacitación en:
- Seguridad en Laravel (mass assignment, validaciones)
- Uso de transacciones de base de datos
- Patrones de diseño MVC
- Componentes Vue reutilizables

---

### 4.4 Luis - Calificación: 3.5/10

**Trabajo realizado:**
- Sistema de asistencias (691 líneas)
- Sistema de notas temporales
- 3 migraciones

**Problemas principales identificados:**

1. **Uso excesivo de Query Builder en lugar de Eloquent**
```php
// Código de Luis - Query Builder
$profesor = DB::table('tbl_profesores')
    ->where('email', $user->email)
    ->first();

$alumnos = DB::table('tbl_alumnos')
    ->whereIn('dni', $inscriptos)
    ->select('id', 'apellido', 'nombre')
    ->get();

// Forma correcta - Eloquent
$profesor = Profesor::where('email', $user->email)->first();
$alumnos = Alumno::whereIn('dni', $inscriptos)->get();
```
Problema: Pierde todas las ventajas de Eloquent (relaciones, eventos, casts, accessors).

2. **Código repetido (violación del principio DRY)**
```php
// Se repite en CADA método
public function listarPorMateria(Request $req)
{
    $profesor = DB::table('tbl_profesores')->where('email', $user->email)->first();
    // ...
}

public function guardarBatch(Request $req)
{
    $profesor = DB::table('tbl_profesores')->where('email', $user->email)->first();
    // ...
}

public function aprobar(Request $req, $id)
{
    $profesor = DB::table('tbl_profesores')->where('email', $user->email)->first();
    // ...
}
```
Debería ser un método privado o usar middleware.

3. **Validaciones insuficientes**
```php
// Valida el array pero no su contenido
$req->validate([
    'materia_id' => 'required|integer',
    'asignaciones' => 'required|array'
]);
// No valida estructura de 'asignaciones'
```

4. **Sin autorización robusta**
```php
public function aprobar($id)
{
    $nota = NotaTemporal::findOrFail($id);
    $nota->estado = 'aprobada';
    $nota->save();
}
// Problema: Cualquier profesor podría aprobar notas de otro
```

5. **Sin transacciones**
```php
foreach ($req->asignaciones as $a) {
    NotaTemporal::updateOrCreate([...]);
    HistorialNota::create([...]);
}
// Si falla a mitad, queda inconsistente
```

6. **Frontend con alert() nativo**
```javascript
await axios.post('/notas/guardar', payload);
alert('Notas temporales guardadas');
```

**Desglose por criterios:**

| Criterio | Calificación | Justificación |
|----------|--------------|---------------|
| Arquitectura | 4/10 | No aprovecha Eloquent ORM |
| Seguridad | 3/10 | Autorización débil |
| Base de Datos | 3/10 | Query Builder en vez de ORM |
| Frontend | 3/10 | Básico, sin componentes |
| Mantenibilidad | 3/10 | Código repetido |
| Buenas Prácticas | 3/10 | No sigue convenciones de Laravel |

**Decisión:** No integrar. Ya existe funcionalidad superior en `ExpedienteController` que implementa:
- Eloquent ORM con relaciones
- Form Requests para validación
- Transacciones de base de datos
- Componentes Vue profesionales
- Sistema de notificaciones automático

**Recomendación para Luis:** Requiere capacitación en:
- Eloquent ORM y relaciones
- Principios DRY (Don't Repeat Yourself)
- Validación y autorización en Laravel
- Transacciones de base de datos
- Vue.js y componentes reutilizables

---

## 5. Tabla Comparativa

| Criterio | Alan | Amilcar | Agustín | Luis |
|----------|------|---------|---------|------|
| Arquitectura y Patrones | 9/10 | 10/10 | 3/10 | 4/10 |
| Seguridad | 10/10 | 10/10 | 1/10 | 3/10 |
| Base de Datos | 10/10 | 10/10 | 3/10 | 3/10 |
| Frontend | 9/10 | 8/10 | 3/10 | 3/10 |
| Mantenibilidad | 9/10 | 10/10 | 3/10 | 3/10 |
| Buenas Prácticas | 8/10 | 9/10 | 2/10 | 3/10 |
| **PROMEDIO** | **9.2/10** | **9.5/10** | **2.5/10** | **3.5/10** |

---

## 6. Decisiones y Recomendaciones

### 6.1 Código para Integrar

**Alan (9.2/10) - INTEGRAR**
- Panel completo de profesores (24 vistas)
- Sistema de asistencias
- Sistema de notas temporales
- Todos los controladores y modelos asociados
- Migraciones de base de datos

**Amilcar (9.5/10) - YA INTEGRADO**
- API REST de Plan de Estudio
- Sistema JWT completo
- Authorization Policies
- Middleware de autenticación

### 6.2 Código Descartado

**Agustín (2.5/10) - NO INTEGRAR**
- Razón: Vulnerabilidades de seguridad críticas
- Acción tomada: Código reescrito profesionalmente
- Estado: Sistema de excepciones y notificaciones ya implementado correctamente

**Luis (3.5/10) - NO INTEGRAR**
- Razón: Funcionalidad duplicada con calidad inferior
- Acción tomada: Ya existe implementación superior en ExpedienteController
- Estado: No requiere integración

### 6.3 Plan de Capacitación

**Para Agustín:**
1. Seguridad en Laravel (mass assignment, validación)
2. Uso de Form Requests
3. Transacciones de base de datos
4. Componentes Vue.js

**Para Luis:**
1. Eloquent ORM y relaciones
2. Principios SOLID y DRY
3. Autorización y middleware
4. Vue 3 Composition API

---

## 7. Conclusiones

### Fortalezas del Equipo
- **Alan y Amilcar** demuestran nivel senior con código production-ready
- Buena comprensión de arquitectura MVC
- Implementación correcta de patrones de diseño

### Áreas de Mejora del Equipo
- Agustín y Luis requieren capacitación técnica
- Falta implementación de tests unitarios en todo el equipo
- Documentación técnica podría mejorarse

### Impacto en el Proyecto
- **Positivo:** 9,815 líneas de código de calidad profesional integradas
- **Evitado:** 1,041 líneas de código con problemas de seguridad y arquitectura
- **Estado actual:** Sistema robusto y escalable

---

## 8. Próximos Pasos

1. **Inmediato:**
   - [ ] Integrar código de Alan
   - [ ] Ejecutar pruebas de integración
   - [ ] Verificar funcionamiento del sistema completo

2. **Corto plazo (1-2 semanas):**
   - [ ] Documentar API de Amilcar
   - [ ] Crear manual de uso del panel de profesores
   - [ ] Implementar tests unitarios

3. **Mediano plazo (1 mes):**
   - [ ] Capacitación para Agustín y Luis
   - [ ] Code review semanal
   - [ ] Implementar CI/CD

---

**Documento elaborado por:** Equipo de Desarrollo - Sistema Académico IES
**Fecha:** 16 de Octubre de 2025
**Versión:** 1.0
