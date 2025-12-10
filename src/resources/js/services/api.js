/**
 * API Service Module
 * 모든 API 엔드포인트를 중앙 집중화하여 관리
 */
import axios from 'axios';

// Axios 인스턴스 생성
const http = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
    withCredentials: true,
});

// CSRF 토큰 자동 설정
http.interceptors.request.use((config) => {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (token) {
        config.headers['X-CSRF-TOKEN'] = token;
    }
    return config;
});

// 응답 인터셉터
http.interceptors.response.use(
    (response) => response.data,
    (error) => {
        const status = error.response?.status;

        if (status === 401) {
            window.location.href = '/login';
        }
        if (status === 419) {
            window.location.reload();
        }
        if (status === 429) {
            console.error('요청이 너무 많습니다. 잠시 후 다시 시도해주세요.');
        }

        return Promise.reject(error);
    }
);

/**
 * API Endpoints
 */
const api = {
    // ==================== Public ====================
    products: {
        list: (params) => http.get('/products', { params }),
        show: (id) => http.get(`/products/${id}`),
    },

    // ==================== Traveler ====================
    wishlist: {
        toggle: (productId) => http.post(`/wishlist/${productId}`),
    },

    bookings: {
        list: () => http.get('/bookings'),
        show: (id) => http.get(`/bookings/${id}`),
        store: (data) => http.post('/bookings', data),
        cancel: (id) => http.delete(`/bookings/${id}`),
    },

    reviews: {
        store: (bookingId, data) => http.post(`/bookings/${bookingId}/review`, data),
        update: (id, data) => http.put(`/reviews/${id}`, data),
        destroy: (id) => http.delete(`/reviews/${id}`),
        report: (id, data) => http.post(`/reviews/${id}/report`, data),
    },

    messages: {
        conversations: () => http.get('/messages'),
        thread: (bookingId) => http.get(`/bookings/${bookingId}/messages`),
        send: (bookingId, data) => http.post(`/bookings/${bookingId}/messages`, data),
        markRead: (id) => http.patch(`/messages/${id}/read`),
    },

    // ==================== Vendor ====================
    vendor: {
        products: {
            list: (params) => http.get('/vendor/products', { params }),
            show: (id) => http.get(`/vendor/products/${id}`),
            store: (data) => http.post('/vendor/products', data),
            update: (id, data) => http.put(`/vendor/products/${id}`, data),
            destroy: (id) => http.delete(`/vendor/products/${id}`),
            submit: (id) => http.post(`/vendor/products/${id}/submit`),
            activate: (id) => http.post(`/vendor/products/${id}/activate`),
            deactivate: (id) => http.post(`/vendor/products/${id}/deactivate`),
            // Images
            uploadImage: (id, formData) => http.post(`/vendor/products/${id}/images`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            }),
            reorderImages: (id, data) => http.put(`/vendor/products/${id}/images/reorder`, data),
            deleteImage: (id, imageId) => http.delete(`/vendor/products/${id}/images/${imageId}`),
            // Schedules
            getSchedules: (id, params) => http.get(`/vendor/products/${id}/schedules`, { params }),
            updateSchedules: (id, data) => http.put(`/vendor/products/${id}/schedules`, data),
            createSchedule: (id, data) => http.post(`/vendor/products/${id}/schedules`, data),
            bulkSchedules: (id, data) => http.post(`/vendor/products/${id}/schedules/bulk`, data),
            openSchedule: (id, data) => http.post(`/vendor/products/${id}/schedules/open`, data),
            closeSchedule: (id, data) => http.post(`/vendor/products/${id}/schedules/close`, data),
        },
        bookings: {
            list: (params) => http.get('/vendor/bookings', { params }),
            show: (id) => http.get(`/vendor/bookings/${id}`),
            approve: (id) => http.patch(`/vendor/bookings/${id}/approve`),
            reject: (id, data) => http.patch(`/vendor/bookings/${id}/reject`, data),
            complete: (id) => http.patch(`/vendor/bookings/${id}/complete`),
            noShow: (id) => http.patch(`/vendor/bookings/${id}/no-show`),
        },
        reviews: {
            reply: (id, data) => http.post(`/vendor/reviews/${id}/reply`, data),
        },
    },

    // ==================== Guide ====================
    guide: {
        schedules: {
            events: (params) => http.get('/guide/schedules/events', { params }),
        },
        checkin: {
            lookup: (data) => http.post('/guide/checkin/lookup', data),
            store: (bookingId) => http.post(`/guide/checkin/${bookingId}`),
        },
        bookings: {
            start: (id) => http.post(`/guide/bookings/${id}/start`),
            complete: (id) => http.post(`/guide/bookings/${id}/complete`),
            noShow: (id) => http.post(`/guide/bookings/${id}/no-show`),
        },
    },
};

// HTTP 인스턴스도 내보내기 (커스텀 요청용)
api.http = http;

export default api;
