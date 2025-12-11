import { defineStore } from 'pinia';

let toastId = 0;

export const useToastStore = defineStore('toast', {
    state: () => ({
        toasts: [],
    }),

    actions: {
        /**
         * 토스트 표시
         * @param {string} type - 'success' | 'error' | 'warning' | 'info'
         * @param {string} message - 표시할 메시지
         * @param {number} duration - 자동 닫힘 시간 (ms), 0이면 자동 닫힘 없음
         */
        show(type, message, duration = 3000) {
            const id = ++toastId;

            this.toasts.push({
                id,
                type,
                message,
                duration,
            });

            // 자동 제거
            if (duration > 0) {
                setTimeout(() => {
                    this.remove(id);
                }, duration);
            }

            return id;
        },

        success(message, duration = 3000) {
            return this.show('success', message, duration);
        },

        error(message, duration = 5000) {
            return this.show('error', message, duration);
        },

        warning(message, duration = 4000) {
            return this.show('warning', message, duration);
        },

        info(message, duration = 3000) {
            return this.show('info', message, duration);
        },

        remove(id) {
            const index = this.toasts.findIndex((toast) => toast.id === id);
            if (index > -1) {
                this.toasts.splice(index, 1);
            }
        },

        clear() {
            this.toasts = [];
        },
    },
});