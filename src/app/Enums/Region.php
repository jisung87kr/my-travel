<?php

namespace App\Enums;

enum Region: string
{
    case SEOUL = 'seoul';
    case GYEONGGI = 'gyeonggi';
    case GANGWON = 'gangwon';
    case CHUNGBUK = 'chungbuk';
    case CHUNGNAM = 'chungnam';
    case JEONBUK = 'jeonbuk';
    case JEONNAM = 'jeonnam';
    case GYEONGBUK = 'gyeongbuk';
    case GYEONGNAM = 'gyeongnam';
    case JEJU = 'jeju';

    public function label(): string
    {
        return match ($this) {
            self::SEOUL => '서울',
            self::GYEONGGI => '경기',
            self::GANGWON => '강원',
            self::CHUNGBUK => '충북',
            self::CHUNGNAM => '충남',
            self::JEONBUK => '전북',
            self::JEONNAM => '전남',
            self::GYEONGBUK => '경북',
            self::GYEONGNAM => '경남',
            self::JEJU => '제주',
        };
    }

    public function labelEn(): string
    {
        return match ($this) {
            self::SEOUL => 'Seoul',
            self::GYEONGGI => 'Gyeonggi',
            self::GANGWON => 'Gangwon',
            self::CHUNGBUK => 'Chungbuk',
            self::CHUNGNAM => 'Chungnam',
            self::JEONBUK => 'Jeonbuk',
            self::JEONNAM => 'Jeonnam',
            self::GYEONGBUK => 'Gyeongbuk',
            self::GYEONGNAM => 'Gyeongnam',
            self::JEJU => 'Jeju',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
