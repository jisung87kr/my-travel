<template>
  <div class="bg-white shadow-sm sticky top-0 z-40 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <!-- Filter Bar -->
      <div class="flex flex-wrap items-center gap-3">
        <!-- Category Dropdown -->
        <div class="relative" ref="categoryRef">
          <button
            @click="toggleDropdown('category')"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-indigo-500"
          >
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span class="text-sm font-medium text-gray-700">
              {{ selectedCategoryLabel }}
            </span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div
            v-show="activeDropdown === 'category'"
            class="absolute top-full mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
          >
            <button
              v-for="category in categories"
              :key="category.value"
              @click="selectFilter('category', category.value)"
              class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition"
              :class="{ 'bg-indigo-50 text-indigo-600': filters.category === category.value }"
            >
              {{ category.label }}
            </button>
          </div>
        </div>

        <!-- Price Dropdown -->
        <div class="relative" ref="priceRef">
          <button
            @click="toggleDropdown('price')"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-indigo-500"
          >
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium text-gray-700">Price</span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div
            v-show="activeDropdown === 'price'"
            class="absolute top-full mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 p-4 z-50"
          >
            <div class="space-y-3">
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Min Price</label>
                <input
                  type="number"
                  v-model.number="tempFilters.minPrice"
                  placeholder="0"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Max Price</label>
                <input
                  type="number"
                  v-model.number="tempFilters.maxPrice"
                  placeholder="1000000"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
              </div>
              <button
                @click="applyPriceFilter"
                class="w-full px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition"
              >
                Apply
              </button>
            </div>
          </div>
        </div>

        <!-- Date Dropdown -->
        <div class="relative" ref="dateRef">
          <button
            @click="toggleDropdown('date')"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-indigo-500"
          >
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="text-sm font-medium text-gray-700">
              {{ filters.date ? formatDate(filters.date) : 'Date' }}
            </span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div
            v-show="activeDropdown === 'date'"
            class="absolute top-full mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 p-4 z-50"
          >
            <input
              type="date"
              v-model="filters.date"
              :min="minDate"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
            <button
              v-if="filters.date"
              @click="clearDate"
              class="w-full mt-2 px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition"
            >
              Clear Date
            </button>
          </div>
        </div>

        <!-- Region Dropdown -->
        <div class="relative" ref="regionRef">
          <button
            @click="toggleDropdown('region')"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-indigo-500"
          >
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="text-sm font-medium text-gray-700">
              {{ selectedRegionLabel }}
            </span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div
            v-show="activeDropdown === 'region'"
            class="absolute top-full mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
          >
            <button
              v-for="region in regions"
              :key="region.value"
              @click="selectFilter('region', region.value)"
              class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition"
              :class="{ 'bg-indigo-50 text-indigo-600': filters.region === region.value }"
            >
              {{ region.label }}
            </button>
          </div>
        </div>

        <!-- Guests Dropdown -->
        <div class="relative" ref="guestsRef">
          <button
            @click="toggleDropdown('guests')"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-indigo-500"
          >
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="text-sm font-medium text-gray-700">
              {{ filters.guests }} Guests
            </span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div
            v-show="activeDropdown === 'guests'"
            class="absolute top-full mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 p-4 z-50"
          >
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium text-gray-700">Number of Guests</span>
              <div class="flex items-center gap-3">
                <button
                  @click="decrementGuests"
                  :disabled="filters.guests <= 1"
                  class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full disabled:opacity-50 hover:bg-gray-100 transition"
                >
                  <span class="text-lg">-</span>
                </button>
                <span class="w-8 text-center font-medium">{{ filters.guests }}</span>
                <button
                  @click="incrementGuests"
                  :disabled="filters.guests >= 20"
                  class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full disabled:opacity-50 hover:bg-gray-100 transition"
                >
                  <span class="text-lg">+</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Clear All Filters -->
        <button
          v-if="hasActiveFilters"
          @click="clearAllFilters"
          class="inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          Clear all
        </button>
      </div>

      <!-- Sort and Results Count -->
      <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
        <p class="text-sm text-gray-600">
          {{ resultsCount }} products found
        </p>
        <div class="flex items-center gap-2">
          <label class="text-sm text-gray-600">Sort by:</label>
          <select
            v-model="filters.sort"
            class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
          >
            <option value="recommended">Recommended</option>
            <option value="price_low">Price: Low to High</option>
            <option value="price_high">Price: High to Low</option>
            <option value="rating">Rating</option>
            <option value="popular">Most Popular</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import debounce from 'lodash/debounce'

const props = defineProps({
  categories: {
    type: Array,
    default: () => [
      { value: '', label: 'All Categories' },
      { value: 'tour', label: 'Tours' },
      { value: 'activity', label: 'Activities' },
      { value: 'accommodation', label: 'Accommodation' },
      { value: 'transportation', label: 'Transportation' },
    ]
  },
  regions: {
    type: Array,
    default: () => [
      { value: '', label: 'All Regions' },
      { value: 'seoul', label: 'Seoul' },
      { value: 'busan', label: 'Busan' },
      { value: 'jeju', label: 'Jeju' },
      { value: 'gangwon', label: 'Gangwon' },
      { value: 'gyeonggi', label: 'Gyeonggi' },
    ]
  },
  initialFilters: {
    type: Object,
    default: () => ({})
  },
  resultsCount: {
    type: Number,
    default: 0
  }
})

const emit = defineEmits(['filter-change'])

// Refs for dropdown elements
const categoryRef = ref(null)
const priceRef = ref(null)
const dateRef = ref(null)
const regionRef = ref(null)
const guestsRef = ref(null)

// State
const activeDropdown = ref(null)
const filters = ref({
  category: '',
  region: '',
  date: '',
  minPrice: null,
  maxPrice: null,
  guests: 1,
  sort: 'recommended',
  ...props.initialFilters
})

const tempFilters = ref({
  minPrice: filters.value.minPrice,
  maxPrice: filters.value.maxPrice
})

// Computed
const selectedCategoryLabel = computed(() => {
  const category = props.categories.find(c => c.value === filters.value.category)
  return category?.label || 'Category'
})

const selectedRegionLabel = computed(() => {
  const region = props.regions.find(r => r.value === filters.value.region)
  return region?.label || 'Region'
})

const minDate = computed(() => {
  const today = new Date()
  return today.toISOString().split('T')[0]
})

const hasActiveFilters = computed(() => {
  return filters.value.category !== '' ||
         filters.value.region !== '' ||
         filters.value.date !== '' ||
         filters.value.minPrice !== null ||
         filters.value.maxPrice !== null ||
         filters.value.guests > 1
})

// Methods
function toggleDropdown(dropdown) {
  if (activeDropdown.value === dropdown) {
    activeDropdown.value = null
  } else {
    activeDropdown.value = dropdown
  }
}

function selectFilter(type, value) {
  filters.value[type] = value
  activeDropdown.value = null
}

function applyPriceFilter() {
  filters.value.minPrice = tempFilters.value.minPrice
  filters.value.maxPrice = tempFilters.value.maxPrice
  activeDropdown.value = null
}

function clearDate() {
  filters.value.date = ''
  activeDropdown.value = null
}

function incrementGuests() {
  if (filters.value.guests < 20) {
    filters.value.guests++
  }
}

function decrementGuests() {
  if (filters.value.guests > 1) {
    filters.value.guests--
  }
}

function clearAllFilters() {
  filters.value = {
    category: '',
    region: '',
    date: '',
    minPrice: null,
    maxPrice: null,
    guests: 1,
    sort: 'recommended'
  }
  tempFilters.value = {
    minPrice: null,
    maxPrice: null
  }
}

function formatDate(dateStr) {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

// Handle clicks outside dropdowns
function handleClickOutside(event) {
  const refs = [categoryRef.value, priceRef.value, dateRef.value, regionRef.value, guestsRef.value]
  const clickedOutside = refs.every(ref => ref && !ref.contains(event.target))

  if (clickedOutside) {
    activeDropdown.value = null
  }
}

// Debounced filter change emitter
const emitFilterChange = debounce(() => {
  emit('filter-change', { ...filters.value })
}, 500)

// Watch for filter changes
watch(filters, () => {
  emitFilterChange()
}, { deep: true })

// Lifecycle
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
/* Additional styles if needed */
</style>
