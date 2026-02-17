/**
 * useChatbot.js
 * Composable con lógica del asistente: respuestas, estado global y configuración.
 */
import { ref } from 'vue';

// ─── Respuestas del bot por categorías ──────────────────────────────────────
const RESPONSES = {
  saludo: {
    keywords: ['hola', 'buenas', 'buenos días', 'buenas tardes', 'buenas noches', 'saludos', 'hey', 'hi'],
    replies: [
      '¡Hola!  ¿En qué te puedo ayudar hoy?',
      '¡Buenas! Estoy aquí para ayudarte. ¿Qué necesitás?',
      '¡Hola! Contame, ¿en qué puedo asistirte?',
    ]
  },
  despedida: {
    keywords: ['chau', 'adiós', 'hasta luego', 'bye', 'gracias', 'hasta pronto', 'ok gracias', 'listo gracias'],
    replies: [
      '¡Hasta luego! Si necesitás algo más, acá estoy. ',
      '¡Que tengas un excelente día! No dudes en volver si tenés alguna consulta.',
      '¡Chau! Fue un gusto ayudarte. ',
    ]
  },
  notas: {
    keywords: ['nota', 'notas', 'calificación', 'calificaciones', 'promedio', 'parcial', 'final', 'examen', 'evaluación'],
    replies: [
      ' Para consultar o cargar notas, dirigite a la sección **Alumnos** dentro de la materia correspondiente. Allí encontrarás el registro de parciales, finales y promedios.',
      'Las notas de parciales se registran directamente en el legajo del alumno. Las notas de final quedan pendientes hasta que el Bedel o Administrador las transfiera.',
      ' Podés ver el historial completo de notas de un alumno desde la sección **Legajos**, buscando por DNI.',
    ]
  },
  asistencias: {
    keywords: ['asistencia', 'asistencias', 'falta', 'faltas', 'presente', 'ausente', 'tomar lista', 'lista'],
    replies: [
      ' Las asistencias se registran desde la sección **Alumnos** de tu materia. Podés tomar lista clase por clase y el sistema calcula el porcentaje de asistencia automáticamente.',
      'Para ver el historial de asistencias de un alumno, ingresá a **Legajos** y buscá por su DNI.',
    ]
  },
  materias: {
    keywords: ['materia', 'materias', 'asignatura', 'asignaturas', 'curso', 'cursado', 'comisión'],
    replies: [
      ' En la pestaña **Materias** podés ver todas tus asignaturas activas, configurar parámetros académicos y acceder a los listados de alumnos.',
      'Antes de cargar la primera nota, asegurate de configurar los parámetros académicos de la materia (porcentaje mínimo de asistencia, escala de notas, etc.).',
    ]
  },
  legajos: {
    keywords: ['legajo', 'legajos', 'historial', 'expediente', 'académico', 'historia académica'],
    replies: [
      ' La sección **Legajos** te permite consultar el historial académico completo de cualquier alumno. Solo ingresá el DNI y verás todas las materias cursadas, notas y estado académico organizado por año.',
    ]
  },
  alumnos: {
    keywords: ['alumno', 'alumnos', 'estudiante', 'estudiantes', 'inscripto', 'inscriptos', 'listado'],
    replies: [
      ' Desde la sección **Alumnos** de cada materia podés ver el listado completo de inscriptos, registrar asistencias y cargar notas individuales.',
    ]
  },
  contrasena: {
    keywords: ['contraseña', 'password', 'clave', 'cambiar contraseña', 'olvidé', 'recuperar'],
    replies: [
      ' Para cambiar tu contraseña, dirigite a tu perfil (ícono de usuario arriba a la derecha) y buscá la opción **Seguridad** o **Cambiar contraseña**.',
      'Si olvidaste tu contraseña, contactá al administrador del sistema para que te la restablezca.',
    ]
  },
  perfil: {
    keywords: ['perfil', 'datos', 'avatar', 'foto', 'nombre', 'email', 'actualizar datos'],
    replies: [
      ' Podés actualizar tu perfil desde el menú de usuario (ícono arriba a la derecha). Allí podés cambiar tu foto, nombre y otros datos personales.',
    ]
  },
  ayuda: {
    keywords: ['ayuda', 'help', 'soporte', 'problema', 'error', 'no funciona', 'no puedo', 'cómo', 'como'],
    replies: [
      ' Estoy aquí para ayudarte. ¿Podés contarme más detalles sobre el problema que tenés? Así puedo orientarte mejor.',
      ' Contame qué es lo que no podés hacer y te ayudo a solucionarlo paso a paso.',
    ]
  },
  default: [
    ' No estoy seguro de entender tu consulta. ¿Podés reformularla? Estoy para ayudarte con notas, asistencias, materias y legajos.',
    'Hmm, no tengo información específica sobre eso. ¿Podés ser más detallado? Puedo ayudarte con temas académicos del sistema.',
    '¿Podrías darme más detalles? Así puedo orientarte mejor. ',
  ]
};

// ─── Respuestas rápidas sugeridas ────────────────────────────────────────────
const DEFAULT_QUICK_REPLIES = [
  '¿Cómo cargo notas?',
  '¿Cómo tomo asistencia?',
  '¿Cómo consulto un legajo?',
  'Tengo un problema',
];

const WELCOME_MESSAGE =
  '¡Hola!  Soy el asistente virtual del Sistema Académico IES.\n¿En qué te puedo ayudar hoy?';

// ─── Composable ──────────────────────────────────────────────────────────────
export function useChatbot(user = null) {
  const isTyping = ref(false);

  /**
   * Genera una respuesta basada en el texto del usuario.
   * Busca coincidencias de keywords en las categorías definidas.
   */
  const generateBotResponse = (userText) => {
    if (!userText?.trim()) return 'No recibí ningún mensaje. ¿En qué te puedo ayudar?';

    const normalized = userText.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');

    // Buscar coincidencia en categorías
    for (const [, category] of Object.entries(RESPONSES)) {
      if (!category.keywords) continue;
      const matched = category.keywords.some(kw =>
        normalized.includes(kw.normalize('NFD').replace(/[\u0300-\u036f]/g, ''))
      );
      if (matched) {
        const replies = category.replies;
        return replies[Math.floor(Math.random() * replies.length)];
      }
    }

    // Respuesta por defecto
    const defaults = RESPONSES.default;
    return defaults[Math.floor(Math.random() * defaults.length)];
  };

  return {
    isTyping,
    welcomeMessage: WELCOME_MESSAGE,
    quickReplies: DEFAULT_QUICK_REPLIES,
    generateBotResponse,
  };
}