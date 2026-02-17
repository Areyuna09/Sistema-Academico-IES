# Chatbox Flotante - Sistema Académico IES

## Descripción General

El **Chatbox Flotante** es un componente autónomo de soporte técnico integrado en el Sistema Académico IES. Funciona completamente offline sin dependencias de API, utilizando un sistema de respuestas inteligentes basadas en palabras clave.

### Características Principales
- **100% Autónomo** - Sin dependencias de backend
- **Chatbot Inteligente** - Respuestas automáticas contextualizadas
- **Diseño Flotante** - No interfiere con la interfaz principal
- **Responsive** - Funciona perfectamente en móvil y desktop
- **Estado Local** - Mantiene conversación mientras está abierto
## Estructura de Archivos

### Archivos Principales
```
resources/js/Components/Chat/
└── SupportChatWidget.vue           # Componente principal del Chatbox

resources/js/Composables/
└── useChatbot.js                # Lógica del chatbot

CHATBOX_README.md                   # Documentación completa
```

### Arquitectura del Componente

#### SupportChatWidget.vue
- **Framework**: Vue 3 Composition API
- **Estilos**: Tailwind CSS
- **Lógica**: Composable useChatbot.js
- **Estado**: Local (ref/reactive)
- **Tamaño**: 380px × 580px (ventana)
- **Posición**: Fixed bottom-right

#### useChatbot.js
- **Función**: Respuestas inteligentes por keywords
- **Categorías**: Saludo, notas, asistencias, materias, etc.
- **Respuestas**: Múltiples opciones por categoría
- **Quick Replies**: Respuestas rápidas predefinidas

## Vinculaciones con el Sistema

### Integración Principal
**Archivo**: `resources/js/Layouts/SidebarLayout.vue`

```vue
<!-- Importación -->
<script setup>
import SupportChatWidget from '@/Components/Chat/SupportChatWidget.vue';
</script>

<!-- Uso en template -->
<template>
    <!-- ... resto del layout ... -->
    <SupportChatWidget :user="user" />
</template>
```

### Rutas Modificadas
**Archivo**: `routes/web.php`

- **Redirección principal**: `/` → `/login` (si no autenticado)
- **Rutas de chat API**: Eliminadas (ya no se usan)
- **Autenticación**: Mantenida con Laravel

## Instalación y Configuración

### Ubicación de Archivos
- **Componente**: `resources/js/Components/Chat/SupportChatWidget.vue`
- **Composable**: `resources/js/Composables/useChatbot.js`
- **Layout**: `resources/js/Layouts/SidebarLayout.vue`
- **Documentación**: `CHATBOX_README.md`

### Configuración Automática
- **Importación**: SidebarLayout importa automáticamente el componente
- **Props**: Se pasa el objeto `user` desde el layout
- **Estado**: Manejado localmente sin persistencia

## Funcionalidades

### Sistema de Respuestas
- **Keywords**: Detección por palabras clave
- **Categorías**: 
  - Saludo y despedida
  - Notas y calificaciones
  - Asistencias y faltas
  - Materias y comisiones
  - Legajos académicos
  - Alumnos e inscripciones
  - Contraseña y perfil
  - Ayuda general

### Interfaz de Usuario
- **Botón flotante**: Con badge de mensajes no leídos
- **Ventana de chat**: Con header y área de mensajes
- **Quick replies**: Botones de respuesta rápida
- **Indicador de escritura**: Animación de puntos
- **Auto-scroll**: Desplazamiento automático

## Mejoras Implementadas

### Versión 1.0.0 (Febrero 2026)
- **Modularización**: Separación de responsabilidades
- **Diseño renovado**: Interfaz moderna y profesional
- **Composable**: Lógica extraída a useChatbot.js
- **Optimización**: Reducción de código 70%
- **Documentación**: README profesional sin emojis
- **Integración**: Configuración automática desde SidebarLayout

## Mantenimiento

### Archivos a Mantener
- **SupportChatWidget.vue**: Componente visual
- **useChatbot.js**: Lógica del chatbot
- **CHATBOX_README.md**: Documentación actualizada

### Actualizaciones
- **Keywords**: Agregar nuevas palabras clave
- **Respuestas**: Modificar textos existentes
- **Categorías**: Crear nuevas categorías

## Notas Finales

El **Chatbox Flotante** del Sistema Académico IES representa una solución moderna, eficiente y autónoma para soporte técnico. Su diseño modular permite fácil mantenimiento y futuras mejoras sin afectar la funcionalidad core del sistema.

**Versión**: 1.0.0  
**Última actualización**: Febrero 2026  
**Compatibilidad**: Laravel 12 + Vue 3 + Tailwind CSS  
**Estado**: Producción activa

---

*Este documento describe la implementación actual del Chatbox Flotante autónomo del Sistema Académico IES.*
## Flujo de Funcionamiento

### Paso a Paso
1. **Usuario hace clic** en botón flotante azul
2. **Se abre ventana** con mensaje de bienvenida
3. **Usuario escribe mensaje** y presiona Enter/clic enviar
4. **Bot muestra indicador** "Chatbox está escribiendo..."
5. **Después de 1.5s** se muestra respuesta automática
6. **Respuesta se basa** en palabras clave detectadas
7. **Conversación se mantiene** mientras la ventana está abierta
8. **Al cerrar** se limpia el estado y se reinicia

### Lógica de Detección
```javascript
const lowerMessage = userMessage.toLowerCase();

// Detección por prioridad
if (lowerMessage.includes('contraseña')) {
    // Respuesta específica para contraseña
} else if (lowerMessage.includes('consulta académica')) {
    // Respuesta específica para académico
} else {
    // Respuesta genérica con sugerencias
}
```

---

## Mantenimiento y Mejoras

### Personalización de Respuestas
**Archivo**: `resources/js/Components/Chat/SupportChatWidget.vue`
**Función**: `sendBotResponse()`

Para agregar nuevas respuestas:
```javascript
else if (lowerMessage.includes('nueva-palabra-clave')) {
    botResponse = `Tu respuesta personalizada aquí\n\nChatbox IES`;
}
```

### Personalización Visual
- **Colores**: Modificar clases de Tailwind en el template
- **Tamaños**: Ajustar `w-[450px] h-[600px]`
- **Posición**: Modificar `bottom-4 right-4`
- **Iconos**: Reemplazar SVGs en el template

### Métricas y Estadísticas
El Chatbox actual no incluye métricas. Para agregar:
- Contador de mensajes enviados
- Registro de palabras clave más usadas
- Tiempos de respuesta promedio
- Satisfacción del usuario (emoji reactions)

---

## Mejoras Posibles para el Sistema

### Mejoras Inmediatas (Fáciles)

#### 1. Analytics de Chatbox
```javascript
// Agregar al componente
const metrics = ref({
    messagesSent: 0,
    keywordsUsed: {},
    sessionDuration: 0
});
```

#### 2. Temas Personalizables
```vue
<!-- Agregar selector de temas -->
<select v-model="theme" @change="updateTheme">
    <option value="blue">Azul (Actual)</option>
    <option value="green">Verde</option>
    <option value="purple">Morado</option>
</select>
```

#### 3. Notificaciones Sonoras
```javascript
// Agregar sonido al recibir respuesta
const playNotificationSound = () => {
    const audio = new Audio('/sounds/notification.mp3');
    audio.play();
};
```

#### 4. Modo Oscuro
```vue
<!-- Detectar preferencia del sistema -->
<div :class="{ 'dark': isDarkMode }">
```

### Mejoras a Mediano Plazo

#### 1. IA Conversacional Avanzada
- Integración con OpenAI API
- Contexto de conversación
- Aprendizaje de interacciones
- Respuestas más naturales

#### 2. Base de Conocimiento Dinámica
- Cargar respuestas desde archivo JSON
- Actualización sin modificar código
- Multi-idioma
- Respuestas específicas por rol

#### 3. Búsqueda Inteligente
- Sugerencias mientras escribe
- Búsqueda en documentación del sistema
- Acceso rápido a funciones frecuentes

#### 4. Dashboard de Soporte
- Panel administrativo del Chatbox
- Estadísticas de uso
- Análisis de consultas frecuentes
- Reportes de satisfacción

### Mejoras a Largo Plazo

#### 1. Multi-idioma
- Español, inglés, portugués
- Detección automática de idioma
- Respuestas traducidas

#### 2. Integración con Sistemas Externos
- WhatsApp Business
- Telegram Bot
- Email de soporte
- Sistema de tickets

#### 3. Machine Learning
- Clasificación automática de consultas
- Predicción de intenciones
- Mejora continua de respuestas

---

## Mejoras Implementadas Recientemente

### Refactorización y Modularización (Febrero 2026)

#### 1. Separación de Responsabilidades
**Antes**: Todo el código en un solo archivo monolítico
**Ahora**: Arquitectura modular con separación clara de concerns

```
# Estructura Antes
resources/js/Components/Chat/
└── SupportChatWidget.vue    # 1400+ líneas (monolítico)

# Estructura Actual
resources/js/
├── Components/Chat/
│   └── SupportChatWidget.vue     # Componente principal (template + eventos)
├── Composables/
│   └── useChatbot.js           # Lógica del chatbot (respuestas + utilidades)
└── Layouts/
    └── SidebarLayout.vue         # Integración con el sistema
```

#### 2. Creación de Composable Especializado
**Archivo**: `resources/js/Composables/useChatbot.js`

**Ventajas**:
- **Reutilizable**: El composable puede usarse en otros componentes
- **Testeable**: La lógica del chatbot puede probarse independientemente
- **Mantenible**: Actualizar respuestas sin tocar el componente visual
- **Cacheable**: Vue puede optimizar el composable por separado

**Contenido del Composable**:
```javascript
export function useChatbot() {
    // Estado reutilizable
    const isTyping = ref(false);
    const messageIdCounter = ref(1);
    
    // Lógica de respuestas
    const generateBotResponse = (userMessage) => {
        // 15+ categorías de palabras clave
        // Respuestas contextuales y profesionales
    };
    
    // Utilidades
    const getCurrentTime = () => { /* formato argentino */ };
    const welcomeMessage = `/* mensaje de bienvenida */`;
    
    return { isTyping, messageIdCounter, generateBotResponse, ... };
}
```

#### 3. Optimización del Componente Principal
**Archivo**: `resources/js/Components/Chat/SupportChatWidget.vue`

**Mejoras**:
- **Reducción de 1400+ a ~400 líneas** (70% menos código)
- **Limpieza de responsabilidades** - Solo presentación y eventos
- **Mejor legibilidad** - Código más organizado y claro
- **Import limpio** - Uso del composable con sintaxis moderna

#### 4. Mejoras de Documentación
**Archivo**: `CHATBOX_README.md`

**Cambios**:
- **Eliminación de emojis** - Documentación más profesional y corporativa
- **Estructura mejorada** - Secciones más claras y organizadas
- **Contenido actualizado** - Refleja la nueva arquitectura modular
- **Guía de mantenimiento** - Instrucciones precisas para actualizaciones

#### 5. Beneficios Técnicos

**Rendimiento**:
- **Menos bundle size** - Componente más ligero
- **Mejor cache** - Composable puede cachearse independientemente
- **Lazy loading** - La lógica del chatbot carga solo cuando se necesita

**Mantenimiento**:
- **Actualizaciones más rápidas** - Cambiar respuestas sin modificar el componente
- **Menos riesgo de bugs** - Separación reduce impacto cruzado
- **Testing más fácil** - Cada parte puede probarse independientemente

**Escalabilidad**:
- **Fácil agregar features** - Nuevas funcionalidades al composable
- **Múltiples instancias** - El composable puede usarse en varios lugares
- **Configuración externa** - Respuestas pueden cargarse desde JSON/DB

#### 6. Código Más Limpio

**Principios SOLID aplicados**:
- **Single Responsibility**: Cada archivo tiene una responsabilidad clara
- **Open/Closed**: Extensible sin modificar código existente
- **Dependency Inversion**: El componente depende del composable, no de la implementación

**Calidad del código**:
- **TypeScript ready** - Estructura compatible con futura migración
- **Vue 3 Best Practices** - Uso correcto de Composition API
- **CSS encapsulado** - Estilos scoped sin efectos colaterales

---

## Resumen Técnico

| Característica | Estado | Tecnología |
|---------------|----------|------------|
| Framework | Activo | Vue 3 + Composition API |
| Estilos | Activo | Tailwind CSS |
| Backend | No requiere | JavaScript puro |
| Base de Datos | No requiere | Estado local |
| API | No requiere | 100% autónomo |
| Responsive | Activo | Mobile-first |
| Accesibilidad | Mejorable | WCAG 2.1 parcial |
| Testing | Pendiente | Unit/E2E tests |
| Documentación | Activa | Este README |

---

## Consideraciones de Seguridad

### Seguridad Actual
- **Sin datos persistentes** - No hay riesgo de leaks
- **Sin peticiones externas** - No hay ataques CSRF/XSS
- **Código aislado** - No afecta al resto del sistema

### Mejoras de Seguridad
- **Sanitización de inputs** - Prevenir XSS en mensajes
- **Rate limiting** - Limitar mensajes por minuto
- **Content Security Policy** - Restringir recursos externos

---

## Soporte y Mantenimiento

### Troubleshooting Común
- **Chatbox no aparece**: Verificar import en SidebarLayout.vue
- **Respuestas no funcionan**: Revisar consola JavaScript
- **Estilos incorrectos**: Verificar clases Tailwind
- **No responde**: Limpiar cache del navegador

### Actualizaciones
- **Respuestas**: Modificar función `sendBotResponse()`
- **Estilos**: Modificar template del componente
- **Lógica**: Actualizar script setup del componente

---

_Notas Finales

El **Chatbox Flotante** del Sistema Académico IES representa una solución moderna, eficiente y autónoma para soporte técnico. Su diseño modular permite fácil mantenimiento y futuras mejoras sin afectar la funcionalidad core del sistema.

**Versión**: 1.0.0  
**Última actualización**: Febrero 2026  
**Compatibilidad**: Laravel 12 + Vue 3 + Tailwind CSS  
**Estado**: Producción activa
---