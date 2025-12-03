<?php

namespace App\Enums;

enum BookingType: string
{
    case INSTANT = 'instant';
    case REQUEST = 'request';

    public function label(): string
    {
        return match ($this) {
            self::INSTANT => '즉시 확정',
            self::REQUEST => '승인 필요',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
