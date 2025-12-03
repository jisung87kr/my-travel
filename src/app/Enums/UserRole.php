<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case VENDOR = 'vendor';
    case GUIDE = 'guide';
    case TRAVELER = 'traveler';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => '관리자',
            self::VENDOR => '제공자',
            self::GUIDE => '가이드',
            self::TRAVELER => '관광객',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
