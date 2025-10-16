import { ref } from 'vue';

// Estado global del diálogo
const isOpen = ref(false);
const dialogType = ref('confirm'); // 'confirm', 'alert', 'prompt'
const dialogTitle = ref('');
const dialogMessage = ref('');
const dialogResolve = ref(null);
const dialogReject = ref(null);
const promptValue = ref('');
const promptPlaceholder = ref('');

export function useDialog() {
    const confirm = (message, title = '¿Estás seguro?') => {
        return new Promise((resolve) => {
            dialogType.value = 'confirm';
            dialogTitle.value = title;
            dialogMessage.value = message;
            dialogResolve.value = resolve;
            isOpen.value = true;
        });
    };

    const alert = (message, title = 'Información') => {
        return new Promise((resolve) => {
            dialogType.value = 'alert';
            dialogTitle.value = title;
            dialogMessage.value = message;
            dialogResolve.value = resolve;
            isOpen.value = true;
        });
    };

    const prompt = (message, title = 'Ingrese un valor', placeholder = '') => {
        return new Promise((resolve) => {
            dialogType.value = 'prompt';
            dialogTitle.value = title;
            dialogMessage.value = message;
            promptPlaceholder.value = placeholder;
            promptValue.value = '';
            dialogResolve.value = resolve;
            isOpen.value = true;
        });
    };

    const handleConfirm = () => {
        if (dialogType.value === 'prompt') {
            dialogResolve.value(promptValue.value);
        } else {
            dialogResolve.value(true);
        }
        close();
    };

    const handleCancel = () => {
        if (dialogType.value === 'prompt') {
            dialogResolve.value(null);
        } else {
            dialogResolve.value(false);
        }
        close();
    };

    const close = () => {
        isOpen.value = false;
        setTimeout(() => {
            dialogResolve.value = null;
            dialogReject.value = null;
            promptValue.value = '';
        }, 300);
    };

    return {
        // Estado
        isOpen,
        dialogType,
        dialogTitle,
        dialogMessage,
        promptValue,
        promptPlaceholder,

        // Métodos
        confirm,
        alert,
        prompt,
        handleConfirm,
        handleCancel,
        close
    };
}
