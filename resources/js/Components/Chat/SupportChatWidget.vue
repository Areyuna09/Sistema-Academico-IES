<template>
  <div class="fixed bottom-5 right-5 z-50 flex flex-col items-end">

    <!-- Botón Flotante -->
    <Transition name="fab">
      <button
        v-if="!isOpen"
        @click="openChat"
        class="chat-fab"
        aria-label="Abrir chat de soporte"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>

        <!-- Badge mensajes no leídos -->
        <Transition name="badge">
          <span v-if="unreadCount > 0" class="unread-badge">
            {{ unreadCount > 99 ? '99+' : unreadCount }}
          </span>
        </Transition>

        <!-- Pulso cuando el bot está "escribiendo" sin chat abierto -->
        <span v-if="pendingBotReply" class="fab-pulse"></span>
      </button>
    </Transition>

    <!-- Ventana del Chat -->
    <Transition name="chat-window">
      <div v-if="isOpen" class="chat-window" role="dialog" aria-label="Chat de soporte">

        <!-- Header -->
        <div class="chat-header">
          <div class="flex items-center gap-3">
            <div class="bot-avatar">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
            <div>
              <p class="font-semibold text-sm leading-tight">Asistente IES</p>
              <div class="flex items-center gap-1.5">
                <span class="status-dot" :class="isTyping ? 'status-typing' : 'status-online'"></span>
                <span class="text-xs opacity-75">{{ isTyping ? 'Escribiendo...' : 'En línea' }}</span>
              </div>
            </div>
          </div>
          <div class="flex items-center gap-1">
            <button @click="clearChat" class="header-btn" title="Limpiar chat">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
            <button @click="closeChat" class="header-btn" title="Cerrar chat">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Área de Mensajes -->
        <div ref="messagesContainer" class="messages-area">

          <!-- Mensaje de bienvenida -->
          <div class="message-row bot-row">
            <div class="message-bubble bot-bubble">
              <p class="whitespace-pre-wrap">{{ welcomeMessage }}</p>
              <time class="message-time">{{ formatTime(new Date()) }}</time>
            </div>
          </div>

          <!-- Mensajes del historial -->
          <TransitionGroup name="message">
            <div
              v-for="msg in messages"
              :key="msg.id"
              class="message-row"
              :class="msg.isOwn ? 'user-row' : 'bot-row'"
            >
              <div class="message-bubble" :class="msg.isOwn ? 'user-bubble' : 'bot-bubble'">
                <p class="whitespace-pre-wrap">{{ msg.text }}</p>
                <time class="message-time" :class="msg.isOwn ? 'text-blue-100' : ''">{{ msg.time }}</time>
              </div>
            </div>
          </TransitionGroup>

          <!-- Indicador de escritura -->
          <Transition name="typing">
            <div v-if="isTyping" class="message-row bot-row">
              <div class="message-bubble bot-bubble typing-bubble">
                <span></span><span></span><span></span>
              </div>
            </div>
          </Transition>
        </div>

        <!-- Sugerencias rápidas -->
        <div v-if="messages.length === 0 && quickReplies.length" class="quick-replies">
          <button
            v-for="reply in quickReplies"
            :key="reply"
            @click="sendQuickReply(reply)"
            class="quick-reply-btn"
          >
            {{ reply }}
          </button>
        </div>

        <!-- Input Area -->
        <div class="chat-input-area">
          <textarea
            ref="inputRef"
            v-model="inputText"
            @keydown.enter.exact.prevent="sendMessage"
            @keydown.enter.shift.exact="addNewline"
            @input="autoResizeTextarea"
            placeholder="Escribe un mensaje... (Enter para enviar)"
            class="chat-textarea"
            rows="1"
            :disabled="isSending"
            aria-label="Mensaje"
          ></textarea>
          <button
            @click="sendMessage"
            :disabled="isSending || !inputText.trim()"
            class="send-btn"
            aria-label="Enviar mensaje"
          >
            <svg v-if="!isSending" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
            </svg>
            <svg v-else class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
          </button>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, nextTick, onMounted } from 'vue';
import { useChatbot } from '@/Composables/useChatbot.js';

const props = defineProps({
  user: { type: Object, default: null }
});

// ─── Composable ──────────────────────────────────────────────────────────────
const { welcomeMessage, quickReplies, generateBotResponse } = useChatbot(props.user);

// ─── Estado ──────────────────────────────────────────────────────────────────
const isOpen         = ref(false);
const isTyping       = ref(false);
const isSending      = ref(false);
const unreadCount    = ref(0);
const pendingBotReply = ref(false);
const messages       = ref([]);
const inputText      = ref('');
let   messageId      = 0;

// ─── Refs del DOM ─────────────────────────────────────────────────────────────
const messagesContainer = ref(null);
const inputRef          = ref(null);

// ─── Helpers ──────────────────────────────────────────────────────────────────
const formatTime = (date) =>
  new Intl.DateTimeFormat('es-AR', { hour: '2-digit', minute: '2-digit' }).format(date);

const scrollToBottom = async () => {
  await nextTick();
  if (messagesContainer.value) {
    messagesContainer.value.scrollTo({
      top: messagesContainer.value.scrollHeight,
      behavior: 'smooth'
    });
  }
};

const autoResizeTextarea = (e) => {
  const ta = e?.target ?? inputRef.value;
  if (!ta) return;
  ta.style.height = 'auto';
  ta.style.height = Math.min(ta.scrollHeight, 120) + 'px';
};

const resetTextarea = () => {
  if (inputRef.value) {
    inputRef.value.style.height = 'auto';
  }
};

// ─── Acciones ─────────────────────────────────────────────────────────────────
const openChat = () => {
  isOpen.value = true;
  unreadCount.value = 0;
  pendingBotReply.value = false;
  nextTick(() => inputRef.value?.focus());
};

const closeChat = () => {
  isOpen.value = false;
};

const clearChat = () => {
  messages.value = [];
  unreadCount.value = 0;
};

const addNewline = () => {
  // Shift+Enter agrega salto de línea (comportamiento nativo)
};

const sendMessage = async () => {
  const text = inputText.value.trim();
  if (!text || isSending.value) return;

  isSending.value = true;

  // Agregar mensaje del usuario
  messages.value.push({
    id: ++messageId,
    text,
    isOwn: true,
    time: formatTime(new Date())
  });

  inputText.value = '';
  resetTextarea();
  await scrollToBottom();

  isSending.value = false;

  // Respuesta del bot con delay realista
  await getBotResponse(text);
};

const sendQuickReply = (text) => {
  inputText.value = text;
  sendMessage();
};

const getBotResponse = async (userText) => {
  isTyping.value = true;
  pendingBotReply.value = true;
  await scrollToBottom();

  // Simulamos tiempo de "pensamiento" variable (1–2.5s)
  const delay = 1000 + Math.random() * 1500;
  await new Promise(r => setTimeout(r, delay));

  const responseText = generateBotResponse(userText);

  messages.value.push({
    id: ++messageId,
    text: responseText,
    isOwn: false,
    time: formatTime(new Date())
  });

  isTyping.value = false;
  pendingBotReply.value = false;

  if (!isOpen.value) unreadCount.value++;

  await scrollToBottom();
};
</script>

<style scoped>
/* ─── FAB ─────────────────────────────────────────────────────────────── */
.chat-fab {
  position: relative;
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 20px rgba(37, 99, 235, 0.45);
  cursor: pointer;
  border: none;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.chat-fab:hover {
  transform: scale(1.08);
  box-shadow: 0 6px 28px rgba(37, 99, 235, 0.55);
}

.unread-badge {
  position: absolute;
  top: -4px;
  right: -4px;
  background: #ef4444;
  color: white;
  font-size: 11px;
  font-weight: 700;
  min-width: 20px;
  height: 20px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 4px;
  border: 2px solid white;
}

.fab-pulse {
  position: absolute;
  top: -2px;
  right: -2px;
  width: 14px;
  height: 14px;
  background: #22c55e;
  border-radius: 50%;
  border: 2px solid white;
  animation: pulse-ring 1.4s ease infinite;
}
@keyframes pulse-ring {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.3); opacity: 0.7; }
}

/* ─── CHAT WINDOW ─────────────────────────────────────────────────────── */
.chat-window {
  position: absolute;
  bottom: 68px;
  right: 0;
  width: 380px;
  max-height: 580px;
  background: white;
  border-radius: 16px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 0 4px 16px rgba(0, 0, 0, 0.08);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

/* ─── HEADER ──────────────────────────────────────────────────────────── */
.chat-header {
  background: linear-gradient(135deg, #1e40af, #2563eb);
  color: white;
  padding: 14px 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-shrink: 0;
}

.bot-avatar {
  width: 38px;
  height: 38px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.status-dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  display: inline-block;
}
.status-online  { background: #4ade80; }
.status-typing  { background: #facc15; animation: pulse-ring 1s infinite; }

.header-btn {
  background: transparent;
  border: none;
  color: rgba(255, 255, 255, 0.8);
  padding: 6px;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.15s, color 0.15s;
}
.header-btn:hover {
  background: rgba(255, 255, 255, 0.15);
  color: white;
}

/* ─── MESSAGES ────────────────────────────────────────────────────────── */
.messages-area {
  flex: 1;
  overflow-y: auto;
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  background: #f8fafc;
  scroll-behavior: smooth;
}

.messages-area::-webkit-scrollbar       { width: 5px; }
.messages-area::-webkit-scrollbar-track { background: transparent; }
.messages-area::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

.message-row {
  display: flex;
  width: 100%;
}
.bot-row  { justify-content: flex-start; }
.user-row { justify-content: flex-end; }

.message-bubble {
  max-width: 78%;
  padding: 10px 14px;
  border-radius: 16px;
  font-size: 14px;
  line-height: 1.5;
  word-break: break-word;
}

.bot-bubble {
  background: white;
  color: #1e293b;
  border-bottom-left-radius: 4px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
}

.user-bubble {
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  color: white;
  border-bottom-right-radius: 4px;
}

.message-time {
  display: block;
  font-size: 11px;
  color: #94a3b8;
  margin-top: 4px;
  text-align: right;
}

/* ─── TYPING INDICATOR ────────────────────────────────────────────────── */
.typing-bubble {
  display: flex;
  align-items: center;
  gap: 5px;
  padding: 12px 16px;
}
.typing-bubble span {
  width: 7px;
  height: 7px;
  background: #94a3b8;
  border-radius: 50%;
  animation: typing-dot 1.3s infinite;
}
.typing-bubble span:nth-child(2) { animation-delay: 0.2s; }
.typing-bubble span:nth-child(3) { animation-delay: 0.4s; }

@keyframes typing-dot {
  0%, 60%, 100% { transform: translateY(0); opacity: 0.5; }
  30%           { transform: translateY(-5px); opacity: 1; }
}

/* ─── QUICK REPLIES ───────────────────────────────────────────────────── */
.quick-replies {
  padding: 8px 16px 4px;
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  background: #f8fafc;
  border-top: 1px solid #e2e8f0;
}
.quick-reply-btn {
  background: white;
  border: 1px solid #cbd5e1;
  color: #2563eb;
  font-size: 12px;
  padding: 5px 12px;
  border-radius: 20px;
  cursor: pointer;
  transition: all 0.15s;
  white-space: nowrap;
}
.quick-reply-btn:hover {
  background: #eff6ff;
  border-color: #93c5fd;
}

/* ─── INPUT AREA ──────────────────────────────────────────────────────── */
.chat-input-area {
  display: flex;
  align-items: flex-end;
  gap: 8px;
  padding: 12px 16px;
  background: white;
  border-top: 1px solid #e2e8f0;
  flex-shrink: 0;
}

.chat-textarea {
  flex: 1;
  resize: none;
  border: 1.5px solid #e2e8f0;
  border-radius: 12px;
  padding: 10px 14px;
  font-size: 14px;
  color: #1e293b;
  background: #f8fafc;
  outline: none;
  transition: border-color 0.2s;
  line-height: 1.5;
  max-height: 120px;
  overflow-y: auto;
  font-family: inherit;
}
.chat-textarea:focus {
  border-color: #2563eb;
  background: white;
}
.chat-textarea::placeholder { color: #94a3b8; }
.chat-textarea:disabled { opacity: 0.5; }

.send-btn {
  width: 42px;
  height: 42px;
  flex-shrink: 0;
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  color: white;
  border: none;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: transform 0.15s, box-shadow 0.15s;
}
.send-btn:hover:not(:disabled) {
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
}
.send-btn:disabled {
  background: #cbd5e1;
  cursor: not-allowed;
}

/* ─── TRANSITIONS ─────────────────────────────────────────────────────── */
.fab-enter-active, .fab-leave-active   { transition: transform 0.25s ease, opacity 0.25s ease; }
.fab-enter-from, .fab-leave-to         { transform: scale(0.5); opacity: 0; }

.chat-window-enter-active { transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.25s ease; }
.chat-window-leave-active { transition: transform 0.2s ease, opacity 0.2s ease; }
.chat-window-enter-from   { transform: scale(0.85) translateY(16px); opacity: 0; transform-origin: bottom right; }
.chat-window-leave-to     { transform: scale(0.9) translateY(8px);  opacity: 0; transform-origin: bottom right; }

.message-enter-active { transition: transform 0.25s ease, opacity 0.25s ease; }
.message-enter-from   { transform: translateY(10px); opacity: 0; }

.typing-enter-active  { transition: opacity 0.2s ease; }
.typing-enter-from    { opacity: 0; }
.typing-leave-active  { transition: opacity 0.15s ease; }
.typing-leave-to      { opacity: 0; }

.badge-enter-active { transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s; }
.badge-enter-from   { transform: scale(0); opacity: 0; }
.badge-leave-active { transition: transform 0.15s ease, opacity 0.15s; }
.badge-leave-to     { transform: scale(0); opacity: 0; }
</style>