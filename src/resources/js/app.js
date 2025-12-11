import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import api from './services/api';
import BookingForm from './components/BookingForm.vue';
import BookingActions from './components/vendor/BookingActions.vue';
import ToastContainer from './components/common/ToastContainer.vue';
import ModalContainer from './components/common/ModalContainer.vue';
import { useToastStore } from './stores/toast';
import { useModalStore } from './stores/modal';

// Pinia 인스턴스 생성
const pinia = createPinia();

// API를 전역으로 노출 (Vue 컴포넌트에서 사용)
window.api = api;

// 전역 토스트 함수
let toastStore = null;
const getToastStore = () => {
    if (!toastStore) {
        toastStore = useToastStore(pinia);
    }
    return toastStore;
};

window.$toast = {
    success: (message, duration) => getToastStore().success(message, duration),
    error: (message, duration) => getToastStore().error(message, duration),
    warning: (message, duration) => getToastStore().warning(message, duration),
    info: (message, duration) => getToastStore().info(message, duration),
    show: (type, message, duration) => getToastStore().show(type, message, duration),
    clear: () => getToastStore().clear(),
};

// 전역 모달 함수
let modalStore = null;
const getModalStore = () => {
    if (!modalStore) {
        modalStore = useModalStore(pinia);
    }
    return modalStore;
};

window.$modal = {
    confirm: (options) => getModalStore().confirm(options),
    confirmDelete: (options) => getModalStore().confirmDelete(options),
    confirmSuccess: (options) => getModalStore().confirmSuccess(options),
    alert: (options) => getModalStore().alert(options),
    close: () => getModalStore().close(),
    setLoading: (loading) => getModalStore().setLoading(loading),
};

// 앱 설정 헬퍼 함수
const setupApp = (app) => {
    app.use(pinia);
    app.component('ToastContainer', ToastContainer);
    app.component('ModalContainer', ModalContainer);
    app.config.globalProperties.$api = api;
    app.config.globalProperties.$toast = getToastStore();
    app.config.globalProperties.$modal = getModalStore();
    return app;
};

// 전역 Vue 인스턴스 설정 함수
window.createVueApp = (component, selector, props = {}) => {
    const app = setupApp(createApp(component, props));
    app.mount(selector);
    return app;
};

// Auto-mount components
document.addEventListener('DOMContentLoaded', () => {
    // Booking Form
    const bookingContainer = document.getElementById('booking-form-container');
    if (bookingContainer) {
        const props = {
            productId: parseInt(bookingContainer.dataset.productId),
            bookingType: bookingContainer.dataset.bookingType,
            adultPrice: parseInt(bookingContainer.dataset.adultPrice || 0),
            childPrice: parseInt(bookingContainer.dataset.childPrice || 0),
            schedules: JSON.parse(bookingContainer.dataset.schedules || '[]'),
        };

        setupApp(createApp(BookingForm, props)).mount('#booking-form-container');
    }

    // Booking Actions (Vendor)
    document.querySelectorAll('[data-booking-actions]').forEach((el) => {
        const props = {
            bookingId: parseInt(el.dataset.bookingId),
            status: el.dataset.bookingStatus,
        };

        setupApp(createApp(BookingActions, props)).mount(el);
    });
});

// Export api for direct usage
export { api };
