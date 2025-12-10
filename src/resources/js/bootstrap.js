import axios from 'axios';
import api from './services/api';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

// API 서비스 전역 등록
window.api = api;
