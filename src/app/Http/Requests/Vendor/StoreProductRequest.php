<?php

namespace App\Http\Requests\Vendor;

use App\Http\Requests\Traits\ProductValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
        );
    }

    public function messages(): array
    {
        return $this->productValidationMessages();
    }

    protected function prepareForValidation(): void
    {
        if (!$this->has('min_persons')) {
            $this->merge(['min_persons' => 1]);
        }
    }
}
