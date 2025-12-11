# Toast & Modal 사용 가이드

## Toast (토스트 알림)

자동으로 사라지는 비간섭적 알림 메시지입니다.

### 전역 사용 (window.$toast)

```javascript
// 성공 메시지 (3초 후 자동 닫힘)
$toast.success('저장되었습니다.');

// 에러 메시지 (5초 후 자동 닫힘)
$toast.error('오류가 발생했습니다.');

// 경고 메시지 (4초 후 자동 닫힘)
$toast.warning('주의가 필요합니다.');

// 정보 메시지 (3초 후 자동 닫힘)
$toast.info('새로운 알림이 있습니다.');

// 커스텀 duration (ms)
$toast.success('저장되었습니다.', 5000); // 5초
$toast.error('오류 발생', 0); // 자동 닫힘 없음

// 타입과 메시지 직접 지정
$toast.show('success', '완료되었습니다.', 3000);

// 모든 토스트 제거
$toast.clear();
```

### Vue 컴포넌트 내부에서 사용

```javascript
export default {
    methods: {
        handleSave() {
            this.$toast.success('저장되었습니다.');
        },
        handleError() {
            this.$toast.error('오류가 발생했습니다.');
        }
    }
}
```

### Blade 템플릿에서 사용

```html
<div id="app">
    <toast-container></toast-container>
</div>

<script type="module">
    createVueApp({
        mounted() {
            this.$toast.success('환영합니다!');
        }
    }, '#app');
</script>
```

---

## Modal (모달 다이얼로그)

사용자 확인이 필요한 모달 다이얼로그입니다.

### 전역 사용 (window.$modal)

```javascript
// 확인 모달
$modal.confirm({
    title: '확인',
    description: '진행하시겠습니까?'
}).then(confirmed => {
    if (confirmed) {
        // 확인 클릭
    } else {
        // 취소 클릭
    }
});

// 삭제 확인 모달 (빨간색 테마)
$modal.confirmDelete({
    title: '삭제 확인',
    description: '정말 삭제하시겠습니까? 이 작업은 되돌릴 수 없습니다.'
}).then(confirmed => {
    if (confirmed) {
        // 삭제 진행
    }
});

// 성공 확인 모달 (초록색 테마)
$modal.confirmSuccess({
    title: '완료',
    description: '작업이 완료되었습니다.'
}).then(() => {
    // 확인 클릭
});

// 단순 알림 모달
$modal.alert({
    title: '알림',
    description: '중요한 공지사항입니다.'
});

// 모달 닫기
$modal.close();

// 로딩 상태 설정
$modal.setLoading(true);  // 로딩 시작
$modal.setLoading(false); // 로딩 종료
```

### Vue 컴포넌트 내부에서 사용

```javascript
export default {
    methods: {
        async handleDelete() {
            const confirmed = await this.$modal.confirmDelete({
                title: '삭제 확인',
                description: '정말 삭제하시겠습니까?'
            });

            if (confirmed) {
                // 삭제 API 호출
                await this.$api.delete('/items/1');
                this.$toast.success('삭제되었습니다.');
            }
        }
    }
}
```

### Blade 템플릿에서 사용

```html
<div id="app">
    <toast-container></toast-container>
    <modal-container>
        <template v-slot:body></template>
    </modal-container>
</div>

<script type="module">
    createVueApp({
        methods: {
            confirmAction() {
                this.$modal.confirm({
                    title: '확인',
                    description: '진행하시겠습니까?'
                }).then(confirmed => {
                    if (confirmed) {
                        this.$toast.success('진행되었습니다.');
                    }
                });
            }
        }
    }, '#app');
</script>
```

---

## 옵션 상세

### Toast 옵션

| 메서드 | 기본 duration | 설명 |
|--------|---------------|------|
| `success(message, duration?)` | 3000ms | 성공 메시지 |
| `error(message, duration?)` | 5000ms | 에러 메시지 |
| `warning(message, duration?)` | 4000ms | 경고 메시지 |
| `info(message, duration?)` | 3000ms | 정보 메시지 |
| `show(type, message, duration?)` | 3000ms | 커스텀 타입 |
| `clear()` | - | 모든 토스트 제거 |

### Modal 옵션

| 옵션 | 타입 | 설명 |
|------|------|------|
| `title` | string | 모달 제목 |
| `description` | string | 모달 설명 |

---

## 필수 설정

### app.js에서 컴포넌트 등록

```javascript
import ToastContainer from './components/common/ToastContainer.vue';
import ModalContainer from './components/common/ModalContainer.vue';

const setupApp = (app) => {
    app.component('ToastContainer', ToastContainer);
    app.component('ModalContainer', ModalContainer);
    // ...
};
```

### Blade 레이아웃에서 컨테이너 추가

```html
<div id="app">
    <toast-container></toast-container>
    <modal-container>
        <template v-slot:body></template>
    </modal-container>

    <!-- 페이지 컨텐츠 -->
</div>
```

---

## 동적 모달 Body 슬롯 사용

모달 타입에 따라 다른 폼을 표시해야 할 때 슬롯 내부에서 `v-if`를 사용합니다.

### 주의사항

**❌ 잘못된 방법** - `v-if`로 `modal-container`를 감싸면 안됩니다:
```html
<!-- 이렇게 하면 모달이 열리지 않습니다! -->
<template v-if="showModal1">
    <modal-container>
        <template v-slot:body>폼 1</template>
    </modal-container>
</template>

<template v-if="showModal2">
    <modal-container>
        <template v-slot:body>폼 2</template>
    </modal-container>
</template>
```

**✅ 올바른 방법** - 하나의 `modal-container`에서 슬롯 내용만 조건부 렌더링:
```html
<modal-container>
    <template v-slot:body>
        <div v-if="modalType === 'cancel'">취소 폼...</div>
        <div v-else-if="modalType === 'noshow'">노쇼 폼...</div>
    </template>
</modal-container>
```

### 전체 예제 (예약 관리)

```html
<div id="app">
    <!-- 버튼들 -->
    <button @click="handleCancel(booking.id)">예약 취소</button>
    <button @click="handleNoShow(booking.id)">노쇼 처리</button>

    <toast-container></toast-container>
    <modal-container>
        <template v-slot:body>
            <!-- 모달 타입에 따라 다른 내용 표시 -->
            <div v-if="modalType === 'cancel'" class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">취소 사유</label>
                <textarea
                    v-model="cancelReason"
                    rows="3"
                    placeholder="취소 사유를 입력하세요..."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl"
                ></textarea>
            </div>
            <div v-else-if="modalType === 'noshow'" class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">노쇼 사유</label>
                <select
                    v-model="noShowReason"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl"
                >
                    <option value="">사유 선택</option>
                    <option value="no_contact">연락 두절</option>
                    <option value="late">시간 초과</option>
                    <option value="other">기타</option>
                </select>
            </div>
        </template>
    </modal-container>
</div>

<script type="module">
    createVueApp({
        data() {
            return {
                modalType: '',           // 'cancel' | 'noshow'
                cancelReason: '',
                noShowReason: '',
                currentBookingId: null,
            };
        },
        methods: {
            handleCancel(bookingId) {
                this.modalType = 'cancel';
                this.cancelReason = '';
                this.currentBookingId = bookingId;

                this.$modal.confirm({
                    title: '예약 취소',
                    description: '정말 이 예약을 취소하시겠습니까?',
                    confirmText: '예약 취소',
                    cancelText: '닫기',
                    variant: 'danger',
                    icon: 'x',
                }).then((confirm) => {
                    if (confirm) {
                        // API 호출: this.cancelReason, this.currentBookingId 사용
                        this.$toast.success('예약이 취소되었습니다.');
                    }
                    this.modalType = '';
                });
            },

            handleNoShow(bookingId) {
                this.modalType = 'noshow';
                this.noShowReason = '';
                this.currentBookingId = bookingId;

                this.$modal.confirm({
                    title: '노쇼 처리',
                    description: '이 예약을 노쇼로 처리하시겠습니까?',
                    confirmText: '노쇼 처리',
                    cancelText: '닫기',
                    variant: 'danger',
                    icon: 'ban',
                }).then((confirm) => {
                    if (confirm) {
                        // API 호출: this.noShowReason, this.currentBookingId 사용
                        this.$toast.success('노쇼로 처리되었습니다.');
                    }
                    this.modalType = '';
                });
            },
        },
    }).mount('#app');
</script>
```

### 동작 흐름

1. `handleCancel()` 호출 → `modalType = 'cancel'` 설정
2. `this.$modal.confirm()` 호출 → 모달 열림
3. 슬롯이 `modalType`에 따라 취소 사유 폼 표시
4. 사용자가 확인/취소 클릭
5. Promise resolve 후 `modalType = ''`으로 리셋