<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->vendor !== null;
    }

    public function rules(): array
    {
        return [
            'schedules' => 'required|array|min:1',
            'schedules.*.id' => 'nullable|integer|exists:product_schedules,id',
            'schedules.*.date' => 'required_without:schedules.*.id|date',
            'schedules.*.total_count' => 'required|integer|min:0|max:1000',
            'schedules.*.start_time' => 'nullable|date_format:H:i',
            'schedules.*.is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'schedules.required' => '일정 정보를 입력해주세요.',
            'schedules.min' => '최소 1개의 일정이 필요합니다.',
            'schedules.*.date.required_without' => '날짜를 입력해주세요.',
            'schedules.*.total_count.required' => '수용 인원을 입력해주세요.',
            'schedules.*.total_count.min' => '수용 인원은 0 이상이어야 합니다.',
        ];
    }
}
