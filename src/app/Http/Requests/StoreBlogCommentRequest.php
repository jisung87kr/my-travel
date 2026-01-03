<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:blog_comments,id',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => '댓글 내용을 입력해주세요.',
            'content.max' => '댓글은 1000자를 초과할 수 없습니다.',
            'parent_id.exists' => '원본 댓글을 찾을 수 없습니다.',
        ];
    }
}
