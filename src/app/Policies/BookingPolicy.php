<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $booking->user_id;
    }

    public function viewAsVendor(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->vendor?->id === $booking->product->vendor_id;
    }

    public function cancel(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $booking->user_id && $booking->canBeCancelled();
    }

    public function manage(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->vendor?->id === $booking->product->vendor_id;
    }
}
