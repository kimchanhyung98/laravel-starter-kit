<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CommentUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'contents' => ['required', 'string', 'min:2', 'max:5000'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
