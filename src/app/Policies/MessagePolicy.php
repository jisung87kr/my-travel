<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    /**
     * Determine if the user can view the message thread for a booking
     */
    public function viewThread(User $user, Booking $booking): bool
    {
        // Admin can view any thread
        if ($user->hasRole('admin')) {
            return true;
        }

        // Traveler (booking owner) can view
        if ($user->id === $booking->user_id) {
            return true;
        }

        // Vendor (product owner) can view
        if ($user->vendor?->id === $booking->product->vendor_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can send a message in the booking thread
     */
    public function send(User $user, Booking $booking): bool
    {
        // Same as viewing - only participants can send messages
        return $this->viewThread($user, $booking);
    }

    /**
     * Determine if the user can mark the message as read
     */
    public function markAsRead(User $user, Message $message): bool
    {
        // Only the receiver can mark as read
        return $user->id === $message->receiver_id;
    }

    /**
     * Determine if the user can delete the message
     */
    public function delete(User $user, Message $message): bool
    {
        // Only admin can delete messages
        return $user->hasRole('admin');
    }
}
