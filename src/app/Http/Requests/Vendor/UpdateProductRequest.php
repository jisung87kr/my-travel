<?php

namespace App\Http\Requests\Vendor;

use App\Enums\BookingType;
use App\Enums\Language;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Enums\Region;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->vendor !== null;
    }

    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'string', Rule::in(ProductType::values())],
            'region' => ['sometimes', 'string', Rule::in(Region::values())],
            'duration' => ['nullable', 'integer', 'min:30', 'max:10080'],
            'min_persons' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'max_persons' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'booking_type' => ['sometimes', 'string', Rule::in(BookingType::values())],
            'meeting_point' => ['nullable', 'string', 'max:255'],
            'meeting_point_detail' => ['nullable', 'string', 'max:1000'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'status' => ['sometimes', 'string', Rule::in([ProductStatus::DRAFT->value, ProductStatus::INACTIVE->value])],

            'translations' => ['sometimes', 'array', 'min:1'],
            'translations.*.locale' => ['required_with:translations', 'string', Rule::in(Language::values())],
            'translations.*.name' => ['required_with:translations.*.locale', 'string', 'max:255'],
            'translations.*.description' => ['required_with:translations.*.locale', 'string', 'max:10000'],
            'translations.*.highlights' => ['nullable', 'string', 'max:5000'],
            'translations.*.included' => ['nullable', 'string', 'max:5000'],
            'translations.*.excluded' => ['nullable', 'string', 'max:5000'],
            'translations.*.itinerary' => ['nullable', 'string', 'max:10000'],
            'translations.*.notes' => ['nullable', 'string', 'max:5000'],

            'prices' => ['sometimes', 'array', 'min:1'],
            'prices.*.type' => ['required_with:prices', 'string', Rule::in(['adult', 'child', 'infant'])],
            'prices.*.label' => ['required_with:prices.*.type', 'string', 'max:50'],
            'prices.*.price' => ['required_with:prices.*.type', 'integer', 'min:0', 'max:100000000'],
            'prices.*.min_age' => ['nullable', 'integer', 'min:0', 'max:100'],
            'prices.*.max_age' => ['nullable', 'integer', 'min:0', 'max:100'],
            'prices.*.is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => '유효하지 않은 상품 유형입니다.',
            'region.in' => '유효하지 않은 지역입니다.',
            'max_persons.gte' => '최대 인원은 최소 인원보다 크거나 같아야 합니다.',
            'booking_type.in' => '유효하지 않은 예약 유형입니다.',
            'status.in' => '유효하지 않은 상태입니다.',
            'translations.min' => '최소 1개 언어의 상품 정보가 필요합니다.',
            'translations.*.locale.required_with' => '언어를 선택해주세요.',
            'translations.*.name.required_with' => '상품명을 입력해주세요.',
            'translations.*.description.required_with' => '상품 설명을 입력해주세요.',
            'prices.min' => '최소 1개의 가격 정보가 필요합니다.',
            'prices.*.type.required_with' => '가격 유형을 선택해주세요.',
            'prices.*.label.required_with' => '가격 라벨을 입력해주세요.',
            'prices.*.price.required_with' => '가격을 입력해주세요.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('max_persons') && $this->has('min_persons')) {
            $this->merge([
                'max_persons_gte' => $this->min_persons,
            ]);
        }
    }

    public function withValidator($validator): void
    {
        $validator->sometimes('max_persons', 'gte:min_persons', function ($input) {
            return isset($input->min_persons) && isset($input->max_persons);
        });
    }
}
