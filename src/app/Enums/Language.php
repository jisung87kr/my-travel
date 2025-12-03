<?php

namespace App\Enums;

enum Language: string
{
    case KO = 'ko';
    case EN = 'en';
    case ZH = 'zh';
    case JA = 'ja';

    public function label(): string
    {
        return match ($this) {
            self::KO => '한국어',
            self::EN => 'English',
            self::ZH => '中文',
            self::JA => '日本語',
        };
    }

    public function nativeLabel(): string
    {
        return match ($this) {
            self::KO => '한국어',
            self::EN => 'English',
            self::ZH => '中文',
            self::JA => '日本語',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function default(): self
    {
        return self::KO;
    }
}
