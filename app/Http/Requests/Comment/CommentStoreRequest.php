<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
            'contents' => ['required', 'string', 'min:2', 'max:5000'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
