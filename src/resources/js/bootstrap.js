import axios from 'axios';
import { toggleWishlist, toggleMainWishlist, removeFromWishlist } from './utils/wishlist';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

// API 서비스 전역 등록

// Wishlist 헬퍼 함수 전역 등록
window.toggleWishlist = toggleWishlist;
window.toggleMainWishlist = toggleMainWishlist;
window.removeFromWishlist = removeFromWishlist;
