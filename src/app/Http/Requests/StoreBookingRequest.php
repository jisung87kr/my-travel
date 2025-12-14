<?php

namespace App\Http\Requests;

use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'integer',
                Rule::exists('products', 'id')->where(function ($query) {
                    $query->where('status', ProductStatus::ACTIVE->value);
                }),
            ],
            'schedule_id' => 'required_without:date|nullable|integer|exists:product_schedules,id',
            'date' => 'required_without:schedule_id|nullable|date|after_or_equal:today',
            'adult_count' => 'required|integer|min:0|max:100',
            'child_count' => 'nullable|integer|min:0|max:100',
            'infant_count' => 'nullable|integer|min:0|max:100',
            'special_request' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'contact_name' => 'nullable|string|max:100',
            'name' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'email' => 'nullable|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => '상품을 선택해주세요.',
            'product_id.exists' => '유효하지 않은 상품입니다.',
            'schedule_id.required_without' => '일정을 선택해주세요.',
            'schedule_id.exists' => '유효하지 않은 일정입니다.',
            'date.required_without' => '날짜를 선택해주세요.',
            'date.after_or_equal' => '오늘 이후의 날짜만 예약 가능합니다.',
            'adult_count.required' => '성인 인원을 입력해주세요.',
            'adult_count.min' => '성인 인원은 0명 이상이어야 합니다.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'child_count' => $this->child_count ?? 0,
            'infant_count' => $this->infant_count ?? 0,
            // Normalize field names (form uses name/email/phone/notes, service expects contact_* and special_request)
            'contact_name' => $this->contact_name ?? $this->name,
            'contact_email' => $this->contact_email ?? $this->email,
            'contact_phone' => $this->contact_phone ?? $this->phone,
            'special_request' => $this->special_request ?? $this->notes,
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $totalPersons = ($this->adult_count ?? 0) + ($this->child_count ?? 0) + ($this->infant_count ?? 0);
            if ($totalPersons < 1) {
                $validator->errors()->add('adult_count', '최소 1명 이상의 인원이 필요합니다.');
            }
        });
    }
}
