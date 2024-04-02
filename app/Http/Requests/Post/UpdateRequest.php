<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['nullable', 'string', 'in:free,notice,faq'],
            'title' => ['required', 'string', 'min:2', 'max:100'],
            'contents' => ['required', 'string', 'min:2'],
            'is_open' => ['nullable', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
