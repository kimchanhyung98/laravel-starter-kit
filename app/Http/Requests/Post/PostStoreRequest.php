<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // 'type' => ['nullable', 'string', 'in:free,notice,faq'],
            'title' => ['required', 'string', 'max:100'],
            'contents' => ['required', 'string', 'min:5'],
            // 'is_published' => ['nullable', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
