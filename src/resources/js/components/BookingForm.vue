<template>
  <div class="booking-form">
    <!-- Date Selection -->
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">
        {{ labels.selectDate }}
      </label>
      <select
        v-model="form.date"
        @change="onDateChange"
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
      >
        <option value="">{{ labels.selectDateFirst }}</option>
        <option
          v-for="schedule in availableSchedules"
          :key="schedule.id"
          :value="schedule.date"
        >
          {{ formatDate(schedule.date) }} ({{ schedule.available_count }} {{ labels.available }})
        </option>
      </select>
      <p v-if="schedules.length === 0" class="mt-2 text-sm text-red-500">
        {{ labels.noAvailableDates }}
      </p>
    </div>

    <!-- Person Count -->
    <div class="mb-4 space-y-3">
      <div class="flex items-center justify-between">
        <label class="text-sm font-medium text-gray-700">{{ labels.adults }}</label>
        <div class="flex items-center gap-3">
          <button
            type="button"
            @click="decrementCount('adult')"
            :disabled="form.adultCount <= 1"
            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full disabled:opacity-50 hover:bg-gray-100"
          >-</button>
          <span class="w-8 text-center font-medium">{{ form.adultCount }}</span>
          <button
            type="button"
            @click="incrementCount('adult')"
            :disabled="totalPersons >= maxPersons"
            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full disabled:opacity-50 hover:bg-gray-100"
          >+</button>
        </div>
      </div>

      <div class="flex items-center justify-between">
        <label class="text-sm font-medium text-gray-700">{{ labels.children }}</label>
        <div class="flex items-center gap-3">
          <button
            type="button"
            @click="decrementCount('child')"
            :disabled="form.childCount <= 0"
            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full disabled:opacity-50 hover:bg-gray-100"
          >-</button>
          <span class="w-8 text-center font-medium">{{ form.childCount }}</span>
          <button
            type="button"
            @click="incrementCount('child')"
            :disabled="totalPersons >= maxPersons"
            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full disabled:opacity-50 hover:bg-gray-100"
          >+</button>
        </div>
      </div>
    </div>

    <!-- Price Summary -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
      <div class="flex justify-between text-sm text-gray-600 mb-2">
        <span>{{ labels.adults }} x {{ form.adultCount }}</span>
        <span>₩{{ formatPrice(adultTotal) }}</span>
      </div>
      <div v-if="form.childCount > 0" class="flex justify-between text-sm text-gray-600 mb-2">
        <span>{{ labels.children }} x {{ form.childCount }}</span>
        <span>₩{{ formatPrice(childTotal) }}</span>
      </div>
      <div class="flex justify-between font-bold text-lg border-t border-gray-200 pt-2 mt-2">
        <span>{{ labels.total }}</span>
        <span>₩{{ formatPrice(totalPrice) }}</span>
      </div>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="mb-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm">
      {{ error }}
    </div>

    <!-- Submit Button -->
    <button
      type="button"
      @click="submitBooking"
      :disabled="!canBook || isSubmitting"
      class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
    >
      <span v-if="isSubmitting">{{ labels.processing }}</span>
      <span v-else>{{ labels.bookNow }}</span>
    </button>

    <p v-if="bookingType === 'request'" class="mt-2 text-sm text-gray-500 text-center">
      {{ labels.pendingApproval }}
    </p>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const props = defineProps({
  productId: { type: Number, required: true },
  bookingType: { type: String, default: 'instant' },
  adultPrice: { type: Number, default: 0 },
  childPrice: { type: Number, default: 0 },
  schedules: { type: Array, default: () => [] },
  labels: {
    type: Object,
    default: () => ({
      selectDate: 'Select Date',
      selectDateFirst: 'Please select a date',
      noAvailableDates: 'No available dates',
      adults: 'Adults',
      children: 'Children',
      total: 'Total',
      bookNow: 'Book Now',
      processing: 'Processing...',
      pendingApproval: 'This booking requires approval from the provider.',
      available: 'available',
      insufficientInventory: 'Not enough availability',
    })
  }
})

const form = ref({
  date: '',
  adultCount: 1,
  childCount: 0,
})

const isSubmitting = ref(false)
const error = ref('')

const availableSchedules = computed(() => {
  return props.schedules.filter(s => s.is_active && s.available_count > 0)
})

const selectedSchedule = computed(() => {
  return props.schedules.find(s => s.date === form.value.date)
})

const maxPersons = computed(() => {
  return selectedSchedule.value?.available_count ?? 99
})

const totalPersons = computed(() => {
  return form.value.adultCount + form.value.childCount
})

const adultTotal = computed(() => {
  return form.value.adultCount * props.adultPrice
})

const childTotal = computed(() => {
  return form.value.childCount * props.childPrice
})

const totalPrice = computed(() => {
  return adultTotal.value + childTotal.value
})

const canBook = computed(() => {
  return form.value.date &&
         form.value.adultCount > 0 &&
         totalPersons.value <= maxPersons.value
})

function incrementCount(type) {
  if (totalPersons.value >= maxPersons.value) return

  if (type === 'adult') {
    form.value.adultCount++
  } else {
    form.value.childCount++
  }
}

function decrementCount(type) {
  if (type === 'adult' && form.value.adultCount > 1) {
    form.value.adultCount--
  } else if (type === 'child' && form.value.childCount > 0) {
    form.value.childCount--
  }
}

function onDateChange() {
  // Reset counts if exceeding new max
  if (totalPersons.value > maxPersons.value) {
    form.value.adultCount = 1
    form.value.childCount = 0
  }
  error.value = ''
}

function formatDate(dateStr) {
  const date = new Date(dateStr)
  return date.toLocaleDateString(undefined, {
    weekday: 'short',
    month: 'short',
    day: 'numeric'
  })
}

function formatPrice(price) {
  return price.toLocaleString()
}

async function submitBooking() {
  if (!canBook.value || isSubmitting.value) return

  isSubmitting.value = true
  error.value = ''

  try {
    const response = await fetch('/bookings', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        product_id: props.productId,
        date: form.value.date,
        adult_count: form.value.adultCount,
        child_count: form.value.childCount,
      })
    })

    const data = await response.json()

    if (!response.ok) {
      throw new Error(data.message || 'Booking failed')
    }

    // Redirect to confirmation page
    window.location.href = `/${document.documentElement.lang}/my/bookings/${data.data.id}`
  } catch (e) {
    error.value = e.message
  } finally {
    isSubmitting.value = false
  }
}
</script>
