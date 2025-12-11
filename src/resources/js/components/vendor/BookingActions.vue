<template>
    <div class="flex items-center justify-end gap-2">
        <a
            :href="`/vendor/bookings/${bookingId}`"
            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-violet-600 hover:text-violet-700 hover:bg-violet-50 rounded-lg transition-all"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            상세
        </a>

        <template v-if="status === 'pending'">
            <button
                type="button"
                @click="showApproveModal = true"
                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-all"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                승인
            </button>
            <button
                type="button"
                @click="showRejectModal = true"
                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                거절
            </button>
        </template>

        <template v-else-if="status === 'confirmed'">
            <button
                type="button"
                @click="showCompleteModal = true"
                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-all"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                완료
            </button>
            <button
                type="button"
                @click="showNoShowModal = true"
                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                노쇼
            </button>
        </template>

        <!-- Approve Modal -->
        <ConfirmModal
            v-model="showApproveModal"
            title="예약 승인"
            description="이 예약을 승인하시겠습니까?"
            confirm-text="승인하기"
            variant="success"
            icon="check"
            :loading="loading"
            @confirm="handleApprove"
        />

        <!-- Reject Modal -->
        <ConfirmModal
            v-model="showRejectModal"
            title="예약 거절"
            description="이 예약을 거절하시겠습니까?"
            confirm-text="거절하기"
            variant="danger"
            icon="x"
            :loading="loading"
            @confirm="handleReject"
        >
            <template #body>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">거절 사유</label>
                    <textarea
                        v-model="rejectReason"
                        rows="3"
                        placeholder="거절 사유를 입력하세요..."
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 focus:bg-white transition-all resize-none"
                    ></textarea>
                </div>
            </template>
        </ConfirmModal>

        <!-- Complete Modal -->
        <ConfirmModal
            v-model="showCompleteModal"
            title="투어 완료"
            description="이 예약을 완료 처리하시겠습니까?"
            confirm-text="완료 처리"
            variant="success"
            icon="complete"
            :loading="loading"
            @confirm="handleComplete"
        />

        <!-- No Show Modal -->
        <ConfirmModal
            v-model="showNoShowModal"
            title="노쇼 처리"
            description="고객이 나타나지 않았습니까?"
            confirm-text="노쇼 처리"
            variant="danger"
            icon="ban"
            warning="노쇼 처리 시 고객에게 패널티가 부과될 수 있습니다."
            :loading="loading"
            @confirm="handleNoShow"
        />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import ConfirmModal from '../common/ConfirmModal.vue';

const props = defineProps({
    bookingId: {
        type: Number,
        required: true,
    },
    status: {
        type: String,
        required: true,
    },
});

const showApproveModal = ref(false);
const showRejectModal = ref(false);
const showCompleteModal = ref(false);
const showNoShowModal = ref(false);
const loading = ref(false);
const rejectReason = ref('');

async function handleApprove() {
    loading.value = true;
    try {
        await window.api.vendor.bookings.approve(props.bookingId);
        window.location.reload();
    } catch (error) {
        console.error('Approve error:', error);
        alert(error.response?.data?.message || '승인 처리 중 오류가 발생했습니다.');
        loading.value = false;
    }
}

async function handleReject() {
    loading.value = true;
    try {
        await window.api.vendor.bookings.reject(props.bookingId, { reason: rejectReason.value });
        window.location.reload();
    } catch (error) {
        console.error('Reject error:', error);
        alert(error.response?.data?.message || '거절 처리 중 오류가 발생했습니다.');
        loading.value = false;
    }
}

async function handleComplete() {
    loading.value = true;
    try {
        await window.api.vendor.bookings.complete(props.bookingId);
        window.location.reload();
    } catch (error) {
        console.error('Complete error:', error);
        alert(error.response?.data?.message || '완료 처리 중 오류가 발생했습니다.');
        loading.value = false;
    }
}

async function handleNoShow() {
    loading.value = true;
    try {
        await window.api.vendor.bookings.noShow(props.bookingId);
        window.location.reload();
    } catch (error) {
        console.error('No-show error:', error);
        alert(error.response?.data?.message || '노쇼 처리 중 오류가 발생했습니다.');
        loading.value = false;
    }
}
</script>
