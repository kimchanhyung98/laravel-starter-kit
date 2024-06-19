<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CommentIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'post_id' => ['required', 'integer', 'exists:posts,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
