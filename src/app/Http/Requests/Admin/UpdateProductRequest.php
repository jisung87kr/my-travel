<?php

namespace App\Http\Requests\Admin;

use App\Enums\ProductStatus;
use App\Http\Requests\Traits\ProductValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    use ProductValidationRules;

    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    public function rules(): array
    {
        return array_merge(
            ['vendor_id' => ['required', 'exists:vendors,id']],
            $this->baseProductRules(),
            ['status' => ['required', Rule::in(ProductStatus::values())]],
            ['translations' => ['required', 'array']],
            $this->translationRules('ko', required: true),
            $this->priceRules(),
            $this->imageRules(),
            [
                'delete_images' => ['nullable', 'array'],
                'delete_images.*' => ['integer', 'exists:product_images,id'],
            ],
        );
    }

    public function messages(): array
    {
        return array_merge(
            $this->productValidationMessages(),
            ['vendor_id.required' => '제공자를 선택해주세요.'],
        );
    }
}
