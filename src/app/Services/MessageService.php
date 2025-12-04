<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;

class MessageService
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function send(Booking $booking, User $sender, string $content): Message
    {
        $receiver = $this->getReceiver($booking, $sender);

        $message = Message::create([
            'booking_id' => $booking->id,
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'content' => $content,
        ]);

        // Send notification
        $this->notificationService->newMessage($message);

        return $message;
    }

    public function markAsRead(Message $message, User $user): void
    {
        if ($message->receiver_id === $user->id && ! $message->read_at) {
            $message->update(['read_at' => now()]);
        }
    }

    public function markThreadAsRead(Booking $booking, User $user): void
    {
        Message::where('booking_id', $booking->id)
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function getThread(Booking $booking): Collection
    {
        return Message::with(['sender', 'receiver'])
            ->where('booking_id', $booking->id)
            ->orderBy('created_at')
            ->get();
    }

    public function getUnreadCount(User $user): int
    {
        return Message::where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    public function getConversations(User $user): Collection
    {
        // Get latest message from each booking conversation
        return Message::with(['booking.product.translations', 'sender', 'receiver'])
            ->where(function ($q) use ($user) {
                $q->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->whereIn('id', function ($query) use ($user) {
                $query->selectRaw('MAX(id)')
                    ->from('messages')
                    ->where(function ($q) use ($user) {
                        $q->where('sender_id', $user->id)
                            ->orWhere('receiver_id', $user->id);
                    })
                    ->groupBy('booking_id');
            })
            ->orderByDesc('created_at')
            ->get();
    }

    private function getReceiver(Booking $booking, User $sender): User
    {
        // If sender is traveler, send to vendor. Otherwise, send to traveler.
        return $sender->id === $booking->user_id
            ? $booking->product->vendor->user
            : $booking->user;
    }
}
