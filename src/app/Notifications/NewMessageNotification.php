<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Message $message
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $senderName = $this->message->sender->name;
        $productName = $this->message->booking->product->getTranslation('ko')?->title ?? '상품';

        return (new MailMessage)
            ->subject('[새 메시지] 새로운 메시지가 도착했습니다')
            ->greeting("{$notifiable->name}님, 안녕하세요!")
            ->line("{$senderName}님으로부터 새로운 메시지가 도착했습니다.")
            ->line("관련 상품: {$productName}")
            ->line("메시지 내용: " . mb_substr($this->message->content, 0, 100) . (mb_strlen($this->message->content) > 100 ? '...' : ''))
            ->action('메시지 확인하기', url("/bookings/{$this->message->booking_id}/messages"))
            ->line('빠른 답변 부탁드립니다.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message_id' => $this->message->id,
            'booking_id' => $this->message->booking_id,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
            'type' => 'new_message',
            'message' => '새로운 메시지가 도착했습니다.',
        ];
    }
}
