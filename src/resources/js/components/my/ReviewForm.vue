<template>
    <div class="review-form">
        <!-- Modal Backdrop -->
        <div v-if="isOpen"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
             @click.self="closeModal">
            <!-- Modal Container -->
            <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Write a Review</h2>
                    <button @click="closeModal"
                            type="button"
                            class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Product Name -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Product</p>
                        <p class="font-semibold text-gray-900">{{ productName }}</p>
                        <p class="text-xs text-gray-500 mt-1">Booking #{{ bookingId }}</p>
                    </div>

                    <!-- Rating Section -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Rating <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <button v-for="star in 5"
                                    :key="star"
                                    type="button"
                                    @click="setRating(star)"
                                    @mouseenter="hoverRating = star"
                                    @mouseleave="hoverRating = 0"
                                    class="focus:outline-none transition-transform hover:scale-110">
                                <svg class="w-10 h-10"
                                     :class="star <= (hoverRating || rating) ? 'text-yellow-400' : 'text-gray-300'"
                                     fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </button>
                            <span v-if="rating > 0" class="ml-2 text-lg font-semibold text-gray-900">
                                {{ rating }}.0
                            </span>
                        </div>
                        <p v-if="errors.rating" class="mt-2 text-sm text-red-600">{{ errors.rating }}</p>
                    </div>

                    <!-- Review Content -->
                    <div class="mb-6">
                        <label for="review-content" class="block text-sm font-medium text-gray-700 mb-2">
                            Your Review <span class="text-red-500">*</span>
                        </label>
                        <textarea id="review-content"
                                  v-model="content"
                                  rows="6"
                                  placeholder="Share your experience with this tour..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                                  :class="{ 'border-red-500': errors.content }"></textarea>
                        <div class="mt-1 flex items-center justify-between">
                            <p v-if="errors.content" class="text-sm text-red-600">{{ errors.content }}</p>
                            <p class="text-sm text-gray-500 ml-auto">{{ content.length }} / 1000</p>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm font-medium text-blue-900 mb-2">Tips for writing a great review:</p>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>- Share specific details about your experience</li>
                            <li>- Mention what you liked or didn't like</li>
                            <li>- Give helpful advice to future travelers</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button @click="closeModal"
                                type="button"
                                class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                            Cancel
                        </button>
                        <button @click="submitReview"
                                type="button"
                                :disabled="isSubmitting"
                                class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            <span v-if="!isSubmitting">Submit Review</span>
                            <span v-else class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Submitting...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ReviewForm',
    props: {
        bookingId: {
            type: [String, Number],
            required: true
        },
        productName: {
            type: String,
            required: true
        },
        modelValue: {
            type: Boolean,
            default: false
        }
    },
    emits: ['update:modelValue', 'submitted'],
    data() {
        return {
            rating: 0,
            hoverRating: 0,
            content: '',
            errors: {},
            isSubmitting: false
        };
    },
    computed: {
        isOpen() {
            return this.modelValue;
        }
    },
    watch: {
        isOpen(newValue) {
            if (newValue) {
                // Reset form when opening
                this.resetForm();
                // Prevent body scroll when modal is open
                document.body.style.overflow = 'hidden';
            } else {
                // Restore body scroll when modal is closed
                document.body.style.overflow = '';
            }
        }
    },
    beforeUnmount() {
        // Clean up body scroll on component unmount
        document.body.style.overflow = '';
    },
    methods: {
        setRating(star) {
            this.rating = star;
            if (this.errors.rating) {
                delete this.errors.rating;
            }
        },
        validateForm() {
            this.errors = {};

            if (this.rating === 0) {
                this.errors.rating = 'Please select a rating';
            }

            if (!this.content.trim()) {
                this.errors.content = 'Please write your review';
            } else if (this.content.length < 10) {
                this.errors.content = 'Review must be at least 10 characters';
            } else if (this.content.length > 1000) {
                this.errors.content = 'Review must not exceed 1000 characters';
            }

            return Object.keys(this.errors).length === 0;
        },
        async submitReview() {
            if (!this.validateForm()) {
                return;
            }

            this.isSubmitting = true;

            try {
                const locale = document.documentElement.lang || 'en';
                const response = await fetch(`/${locale}/my/reviews`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        booking_id: this.bookingId,
                        rating: this.rating,
                        content: this.content.trim()
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.$emit('submitted', data.review);
                    this.closeModal();
                    // Show success message
                    alert('Thank you for your review!');
                    // Optionally reload or redirect
                    window.location.reload();
                } else {
                    this.errors = data.errors || { general: data.message || 'Failed to submit review' };
                    if (this.errors.general) {
                        alert(this.errors.general);
                    }
                }
            } catch (error) {
                console.error('Submit review error:', error);
                alert('An error occurred while submitting your review');
            } finally {
                this.isSubmitting = false;
            }
        },
        closeModal() {
            this.$emit('update:modelValue', false);
        },
        resetForm() {
            this.rating = 0;
            this.hoverRating = 0;
            this.content = '';
            this.errors = {};
        }
    }
};
</script>

<style scoped>
/* Fix modal z-index and scrolling */
.review-form {
    position: relative;
    z-index: 1000;
}
</style>
