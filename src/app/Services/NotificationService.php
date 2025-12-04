<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Message;
use App\Notifications\BookingApprovedNotification;
use App\Notifications\BookingCancelledNotification;
use App\Notifications\BookingCreatedNotification;
use App\Notifications\BookingRejectedNotification;
use App\Notifications\NewBookingRequestNotification;
use App\Notifications\NewMessageNotification;

class NotificationService
{
    public function bookingCreated(Booking $booking): void
    {
        // Notify traveler
        $booking->user->notify(new BookingCreatedNotification($booking));

        // Notify vendor (if pending approval)
        if ($booking->status->value === 'pending') {
            $booking->product->vendor->user->notify(
                new NewBookingRequestNotification($booking)
            );
        }
    }

    public function bookingApproved(Booking $booking): void
    {
        $booking->user->notify(new BookingApprovedNotification($booking));
    }

    public function bookingRejected(Booking $booking): void
    {
        $booking->user->notify(new BookingRejectedNotification($booking));
    }

    public function bookingCancelled(Booking $booking): void
    {
        // Notify the other party
        $booking->user->notify(new BookingCancelledNotification($booking));
        $booking->product->vendor->user->notify(new BookingCancelledNotification($booking));
    }

    public function newMessage(Message $message): void
    {
        $message->receiver->notify(new NewMessageNotification($message));
    }
}
