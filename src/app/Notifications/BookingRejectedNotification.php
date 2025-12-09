<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRejectedNotification extends Notification implements ShouldQueue
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
            ->subject('[예약 거절] 예약이 거절되었습니다')
            ->greeting("{$notifiable->name}님, 안녕하세요.")
            ->line("죄송합니다. 예약이 거절되었습니다.")
            ->line("상품: {$productName}")
            ->line("요청일: {$date}")
            ->line("예약번호: {$this->booking->booking_code}")
            ->action('다른 상품 보기', url('/products'))
            ->line('다른 상품을 찾아보시거나, 다른 날짜로 예약을 시도해 주세요.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'type' => 'booking_rejected',
            'message' => '예약이 거절되었습니다.',
        ];
    }
}
