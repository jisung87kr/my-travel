<?php

namespace App\Http\Requests\Vendor;

use App\Http\Requests\Traits\ProductValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    use ProductValidationRules;

    public function authorize(): bool
    {
        return $this->user()?->vendor !== null;
    }

    public function rules(): array
    {
        return array_merge(
            $this->baseProductRules(),
            ['status' => ['nullable', 'string', Rule::in(['draft', 'pending'])]],
            ['translations' => ['required', 'array', 'min:1']],
            $this->translationRules('ko', required: true),
            $this->translationRules('en', required: false),
            $this->priceRules(),
            $this->imageRules(),
            $this->scheduleRules(),
            [
                'delete_images' => ['nullable', 'array'],
                'delete_images.*' => ['integer', 'exists:product_images,id'],
            ],
        );
    }

    public function messages(): array
    {
        return $this->productValidationMessages();
    }
}
