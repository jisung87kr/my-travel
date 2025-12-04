<template>
  <div v-if="!mobile" class="bg-white shadow-card rounded-xl p-6 sticky top-24">
    <!-- Price -->
    <div class="mb-6">
      <div class="flex items-baseline">
        <span class="text-3xl font-bold text-gray-900">₩{{ formatPrice(adultPrice) }}</span>
        <span class="text-gray-600 ml-2">/person</span>
      </div>
      <div v-if="childPrice > 0" class="text-sm text-gray-600 mt-1">
        Child: ₩{{ formatPrice(childPrice) }}/person
      </div>
    </div>

    <!-- Date Selection -->
    <div class="mb-4">
      <label class="block text-sm font-semibold text-gray-900 mb-2">Date</label>
      <div class="relative">
        <input
          v-model="selectedDate"
          type="date"
          :min="minDate"
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          @change="validateDate"
        >
        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
      </div>
      <p v-if="!isDateAvailable" class="text-xs text-red-600 mt-1">
        This date is not available
      </p>
    </div>

    <!-- Guests Selection -->
    <div class="mb-4">
      <label class="block text-sm font-semibold text-gray-900 mb-2">Guests</label>

      <!-- Adults -->
      <div class="flex items-center justify-between py-3 border-b border-gray-200">
        <div>
          <div class="text-sm font-medium text-gray-900">Adults</div>
          <div class="text-xs text-gray-600">Age 13+</div>
        </div>
        <div class="flex items-center gap-3">
          <button
            @click="decrementAdults"
            :disabled="adults <= 1"
            class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:border-gray-400 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
            </svg>
          </button>
          <span class="w-8 text-center font-semibold">{{ adults }}</span>
          <button
            @click="incrementAdults"
            :disabled="adults >= maxGuests"
            class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:border-gray-400 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Children -->
      <div class="flex items-center justify-between py-3">
        <div>
          <div class="text-sm font-medium text-gray-900">Children</div>
          <div class="text-xs text-gray-600">Age 2-12</div>
        </div>
        <div class="flex items-center gap-3">
          <button
            @click="decrementChildren"
            :disabled="children <= 0"
            class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:border-gray-400 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
            </svg>
          </button>
          <span class="w-8 text-center font-semibold">{{ children }}</span>
          <button
            @click="incrementChildren"
            :disabled="totalGuests >= maxGuests"
            class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:border-gray-400 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Total Price -->
    <div class="py-4 border-t border-gray-200 mb-4">
      <div class="flex justify-between items-center mb-2">
        <span class="text-sm text-gray-600">{{ adults }} Adults × ₩{{ formatPrice(adultPrice) }}</span>
        <span class="text-sm font-semibold">₩{{ formatPrice(adults * adultPrice) }}</span>
      </div>
      <div v-if="children > 0" class="flex justify-between items-center mb-2">
        <span class="text-sm text-gray-600">{{ children }} Children × ₩{{ formatPrice(childPrice) }}</span>
        <span class="text-sm font-semibold">₩{{ formatPrice(children * childPrice) }}</span>
      </div>
      <div class="flex justify-between items-center pt-2 border-t border-gray-200">
        <span class="text-base font-bold text-gray-900">Total</span>
        <span class="text-2xl font-bold text-blue-600">₩{{ formatPrice(totalPrice) }}</span>
      </div>
    </div>

    <!-- Book Button -->
    <button
      @click="handleBooking"
      :disabled="!canBook"
      class="w-full py-4 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold rounded-lg transition-colors"
    >
      {{ canBook ? 'Book Now' : 'Select Date' }}
    </button>

    <!-- Free Cancellation Notice -->
    <p class="text-xs text-center text-gray-600 mt-3">
      Free cancellation up to 24 hours before the experience
    </p>
  </div>

  <!-- Mobile Fixed Bottom Bar -->
  <div v-else class="flex items-center justify-between gap-4">
    <div class="flex-1">
      <div class="flex items-baseline">
        <span class="text-xl font-bold text-gray-900">₩{{ formatPrice(totalPrice) }}</span>
        <span class="text-sm text-gray-600 ml-1">total</span>
      </div>
      <button
        @click="showMobileModal = true"
        class="text-sm text-blue-600 underline"
      >
        {{ adults }} Adults{{ children > 0 ? `, ${children} Children` : '' }}
      </button>
    </div>
    <button
      @click="handleBooking"
      :disabled="!canBook"
      class="px-8 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold rounded-lg transition-colors"
    >
      Book
    </button>
  </div>

  <!-- Mobile Modal -->
  <Teleport to="body">
    <transition name="slide-up">
      <div
        v-if="showMobileModal"
        class="fixed inset-0 z-50 lg:hidden"
        @click="showMobileModal = false"
      >
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div
          class="absolute bottom-0 left-0 right-0 bg-white rounded-t-2xl p-6 max-h-[90vh] overflow-y-auto"
          @click.stop
        >
          <!-- Header -->
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Select Date & Guests</h3>
            <button
              @click="showMobileModal = false"
              class="p-2 hover:bg-gray-100 rounded-full"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Date Selection -->
          <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-900 mb-2">Date</label>
            <input
              v-model="selectedDate"
              type="date"
              :min="minDate"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              @change="validateDate"
            >
            <p v-if="!isDateAvailable" class="text-xs text-red-600 mt-1">
              This date is not available
            </p>
          </div>

          <!-- Guests Selection -->
          <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-900 mb-3">Guests</label>

            <!-- Adults -->
            <div class="flex items-center justify-between py-4 border-b border-gray-200">
              <div>
                <div class="text-base font-medium text-gray-900">Adults</div>
                <div class="text-sm text-gray-600">Age 13+</div>
              </div>
              <div class="flex items-center gap-4">
                <button
                  @click="decrementAdults"
                  :disabled="adults <= 1"
                  class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center disabled:opacity-50"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                  </svg>
                </button>
                <span class="w-10 text-center text-lg font-semibold">{{ adults }}</span>
                <button
                  @click="incrementAdults"
                  :disabled="adults >= maxGuests"
                  class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center disabled:opacity-50"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Children -->
            <div class="flex items-center justify-between py-4">
              <div>
                <div class="text-base font-medium text-gray-900">Children</div>
                <div class="text-sm text-gray-600">Age 2-12</div>
              </div>
              <div class="flex items-center gap-4">
                <button
                  @click="decrementChildren"
                  :disabled="children <= 0"
                  class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center disabled:opacity-50"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                  </svg>
                </button>
                <span class="w-10 text-center text-lg font-semibold">{{ children }}</span>
                <button
                  @click="incrementChildren"
                  :disabled="totalGuests >= maxGuests"
                  class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center disabled:opacity-50"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Total -->
          <div class="py-4 bg-gray-50 rounded-lg px-4 mb-4">
            <div class="flex justify-between items-center">
              <span class="text-base font-semibold text-gray-900">Total</span>
              <span class="text-2xl font-bold text-blue-600">₩{{ formatPrice(totalPrice) }}</span>
            </div>
          </div>

          <!-- Apply Button -->
          <button
            @click="showMobileModal = false"
            class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg"
          >
            Apply
          </button>
        </div>
      </div>
    </transition>
  </Teleport>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  productId: {
    type: Number,
    required: true
  },
  adultPrice: {
    type: Number,
    required: true
  },
  childPrice: {
    type: Number,
    default: 0
  },
  availableDates: {
    type: Array,
    default: () => []
  },
  mobile: {
    type: Boolean,
    default: false
  },
  maxGuests: {
    type: Number,
    default: 10
  }
});

// State
const selectedDate = ref('');
const adults = ref(2);
const children = ref(0);
const showMobileModal = ref(false);

// Computed
const totalGuests = computed(() => adults.value + children.value);

const totalPrice = computed(() => {
  return (adults.value * props.adultPrice) + (children.value * props.childPrice);
});

const minDate = computed(() => {
  const today = new Date();
  return today.toISOString().split('T')[0];
});

const isDateAvailable = computed(() => {
  if (!selectedDate.value) return true;
  if (props.availableDates.length === 0) return true;
  return props.availableDates.includes(selectedDate.value);
});

const canBook = computed(() => {
  return selectedDate.value && isDateAvailable.value && totalGuests.value > 0;
});

// Methods
const formatPrice = (price) => {
  return price.toLocaleString('ko-KR');
};

const incrementAdults = () => {
  if (adults.value < props.maxGuests) {
    adults.value++;
  }
};

const decrementAdults = () => {
  if (adults.value > 1) {
    adults.value--;
  }
};

const incrementChildren = () => {
  if (totalGuests.value < props.maxGuests) {
    children.value++;
  }
};

const decrementChildren = () => {
  if (children.value > 0) {
    children.value--;
  }
};

const validateDate = () => {
  if (!isDateAvailable.value) {
    selectedDate.value = '';
  }
};

const handleBooking = () => {
  if (!canBook.value) return;

  // Navigate to booking page
  const params = new URLSearchParams({
    date: selectedDate.value,
    adults: adults.value,
    children: children.value
  });

  window.location.href = `/booking/${props.productId}?${params.toString()}`;
};
</script>

<style scoped>
.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.3s ease;
}

.slide-up-enter-from,
.slide-up-leave-to {
  transform: translateY(100%);
}

.slide-up-enter-from .absolute.inset-0,
.slide-up-leave-to .absolute.inset-0 {
  opacity: 0;
}
</style>
