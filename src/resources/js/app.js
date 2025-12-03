import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';

// Vue 컴포넌트들을 필요한 곳에서 동적으로 마운트
const pinia = createPinia();

// 전역 Vue 인스턴스 설정 함수
window.createVueApp = (component, selector, props = {}) => {
    const app = createApp(component, props);
    app.use(pinia);
    app.mount(selector);
    return app;
};
