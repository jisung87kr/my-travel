<?php

namespace App\Http\Requests\Vendor;

use App\Enums\BookingType;
use App\Enums\ProductType;
use App\Enums\Region;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->vendor !== null;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(ProductType::values())],
            'region' => ['required', 'string', Rule::in(Region::values())],
            'duration' => ['nullable', 'integer', 'min:30', 'max:10080'],
            'min_persons' => ['nullable', 'integer', 'min:1', 'max:100'],
            'max_persons' => ['nullable', 'integer', 'min:1', 'max:100'],
            'booking_type' => ['required', 'string', Rule::in(BookingType::values())],
            'meeting_point' => ['nullable', 'string', 'max:255'],
            'meeting_point_detail' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'string', Rule::in(['draft', 'pending'])],

            // Translations - keyed by locale (ko, en, etc.)
            'translations' => ['required', 'array', 'min:1'],
            'translations.ko' => ['required', 'array'],
            'translations.ko.title' => ['required', 'string', 'max:255'],
            'translations.ko.short_description' => ['nullable', 'string', 'max:500'],
            'translations.ko.description' => ['required', 'string', 'max:10000'],
            'translations.ko.includes' => ['nullable', 'string', 'max:5000'],
            'translations.ko.excludes' => ['nullable', 'string', 'max:5000'],

            'translations.en' => ['nullable', 'array'],
            'translations.en.title' => ['nullable', 'string', 'max:255'],
            'translations.en.short_description' => ['nullable', 'string', 'max:500'],
            'translations.en.description' => ['nullable', 'string', 'max:10000'],
            'translations.en.includes' => ['nullable', 'string', 'max:5000'],
            'translations.en.excludes' => ['nullable', 'string', 'max:5000'],

            // Prices - keyed by type (adult, child)
            'prices' => ['required', 'array'],
            'prices.adult' => ['required', 'numeric', 'min:0', 'max:100000000'],
            'prices.child' => ['nullable', 'numeric', 'min:0', 'max:100000000'],

            // Images
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }

    public function messages(): array
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

    protected function prepareForValidation(): void
    {
        // Set default values
        if (!$this->has('min_persons')) {
            $this->merge(['min_persons' => 1]);
        }
    }
}
