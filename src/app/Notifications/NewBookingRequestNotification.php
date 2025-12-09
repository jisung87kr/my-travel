<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $productName = $this->booking->product->getTranslation('ko')?->title ?? '상품';
        $date = $this->booking->schedule?->date?->format('Y년 m월 d일') ?? '미정';

        return (new MailMessage)
            ->subject('[새 예약 요청] 승인이 필요한 예약이 있습니다')
            ->greeting("{$notifiable->name}님, 안녕하세요!")
            ->line("새로운 예약 요청이 들어왔습니다.")
            ->line("상품: {$productName}")
            ->line("고객명: {$this->booking->user->name}")
            ->line("예약일: {$date}")
            ->line("인원: {$this->booking->quantity}명")
            ->action('예약 확인하기', url('/vendor/bookings'))
            ->line('빠른 시간 내에 승인 또는 거절해 주세요.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'type' => 'new_booking_request',
            'message' => '새로운 예약 요청이 있습니다.',
        ];
    }
}
