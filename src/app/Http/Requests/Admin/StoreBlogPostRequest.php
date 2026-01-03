<?php

namespace App\Http\Requests\Admin;

use App\Enums\BlogPostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'category_id' => 'nullable|exists:blog_categories,id',
            'series_id' => 'nullable|exists:blog_series,id',
            'series_order' => 'nullable|integer|min:1',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:5120',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'status' => ['required', Rule::in(['draft', 'published'])],
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '제목을 입력해주세요.',
            'title.max' => '제목은 255자를 초과할 수 없습니다.',
            'slug.unique' => '이미 사용 중인 슬러그입니다.',
            'content.required' => '내용을 입력해주세요.',
            'thumbnail.image' => '썸네일은 이미지 파일이어야 합니다.',
            'thumbnail.max' => '썸네일은 5MB를 초과할 수 없습니다.',
            'excerpt.max' => '요약은 500자를 초과할 수 없습니다.',
            'meta_title.max' => 'SEO 제목은 60자를 초과할 수 없습니다.',
            'meta_description.max' => 'SEO 설명은 160자를 초과할 수 없습니다.',
        ];
    }
}
