<template>
    <Teleport to="body">
        <div class="fixed top-20 right-4 z-50 flex flex-col gap-3 pointer-events-none">
            <TransitionGroup name="toast">
                <div
                    v-for="toast in toasts"
                    :key="toast.id"
                    class="pointer-events-auto max-w-sm w-full bg-white rounded-xl shadow-lg border overflow-hidden"
                    :class="borderClass[toast.type]"
                >
                    <div class="p-4">
                        <div class="flex items-start gap-3">
                            <!-- Icon -->
                            <div
                                class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center"
                                :class="iconBgClass[toast.type]"
                            >
                                <component
                                    :is="icons[toast.type]"
                                    class="w-5 h-5"
                                    :class="iconClass[toast.type]"
                                />
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900">
                                    {{ titles[toast.type] }}
                                </p>
                                <p class="mt-1 text-sm text-slate-600">
                                    {{ toast.message }}
                                </p>
                            </div>

                            <!-- Close Button -->
                            <button
                                @click="remove(toast.id)"
                                class="flex-shrink-0 p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div
                        v-if="toast.duration > 0"
                        class="h-1 origin-left"
                        :class="progressClass[toast.type]"
                        :style="{ animation: `shrink ${toast.duration}ms linear forwards` }"
                    ></div>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<script setup>
import { computed, h } from 'vue';
import { useToastStore } from '../../stores/toast';

const toastStore = useToastStore();

const toasts = computed(() => toastStore.toasts);

function remove(id) {
    toastStore.remove(id);
}

// 타입별 스타일
const titles = {
    success: '성공',
    error: '오류',
    warning: '주의',
    info: '알림',
};

const borderClass = {
    success: 'border-emerald-200',
    error: 'border-red-200',
    warning: 'border-amber-200',
    info: 'border-blue-200',
};

const iconBgClass = {
    success: 'bg-emerald-100',
    error: 'bg-red-100',
    warning: 'bg-amber-100',
    info: 'bg-blue-100',
};

const iconClass = {
    success: 'text-emerald-600',
    error: 'text-red-600',
    warning: 'text-amber-600',
    info: 'text-blue-600',
};

const progressClass = {
    success: 'bg-emerald-500',
    error: 'bg-red-500',
    warning: 'bg-amber-500',
    info: 'bg-blue-500',
};

// 아이콘 컴포넌트
const SuccessIcon = {
    render() {
        return h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', {
                'stroke-linecap': 'round',
                'stroke-linejoin': 'round',
                'stroke-width': '2',
                d: 'M5 13l4 4L19 7',
            }),
        ]);
    },
};

const ErrorIcon = {
    render() {
        return h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', {
                'stroke-linecap': 'round',
                'stroke-linejoin': 'round',
                'stroke-width': '2',
                d: 'M6 18L18 6M6 6l12 12',
            }),
        ]);
    },
};

const WarningIcon = {
    render() {
        return h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', {
                'stroke-linecap': 'round',
                'stroke-linejoin': 'round',
                'stroke-width': '2',
                d: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
            }),
        ]);
    },
};

const InfoIcon = {
    render() {
        return h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', {
                'stroke-linecap': 'round',
                'stroke-linejoin': 'round',
                'stroke-width': '2',
                d: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            }),
        ]);
    },
};

const icons = {
    success: SuccessIcon,
    error: ErrorIcon,
    warning: WarningIcon,
    info: InfoIcon,
};
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}

.toast-enter-from {
    opacity: 0;
    transform: translateX(100%);
}

.toast-leave-to {
    opacity: 0;
    transform: translateX(100%);
}

.toast-move {
    transition: transform 0.3s ease;
}

@keyframes shrink {
    from {
        transform: scaleX(1);
    }
    to {
        transform: scaleX(0);
    }
}
</style>