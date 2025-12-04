<template>
  <div class="bg-white rounded-xl shadow-sm p-6">
    <!-- Header -->
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-4">Reviews</h2>

      <!-- Average Rating -->
      <div class="flex items-center gap-6 pb-6 border-b border-gray-200">
        <div class="text-center">
          <div class="text-5xl font-bold text-gray-900 mb-1">{{ averageRating.toFixed(1) }}</div>
          <div class="flex items-center justify-center mb-1">
            <svg
              v-for="i in 5"
              :key="i"
              class="w-5 h-5"
              :class="i <= Math.round(averageRating) ? 'text-yellow-400' : 'text-gray-300'"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
          </div>
          <div class="text-sm text-gray-600">{{ reviews.length }} reviews</div>
        </div>

        <!-- Rating Breakdown -->
        <div class="flex-1">
          <div
            v-for="rating in [5, 4, 3, 2, 1]"
            :key="rating"
            class="flex items-center gap-2 mb-2"
          >
            <span class="text-sm text-gray-600 w-8">{{ rating }}</span>
            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
              <div
                class="h-full bg-yellow-400 rounded-full"
                :style="{ width: `${getRatingPercentage(rating)}%` }"
              ></div>
            </div>
            <span class="text-sm text-gray-600 w-10 text-right">{{ getRatingCount(rating) }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Review List -->
    <div class="space-y-6">
      <div
        v-for="(review, index) in displayedReviews"
        :key="review.id"
        class="pb-6 border-b border-gray-200 last:border-0"
      >
        <!-- Review Header -->
        <div class="flex items-start gap-4 mb-3">
          <img
            :src="review.avatar"
            :alt="review.user"
            class="w-12 h-12 rounded-full"
          >
          <div class="flex-1">
            <div class="flex items-center justify-between mb-1">
              <h4 class="font-semibold text-gray-900">{{ review.user }}</h4>
              <time class="text-sm text-gray-600">{{ formatDate(review.date) }}</time>
            </div>
            <div class="flex items-center">
              <svg
                v-for="i in 5"
                :key="i"
                class="w-4 h-4"
                :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-300'"
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
            </div>
          </div>
        </div>

        <!-- Review Content -->
        <p class="text-gray-700 leading-relaxed">{{ review.comment }}</p>

        <!-- Helpful Button -->
        <div class="mt-3 flex items-center gap-4">
          <button
            class="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-1"
            @click="toggleHelpful(review.id)"
          >
            <svg
              class="w-4 h-4"
              :class="review.helpful ? 'fill-current' : ''"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"
              />
            </svg>
            Helpful{{ review.helpfulCount ? ` (${review.helpfulCount})` : '' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Show More Button -->
    <div v-if="reviews.length > maxReviews" class="mt-6 text-center">
      <button
        v-if="!showAll"
        @click="showAll = true"
        class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:border-gray-400 hover:bg-gray-50 transition-colors"
      >
        Show all {{ reviews.length }} reviews
      </button>
      <button
        v-else
        @click="showAll = false"
        class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:border-gray-400 hover:bg-gray-50 transition-colors"
      >
        Show less
      </button>
    </div>

    <!-- Write Review Button -->
    <div class="mt-6 pt-6 border-t border-gray-200">
      <button
        @click="openWriteReview"
        class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
        </svg>
        Write a Review
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  reviews: {
    type: Array,
    required: true,
    default: () => []
  },
  averageRating: {
    type: Number,
    required: true,
    default: 0
  },
  maxReviews: {
    type: Number,
    default: 6
  }
});

// State
const showAll = ref(false);

// Computed
const displayedReviews = computed(() => {
  if (showAll.value) {
    return props.reviews;
  }
  return props.reviews.slice(0, props.maxReviews);
});

// Methods
const getRatingCount = (rating) => {
  return props.reviews.filter(r => r.rating === rating).length;
};

const getRatingPercentage = (rating) => {
  const count = getRatingCount(rating);
  return props.reviews.length > 0 ? (count / props.reviews.length) * 100 : 0;
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  const now = new Date();
  const diffTime = Math.abs(now - date);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  if (diffDays === 0) {
    return 'Today';
  } else if (diffDays === 1) {
    return 'Yesterday';
  } else if (diffDays < 7) {
    return `${diffDays} days ago`;
  } else if (diffDays < 30) {
    const weeks = Math.floor(diffDays / 7);
    return `${weeks} ${weeks === 1 ? 'week' : 'weeks'} ago`;
  } else if (diffDays < 365) {
    const months = Math.floor(diffDays / 30);
    return `${months} ${months === 1 ? 'month' : 'months'} ago`;
  } else {
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
  }
};

const toggleHelpful = (reviewId) => {
  // Find review and toggle helpful
  const review = props.reviews.find(r => r.id === reviewId);
  if (review) {
    if (!review.helpful) {
      review.helpful = true;
      review.helpfulCount = (review.helpfulCount || 0) + 1;
    } else {
      review.helpful = false;
      review.helpfulCount = Math.max(0, (review.helpfulCount || 0) - 1);
    }

    // In a real app, you would send this to an API
    console.log('Toggle helpful for review:', reviewId, review.helpful);
  }
};

const openWriteReview = () => {
  // In a real app, this would open a modal or navigate to a review form
  console.log('Open write review modal');
  alert('Write review functionality would open here');
};
</script>

<style scoped>
/* No additional styles needed - using Tailwind */
</style>
