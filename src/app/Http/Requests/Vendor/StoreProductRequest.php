<?php

namespace App\Http\Requests\Vendor;

use App\Enums\BookingType;
use App\Enums\Language;
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
            'min_persons' => ['required', 'integer', 'min:1', 'max:100'],
            'max_persons' => ['required', 'integer', 'min:1', 'max:100', 'gte:min_persons'],
            'booking_type' => ['required', 'string', Rule::in(BookingType::values())],
            'meeting_point' => ['nullable', 'string', 'max:255'],
            'meeting_point_detail' => ['nullable', 'string', 'max:1000'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],

            'translations' => ['required', 'array', 'min:1'],
            'translations.*.locale' => ['required', 'string', Rule::in(Language::values())],
            'translations.*.name' => ['required', 'string', 'max:255'],
            'translations.*.description' => ['required', 'string', 'max:10000'],
            'translations.*.highlights' => ['nullable', 'string', 'max:5000'],
            'translations.*.included' => ['nullable', 'string', 'max:5000'],
            'translations.*.excluded' => ['nullable', 'string', 'max:5000'],
            'translations.*.itinerary' => ['nullable', 'string', 'max:10000'],
            'translations.*.notes' => ['nullable', 'string', 'max:5000'],

            'prices' => ['required', 'array', 'min:1'],
            'prices.*.type' => ['required', 'string', Rule::in(['adult', 'child', 'infant'])],
            'prices.*.label' => ['required', 'string', 'max:50'],
            'prices.*.price' => ['required', 'integer', 'min:0', 'max:100000000'],
            'prices.*.min_age' => ['nullable', 'integer', 'min:0', 'max:100'],
            'prices.*.max_age' => ['nullable', 'integer', 'min:0', 'max:100'],
            'prices.*.is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => '상품 유형을 선택해주세요.',
            'type.in' => '유효하지 않은 상품 유형입니다.',
            'region.required' => '지역을 선택해주세요.',
            'region.in' => '유효하지 않은 지역입니다.',
            'min_persons.required' => '최소 인원을 입력해주세요.',
            'max_persons.required' => '최대 인원을 입력해주세요.',
            'max_persons.gte' => '최대 인원은 최소 인원보다 크거나 같아야 합니다.',
            'booking_type.required' => '예약 유형을 선택해주세요.',
            'translations.required' => '상품 정보를 입력해주세요.',
            'translations.min' => '최소 1개 언어의 상품 정보가 필요합니다.',
            'translations.*.locale.required' => '언어를 선택해주세요.',
            'translations.*.name.required' => '상품명을 입력해주세요.',
            'translations.*.description.required' => '상품 설명을 입력해주세요.',
            'prices.required' => '가격 정보를 입력해주세요.',
            'prices.min' => '최소 1개의 가격 정보가 필요합니다.',
            'prices.*.type.required' => '가격 유형을 선택해주세요.',
            'prices.*.label.required' => '가격 라벨을 입력해주세요.',
            'prices.*.price.required' => '가격을 입력해주세요.',
        ];
    }
}
