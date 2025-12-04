<?php

namespace App\Policies;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Determine if the user can create a review for the booking
     */
    public function create(User $user, Booking $booking): bool
    {
        // Must be the booking owner
        if ($user->id !== $booking->user_id) {
            return false;
        }

        // Booking must be completed
        if ($booking->status !== BookingStatus::COMPLETED) {
            return false;
        }

        // Only one review per booking
        if ($booking->review()->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the user can view the review
     */
    public function view(?User $user, Review $review): bool
    {
        // Public reviews are visible to everyone
        return $review->is_visible;
    }

    /**
     * Determine if the user can update the review
     */
    public function update(User $user, Review $review): bool
    {
        // Admin can update any review
        if ($user->hasRole('admin')) {
            return true;
        }

        // Only the author can update
        return $user->id === $review->user_id;
    }

    /**
     * Determine if the user can delete the review
     */
    public function delete(User $user, Review $review): bool
    {
        // Admin can delete any review
        if ($user->hasRole('admin')) {
            return true;
        }

        // Only the author can delete
        return $user->id === $review->user_id;
    }

    /**
     * Determine if the user can reply to the review
     */
    public function reply(User $user, Review $review): bool
    {
        // Already replied
        if ($review->vendor_reply) {
            return false;
        }

        // Admin can reply
        if ($user->hasRole('admin')) {
            return true;
        }

        // Must be the vendor of the product
        $booking = $review->booking;
        if (!$booking || !$booking->product) {
            return false;
        }

        return $user->vendor?->id === $booking->product->vendor_id;
    }

    /**
     * Determine if the user can report the review
     */
    public function report(User $user, Review $review): bool
    {
        // Cannot report own review
        return $user->id !== $review->user_id;
    }
}
