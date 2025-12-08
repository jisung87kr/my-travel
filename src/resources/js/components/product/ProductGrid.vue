<template>
  <div class="product-grid-container">
    <!-- Product Grid -->
    <div
      v-if="!loading || products.length > 0"
      class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
    >
      <div
        v-for="product in products"
        :key="product.id"
        class="product-card bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden group"
      >
        <!-- Product Image -->
        <a :href="product.url" class="block relative overflow-hidden aspect-[4/3]">
          <img
            :src="product.image"
            :alt="product.title"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
          />
          <!-- Badge -->
          <div
            v-if="product.badge"
            class="absolute top-3 left-3 px-2 py-1 text-xs font-semibold rounded"
            :class="getBadgeClass(product.badge.type)"
          >
            {{ product.badge.text }}
          </div>
          <!-- Wishlist Button -->
          <button
            @click.prevent="toggleWishlist(product.id)"
            class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-sm hover:bg-gray-50 transition-all duration-200 hover:scale-110"
            :class="isWishlisted(product.id) ? 'ring-2 ring-pink-200' : ''"
          >
            <svg
              class="w-5 h-5 transition-all duration-200"
              :class="isWishlisted(product.id) ? 'text-pink-500' : 'text-gray-400'"
              :fill="isWishlisted(product.id) ? 'currentColor' : 'none'"
              stroke="currentColor"
              viewBox="0 0 24 24"
              stroke-width="1.5"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"
              />
            </svg>
          </button>
        </a>

        <!-- Product Info -->
        <div class="p-4">
          <!-- Type and Region -->
          <div class="flex items-center gap-2 mb-2">
            <span class="inline-flex items-center gap-1 text-xs text-gray-500">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
              </svg>
              {{ product.type || product.category }}
            </span>
            <span class="text-gray-300">•</span>
            <span class="inline-flex items-center gap-1 text-xs text-gray-500">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              {{ product.region }}
            </span>
          </div>

          <!-- Title -->
          <a :href="product.url" class="block">
            <h3 class="text-base font-semibold text-gray-900 mb-2 line-clamp-2 hover:text-indigo-600 transition">
              {{ product.title }}
            </h3>
          </a>

          <!-- Rating and Reviews -->
          <div class="flex items-center gap-2 mb-3">
            <div class="flex items-center">
              <svg
                v-for="star in 5"
                :key="star"
                class="w-4 h-4"
                :class="star <= (product.rating || 0) ? 'text-yellow-400 fill-current' : 'text-gray-300'"
                viewBox="0 0 20 20"
              >
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            </div>
            <span class="text-sm text-gray-600">
              {{ (product.rating || 0).toFixed(1) }} ({{ product.reviewCount || 0 }})
            </span>
          </div>

          <!-- Duration/Info -->
          <p v-if="product.duration" class="text-sm text-gray-600 mb-3 flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ product.duration }}
          </p>

          <!-- Price -->
          <div class="flex items-baseline gap-2">
            <span class="text-xl font-bold text-gray-900">
              {{ formatPrice(product.price) }}
            </span>
            <span class="text-sm text-gray-500">
              / person
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading Skeletons -->
    <div
      v-if="loading && products.length === 0"
      class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
    >
      <div
        v-for="i in skeletonCount"
        :key="i"
        class="bg-white rounded-lg shadow-sm overflow-hidden animate-pulse"
      >
        <div class="aspect-[4/3] bg-gray-200"></div>
        <div class="p-4">
          <div class="h-4 bg-gray-200 rounded mb-2 w-3/4"></div>
          <div class="h-4 bg-gray-200 rounded mb-3 w-1/2"></div>
          <div class="h-3 bg-gray-200 rounded mb-2 w-full"></div>
          <div class="h-3 bg-gray-200 rounded mb-3 w-5/6"></div>
          <div class="h-6 bg-gray-200 rounded w-1/3"></div>
        </div>
      </div>
    </div>

    <!-- Infinite Scroll Loading -->
    <div
      v-if="loading && products.length > 0"
      class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6"
    >
      <div
        v-for="i in 4"
        :key="`loading-${i}`"
        class="bg-white rounded-lg shadow-sm overflow-hidden animate-pulse"
      >
        <div class="aspect-[4/3] bg-gray-200"></div>
        <div class="p-4">
          <div class="h-4 bg-gray-200 rounded mb-2 w-3/4"></div>
          <div class="h-4 bg-gray-200 rounded mb-3 w-1/2"></div>
          <div class="h-3 bg-gray-200 rounded mb-2 w-full"></div>
          <div class="h-3 bg-gray-200 rounded mb-3 w-5/6"></div>
          <div class="h-6 bg-gray-200 rounded w-1/3"></div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="!loading && products.length === 0" class="text-center py-16">
      <svg class="mx-auto w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <h3 class="mt-4 text-lg font-medium text-gray-900">No products found</h3>
      <p class="mt-2 text-gray-600">Try adjusting your filters to see more results.</p>
    </div>

    <!-- Intersection Observer Target -->
    <div ref="observerTarget" class="h-10"></div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, watch } from 'vue'

const props = defineProps({
  products: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  },
  hasMore: {
    type: Boolean,
    default: true
  },
  skeletonCount: {
    type: Number,
    default: 8
  }
})

const emit = defineEmits(['load-more', 'toggle-wishlist'])

// Local reactive state for wishlist status
const wishlistState = reactive({})

// Initialize wishlist state from props
watch(() => props.products, (newProducts) => {
  newProducts.forEach(product => {
    if (!(product.id in wishlistState)) {
      wishlistState[product.id] = product.isWishlisted || false
    }
  })
}, { immediate: true, deep: true })

// Check if product is wishlisted
function isWishlisted(productId) {
  return wishlistState[productId] || false
}

// Refs
const observerTarget = ref(null)
let observer = null

// Methods
function formatPrice(price) {
  return new Intl.NumberFormat('ko-KR', {
    style: 'currency',
    currency: 'KRW',
    minimumFractionDigits: 0
  }).format(price)
}

function getBadgeClass(type) {
  const classes = {
    new: 'bg-green-500 text-white',
    hot: 'bg-red-500 text-white',
    sale: 'bg-orange-500 text-white',
    featured: 'bg-indigo-500 text-white'
  }
  return classes[type] || 'bg-gray-500 text-white'
}

async function toggleWishlist(productId) {
  try {
    const response = await fetch(`/wishlist/${productId}`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    })

    if (!response.ok) {
      if (response.status === 401) {
        window.location.href = '/login'
        return
      }
      throw new Error('Network response was not ok')
    }

    const data = await response.json()

    if (data.success) {
      // Update local reactive state
      wishlistState[productId] = data.added
    }
  } catch (error) {
    console.error('Wishlist error:', error)
  }

  emit('toggle-wishlist', productId)
}

function handleIntersection(entries) {
  const [entry] = entries

  if (entry.isIntersecting && props.hasMore && !props.loading) {
    emit('load-more')
  }
}

// Lifecycle
onMounted(() => {
  if (observerTarget.value) {
    observer = new IntersectionObserver(handleIntersection, {
      root: null,
      rootMargin: '200px',
      threshold: 0.1
    })

    observer.observe(observerTarget.value)
  }
})

onUnmounted(() => {
  if (observer) {
    observer.disconnect()
  }
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
