<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingApprovedNotification extends Notification implements ShouldQueue
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
        $date = $this->booking->booking_date->format('Y년 m월 d일');

        return (new MailMessage)
            ->subject('[예약 승인] 예약이 확정되었습니다')
            ->greeting("{$notifiable->name}님, 안녕하세요!")
            ->line("예약이 승인되어 확정되었습니다.")
            ->line("상품: {$productName}")
            ->line("예약일: {$date}")
            ->line("예약번호: {$this->booking->booking_code}")
            ->action('예약 확인하기', url('/my/bookings'))
            ->line('즐거운 여행 되세요!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'type' => 'booking_approved',
            'message' => '예약이 승인되었습니다.',
        ];
    }
}
