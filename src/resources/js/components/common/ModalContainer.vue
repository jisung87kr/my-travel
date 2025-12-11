<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="modal.isOpen"
                class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
                @click.self="handleCancel"
            >
                <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl mx-4">
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0"
                            :class="iconBgClass"
                        >
                            <component :is="iconComponent" class="w-6 h-6" :class="iconClass" />
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">{{ modal.title }}</h3>
                            <p v-if="modal.description" class="text-sm text-slate-500">{{ modal.description }}</p>
                        </div>
                    </div>

                    <slot name="body"></slot>

                    <div v-if="modal.warning" class="mb-6">
                        <p class="text-sm text-slate-600 bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <svg class="w-5 h-5 text-amber-500 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ modal.warning }}
                        </p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button
                            v-if="modal.showCancel"
                            type="button"
                            @click="handleCancel"
                            class="px-5 py-2.5 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all"
                            :disabled="modal.loading"
                        >
                            {{ modal.cancelText }}
                        </button>
                        <button
                            type="button"
                            @click="handleConfirm"
                            class="px-5 py-2.5 text-white rounded-xl font-medium shadow-lg transition-all disabled:opacity-50"
                            :class="confirmButtonClass"
                            :disabled="modal.loading"
                        >
                            <svg v-if="modal.loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ modal.confirmText }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { computed, h, onMounted, onUnmounted } from 'vue';
import { useModalStore } from '../../stores/modal';

const modal = useModalStore();

function handleConfirm() {
    modal.handleConfirm();
}

function handleCancel() {
    if (!modal.loading) {
        modal.handleCancel();
    }
}

// 스타일 클래스
const iconBgClass = computed(() => {
    const classes = {
        primary: 'bg-violet-100',
        success: 'bg-emerald-100',
        danger: 'bg-red-100',
    };
    return classes[modal.variant] || classes.primary;
});

const iconClass = computed(() => {
    const classes = {
        primary: 'text-violet-600',
        success: 'text-emerald-600',
        danger: 'text-red-600',
    };
    return classes[modal.variant] || classes.primary;
});

const confirmButtonClass = computed(() => {
    const classes = {
        primary: 'bg-gradient-to-r from-violet-600 to-violet-700 hover:from-violet-700 hover:to-violet-800 shadow-violet-500/30',
        success: 'bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 shadow-emerald-500/30',
        danger: 'bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 shadow-red-500/30',
    };
    return classes[modal.variant] || classes.primary;
});

// 아이콘 컴포넌트
const CheckIcon = {
    render() {
        return h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M5 13l4 4L19 7' })
        ]);
    }
};

const XIcon = {
    render() {
        return h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M6 18L18 6M6 6l12 12' })
        ]);
    }
};

const BanIcon = {
    render() {
        return h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' })
        ]);
    }
};

const CompleteIcon = {
    render() {
        return h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' })
        ]);
    }
};

const InfoIcon = {
    render() {
        return h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' })
        ]);
    }
};

const WarningIcon = {
    render() {
        return h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' })
        ]);
    }
};

const iconComponent = computed(() => {
    const icons = {
        check: CheckIcon,
        x: XIcon,
        ban: BanIcon,
        complete: CompleteIcon,
        info: InfoIcon,
        warning: WarningIcon,
    };
    return icons[modal.icon] || CheckIcon;
});

// Escape 키 처리
function handleKeydown(e) {
    if (e.key === 'Escape' && modal.isOpen && !modal.loading) {
        handleCancel();
    }
}

onMounted(() => {
    document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown);
});
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}

.modal-enter-active > div,
.modal-leave-active > div {
    transition: transform 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-from > div,
.modal-leave-to > div {
    transform: scale(0.95);
}
</style>
