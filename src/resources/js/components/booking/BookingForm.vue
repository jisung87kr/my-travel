<template>
  <div class="booking-form-container">
    <form @submit.prevent="submitBooking" class="space-y-8">
      <!-- Section 1: Date & Guest Count Confirmation -->
      <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">1. 날짜/인원 확인</h2>

        <!-- Date Selection -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">날짜</label>
          <input
            v-model="form.date"
            type="date"
            class="w-full px-4 py-2.5 text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
            :min="minDate"
            required
          />
          <p v-if="errors.date" class="mt-1.5 text-sm text-red-600">{{ errors.date }}</p>
        </div>

        <!-- Adults Count -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">성인</label>
          <div class="flex items-center gap-4">
            <button
              type="button"
              @click="decrementAdults"
              class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed"
              :disabled="form.adults <= 1"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
              </svg>
            </button>
            <span class="text-xl font-semibold text-gray-900 w-12 text-center">{{ form.adults }}</span>
            <button
              type="button"
              @click="incrementAdults"
              class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full hover:bg-gray-50 transition"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Children Count -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">아동</label>
          <div class="flex items-center gap-4">
            <button
              type="button"
              @click="decrementChildren"
              class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed"
              :disabled="form.children <= 0"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
              </svg>
            </button>
            <span class="text-xl font-semibold text-gray-900 w-12 text-center">{{ form.children }}</span>
            <button
              type="button"
              @click="incrementChildren"
              class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full hover:bg-gray-50 transition"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Section 2: Options -->
      <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">2. 옵션 선택</h2>

        <div class="space-y-3">
          <label
            v-for="option in options"
            :key="option.id"
            class="flex items-center justify-between p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition"
          >
            <div class="flex items-center">
              <input
                v-model="form.selectedOptions"
                type="checkbox"
                :value="option.id"
                class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
              />
              <span class="ml-3 text-base text-gray-900">{{ option.name }}</span>
            </div>
            <span class="text-base font-medium text-gray-900">+₩{{ formatPrice(option.price) }}</span>
          </label>
        </div>
      </div>

      <!-- Section 3: Booker Information -->
      <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">3. 예약자 정보</h2>

        <!-- Name -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            이름 <span class="text-red-500">*</span>
          </label>
          <input
            v-model="form.bookerName"
            type="text"
            placeholder="이름을 입력하세요"
            class="w-full px-4 py-2.5 text-base border rounded-xl transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            :class="errors.bookerName ? 'border-red-300' : 'border-gray-300'"
            required
          />
          <p v-if="errors.bookerName" class="mt-1.5 text-sm text-red-600">{{ errors.bookerName }}</p>
        </div>

        <!-- Phone -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            연락처 <span class="text-red-500">*</span>
          </label>
          <input
            v-model="form.bookerPhone"
            type="tel"
            placeholder="010-0000-0000"
            class="w-full px-4 py-2.5 text-base border rounded-xl transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            :class="errors.bookerPhone ? 'border-red-300' : 'border-gray-300'"
            required
          />
          <p v-if="errors.bookerPhone" class="mt-1.5 text-sm text-red-600">{{ errors.bookerPhone }}</p>
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            이메일 <span class="text-red-500">*</span>
          </label>
          <input
            v-model="form.bookerEmail"
            type="email"
            placeholder="example@email.com"
            class="w-full px-4 py-2.5 text-base border rounded-xl transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            :class="errors.bookerEmail ? 'border-red-300' : 'border-gray-300'"
            required
          />
          <p v-if="errors.bookerEmail" class="mt-1.5 text-sm text-red-600">{{ errors.bookerEmail }}</p>
        </div>
      </div>

      <!-- Section 4: Special Requests -->
      <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">4. 요청사항</h2>

        <textarea
          v-model="form.requests"
          rows="4"
          placeholder="가이드에게 전달할 특별한 요청사항이 있으시면 입력해주세요"
          class="w-full px-4 py-2.5 text-base border border-gray-300 rounded-xl transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"
        ></textarea>
      </div>

      <!-- Submit Button -->
      <div class="bg-white rounded-xl shadow-sm p-6">
        <button
          type="submit"
          :disabled="isSubmitting || !isFormValid"
          class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span v-if="isSubmitting">처리 중...</span>
          <span v-else>예약 확정하기</span>
        </button>

        <p v-if="errors.submit" class="mt-3 text-sm text-red-600 text-center">{{ errors.submit }}</p>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  product: {
    type: Object,
    required: true
  },
  initialDate: {
    type: String,
    default: ''
  },
  initialAdults: {
    type: Number,
    default: 2
  },
  initialChildren: {
    type: Number,
    default: 0
  },
  options: {
    type: Array,
    default: () => []
  }
});

// Form state
const form = ref({
  date: props.initialDate,
  adults: props.initialAdults,
  children: props.initialChildren,
  selectedOptions: [],
  bookerName: '',
  bookerPhone: '',
  bookerEmail: '',
  requests: ''
});

// UI state
const isSubmitting = ref(false);
const errors = ref({
  date: '',
  bookerName: '',
  bookerPhone: '',
  bookerEmail: '',
  submit: ''
});

// Computed properties
const minDate = computed(() => {
  const today = new Date();
  return today.toISOString().split('T')[0];
});

const subtotal = computed(() => {
  return (form.value.adults * props.product.adultPrice) +
         (form.value.children * props.product.childPrice);
});

const optionsTotal = computed(() => {
  return form.value.selectedOptions.reduce((total, optionId) => {
    const option = props.options.find(opt => opt.id === optionId);
    return total + (option ? option.price : 0);
  }, 0);
});

const totalPrice = computed(() => {
  return subtotal.value + optionsTotal.value;
});

const isFormValid = computed(() => {
  return form.value.date &&
         form.value.bookerName.length >= 2 &&
         form.value.bookerPhone.length >= 10 &&
         form.value.bookerEmail.includes('@');
});

// Methods
function incrementAdults() {
  form.value.adults++;
  updateSummary();
}

function decrementAdults() {
  if (form.value.adults > 1) {
    form.value.adults--;
    updateSummary();
  }
}

function incrementChildren() {
  form.value.children++;
  updateSummary();
}

function decrementChildren() {
  if (form.value.children > 0) {
    form.value.children--;
    updateSummary();
  }
}

function formatPrice(price) {
  return price.toLocaleString();
}

function validateForm() {
  errors.value = {
    date: '',
    bookerName: '',
    bookerPhone: '',
    bookerEmail: '',
    submit: ''
  };

  let isValid = true;

  // Validate date
  if (!form.value.date) {
    errors.value.date = '날짜를 선택해주세요';
    isValid = false;
  }

  // Validate name
  if (form.value.bookerName.length < 2) {
    errors.value.bookerName = '이름은 2자 이상 입력해주세요';
    isValid = false;
  }

  // Validate phone
  const phoneRegex = /^010-\d{4}-\d{4}$/;
  if (!phoneRegex.test(form.value.bookerPhone)) {
    errors.value.bookerPhone = '연락처는 010-0000-0000 형식으로 입력해주세요';
    isValid = false;
  }

  // Validate email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(form.value.bookerEmail)) {
    errors.value.bookerEmail = '올바른 이메일 형식을 입력해주세요';
    isValid = false;
  }

  return isValid;
}

function updateSummary() {
  // Update the order summary in the sidebar
  const summaryDate = document.getElementById('summary-date');
  const summaryGuests = document.getElementById('summary-guests');
  const adultBreakdown = document.getElementById('adult-breakdown');
  const adultTotal = document.getElementById('adult-total');
  const childRow = document.getElementById('child-row');
  const childBreakdown = document.getElementById('child-breakdown');
  const childTotal = document.getElementById('child-total');
  const optionsRow = document.getElementById('options-row');
  const optionsTotalEl = document.getElementById('options-total');
  const totalPriceEl = document.getElementById('total-price');

  if (summaryDate && form.value.date) {
    const dateObj = new Date(form.value.date);
    const days = ['일', '월', '화', '수', '목', '금', '토'];
    summaryDate.textContent = `${form.value.date} (${days[dateObj.getDay()]})`;
  }

  if (summaryGuests) {
    let guestText = `성인 ${form.value.adults}`;
    if (form.value.children > 0) {
      guestText += `, 아동 ${form.value.children}`;
    }
    summaryGuests.textContent = guestText;
  }

  if (adultBreakdown && adultTotal) {
    const adultPrice = form.value.adults * props.product.adultPrice;
    adultBreakdown.textContent = `성인 ${form.value.adults} × ₩${formatPrice(props.product.adultPrice)}`;
    adultTotal.textContent = `₩${formatPrice(adultPrice)}`;
  }

  if (childRow && childBreakdown && childTotal) {
    if (form.value.children > 0) {
      const childPrice = form.value.children * props.product.childPrice;
      childRow.style.display = 'flex';
      childBreakdown.textContent = `아동 ${form.value.children} × ₩${formatPrice(props.product.childPrice)}`;
      childTotal.textContent = `₩${formatPrice(childPrice)}`;
    } else {
      childRow.style.display = 'none';
    }
  }

  if (optionsRow && optionsTotalEl) {
    if (optionsTotal.value > 0) {
      optionsRow.style.display = 'flex';
      optionsTotalEl.textContent = `₩${formatPrice(optionsTotal.value)}`;
    } else {
      optionsRow.style.display = 'none';
    }
  }

  if (totalPriceEl) {
    totalPriceEl.textContent = `₩${formatPrice(totalPrice.value)}`;
  }
}

async function submitBooking() {
  if (!validateForm()) {
    return;
  }

  isSubmitting.value = true;
  errors.value.submit = '';

  try {
    const response = await fetch('/api/bookings', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        product_id: props.product.id,
        date: form.value.date,
        adult_count: form.value.adults,
        child_count: form.value.children,
        options: form.value.selectedOptions,
        booker_name: form.value.bookerName,
        booker_phone: form.value.bookerPhone,
        booker_email: form.value.bookerEmail,
        requests: form.value.requests,
        total_price: totalPrice.value
      })
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message || '예약 처리 중 오류가 발생했습니다');
    }

    // Redirect to complete page
    const locale = document.documentElement.lang || 'ko';
    window.location.href = `/${locale}/bookings/complete?booking_id=${data.booking.id}`;
  } catch (error) {
    errors.value.submit = error.message;
  } finally {
    isSubmitting.value = false;
  }
}

// Watch for changes to update summary
watch(() => [form.value.date, form.value.adults, form.value.children, form.value.selectedOptions], () => {
  updateSummary();
}, { deep: true });

// Initial update
updateSummary();
</script>

<style scoped>
.booking-form-container {
  /* Add any custom styles here */
}
</style>
