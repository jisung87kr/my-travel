<?php

namespace App\Enums;

enum ProductType: string
{
    case DAY_TOUR = 'day_tour';
    case PACKAGE = 'package';
    case ACTIVITY = 'activity';

    public function label(): string
    {
        return match ($this) {
            self::DAY_TOUR => '당일 코스',
            self::PACKAGE => '숙박 패키지',
            self::ACTIVITY => '단일 체험',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
