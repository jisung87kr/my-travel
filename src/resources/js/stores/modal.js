import { defineStore } from 'pinia';

export const useModalStore = defineStore('modal', {
    state: () => ({
        isOpen: false,
        title: '',
        description: '',
        confirmText: '확인',
        cancelText: '취소',
        variant: 'primary', // primary, success, danger
        icon: 'check', // check, x, ban, complete, info, warning
        warning: '',
        loading: false,
        showCancel: true,
        resolve: null,
        reject: null,
    }),

    actions: {
        /**
         * 확인 모달 표시 (Promise 반환)
         * @param {Object} options - 모달 옵션
         * @returns {Promise<boolean>} - 확인: true, 취소: false
         */
        confirm(options = {}) {
            return new Promise((resolve, reject) => {
                this.title = options.title || '확인';
                this.description = options.description || '';
                this.confirmText = options.confirmText || '확인';
                this.cancelText = options.cancelText || '취소';
                this.variant = options.variant || 'primary';
                this.icon = options.icon || 'check';
                this.warning = options.warning || '';
                this.showCancel = options.showCancel !== false;
                this.loading = false;
                this.resolve = resolve;
                this.reject = reject;
                this.isOpen = true;
            });
        },

        /**
         * 삭제 확인 모달 (danger 스타일)
         */
        confirmDelete(options = {}) {
            return this.confirm({
                title: options.title || '삭제 확인',
                description: options.description || '정말 삭제하시겠습니까? 이 작업은 되돌릴 수 없습니다.',
                confirmText: options.confirmText || '삭제',
                variant: 'danger',
                icon: 'x',
                ...options,
            });
        },

        /**
         * 성공 확인 모달
         */
        confirmSuccess(options = {}) {
            return this.confirm({
                variant: 'success',
                icon: 'check',
                ...options,
            });
        },

        /**
         * 알림 모달 (확인 버튼만)
         */
        alert(options = {}) {
            return this.confirm({
                showCancel: false,
                confirmText: options.confirmText || '확인',
                ...options,
            });
        },

        /**
         * 모달 확인 처리
         */
        handleConfirm() {
            if (this.resolve) {
                this.resolve(true);
            }
            this.close();
        },

        /**
         * 모달 취소 처리
         */
        handleCancel() {
            if (this.resolve) {
                this.resolve(false);
            }
            this.close();
        },

        /**
         * 로딩 상태 설정
         */
        setLoading(loading) {
            this.loading = loading;
        },

        /**
         * 모달 닫기
         */
        close() {
            this.isOpen = false;
            this.loading = false;
            this.resolve = null;
            this.reject = null;
        },
    },
});
