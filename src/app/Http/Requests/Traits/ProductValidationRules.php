<?php

namespace App\Http\Requests\Traits;

use App\Enums\BookingType;
use App\Enums\ProductType;
use App\Enums\Region;
use Illuminate\Validation\Rule;

trait ProductValidationRules
{
    protected function baseProductRules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(ProductType::values())],
            'region' => ['required', 'string', Rule::in(Region::values())],
            'duration' => ['nullable', 'integer', 'min:0', 'max:10080'],
            'min_persons' => ['nullable', 'integer', 'min:1', 'max:100'],
            'max_persons' => ['nullable', 'integer', 'min:1', 'max:100'],
            'booking_type' => ['required', 'string', Rule::in(BookingType::values())],
        ];
    }

    protected function translationRules(string $locale, bool $required = true): array
    {
        $requiredRule = $required ? 'required' : 'nullable';

        return [
            "translations.{$locale}" => [$requiredRule, 'array'],
            "translations.{$locale}.title" => [$requiredRule, 'string', 'max:255'],
            "translations.{$locale}.short_description" => ['nullable', 'string', 'max:500'],
            "translations.{$locale}.description" => [$requiredRule, 'string', 'max:10000'],
            "translations.{$locale}.includes" => ['nullable', 'string', 'max:5000'],
            "translations.{$locale}.excludes" => ['nullable', 'string', 'max:5000'],
            "translations.{$locale}.notes" => ['nullable', 'string', 'max:5000'],
            "translations.{$locale}.meeting_point" => ['nullable', 'string', 'max:255'],
            "translations.{$locale}.meeting_point_detail" => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function priceRules(): array
    {
        return [
            'prices' => ['required', 'array'],
            'prices.adult' => ['required', 'numeric', 'min:0', 'max:100000000'],
            'prices.child' => ['nullable', 'numeric', 'min:0', 'max:100000000'],
        ];
    }

    protected function imageRules(): array
    {
        return [
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }

    protected function productValidationMessages(): array
    {
        return [
            'type.required' => '상품 유형을 선택해주세요.',
            'type.in' => '유효하지 않은 상품 유형입니다.',
            'region.required' => '지역을 선택해주세요.',
            'region.in' => '유효하지 않은 지역입니다.',
            'booking_type.required' => '예약 유형을 선택해주세요.',
            'translations.required' => '상품 정보를 입력해주세요.',
            'translations.ko.required' => '한국어 상품 정보를 입력해주세요.',
            'translations.ko.title.required' => '상품명을 입력해주세요.',
            'translations.ko.description.required' => '상품 설명을 입력해주세요.',
            'prices.required' => '가격 정보를 입력해주세요.',
            'prices.adult.required' => '성인 가격을 입력해주세요.',
            'prices.adult.numeric' => '성인 가격은 숫자여야 합니다.',
            'prices.child.numeric' => '아동 가격은 숫자여야 합니다.',
        ];
    }
}
