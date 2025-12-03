<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case NO_SHOW = 'no_show';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => '승인 대기',
            self::CONFIRMED => '확정',
            self::IN_PROGRESS => '진행 중',
            self::COMPLETED => '완료',
            self::CANCELLED => '취소',
            self::NO_SHOW => '노쇼',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::CONFIRMED => 'blue',
            self::IN_PROGRESS => 'indigo',
            self::COMPLETED => 'green',
            self::CANCELLED => 'gray',
            self::NO_SHOW => 'red',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function canTransitionTo(BookingStatus $status): bool
    {
        return match ($this) {
            self::PENDING => in_array($status, [self::CONFIRMED, self::CANCELLED]),
            self::CONFIRMED => in_array($status, [self::IN_PROGRESS, self::CANCELLED, self::NO_SHOW]),
            self::IN_PROGRESS => in_array($status, [self::COMPLETED, self::NO_SHOW]),
            self::COMPLETED, self::CANCELLED, self::NO_SHOW => false,
        };
    }
}
