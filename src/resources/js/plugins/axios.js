import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
    withCredentials: true,
});

// CSRF 토큰 자동 설정
api.interceptors.request.use((config) => {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (token) {
        config.headers['X-CSRF-TOKEN'] = token;
    }
    return config;
});

// 응답 인터셉터 (에러 핸들링)
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // 인증 만료 시 로그인 페이지로 이동
            window.location.href = '/login';
        }
        if (error.response?.status === 419) {
            // CSRF 토큰 만료 시 페이지 새로고침
            window.location.reload();
        }
        if (error.response?.status === 422) {
            // 유효성 검사 에러는 그대로 전달
            return Promise.reject(error);
        }
        if (error.response?.status === 429) {
            // Rate limit 초과
            console.error('Too many requests. Please try again later.');
        }
        return Promise.reject(error);
    }
);

export default api;
