import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import BookingForm from './components/BookingForm.vue';

// Vue 컴포넌트들을 필요한 곳에서 동적으로 마운트
const pinia = createPinia();

// 전역 Vue 인스턴스 설정 함수
window.createVueApp = (component, selector, props = {}) => {
    const app = createApp(component, props);
    app.use(pinia);
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
        app.mount('#booking-form-container');
    }
});
