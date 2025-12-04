<template>
    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
        <div class="flex flex-col sm:flex-row">
            <!-- Image -->
            <div class="sm:w-48 h-32 sm:h-auto flex-shrink-0">
                <img :src="booking.product_image"
                     :alt="booking.product_title"
                     class="w-full h-full object-cover">
            </div>

            <!-- Content -->
            <div class="flex-1 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                    <div>
                        <!-- Status Badge -->
                        <span class="px-2 py-1 text-xs font-medium rounded-full"
                              :style="{
                                  backgroundColor: statusColor + '20',
                                  color: statusColor
                              }">
                            {{ statusLabel }}
                        </span>

                        <!-- Product Title -->
                        <h3 class="mt-2 font-semibold text-gray-900">{{ booking.product_title }}</h3>

                        <!-- Booking Code -->
                        <p class="text-sm text-gray-500">{{ booking.booking_code }}</p>
                    </div>

                    <!-- Price -->
                    <div class="text-right">
                        <p class="text-lg font-bold text-gray-900">{{ formattedPrice }}</p>
                    </div>
                </div>

                <!-- Date and Participants Info -->
                <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-600">
                    <!-- Date -->
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ booking.formatted_date }}
                    </div>

                    <!-- Participants -->
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        {{ participantsText }}
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 flex flex-wrap gap-2">
                    <!-- View Details Button -->
                    <a :href="detailUrl"
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        View Details
                    </a>

                    <!-- Cancel Button (for pending/confirmed bookings) -->
                    <button v-if="canCancel"
                            @click="handleCancel"
                            class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                        Cancel Booking
                    </button>

                    <!-- Write Review Button (for completed bookings without review) -->
                    <button v-if="canWriteReview"
                            @click="handleWriteReview"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                        Write Review
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'BookingCard',
    props: {
        booking: {
            type: Object,
            required: true,
            validator: (value) => {
                return value.id && value.product_title && value.status;
            }
        }
    },
    computed: {
        statusColor() {
            const colors = {
                pending: '#F59E0B',    // amber
                confirmed: '#10B981',  // green
                completed: '#6366F1',  // indigo
                cancelled: '#EF4444'   // red
            };
            return colors[this.booking.status] || '#6B7280';
        },
        statusLabel() {
            const labels = {
                pending: 'Pending',
                confirmed: 'Confirmed',
                completed: 'Completed',
                cancelled: 'Cancelled'
            };
            return this.booking.status_label || labels[this.booking.status] || this.booking.status;
        },
        formattedPrice() {
            if (this.booking.formatted_price) {
                return `₩${this.booking.formatted_price}`;
            }
            return `₩${this.booking.total_price?.toLocaleString() || '0'}`;
        },
        participantsText() {
            const adults = this.booking.adult_count || 0;
            const children = this.booking.child_count || 0;

            let text = `${adults} Adult${adults > 1 ? 's' : ''}`;
            if (children > 0) {
                text += `, ${children} Child${children > 1 ? 'ren' : ''}`;
            }
            return text;
        },
        canCancel() {
            return ['pending', 'confirmed'].includes(this.booking.status);
        },
        canWriteReview() {
            return this.booking.status === 'completed' && !this.booking.has_review;
        },
        detailUrl() {
            const locale = document.documentElement.lang || 'en';
            return `/${locale}/my/bookings/${this.booking.id}`;
        }
    },
    methods: {
        async handleCancel() {
            if (!confirm('Are you sure you want to cancel this booking?')) {
                return;
            }

            try {
                const locale = document.documentElement.lang || 'en';
                const response = await fetch(`/${locale}/my/bookings/${this.booking.id}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.$emit('cancelled', this.booking.id);
                    // Reload page to show updated status
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to cancel booking');
                }
            } catch (error) {
                console.error('Cancel booking error:', error);
                alert('An error occurred while canceling the booking');
            }
        },
        handleWriteReview() {
            this.$emit('write-review', {
                bookingId: this.booking.id,
                productName: this.booking.product_title
            });
        }
    }
};
</script>

<style scoped>
/* Additional styles if needed */
</style>
