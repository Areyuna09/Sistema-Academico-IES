<template>
  <div class="fixed bottom-4 right-4 z-50">
    <!-- Botón Flotante -->
    <div
      v-if="!isOpen"
      @click="toggleChat"
      class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-5 shadow-xl cursor-pointer transition-all transform hover:scale-110 relative"
      style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;"
    >
      <!-- Icono de Chat -->
      <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
      </svg>
      
      <!-- Badge de mensajes no leídos -->
      <span v-if="unreadCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold">
        {{ unreadCount > 99 ? '99+' : unreadCount }}
      </span>
      
      <!-- Indicador de escritura -->
      <span v-if="isTyping" class="absolute -top-1 -right-1 bg-green-500 rounded-full h-4 w-4 animate-pulse"></span>
    </div>

    <!-- Ventana del Chat -->
    <div
      v-if="isOpen"
      class="bg-white rounded-lg shadow-2xl w-[450px] h-[600px] flex flex-col"
      style="position: absolute; bottom: 80px; right: 0;"
    >
      <!-- Header -->
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 rounded-t-lg flex items-center justify-between shadow-lg">
        <div class="flex items-center">
          <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
          <div class="ml-3">
            <h3 class="font-bold text-lg">Asistente de Soporte</h3>
            <p class="text-xs text-blue-100">Sistema Académico IES</p>
          </div>
        </div>
        <button
          @click="toggleChat"
          class="text-white hover:text-blue-200 transition-colors duration-200 p-2 rounded-lg hover:bg-blue-800"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Área de Mensajes -->
      <div 
        ref="messagesContainer"
        class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50 messages-area"
        style="max-height: 450px; display: flex; flex-direction: column; gap: 16px;"
      >
        <!-- Mensaje de bienvenida -->
        <div v-if="messages.length === 0" class="flex justify-start">
          <div class="max-w-xs lg:max-w-md order-1">
            <div class="bg-gray-100 rounded-lg p-3">
              <div class="flex items-center mb-1">
                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs mr-2">
                  C
                </div>
                <span class="text-xs text-gray-600">Chatbox</span>
              </div>
              <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ welcomeMessage }}</p>
              <p class="text-xs text-gray-500 mt-1">{{ getCurrentTime() }}</p>
            </div>
          </div>
        </div>

        <!-- Mensajes -->
        <div
          v-for="message in messages"
          :key="message.id"
          class="message-item"
        >
          <div :class="[
            'max-w-xs lg:max-w-md',
            message.is_own ? 'order-2' : 'order-1'
          ]">
            <!-- Mensaje del Asistente (siempre a la izquierda) -->
            <div v-if="!message.is_own" class="bg-gray-100 rounded-lg p-3">
              <div class="flex items-center mb-1">
                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs mr-2">
                  A
                </div>
                <span class="text-xs text-gray-600">Asistente</span>
              </div>
              <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ message.message }}</p>
              <p class="text-xs text-gray-500 mt-1">{{ message.created_at }}</p>
            </div>
            
            <!-- Mensaje del Usuario (siempre a la derecha) -->
            <div v-else class="bg-blue-600 text-white rounded-lg p-3">
              <p class="text-sm whitespace-pre-wrap">{{ message.message }}</p>
              <p class="text-xs text-blue-200 mt-1">{{ message.created_at }}</p>
            </div>
          </div>
        </div>

        <!-- Indicador de escritura -->
        <div v-if="isTyping" class="flex justify-start">
          <div class="bg-gray-100 rounded-lg p-3">
            <div class="flex items-center">
              <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs mr-2">
                C
              </div>
              <div class="flex space-x-1">
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
              </div>
            </div>
            <p class="text-xs text-gray-500 mt-1">Chatbox está escribiendo...</p>
          </div>
        </div>
      </div>

      <!-- Área de Input -->
      <div class="border-t border-gray-200 p-4 bg-white">
        <form @submit.prevent="sendMessage" class="flex items-end space-x-2">
          <div class="flex-1">
            <textarea
              v-model="messageText"
              @input="handleTyping"
              placeholder="Escribe tu mensaje..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
              rows="1"
              style="min-height: 40px; max-height: 100px;"
              :disabled="sending"
            ></textarea>
          </div>
          <button
            type="submit"
            :disabled="sending || !messageText.trim()"
            class="bg-blue-600 text-white p-4 rounded-lg hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition flex-shrink-0"
            style="min-height: 50px; min-width: 50px;"
          >
            <svg v-if="!sending" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
            </svg>
            <div v-else class="w-6 h-6 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick } from 'vue';
import { useChatbot } from '@/Composables/useChatbot.js';

const props = defineProps({
  user: Object,
});

// Usar el composable del chatbot
const { isTyping, messageIdCounter, welcomeMessage, getCurrentTime, generateBotResponse } = useChatbot();

// Estado local del componente
const isOpen = ref(false);
const messages = ref([]);
const messageText = ref('');
const sending = ref(false);
const unreadCount = ref(0);
const messagesContainer = ref(null);

const toggleChat = () => {
  isOpen.value = !isOpen.value;
  
  if (!isOpen.value) {
    // Limpiar estado solo al cerrar
    messages.value = [];
    messageText.value = '';
    unreadCount.value = 0;
  }
};

const sendMessage = async () => {
  if (!messageText.value.trim() || sending.value) return;
  
  sending.value = true;
  
  try {
    // Agregar mensaje del usuario
    const userMessage = {
      id: messageIdCounter.value++,
      message: messageText.value,
      is_own: true,
      created_at: getCurrentTime()
    };
    
    messages.value.push(userMessage);
    const userMessageText = messageText.value;
    messageText.value = '';
    
    // Auto-scroll después de enviar mensaje
    await nextTick();
    scrollToBottom();
    
    // Enviar respuesta automática del chatbot
    setTimeout(() => {
      sendBotResponse(userMessageText);
    }, 1000);
    
  } catch (error) {
    console.error('Error al enviar mensaje:', error);
  } finally {
    sending.value = false;
  }
};

const sendBotResponse = async (userMessage) => {
  isTyping.value = true;
  
  // Simular tiempo de "pensamiento" del bot
  await new Promise(resolve => setTimeout(resolve, 1500));
  
  try {
    // Generar respuesta usando el composable
    const botResponse = generateBotResponse(userMessage);
    
    // Agregar respuesta del bot
    const botMessage = {
      id: messageIdCounter.value++,
      message: botResponse,
      is_own: false,
      created_at: getCurrentTime()
    };
    
    messages.value.push(botMessage);
    
    // Ocultar indicador de escritura
    isTyping.value = false;
  } catch (error) {
    console.error('Error al enviar respuesta del bot:', error);
    isTyping.value = false;
  }
};

const handleTyping = () => {
  // Auto-resize textarea
  const textarea = event.target;
  textarea.style.height = 'auto';
  textarea.style.height = Math.min(textarea.scrollHeight, 100) + 'px';
};

const scrollToBottom = () => {
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
  }
};
</script>

<style scoped>
/* Animación de puntos para indicador de escritura */
@keyframes bounce {
  0%, 80%, 100% {
    transform: scale(0);
  }
  40% {
    transform: scale(1);
  }
}

.animate-bounce {
  animation: bounce 1.4s infinite;
}

/* Scrollbar personalizado */
.messages-area::-webkit-scrollbar {
  width: 8px;
}

.messages-area::-webkit-scrollbar-track {
  background: #f8f9fa;
  border-radius: 10px;
}

.messages-area::-webkit-scrollbar-thumb {
  background: #4b5560;
  border-radius: 10px;
}

.messages-area::-webkit-scrollbar-thumb:hover {
  background: #6b7280;
}

/* Estilos para mensajes individuales */
.message-item {
  display: flex;
  margin-bottom: 16px;
  flex-direction: column;
  gap: 12px;
  padding: 12px;
  border-radius: 8px;
  transition: all 0.2s ease;
}

.message-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.message-item:last-child {
  margin-bottom: 0;
}
</style>
