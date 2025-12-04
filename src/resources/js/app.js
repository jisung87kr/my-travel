import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import api from './plugins/axios';
import BookingForm from './components/BookingForm.vue';

// Pinia 인스턴스 생성
const pinia = createPinia();

// 전역 Vue 인스턴스 설정 함수
window.createVueApp = (component, selector, props = {}) => {
    const app = createApp(component, props);
    app.use(pinia);
    app.config.globalProperties.$api = api;
    app.mount(selector);
    return app;
};

// Auto-mount booking form
document.addEventListener('DOMContentLoaded', () => {
    const bookingContainer = document.getElementById('booking-form-container');
    if (bookingContainer) {
        const props = {
            productId: parseInt(bookingContainer.dataset.productId),
            bookingType: bookingContainer.dataset.bookingType,
            adultPrice: parseInt(bookingContainer.dataset.adultPrice || 0),
            childPrice: parseInt(bookingContainer.dataset.childPrice || 0),
            schedules: JSON.parse(bookingContainer.dataset.schedules || '[]'),
        };

        const app = createApp(BookingForm, props);
        app.use(pinia);
        app.config.globalProperties.$api = api;
        app.mount('#booking-form-container');
    }
});

// Export api for direct usage
export { api };
