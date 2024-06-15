<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'min:2', 'max:100'],
            'type' => ['nullable', 'string', 'in:free,notice,faq'],
            // 'started_at' => ['nullable', 'date_format:Y-m-d'],
            // 'ended_at' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:started_at'],
            'per_page' => ['nullable', 'integer', 'between:5,20'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
