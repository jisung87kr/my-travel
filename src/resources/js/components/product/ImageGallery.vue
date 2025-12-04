<template>
  <div class="image-gallery">
    <!-- Desktop Grid Layout -->
    <div class="hidden md:grid md:grid-cols-4 md:grid-rows-2 gap-2 h-[500px] rounded-xl overflow-hidden">
      <!-- Main Image (Large) -->
      <div class="col-span-2 row-span-2 cursor-pointer group relative" @click="openLightbox(0)">
        <img
          :src="images[0]"
          alt="Main product image"
          class="w-full h-full object-cover transition-transform group-hover:scale-105"
        >
        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity"></div>
      </div>

      <!-- Small Images -->
      <div
        v-for="(image, index) in images.slice(1, 5)"
        :key="index"
        class="cursor-pointer group relative"
        @click="openLightbox(index + 1)"
      >
        <img
          :src="image"
          :alt="`Product image ${index + 2}`"
          class="w-full h-full object-cover transition-transform group-hover:scale-105"
        >
        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity"></div>

        <!-- Show more indicator on last image -->
        <div
          v-if="index === 3 && images.length > 5"
          class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
          <span class="text-white text-lg font-semibold">+{{ images.length - 5 }} more</span>
        </div>
      </div>
    </div>

    <!-- Mobile Swipe Carousel -->
    <div class="md:hidden relative">
      <div class="overflow-hidden rounded-xl">
        <div
          class="flex transition-transform duration-300 ease-in-out"
          :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
        >
          <div
            v-for="(image, index) in images"
            :key="index"
            class="w-full flex-shrink-0"
          >
            <img
              :src="image"
              :alt="`Product image ${index + 1}`"
              class="w-full h-[300px] object-cover"
              @click="openLightbox(index)"
            >
          </div>
        </div>
      </div>

      <!-- Mobile Navigation -->
      <button
        v-if="currentSlide > 0"
        @click="prevSlide"
        class="absolute left-2 top-1/2 -translate-y-1/2 p-2 bg-white/80 rounded-full hover:bg-white shadow-lg"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>

      <button
        v-if="currentSlide < images.length - 1"
        @click="nextSlide"
        class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-white/80 rounded-full hover:bg-white shadow-lg"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>

      <!-- Dots Indicator -->
      <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
        <button
          v-for="(image, index) in images"
          :key="index"
          @click="goToSlide(index)"
          class="w-2 h-2 rounded-full transition-all"
          :class="currentSlide === index ? 'bg-white w-6' : 'bg-white/60'"
        ></button>
      </div>
    </div>

    <!-- Lightbox Modal -->
    <Teleport to="body">
      <transition name="fade">
        <div
          v-if="showLightbox"
          class="fixed inset-0 z-50 bg-black bg-opacity-95 flex items-center justify-center"
          @click="closeLightbox"
        >
          <!-- Close Button -->
          <button
            class="absolute top-4 right-4 text-white p-2 hover:bg-white/10 rounded-full"
            @click="closeLightbox"
          >
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>

          <!-- Image Counter -->
          <div class="absolute top-4 left-4 text-white text-lg font-semibold">
            {{ lightboxIndex + 1 }} / {{ images.length }}
          </div>

          <!-- Main Lightbox Image -->
          <div class="relative max-w-7xl max-h-[90vh] px-16" @click.stop>
            <img
              :src="images[lightboxIndex]"
              :alt="`Product image ${lightboxIndex + 1}`"
              class="max-w-full max-h-[90vh] object-contain"
            >

            <!-- Previous Button -->
            <button
              v-if="lightboxIndex > 0"
              @click.stop="prevLightboxImage"
              class="absolute left-0 top-1/2 -translate-y-1/2 p-3 bg-white/20 hover:bg-white/30 rounded-full text-white"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
              </svg>
            </button>

            <!-- Next Button -->
            <button
              v-if="lightboxIndex < images.length - 1"
              @click.stop="nextLightboxImage"
              class="absolute right-0 top-1/2 -translate-y-1/2 p-3 bg-white/20 hover:bg-white/30 rounded-full text-white"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </button>
          </div>

          <!-- Thumbnails -->
          <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 overflow-x-auto max-w-[90vw] px-4">
            <button
              v-for="(image, index) in images"
              :key="index"
              @click.stop="lightboxIndex = index"
              class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all"
              :class="lightboxIndex === index ? 'border-white' : 'border-transparent opacity-60 hover:opacity-100'"
            >
              <img :src="image" :alt="`Thumbnail ${index + 1}`" class="w-full h-full object-cover">
            </button>
          </div>
        </div>
      </transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  images: {
    type: Array,
    required: true,
    default: () => []
  }
});

const currentSlide = ref(0);
const showLightbox = ref(false);
const lightboxIndex = ref(0);

// Mobile carousel navigation
const nextSlide = () => {
  if (currentSlide.value < props.images.length - 1) {
    currentSlide.value++;
  }
};

const prevSlide = () => {
  if (currentSlide.value > 0) {
    currentSlide.value--;
  }
};

const goToSlide = (index) => {
  currentSlide.value = index;
};

// Lightbox functions
const openLightbox = (index) => {
  lightboxIndex.value = index;
  showLightbox.value = true;
  document.body.style.overflow = 'hidden';
};

const closeLightbox = () => {
  showLightbox.value = false;
  document.body.style.overflow = '';
};

const nextLightboxImage = () => {
  if (lightboxIndex.value < props.images.length - 1) {
    lightboxIndex.value++;
  }
};

const prevLightboxImage = () => {
  if (lightboxIndex.value > 0) {
    lightboxIndex.value--;
  }
};

// Keyboard navigation for lightbox
const handleKeydown = (e) => {
  if (!showLightbox.value) return;

  if (e.key === 'Escape') {
    closeLightbox();
  } else if (e.key === 'ArrowRight') {
    nextLightboxImage();
  } else if (e.key === 'ArrowLeft') {
    prevLightboxImage();
  }
};

onMounted(() => {
  window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown);
  document.body.style.overflow = '';
});

// Touch swipe support for mobile
let touchStartX = 0;
let touchEndX = 0;

const handleTouchStart = (e) => {
  touchStartX = e.changedTouches[0].screenX;
};

const handleTouchEnd = (e) => {
  touchEndX = e.changedTouches[0].screenX;
  handleSwipe();
};

const handleSwipe = () => {
  if (touchStartX - touchEndX > 50) {
    nextSlide();
  } else if (touchEndX - touchStartX > 50) {
    prevSlide();
  }
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
